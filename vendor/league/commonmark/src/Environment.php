<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark;

use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\Delimiter\Processor\DelimiterProcessorCollection;
use League\CommonMark\Delimiter\Processor\DelimiterProcessorInterface;
use League\CommonMark\Event\AbstractEvent;
use League\CommonMark\Extension\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use League\CommonMark\Util\Configuration;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\Util\PrioritizedList;

final class Environment implements ConfigurableEnvironmentInterface
{
    /**
     * @var ExtensionInterface[]
     */
    private $extensions = [];

    /**
     * @var ExtensionInterface[]
     */
    private $uninitializedExtensions = [];

    /**
     * @var bool
     */
    private $extensionsInitialized = false;

    /**
     * @var PrioritizedList<BlockParserInterface>
     */
    private $blockParsers;

    /**
     * @var PrioritizedList<InlineParserInterface>
     */
    private $inlineParsers;

    /**
     * @var array<string, PrioritizedList<InlineParserInterface>>
     */
    private $inlineParsersByCharacter = [];

    /**
     * @var DelimiterProcessorCollection
     */
    private $delimiterProcessors;

    /**
     * @var array<string, PrioritizedList<BlockRendererInterface>>
     */
    private $blockRenderersByClass = [];

    /**
     * @var array<string, PrioritizedList<InlineRendererInterface>>
     */
    private $inlineRenderersByClass = [];

    /**
     * @var array<string, PrioritizedList<callable>>
     */
    private $listeners = [];

    /**
     * @var Configuration
     */
    private $config;

    /**
     * @var string
     */
    private $inlineParserCharacterRegex;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = new Configuration($config);

