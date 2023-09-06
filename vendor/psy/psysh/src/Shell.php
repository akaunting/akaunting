<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2023 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy;

use Psy\CodeCleaner\NoReturnValue;
use Psy\Exception\BreakException;
use Psy\Exception\ErrorException;
use Psy\Exception\Exception as PsyException;
use Psy\Exception\RuntimeException;
use Psy\Exception\ThrowUpException;
use Psy\ExecutionLoop\ProcessForker;
use Psy\ExecutionLoop\RunkitReloader;
use Psy\Formatter\TraceFormatter;
use Psy\Input\ShellInput;
use Psy\Input\SilentInput;
use Psy\Output\ShellOutput;
use Psy\TabCompletion\Matcher;
use Psy\VarDumper\PresenterAware;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The Psy Shell application.
 *
 * Usage:
 *
 *     $shell = new Shell;
 *     $shell->run();
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
class Shell extends Application
{
    const VERSION = 'v0.11.20';

    /** @deprecated */
    const PROMPT = '>>> ';
    /** @deprecated */
    const BUFF_PROMPT = '... ';
    /** @deprecated */
    const REPLAY = '--> ';
    /** @deprecated */
    const RETVAL = '=> ';

    private $config;
    private $cleaner;
    private $output;
    private $originalVerbosity;
    private $readline;
    private $inputBuffer;
    private $code;
    private $codeBuffer;
    private $codeBufferOpen;
    private $codeStack;
    private $stdoutBuffer;
    private $context;
    private $includes;
    private $outputWantsNewline = false;
    private $loopListeners;
    private $autoCompleter;
    private $matchers = [];
    private $commandsMatcher;
    private $lastExecSuccess = true;
    private $nonInteractive = false;
    private $errorReporting;

    /**
     * Create a new Psy Shell.
     *
     * @param Configuration|null $config (default: null)
     */
    public function __construct(Configuration $config = null)
    {
        $this->config = $config ?: new Configuration();
        $this->cleaner = $this->config->getCodeCleaner();
        $this->context = new Context();
        $this->includes = [];
        $this->readline = $this->config->getReadline();
        $this->inputBuffer = [];
        $this->codeStack = [];
        $this->stdoutBuffer = '';
        $this->loopListeners = $this->getDefaultLoopListeners();

        parent::__construct('Psy Shell', self::VERSION);

        $this->config->setShell($this);

        // Register the current shell session's config with \Psy\info
        \Psy\info($this->config);
    }

    /**
     * Check whether the first thing in a backtrace is an include call.
     *
     * This is used by the psysh bin to decide whether to start a shell on boot,
     * or to simply autoload the library.
     */
    public static function isIncluded(array $trace): bool
    {
        $isIncluded = isset($trace[0]['function']) &&
          \in_array($trace[0]['function'], ['require', 'include', 'require_once', 'include_once']);

        // Detect Composer PHP bin proxies.
        if ($isIncluded && \array_key_exists('_composer_autoload_path', $GLOBALS) && \preg_match('{[\\\\/]psysh$}', $trace[0]['file'])) {
            // If we're in a bin proxy, we'll *always* see one include, but we
            // care if we see a second immediately after that.
            return isset($trace[1]['function']) &&
                \in_array($trace[1]['function'], ['require', 'include', 'require_once', 'include_once']);
        }

        return $isIncluded;
    }

    /**
     * Check if the currently running PsySH bin is a phar archive.
     */
    public static function isPhar(): bool
    {
        return \class_exists("\Phar") && \Phar::running() !== '' && \strpos(__FILE__, \Phar::running(true)) === 0;
    }

    /**
     * Invoke a Psy Shell from the current context.
     *
     * @see Psy\debug
     * @deprecated will be removed in 1.0. Use \Psy\debug instead
     *
     * @param array         $vars   Scope variables from the calling context (default: [])
     * @param object|string $bindTo Bound object ($this) or class (self) value for the shell
     *
     * @return array Scope variables from the debugger session
     */
    public static function debug(array $vars = [], $bindTo = null): array
    {
        return \Psy\debug($vars, $bindTo);
    }

    /**
     * Adds a command object.
     *
     * {@inheritdoc}
     *
     * @param BaseCommand $command A Symfony Console Command object
     *
     * @return BaseCommand The registered command
     */
    public function add(BaseCommand $command): BaseCommand
    {
        if ($ret = parent::add($command)) {
            if ($ret instanceof ContextAware) {
                $ret->setContext($this->context);
            }

            if ($ret instanceof PresenterAware) {
                $ret->setPresenter($this->config->getPresenter());
            }

            if (isset($this->commandsMatcher)) {
                $this->commandsMatcher->setCommands($this->all());
            }
        }

        return $ret;
    }

