<?php
declare(strict_types=1);

namespace Plank\Mediable\SourceAdapters;

use Plank\Mediable\Exceptions\MediaUpload\ConfigurationException;

/**
 * Source Adapter Factory.
 *
 * Generates SourceAdapter instances for different sources
 */
class SourceAdapterFactory
{
    /**
     * Map of which adapters to use for a given source class.
     * @var string[]
     */
    private $classAdapters = [];

    /**
     * Map of which adapters to use for a given string pattern.
     * @var string[]
     */
    private $patternAdapters = [];

    /**
     * Create a Source Adapter for the provided source.
     * @param  object|string|resource $source
     * @return SourceAdapterInterface
     * @throws ConfigurationException If the provided source does not match any of the mapped classes or patterns
     */
    public function create($source): SourceAdapterInterface
    {
        $adapter = null;

        if ($source instanceof SourceAdapterInterface) {
            return $source;
        } elseif (is_object($source)) {
            $adapter = $this->adaptClass($source);
        } elseif (is_resource($source)) {
            $adapter = StreamResourceAdapter::class;
        } elseif (is_string($source)) {
            $adapter = $this->adaptString($source);
        }

        if ($adapter) {
            return new $adapter($source);
        }

        throw ConfigurationException::unrecognizedSource($source);
    }

    /**
     * Specify the FQCN of a SourceAdapter class to use when the source inherits from a given class.
     * @param string $adapterClass
     * @param string $sourceClass
     * @return void
     *
     * @throws ConfigurationException
     */
    public function setAdapterForClass(string $adapterClass, string $sourceClass): void
    {
        $this->validateAdapterClass($adapterClass);
        $this->classAdapters[$sourceClass] = $adapterClass;
    }

    /**
     * Specify the FQCN of a SourceAdapter class to use when the source is a string matching the given pattern.
     * @param string $adapterClass
     * @param string $sourcePattern
     * @return void
     *
     * @throws ConfigurationException
     */
    public function setAdapterForPattern(string $adapterClass, string $sourcePattern): void
    {
        $this->validateAdapterClass($adapterClass);
        $this->patternAdapters[$sourcePattern] = $adapterClass;
    }

    /**
     * Choose an adapter class for the class of the provided object.
     * @param  object $source
     * @return string|null
     */
    private function adaptClass(object $source): ?string
    {
        foreach ($this->classAdapters as $class => $adapter) {
            if ($source instanceof $class) {
                return $adapter;
            }
        }

        return null;
    }

    /**
     * Choose an adapter class for the provided string.
     * @param  string $source
     * @return string|null
     */
    private function adaptString(string $source): ?string
    {
        foreach ($this->patternAdapters as $pattern => $adapter) {
            $pattern = '/' . str_replace('/', '\\/', $pattern) . '/i';
            if (preg_match($pattern, $source)) {
                return $adapter;
            }
        }

        return null;
    }

    /**
     * Verify that the provided class implements the SourceAdapter interface.
     * @param  string $class
     * @throws ConfigurationException If class is not valid
     * @return void
     */
    private function validateAdapterClass(string $class): void
    {
        $implements = class_implements($class, true);

        if (!in_array(SourceAdapterInterface::class, $implements)) {
            throw ConfigurationException::cannotSetAdapter($class);
        }
    }
}