        $this->blockParsers = new PrioritizedList();
        $this->inlineParsers = new PrioritizedList();
        $this->delimiterProcessors = new DelimiterProcessorCollection();
    }

    public function mergeConfig(array $config = [])
    {
        $this->assertUninitialized('Failed to modify configuration.');

        $this->config->merge($config);
    }

    public function setConfig(array $config = [])
    {
        $this->assertUninitialized('Failed to modify configuration.');

        $this->config->replace($config);
    }

    public function getConfig($key = null, $default = null)
    {
        return $this->config->get($key, $default);
    }

    public function addBlockParser(BlockParserInterface $parser, int $priority = 0): ConfigurableEnvironmentInterface
    {
        $this->assertUninitialized('Failed to add block parser.');

        $this->blockParsers->add($parser, $priority);
        $this->injectEnvironmentAndConfigurationIfNeeded($parser);

        return $this;
    }

    public function addInlineParser(InlineParserInterface $parser, int $priority = 0): ConfigurableEnvironmentInterface
    {
        $this->assertUninitialized('Failed to add inline parser.');

        $this->inlineParsers->add($parser, $priority);
        $this->injectEnvironmentAndConfigurationIfNeeded($parser);

        foreach ($parser->getCharacters() as $character) {
            if (!isset($this->inlineParsersByCharacter[$character])) {
                $this->inlineParsersByCharacter[$character] = new PrioritizedList();
            }

            $this->inlineParsersByCharacter[$character]->add($parser, $priority);
        }

        return $this;
    }

    public function addDelimiterProcessor(DelimiterProcessorInterface $processor): ConfigurableEnvironmentInterface
    {
        $this->assertUninitialized('Failed to add delimiter processor.');
        $this->delimiterProcessors->add($processor);
        $this->injectEnvironmentAndConfigurationIfNeeded($processor);

        return $this;
    }

    public function addBlockRenderer($blockClass, BlockRendererInterface $blockRenderer, int $priority = 0): ConfigurableEnvironmentInterface
    {
        $this->assertUninitialized('Failed to add block renderer.');

        if (!isset($this->blockRenderersByClass[$blockClass])) {
            $this->blockRenderersByClass[$blockClass] = new PrioritizedList();
        }

        $this->blockRenderersByClass[$blockClass]->add($blockRenderer, $priority);
        $this->injectEnvironmentAndConfigurationIfNeeded($blockRenderer);

        return $this;
    }

    public function addInlineRenderer(string $inlineClass, InlineRendererInterface $renderer, int $priority = 0): ConfigurableEnvironmentInterface
    {
        $this->assertUninitialized('Failed to add inline renderer.');

        if (!isset($this->inlineRenderersByClass[$inlineClass])) {
            $this->inlineRenderersByClass[$inlineClass] = new PrioritizedList();
        }

        $this->inlineRenderersByClass[$inlineClass]->add($renderer, $priority);
        $this->injectEnvironmentAndConfigurationIfNeeded($renderer);

        return $this;
    }

    public function getBlockParsers(): iterable
    {
        if (!$this->extensionsInitialized) {
            $this->initializeExtensions();
        }

        return $this->blockParsers->getIterator();
    }

    public function getInlineParsersForCharacter(string $character): iterable
    {
        if (!$this->extensionsInitialized) {
            $this->initializeExtensions();
        }

        if (!isset($this->inlineParsersByCharacter[$character])) {
            return [];
        }

        return $this->inlineParsersByCharacter[$character]->getIterator();
    }

    public function getDelimiterProcessors(): DelimiterProcessorCollection
    {
        if (!$this->extensionsInitialized) {
            $this->initializeExtensions();
        }

        return $this->delimiterProcessors;
    }

    public function getBlockRenderersForClass(string $blockClass): iterable
    {
        if (!$this->extensionsInitialized) {
            $this->initializeExtensions();
        }

        return $this->getRenderersByClass($this->blockRenderersByClass, $blockClass, BlockRendererInterface::class);
    }

    public function getInlineRenderersForClass(string $inlineClass): iterable
    {
        if (!$this->extensionsInitialized) {
            $this->initializeExtensions();
        }

        return $this->getRenderersByClass($this->inlineRenderersByClass, $inlineClass, InlineRendererInterface::class);
    }

    /**
     * Get all registered extensions
     *
     * @return ExtensionInterface[]
     */
    public function getExtensions(): iterable
    {
        return $this->extensions;
    }

    /**
     * Add a single extension
     *
     * @param ExtensionInterface $extension
     *
     * @return $this
     */
    public function addExtension(ExtensionInterface $extension): ConfigurableEnvironmentInterface
    {
        $this->assertUninitialized('Failed to add extension.');

        $this->extensions[] = $extension;
        $this->uninitializedExtensions[] = $extension;

        return $this;
    }

    private function initializeExtensions(): void
    {
        // Ask all extensions to register their components
        while (!empty($this->uninitializedExtensions)) {
            foreach ($this->uninitializedExtensions as $i => $extension) {
                $extension->register($this);
                unset($this->uninitializedExtensions[$i]);
            }
        }

        $this->extensionsInitialized = true;

        // Lastly, let's build a regex which matches non-inline characters
        // This will enable a huge performance boost with inline parsing
        $this->buildInlineParserCharacterRegex();
    }

    /**
     * @param object $object
     */
    private function injectEnvironmentAndConfigurationIfNeeded($object): void
    {
        if ($object instanceof EnvironmentAwareInterface) {
            $object->setEnvironment($this);
        }

        if ($object instanceof ConfigurationAwareInterface) {
            $object->setConfiguration($this->config);
        }
    }

    public static function createCommonMarkEnvironment(): ConfigurableEnvironmentInterface
    {
        $environment = new static();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->mergeConfig([
            'renderer' => [
                'block_separator' => "\n",
                'inner_separator' => "\n",
                'soft_break'      => "\n",
            ],
            'html_input'         => self::HTML_INPUT_ALLOW,
            'allow_unsafe_links' => true,
            'max_nesting_level'  => \INF,
        ]);

        return $environment;
    }

    public static function createGFMEnvironment(): ConfigurableEnvironmentInterface
    {
        $environment = self::createCommonMarkEnvironment();
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        return $environment;
    }

    public function getInlineParserCharacterRegex(): string
    {
        return $this->inlineParserCharacterRegex;
    }

    public function addEventListener(string $eventClass, callable $listener, int $priority = 0): ConfigurableEnvironmentInterface
    {
        $this->assertUninitialized('Failed to add event listener.');

        if (!isset($this->listeners[$eventClass])) {
            $this->listeners[$eventClass] = new PrioritizedList();
        }

        $this->listeners[$eventClass]->add($listener, $priority);

        if (\is_object($listener)) {
            $this->injectEnvironmentAndConfigurationIfNeeded($listener);
        } elseif (\is_array($listener) && \is_object($listener[0])) {
            $this->injectEnvironmentAndConfigurationIfNeeded($listener[0]);
        }

        return $this;
    }

    public function dispatch(AbstractEvent $event): void
    {
        if (!$this->extensionsInitialized) {
            $this->initializeExtensions();
        }

        $type = \get_class($event);

        foreach ($this->listeners[$type] ?? [] as $listener) {
            if ($event->isPropagationStopped()) {
                return;
            }

            $listener($event);
        }
    }

    private function buildInlineParserCharacterRegex(): void
    {
        $chars = \array_unique(\array_merge(
            \array_keys($this->inlineParsersByCharacter),
            $this->delimiterProcessors->getDelimiterCharacters()
        ));

        if (empty($chars)) {
            // If no special inline characters exist then parse the whole line
            $this->inlineParserCharacterRegex = '/^.+$/';
        } else {
            // Match any character which inline parsers are not interested in
            $this->inlineParserCharacterRegex = '/^[^' . \preg_quote(\implode('', $chars), '/') . ']+/';

            // Only add the u modifier (which slows down performance) if we have a multi-byte UTF-8 character in our regex
            if (\strlen($this->inlineParserCharacterRegex) > \mb_strlen($this->inlineParserCharacterRegex)) {
                $this->inlineParserCharacterRegex .= 'u';
            }
        }
    }

    /**
     * @param string $message
     *
     * @throws \RuntimeException
     */
    private function assertUninitialized(string $message): void
    {
        if ($this->extensionsInitialized) {
            throw new \RuntimeException($message . ' Extensions have already been initialized.');
        }
    }

    /**
     * @param array<string, PrioritizedList> $list
     * @param string                         $class
     * @param string                         $type
     *
     * @return iterable
     *
     * @phpstan-template T
     *
     * @phpstan-param array<string, PrioritizedList<T>> $list
     * @phpstan-param string                            $class
     * @phpstan-param class-string<T>                   $type
     *
     * @phpstan-return iterable<T>
     */
    private function getRenderersByClass(array &$list, string $class, string $type): iterable
    {
        // If renderers are defined for this specific class, return them immediately
        if (isset($list[$class])) {
            return $list[$class];
        }

        while (\class_exists($parent = $parent ?? $class) && $parent = \get_parent_class($parent)) {
            if (!isset($list[$parent])) {
                continue;
            }

            // "Cache" this result to avoid future loops
            return $list[$class] = $list[$parent];
        }

        return [];
    }
}