    /**
     * Gets the default input definition.
     *
     * @return InputDefinition An InputDefinition instance
     */
    protected function getDefaultInputDefinition(): InputDefinition
    {
        return new InputDefinition([
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),
            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message.'),
        ]);
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands(): array
    {
        $sudo = new Command\SudoCommand();
        $sudo->setReadline($this->readline);

        $hist = new Command\HistoryCommand();
        $hist->setReadline($this->readline);

        return [
            new Command\HelpCommand(),
            new Command\ListCommand(),
            new Command\DumpCommand(),
            new Command\DocCommand(),
            new Command\ShowCommand(),
            new Command\WtfCommand(),
            new Command\WhereamiCommand(),
            new Command\ThrowUpCommand(),
            new Command\TimeitCommand(),
            new Command\TraceCommand(),
            new Command\BufferCommand(),
            new Command\ClearCommand(),
            new Command\EditCommand($this->config->getRuntimeDir()),
            // new Command\PsyVersionCommand(),
            $sudo,
            $hist,
            new Command\ExitCommand(),
        ];
    }

    /**
     * @return Matcher\AbstractMatcher[]
     */
    protected function getDefaultMatchers(): array
    {
        // Store the Commands Matcher for later. If more commands are added,
        // we'll update the Commands Matcher too.
        $this->commandsMatcher = new Matcher\CommandsMatcher($this->all());

        return [
            $this->commandsMatcher,
            new Matcher\KeywordsMatcher(),
            new Matcher\VariablesMatcher(),
            new Matcher\ConstantsMatcher(),
            new Matcher\FunctionsMatcher(),
            new Matcher\ClassNamesMatcher(),
            new Matcher\ClassMethodsMatcher(),
            new Matcher\ClassAttributesMatcher(),
            new Matcher\ObjectMethodsMatcher(),
            new Matcher\ObjectAttributesMatcher(),
            new Matcher\ClassMethodDefaultParametersMatcher(),
            new Matcher\ObjectMethodDefaultParametersMatcher(),
            new Matcher\FunctionDefaultParametersMatcher(),
        ];
    }

    /**
     * @deprecated Nothing should use this anymore
     */
    protected function getTabCompletionMatchers()
    {
        @\trigger_error('getTabCompletionMatchers is no longer used', \E_USER_DEPRECATED);
    }

    /**
     * Gets the default command loop listeners.
     *
     * @return array An array of Execution Loop Listener instances
     */
    protected function getDefaultLoopListeners(): array
    {
        $listeners = [];

        if (ProcessForker::isSupported() && $this->config->usePcntl()) {
            $listeners[] = new ProcessForker();
        }

        if (RunkitReloader::isSupported()) {
            $listeners[] = new RunkitReloader();
        }

        return $listeners;
    }

    /**
     * Add tab completion matchers.
     *
     * @param array $matchers
     */
    public function addMatchers(array $matchers)
    {
        $this->matchers = \array_merge($this->matchers, $matchers);

        if (isset($this->autoCompleter)) {
            $this->addMatchersToAutoCompleter($matchers);
        }
    }

    /**
     * @deprecated Call `addMatchers` instead
     *
     * @param array $matchers
     */
    public function addTabCompletionMatchers(array $matchers)
    {
        $this->addMatchers($matchers);
    }

    /**
     * Set the Shell output.
     *
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
        $this->originalVerbosity = $output->getVerbosity();
    }

    /**
     * Runs PsySH.
     *
     * @param InputInterface|null  $input  An Input instance
     * @param OutputInterface|null $output An Output instance
     *
     * @return int 0 if everything went fine, or an error code
     */
    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        // We'll just ignore the input passed in, and set up our own!
        $input = new ArrayInput([]);

        if ($output === null) {
            $output = $this->config->getOutput();
        }

        $this->setAutoExit(false);
        $this->setCatchExceptions(false);

        try {
            return parent::run($input, $output);
        } catch (\Throwable $e) {
            $this->writeException($e);
        }

