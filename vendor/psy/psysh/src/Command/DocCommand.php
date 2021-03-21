<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2020 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Command;

use Psy\Formatter\DocblockFormatter;
use Psy\Formatter\SignatureFormatter;
use Psy\Input\CodeArgument;
use Psy\Reflection\ReflectionClassConstant;
use Psy\Reflection\ReflectionConstant_;
use Psy\Reflection\ReflectionLanguageConstruct;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Read the documentation for an object, class, constant, method or property.
 */
class DocCommand extends ReflectingCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('doc')
            ->setAliases(['rtfm', 'man'])
            ->setDefinition([
                new InputOption('all', 'a', InputOption::VALUE_NONE, 'Show documentation for superclasses as well as the current class.'),
                new CodeArgument('target', CodeArgument::REQUIRED, 'Function, class, instance, constant, method or property to document.'),
            ])
            ->setDescription('Read the documentation for an object, class, constant, method or property.')
            ->setHelp(
                <<<HELP
Read the documentation for an object, class, constant, method or property.

It's awesome for well-documented code, not quite as awesome for poorly documented code.

e.g.
<return>>>> doc preg_replace</return>
<return>>>> doc Psy\Shell</return>
<return>>>> doc Psy\Shell::debug</return>
<return>>>> \$s = new Psy\Shell</return>
<return>>>> doc \$s->run</return>
HELP
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $value = $input->getArgument('target');
        if (ReflectionLanguageConstruct::isLanguageConstruct($value)) {
            $reflector = new ReflectionLanguageConstruct($value);
            $doc = $this->getManualDocById($value);
        } else {
            list($target, $reflector) = $this->getTargetAndReflector($value);
            $doc = $this->getManualDoc($reflector) ?: DocblockFormatter::format($reflector);
        }

        $db = $this->getApplication()->getManualDb();

        if ($output instanceof ShellOutput) {
            $output->startPaging();
        }

        // Maybe include the declaring class
        if ($reflector instanceof \ReflectionMethod || $reflector instanceof \ReflectionProperty) {
            $output->writeln(SignatureFormatter::format($reflector->getDeclaringClass()));
        }

        $output->writeln(SignatureFormatter::format($reflector));
        $output->writeln('');

        if (empty($doc) && !$db) {
            $output->writeln('<warning>PHP manual not found</warning>');
            $output->writeln('    To document core PHP functionality, download the PHP reference manual:');
            $output->writeln('    https://github.com/bobthecow/psysh/wiki/PHP-manual');
        } else {
            $output->writeln($doc);
        }

        if ($input->getOption('all')) {
            $parent = $reflector;
            foreach ($this->getParentReflectors($reflector) as $parent) {
                $output->writeln('');
                $output->writeln('---');
                $output->writeln('');

                // Maybe include the declaring class
                if ($parent instanceof \ReflectionMethod || $parent instanceof \ReflectionProperty) {
                    $output->writeln(SignatureFormatter::format($parent->getDeclaringClass()));
                }

                $output->writeln(SignatureFormatter::format($parent));
                $output->writeln('');

                if ($doc = $this->getManualDoc($parent) ?: DocblockFormatter::format($parent)) {
                    $output->writeln($doc);
                }
            }
        }

        if ($output instanceof ShellOutput) {
            $output->stopPaging();
        }

        // Set some magic local variables
        $this->setCommandScopeVariables($reflector);

        return 0;
    }

    private function getManualDoc($reflector)
    {
        switch (\get_class($reflector)) {
            case \ReflectionClass::class:
            case \ReflectionObject::class:
            case \ReflectionFunction::class:
                $id = $reflector->name;
                break;

            case \ReflectionMethod::class:
                $id = $reflector->class.'::'.$reflector->name;
                break;

            case \ReflectionProperty::class:
                $id = $reflector->class.'::$'.$reflector->name;
                break;

            case \ReflectionClassConstant::class:
            case ReflectionClassConstant::class:
                // @todo this is going to collide with ReflectionMethod ids
                // someday... start running the query by id + type if the DB
                // supports it.
                $id = $reflector->class.'::'.$reflector->name;
                break;

            case ReflectionConstant_::class:
                $id = $reflector->name;
                break;

            default:
                return false;
        }

        return $this->getManualDocById($id);
    }

    /**
     * Get all all parent Reflectors for a given Reflector.
     *
     * For example, passing a Class, Object or TraitReflector will yield all
     * traits and parent classes. Passing a Method or PropertyReflector will
     * yield Reflectors for the same-named method or property on all traits and
     * parent classes.
     *
     * @return Generator a whole bunch of \Reflector instances
     */
    private function getParentReflectors($reflector)
    {
        switch (\get_class($reflector)) {
            case \ReflectionClass::class:
            case \ReflectionObject::class:
                foreach ($reflector->getTraits() as $trait) {
                    yield $trait;
                }

                foreach ($reflector->getInterfaces() as $interface) {
                    yield $interface;
                }

                while ($reflector = $reflector->getParentClass()) {
                    yield $reflector;

                    foreach ($reflector->getTraits() as $trait) {
                        yield $trait;
                    }

                    foreach ($reflector->getInterfaces() as $interface) {
                        yield $interface;
                    }
                }

                return;

            case \ReflectionMethod::class:
                foreach ($this->getParentReflectors($reflector->getDeclaringClass()) as $parent) {
                    if ($parent->hasMethod($reflector->getName())) {
                        yield $parent->getMethod($reflector->getName());
                    }
                }

                return;

            case \ReflectionProperty::class:
                foreach ($this->getParentReflectors($reflector->getDeclaringClass()) as $parent) {
                    if ($parent->hasProperty($reflector->getName())) {
                        yield $parent->getProperty($reflector->getName());
                    }
                }
                break;
        }
    }

    private function getManualDocById($id)
    {
        if ($db = $this->getApplication()->getManualDb()) {
            return $db
                ->query(\sprintf('SELECT doc FROM php_manual WHERE id = %s', $db->quote($id)))
                ->fetchColumn(0);
        }
    }
}