        return 1;
    }

    /**
     * Runs PsySH.
     *
     * @throws \Throwable if thrown via the `throw-up` command
     *
     * @param InputInterface  $input  An Input instance
     * @param OutputInterface $output An Output instance
     *
     * @return int 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        $this->setOutput($output);
        $this->resetCodeBuffer();

        if ($input->isInteractive()) {
            // @todo should it be possible to have raw output in an interactive run?
            return $this->doInteractiveRun();
        } else {
            return $this->doNonInteractiveRun($this->config->rawOutput());
        }
    }

    /**
     * Run PsySH in interactive mode.
     *
     * Initializes tab completion and readline history, then spins up the
     * execution loop.
     *
     * @throws \Throwable if thrown via the `throw-up` command
     *
     * @return int 0 if everything went fine, or an error code
     */
    private function doInteractiveRun(): int
    {
        $this->initializeTabCompletion();
        $this->readline->readHistory();

        $this->output->writeln($this->getHeader());
        $this->writeVersionInfo();
        $this->writeStartupMessage();

        try {
            $this->beforeRun();
            $this->loadIncludes();
            $loop = new ExecutionLoopClosure($this);
            $loop->execute();
            $this->afterRun();
        } catch (ThrowUpException $e) {
            throw $e->getPrevious();
        } catch (BreakException $e) {
            // The ProcessForker throws a BreakException to finish the main thread.
        }

        return 0;
    }

    /**
     * Run PsySH in non-interactive mode.
     *
     * Note that this isn't very useful unless you supply "include" arguments at
     * the command line, or code via stdin.
     *
     * @param bool $rawOutput
     *
     * @return int 0 if everything went fine, or an error code
     */
    private function doNonInteractiveRun(bool $rawOutput): int
    {
        $this->nonInteractive = true;

        // If raw output is enabled (or output is piped) we don't want startup messages.
        if (!$rawOutput && !$this->config->outputIsPiped()) {
            $this->output->writeln($this->getHeader());
            $this->writeVersionInfo();
            $this->writeStartupMessage();
        }

        $this->beforeRun();
        $this->loadIncludes();

        // For non-interactive execution, read only from the input buffer or from piped input.
        // Otherwise it'll try to readline and hang, waiting for user input with no indication of
        // what's holding things up.
        if (!empty($this->inputBuffer) || $this->config->inputIsPiped()) {
            $this->getInput(false);
        }

        if ($this->hasCode()) {
            $ret = $this->execute($this->flushCode());
            $this->writeReturnValue($ret, $rawOutput);
        }

        $this->afterRun();
        $this->nonInteractive = false;

        return 0;
    }

    /**
     * Configures the input and output instances based on the user arguments and options.
     */
    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        // @todo overrides via environment variables (or should these happen in config? ... probably config)
        $input->setInteractive($this->config->getInputInteractive());

        if ($this->config->getOutputDecorated() !== null) {
            $output->setDecorated($this->config->getOutputDecorated());
        }

        $output->setVerbosity($this->config->getOutputVerbosity());
    }

    /**
     * Load user-defined includes.
     */
    private function loadIncludes()
    {
        // Load user-defined includes
        $load = function (self $__psysh__) {
            \set_error_handler([$__psysh__, 'handleError']);
            foreach ($__psysh__->getIncludes() as $__psysh_include__) {
                try {
                    include_once $__psysh_include__;
                } catch (\Exception $_e) {
                    $__psysh__->writeException($_e);
                }
            }
            \restore_error_handler();
            unset($__psysh_include__);

            // Override any new local variables with pre-defined scope variables
            \extract($__psysh__->getScopeVariables(false));

            // ... then add the whole mess of variables back.
            $__psysh__->setScopeVariables(\get_defined_vars());
        };

        $load($this);
    }

    /**
     * Read user input.
     *
     * This will continue fetching user input until the code buffer contains
     * valid code.
     *
     * @throws BreakException if user hits Ctrl+D
     *
     * @param bool $interactive
     */
    public function getInput(bool $interactive = true)
    {
        $this->codeBufferOpen = false;

        do {
            // reset output verbosity (in case it was altered by a subcommand)
            $this->output->setVerbosity($this->originalVerbosity);

            $input = $this->readline();

            /*
             * Handle Ctrl+D. It behaves differently in different cases:
             *
             *   1) In an expression, like a function or "if" block, clear the input buffer
             *   2) At top-level session, behave like the exit command
             *   3) When non-interactive, return, because that's the end of stdin
             */
            if ($input === false) {
                if (!$interactive) {
                    return;
                }

                $this->output->writeln('');

                if ($this->hasCode()) {
                    $this->resetCodeBuffer();
                } else {
                    throw new BreakException('Ctrl+D');
                }
            }

            // handle empty input
            if (\trim($input) === '' && !$this->codeBufferOpen) {
                continue;
            }

            $input = $this->onInput($input);

            // If the input isn't in an open string or comment, check for commands to run.
            if ($this->hasCommand($input) && !$this->inputInOpenStringOrComment($input)) {
                $this->addHistory($input);
                $this->runCommand($input);

                continue;
            }

            $this->addCode($input);
        } while (!$interactive || !$this->hasValidCode());
    }

    /**
     * Check whether the code buffer (plus current input) is in an open string or comment.
     *
     * @param string $input current line of input
     *
     * @return bool true if the input is in an open string or comment
     */
    private function inputInOpenStringOrComment(string $input): bool
    {
        if (!$this->hasCode()) {
            return false;
        }

        $code = $this->codeBuffer;
        $code[] = $input;
        $tokens = @\token_get_all('<?php '.\implode("\n", $code));
        $last = \array_pop($tokens);

        return $last === '"' || $last === '`' ||
            (\is_array($last) && \in_array($last[0], [\T_ENCAPSED_AND_WHITESPACE, \T_START_HEREDOC, \T_COMMENT]));
    }

    /**
     * Run execution loop listeners before the shell session.
     */
    protected function beforeRun()
    {
        foreach ($this->loopListeners as $listener) {
            $listener->beforeRun($this);
        }
    }

    /**
     * Run execution loop listeners at the start of each loop.
     */
    public function beforeLoop()
    {
        foreach ($this->loopListeners as $listener) {
            $listener->beforeLoop($this);
        }
    }

    /**
     * Run execution loop listeners on user input.
     *
     * @param string $input
     */
    public function onInput(string $input): string
    {
        foreach ($this->loopListeners as $listeners) {
            if (($return = $listeners->onInput($this, $input)) !== null) {
                $input = $return;
            }
        }

        return $input;
    }

    /**
     * Run execution loop listeners on code to be executed.
     *
     * @param string $code
     */
    public function onExecute(string $code): string
    {
        $this->errorReporting = \error_reporting();

        foreach ($this->loopListeners as $listener) {
            if (($return = $listener->onExecute($this, $code)) !== null) {
                $code = $return;
            }
        }

        $output = $this->output;
        if ($output instanceof ConsoleOutput) {
            $output = $output->getErrorOutput();
        }

        $output->writeln(\sprintf('<whisper>%s</whisper>', OutputFormatter::escape($code)), ConsoleOutput::VERBOSITY_DEBUG);

        return $code;
    }

    /**
     * Run execution loop listeners after each loop.
     */
    public function afterLoop()
    {
        foreach ($this->loopListeners as $listener) {
            $listener->afterLoop($this);
        }
    }

    /**
     * Run execution loop listers after the shell session.
     */
    protected function afterRun()
    {
        foreach ($this->loopListeners as $listener) {
            $listener->afterRun($this);
        }
    }

    /**
     * Set the variables currently in scope.
     *
     * @param array $vars
     */
    public function setScopeVariables(array $vars)
    {
        $this->context->setAll($vars);
    }

    /**
     * Return the set of variables currently in scope.
     *
     * @param bool $includeBoundObject Pass false to exclude 'this'. If you're
     *                                 passing the scope variables to `extract`
     *                                 in PHP 7.1+, you _must_ exclude 'this'
     *
     * @return array Associative array of scope variables
     */
    public function getScopeVariables(bool $includeBoundObject = true): array
    {
        $vars = $this->context->getAll();

        if (!$includeBoundObject) {
            unset($vars['this']);
        }

        return $vars;
    }

    /**
     * Return the set of magic variables currently in scope.
     *
     * @param bool $includeBoundObject Pass false to exclude 'this'. If you're
     *                                 passing the scope variables to `extract`
     *                                 in PHP 7.1+, you _must_ exclude 'this'
     *
     * @return array Associative array of magic scope variables
     */
    public function getSpecialScopeVariables(bool $includeBoundObject = true): array
    {
        $vars = $this->context->getSpecialVariables();

        if (!$includeBoundObject) {
            unset($vars['this']);
        }

        return $vars;
    }

    /**
     * Return the set of variables currently in scope which differ from the
     * values passed as $currentVars.
     *
     * This is used inside the Execution Loop Closure to pick up scope variable
     * changes made by commands while the loop is running.
     *
     * @param array $currentVars
     *
     * @return array Associative array of scope variables which differ from $currentVars
     */
    public function getScopeVariablesDiff(array $currentVars): array
    {
        $newVars = [];

        foreach ($this->getScopeVariables(false) as $key => $value) {
            if (!\array_key_exists($key, $currentVars) || $currentVars[$key] !== $value) {
                $newVars[$key] = $value;
            }
        }

        return $newVars;
    }

    /**
     * Get the set of unused command-scope variable names.
     *
     * @return array Array of unused variable names
     */
    public function getUnusedCommandScopeVariableNames(): array
    {
        return $this->context->getUnusedCommandScopeVariableNames();
    }

    /**
     * Get the set of variable names currently in scope.
     *
     * @return array Array of variable names
     */
    public function getScopeVariableNames(): array
    {
        return \array_keys($this->context->getAll());
    }

    /**
     * Get a scope variable value by name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getScopeVariable(string $name)
    {
        return $this->context->get($name);
    }

    /**
     * Set the bound object ($this variable) for the interactive shell.
     *
     * @param object|null $boundObject
     */
    public function setBoundObject($boundObject)
    {
        $this->context->setBoundObject($boundObject);
    }

    /**
     * Get the bound object ($this variable) for the interactive shell.
     *
     * @return object|null
     */
    public function getBoundObject()
    {
        return $this->context->getBoundObject();
    }

    /**
     * Set the bound class (self) for the interactive shell.
     *
     * @param string|null $boundClass
     */
    public function setBoundClass($boundClass)
    {
        $this->context->setBoundClass($boundClass);
    }

    /**
     * Get the bound class (self) for the interactive shell.
     *
     * @return string|null
     */
    public function getBoundClass()
    {
        return $this->context->getBoundClass();
    }

    /**
     * Add includes, to be parsed and executed before running the interactive shell.
     *
     * @param array $includes
     */
    public function setIncludes(array $includes = [])
    {
        $this->includes = $includes;
    }

    /**
     * Get PHP files to be parsed and executed before running the interactive shell.
     *
     * @return string[]
     */
    public function getIncludes(): array
    {
        return \array_merge($this->config->getDefaultIncludes(), $this->includes);
    }

    /**
     * Check whether this shell's code buffer contains code.
     *
     * @return bool True if the code buffer contains code
     */
    public function hasCode(): bool
    {
        return !empty($this->codeBuffer);
    }

    /**
     * Check whether the code in this shell's code buffer is valid.
     *
     * If the code is valid, the code buffer should be flushed and evaluated.
     *
     * @return bool True if the code buffer content is valid
     */
    protected function hasValidCode(): bool
    {
        return !$this->codeBufferOpen && $this->code !== false;
    }

    /**
     * Add code to the code buffer.
     *
     * @param string $code
     * @param bool   $silent
     */
    public function addCode(string $code, bool $silent = false)
    {
        try {
            // Code lines ending in \ keep the buffer open
            if (\substr(\rtrim($code), -1) === '\\') {
                $this->codeBufferOpen = true;
                $code = \substr(\rtrim($code), 0, -1);
            } else {
                $this->codeBufferOpen = false;
            }

            $this->codeBuffer[] = $silent ? new SilentInput($code) : $code;
            $this->code = $this->cleaner->clean($this->codeBuffer, $this->config->requireSemicolons());
        } catch (\Throwable $e) {
            // Add failed code blocks to the readline history.
            $this->addCodeBufferToHistory();

            throw $e;
        }
    }

    /**
     * Set the code buffer.
     *
     * This is mostly used by `Shell::execute`. Any existing code in the input
     * buffer is pushed onto a stack and will come back after this new code is
     * executed.
     *
     * @throws \InvalidArgumentException if $code isn't a complete statement
     *
     * @param string $code
     * @param bool   $silent
     */
    private function setCode(string $code, bool $silent = false)
    {
        if ($this->hasCode()) {
            $this->codeStack[] = [$this->codeBuffer, $this->codeBufferOpen, $this->code];
        }

        $this->resetCodeBuffer();
        try {
            $this->addCode($code, $silent);
        } catch (\Throwable $e) {
            $this->popCodeStack();

            throw $e;
        }

        if (!$this->hasValidCode()) {
            $this->popCodeStack();

            throw new \InvalidArgumentException('Unexpected end of input');
        }
    }

    /**
     * Get the current code buffer.
     *
     * This is useful for commands which manipulate the buffer.
     *
     * @return string[]
     */
    public function getCodeBuffer(): array
    {
        return $this->codeBuffer;
    }

    /**
     * Run a Psy Shell command given the user input.
     *
     * @throws \InvalidArgumentException if the input is not a valid command
     *
     * @param string $input User input string
     *
     * @return mixed Who knows?
     */
    protected function runCommand(string $input)
    {
        $command = $this->getCommand($input);

        if (empty($command)) {
            throw new \InvalidArgumentException('Command not found: '.$input);
        }

        $input = new ShellInput(\str_replace('\\', '\\\\', \rtrim($input, " \t\n\r\0\x0B;")));

        if ($input->hasParameterOption(['--help', '-h'])) {
            $helpCommand = $this->get('help');
            if (!$helpCommand instanceof Command\HelpCommand) {
                throw new RuntimeException('Invalid help command instance');
            }
            $helpCommand->setCommand($command);

            return $helpCommand->run(new StringInput(''), $this->output);
        }

        return $command->run($input, $this->output);
    }

    /**
     * Reset the current code buffer.
     *
     * This should be run after evaluating user input, catching exceptions, or
     * on demand by commands such as BufferCommand.
     */
    public function resetCodeBuffer()
    {
        $this->codeBuffer = [];
        $this->code = false;
    }

    /**
     * Inject input into the input buffer.
     *
     * This is useful for commands which want to replay history.
     *
     * @param string|array $input
     * @param bool         $silent
     */
    public function addInput($input, bool $silent = false)
    {
        foreach ((array) $input as $line) {
            $this->inputBuffer[] = $silent ? new SilentInput($line) : $line;
        }
    }

    /**
     * Flush the current (valid) code buffer.
     *
     * If the code buffer is valid, resets the code buffer and returns the
     * current code.
     *
     * @return string|null PHP code buffer contents
     */
    public function flushCode()
    {
        if ($this->hasValidCode()) {
            $this->addCodeBufferToHistory();
            $code = $this->code;
            $this->popCodeStack();

            return $code;
        }
    }

    /**
     * Reset the code buffer and restore any code pushed during `execute` calls.
     */
    private function popCodeStack()
    {
        $this->resetCodeBuffer();

        if (empty($this->codeStack)) {
            return;
        }

        list($codeBuffer, $codeBufferOpen, $code) = \array_pop($this->codeStack);

        $this->codeBuffer = $codeBuffer;
        $this->codeBufferOpen = $codeBufferOpen;
        $this->code = $code;
    }

    /**
     * (Possibly) add a line to the readline history.
     *
     * Like Bash, if the line starts with a space character, it will be omitted
     * from history. Note that an entire block multi-line code input will be
     * omitted iff the first line begins with a space.
     *
     * Additionally, if a line is "silent", i.e. it was initially added with the
     * silent flag, it will also be omitted.
     *
     * @param string|SilentInput $line
     */
    private function addHistory($line)
    {
        if ($line instanceof SilentInput) {
            return;
        }

        // Skip empty lines and lines starting with a space
        if (\trim($line) !== '' && \substr($line, 0, 1) !== ' ') {
            $this->readline->addHistory($line);
        }
    }

    /**
     * Filter silent input from code buffer, write the rest to readline history.
     */
    private function addCodeBufferToHistory()
    {
        $codeBuffer = \array_filter($this->codeBuffer, function ($line) {
            return !$line instanceof SilentInput;
        });

        $this->addHistory(\implode("\n", $codeBuffer));
    }

    /**
     * Get the current evaluation scope namespace.
     *
     * @see CodeCleaner::getNamespace
     *
     * @return string|null Current code namespace
     */
    public function getNamespace()
    {
        if ($namespace = $this->cleaner->getNamespace()) {
            return \implode('\\', $namespace);
        }
    }

    /**
     * Write a string to stdout.
     *
     * This is used by the shell loop for rendering output from evaluated code.
     *
     * @param string $out
     * @param int    $phase Output buffering phase
     */
    public function writeStdout(string $out, int $phase = \PHP_OUTPUT_HANDLER_END)
    {
        if ($phase & \PHP_OUTPUT_HANDLER_START) {
            if ($this->output instanceof ShellOutput) {
                $this->output->startPaging();
            }
        }

        $isCleaning = $phase & \PHP_OUTPUT_HANDLER_CLEAN;

        // Incremental flush
        if ($out !== '' && !$isCleaning) {
            $this->output->write($out, false, OutputInterface::OUTPUT_RAW);
            $this->outputWantsNewline = (\substr($out, -1) !== "\n");
            $this->stdoutBuffer .= $out;
        }

        // Output buffering is done!
        if ($phase & \PHP_OUTPUT_HANDLER_END) {
            // Write an extra newline if stdout didn't end with one
            if ($this->outputWantsNewline) {
                if (!$this->config->rawOutput() && !$this->config->outputIsPiped()) {
                    $this->output->writeln(\sprintf('<whisper>%s</whisper>', $this->config->useUnicode() ? '⏎' : '\\n'));
                } else {
                    $this->output->writeln('');
                }
                $this->outputWantsNewline = false;
            }

            // Save the stdout buffer as $__out
            if ($this->stdoutBuffer !== '') {
                $this->context->setLastStdout($this->stdoutBuffer);
                $this->stdoutBuffer = '';
            }

            if ($this->output instanceof ShellOutput) {
                $this->output->stopPaging();
            }
        }
    }

    /**
     * Write a return value to stdout.
     *
     * The return value is formatted or pretty-printed, and rendered in a
     * visibly distinct manner (in this case, as cyan).
     *
     * @see self::presentValue
     *
     * @param mixed $ret
     * @param bool  $rawOutput Write raw var_export-style values
     */
    public function writeReturnValue($ret, bool $rawOutput = false)
    {
        $this->lastExecSuccess = true;

        if ($ret instanceof NoReturnValue) {
            return;
        }

        $this->context->setReturnValue($ret);

        if ($rawOutput) {
            $formatted = \var_export($ret, true);
        } else {
            $prompt = $this->config->theme()->returnValue();
            $indent = \str_repeat(' ', \strlen($prompt));
            $formatted = $this->presentValue($ret);
            $formattedRetValue = \sprintf('<whisper>%s</whisper>', $prompt);

            $formatted = $formattedRetValue.\str_replace(\PHP_EOL, \PHP_EOL.$indent, $formatted);
        }

        if ($this->output instanceof ShellOutput) {
            $this->output->page($formatted.\PHP_EOL);
        } else {
            $this->output->writeln($formatted);
        }
    }

    /**
     * Renders a caught Exception or Error.
     *
     * Exceptions are formatted according to severity. ErrorExceptions which were
     * warnings or Strict errors aren't rendered as harshly as real errors.
     *
     * Stores $e as the last Exception in the Shell Context.
     *
     * @param \Throwable $e An exception or error instance
     */
    public function writeException(\Throwable $e)
    {
        // No need to write the break exception during a non-interactive run.
        if ($e instanceof BreakException && $this->nonInteractive) {
            $this->resetCodeBuffer();

            return;
        }

        // Break exceptions don't count :)
        if (!$e instanceof BreakException) {
            $this->lastExecSuccess = false;
            $this->context->setLastException($e);
        }

        $output = $this->output;
        if ($output instanceof ConsoleOutput) {
            $output = $output->getErrorOutput();
        }

        if (!$this->config->theme()->compact()) {
            $output->writeln('');
        }

        $output->writeln($this->formatException($e));

        if (!$this->config->theme()->compact()) {
            $output->writeln('');
        }

        // Include an exception trace (as long as this isn't a BreakException).
        if (!$e instanceof BreakException && $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $trace = TraceFormatter::formatTrace($e);
            if (\count($trace) !== 0) {
                $output->writeln('--');
                $output->write($trace, true);
                $output->writeln('');
            }
        }

        $this->resetCodeBuffer();
    }

    /**
     * Check whether the last exec was successful.
     *
     * Returns true if a return value was logged rather than an exception.
     */
    public function getLastExecSuccess(): bool
    {
        return $this->lastExecSuccess;
    }

    /**
     * Helper for formatting an exception or error for writeException().
     *
     * @todo extract this to somewhere it makes more sense
     *
     * @param \Throwable $e
     */
    public function formatException(\Throwable $e): string
    {
        $indent = $this->config->theme()->compact() ? '' : '  ';

        if ($e instanceof BreakException) {
            return \sprintf('%s<info> INFO </info> %s.', $indent, \rtrim($e->getRawMessage(), '.'));
        } elseif ($e instanceof PsyException) {
            $message = $e->getLine() > 1
                ? \sprintf('%s in %s on line %d', $e->getRawMessage(), $e->getFile(), $e->getLine())
                : \sprintf('%s in %s', $e->getRawMessage(), $e->getFile());

            $messageLabel = \strtoupper($this->getMessageLabel($e));
        } else {
            $message = $e->getMessage();
            $messageLabel = $this->getMessageLabel($e);
        }

        $message = \preg_replace(
            "#(\\w:)?([\\\\/]\\w+)*[\\\\/]src[\\\\/]Execution(?:Loop)?Closure.php\(\d+\) : eval\(\)'d code#",
            "eval()'d code",
            $message
        );

        $message = \str_replace(" in eval()'d code", '', $message);
        $message = \trim($message);

        // Ensures the given string ends with punctuation...
        if (!empty($message) && !\in_array(\substr($message, -1), ['.', '?', '!', ':'])) {
            $message = "$message.";
        }

        // Ensures the given message only contains relative paths...
        $message = \str_replace(\getcwd().\DIRECTORY_SEPARATOR, '', $message);

        $severity = ($e instanceof \ErrorException) ? $this->getSeverity($e) : 'error';

        return \sprintf('%s<%s> %s </%s> %s', $indent, $severity, $messageLabel, $severity, OutputFormatter::escape($message));
    }

    /**
     * Helper for getting an output style for the given ErrorException's level.
     *
     * @param \ErrorException $e
     */
    protected function getSeverity(\ErrorException $e): string
    {
        $severity = $e->getSeverity();
        if ($severity & \error_reporting()) {
            switch ($severity) {
                case \E_WARNING:
                case \E_NOTICE:
                case \E_CORE_WARNING:
                case \E_COMPILE_WARNING:
                case \E_USER_WARNING:
                case \E_USER_NOTICE:
                case \E_USER_DEPRECATED:
                case \E_DEPRECATED:
                case \E_STRICT:
                    return 'warning';

                default:
                    return 'error';
            }
        } else {
            // Since this is below the user's reporting threshold, it's always going to be a warning.
            return 'warning';
        }
    }

    /**
     * Helper for getting an output style for the given ErrorException's level.
     *
     * @param \Throwable $e
     */
    protected function getMessageLabel(\Throwable $e): string
    {
        if ($e instanceof \ErrorException) {
            $severity = $e->getSeverity();

            if ($severity & \error_reporting()) {
                switch ($severity) {
                    case \E_WARNING:
                        return 'Warning';
                    case \E_NOTICE:
                        return 'Notice';
                    case \E_CORE_WARNING:
                        return 'Core Warning';
                    case \E_COMPILE_WARNING:
                        return 'Compile Warning';
                    case \E_USER_WARNING:
                        return 'User Warning';
                    case \E_USER_NOTICE:
                        return 'User Notice';
                    case \E_USER_DEPRECATED:
                        return 'User Deprecated';
                    case \E_DEPRECATED:
                        return 'Deprecated';
                    case \E_STRICT:
                        return 'Strict';
                }
            }
        }

        if ($e instanceof PsyException) {
            $exceptionShortName = (new \ReflectionClass($e))->getShortName();
            $typeParts = \preg_split('/(?=[A-Z])/', $exceptionShortName);
            \array_pop($typeParts); // Removes "Exception"

            return \trim(\strtoupper(\implode(' ', $typeParts)));
        }

        return \get_class($e);
    }

    /**
     * Execute code in the shell execution context.
     *
     * @param string $code
     * @param bool   $throwExceptions
     *
     * @return mixed
     */
    public function execute(string $code, bool $throwExceptions = false)
    {
        $this->setCode($code, true);
        $closure = new ExecutionClosure($this);

        if ($throwExceptions) {
            return $closure->execute();
        }

        try {
            return $closure->execute();
        } catch (\Throwable $_e) {
            $this->writeException($_e);
        }
    }

    /**
     * Helper for throwing an ErrorException.
     *
     * This allows us to:
     *
     *     set_error_handler([$psysh, 'handleError']);
     *
     * Unlike ErrorException::throwException, this error handler respects error
     * levels; i.e. it logs warnings and notices, but doesn't throw exceptions.
     * This should probably only be used in the inner execution loop of the
     * shell, as most of the time a thrown exception is much more useful.
     *
     * If the error type matches the `errorLoggingLevel` config, it will be
     * logged as well, regardless of the `error_reporting` level.
     *
     * @see \Psy\Exception\ErrorException::throwException
     * @see \Psy\Shell::writeException
     *
     * @throws \Psy\Exception\ErrorException depending on the error level
     *
     * @param int    $errno   Error type
     * @param string $errstr  Message
     * @param string $errfile Filename
     * @param int    $errline Line number
     */
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        // This is an error worth throwing.
        //
        // n.b. Technically we can't handle all of these in userland code, but
        // we'll list 'em all for good measure
        if ($errno & (\E_ERROR | \E_PARSE | \E_CORE_ERROR | \E_COMPILE_ERROR | \E_USER_ERROR | \E_RECOVERABLE_ERROR)) {
            ErrorException::throwException($errno, $errstr, $errfile, $errline);
        }

        // When errors are suppressed, the error_reporting value will differ
        // from when we started executing. In that case, we won't log errors.
        $errorsSuppressed = $this->errorReporting !== null && $this->errorReporting !== \error_reporting();

        // Otherwise log it and continue.
        if ($errno & \error_reporting() || (!$errorsSuppressed && ($errno & $this->config->errorLoggingLevel()))) {
            $this->writeException(new ErrorException($errstr, 0, $errno, $errfile, $errline));
        }
    }

    /**
     * Format a value for display.
     *
     * @see Presenter::present
     *
     * @param mixed $val
     *
     * @return string Formatted value
     */
    protected function presentValue($val): string
    {
        return $this->config->getPresenter()->present($val);
    }

    /**
     * Get a command (if one exists) for the current input string.
     *
     * @param string $input
     *
     * @return BaseCommand|null
     */
    protected function getCommand(string $input)
    {
        $input = new StringInput($input);
        if ($name = $input->getFirstArgument()) {
            return $this->get($name);
        }
    }

    /**
     * Check whether a command is set for the current input string.
     *
     * @param string $input
     *
     * @return bool True if the shell has a command for the given input
     */
    protected function hasCommand(string $input): bool
    {
        if (\preg_match('/([^\s]+?)(?:\s|$)/A', \ltrim($input), $match)) {
            return $this->has($match[1]);
        }

        return false;
    }

    /**
     * Get the current input prompt.
     *
     * @return string|null
     */
    protected function getPrompt()
    {
        if ($this->output->isQuiet()) {
            return null;
        }

        $theme = $this->config->theme();

        if ($this->hasCode()) {
            return $theme->bufferPrompt();
        }

        return $theme->prompt();
    }

    /**
     * Read a line of user input.
     *
     * This will return a line from the input buffer (if any exist). Otherwise,
     * it will ask the user for input.
     *
     * If readline is enabled, this delegates to readline. Otherwise, it's an
     * ugly `fgets` call.
     *
     * @param bool $interactive
     *
     * @return string|false One line of user input
     */
    protected function readline(bool $interactive = true)
    {
        $prompt = $this->config->theme()->replayPrompt();

        if (!empty($this->inputBuffer)) {
            $line = \array_shift($this->inputBuffer);
            if (!$line instanceof SilentInput) {
                $this->output->writeln(\sprintf('<whisper>%s</whisper><aside>%s</aside>', $prompt, OutputFormatter::escape($line)));
            }

            return $line;
        }

        $bracketedPaste = $interactive && $this->config->useBracketedPaste();

        if ($bracketedPaste) {
            \printf("\e[?2004h"); // Enable bracketed paste
        }

        $line = $this->readline->readline($this->getPrompt());

        if ($bracketedPaste) {
            \printf("\e[?2004l"); // ... and disable it again
        }

        return $line;
    }

    /**
     * Get the shell output header.
     */
    protected function getHeader(): string
    {
        return \sprintf('<whisper>%s by Justin Hileman</whisper>', $this->getVersion());
    }

    /**
     * Get the current version of Psy Shell.
     *
     * @deprecated call self::getVersionHeader instead
     */
    public function getVersion(): string
    {
        return self::getVersionHeader($this->config->useUnicode());
    }

    /**
     * Get a pretty header including the current version of Psy Shell.
     *
     * @param bool $useUnicode
     */
    public static function getVersionHeader(bool $useUnicode = false): string
    {
        $separator = $useUnicode ? '—' : '-';

        return \sprintf('Psy Shell %s (PHP %s %s %s)', self::VERSION, \PHP_VERSION, $separator, \PHP_SAPI);
    }

    /**
     * Get a PHP manual database instance.
     *
     * @return \PDO|null
     */
    public function getManualDb()
    {
        return $this->config->getManualDb();
    }

    /**
     * @deprecated Tab completion is provided by the AutoCompleter service
     */
    protected function autocomplete($text)
    {
        @\trigger_error('Tab completion is provided by the AutoCompleter service', \E_USER_DEPRECATED);
    }

    /**
     * Initialize tab completion matchers.
     *
     * If tab completion is enabled this adds tab completion matchers to the
     * auto completer and sets context if needed.
     */
    protected function initializeTabCompletion()
    {
        if (!$this->config->useTabCompletion()) {
            return;
        }

        $this->autoCompleter = $this->config->getAutoCompleter();

        // auto completer needs shell to be linked to configuration because of
        // the context aware matchers
        $this->addMatchersToAutoCompleter($this->getDefaultMatchers());
        $this->addMatchersToAutoCompleter($this->matchers);

        $this->autoCompleter->activate();
    }

    /**
     * Add matchers to the auto completer, setting context if needed.
     *
     * @param array $matchers
     */
    private function addMatchersToAutoCompleter(array $matchers)
    {
        foreach ($matchers as $matcher) {
            if ($matcher instanceof ContextAware) {
                $matcher->setContext($this->context);
            }
            $this->autoCompleter->addMatcher($matcher);
        }
    }

    /**
     * @todo Implement prompt to start update
     *
     * @return void|string
     */
    protected function writeVersionInfo()
    {
        if (\PHP_SAPI !== 'cli') {
            return;
        }

        try {
            $client = $this->config->getChecker();
            if (!$client->isLatest()) {
                $this->output->writeln(\sprintf('<whisper>New version is available at psysh.org/psysh (current: %s, latest: %s)</whisper>', self::VERSION, $client->getLatest()));
            }
        } catch (\InvalidArgumentException $e) {
            $this->output->writeln($e->getMessage());
        }
    }

    /**
     * Write a startup message if set.
     */
    protected function writeStartupMessage()
    {
        $message = $this->config->getStartupMessage();
        if ($message !== null && $message !== '') {
            $this->output->writeln($message);
        }
    }
}
