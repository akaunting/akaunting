<?php

namespace Dingo\Blueprint;

use Dingo\Blueprint\Annotation\Attribute;
use Dingo\Blueprint\Annotation\NamedType;
use Dingo\Blueprint\Annotation\Parameter;
use ReflectionClass;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\SimpleAnnotationReader;

class Blueprint
{
    const T_NUMBER = 'number';
    const T_FLOAT = 'float';
    const T_ARRAY = 'array';
    const T_OBJECT = 'object';
    const T_STRING = 'string';

    /**
     * Simple annotation reader instance.
     *
     * @var \Doctrine\Common\Annotations\SimpleAnnotationReader
     */
    protected $reader;

    /**
     * Filesytsem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Include path for documentation files.
     *
     * @var string
     */
    protected $includePath;

    /**
     * Create a new generator instance.
     *
     * @param \Doctrine\Common\Annotations\SimpleAnnotationReader $reader
     * @param \Illuminate\Filesystem\Filesystem                   $files
     *
     * @return void
     */
    public function __construct(SimpleAnnotationReader $reader, Filesystem $files)
    {
        $this->reader = $reader;
        $this->files = $files;

        $this->registerAnnotationLoader();
    }

    /**
     * Register the annotation loader.
     *
     * @return void
     */
    protected function registerAnnotationLoader()
    {
        $this->reader->addNamespace('Dingo\\Blueprint\\Annotation');
        $this->reader->addNamespace('Dingo\\Blueprint\\Annotation\\Method');

        AnnotationRegistry::registerLoader(function ($class) {
            $path = __DIR__.'/'.str_replace(['Dingo\\Blueprint\\', '\\'], ['', DIRECTORY_SEPARATOR], $class).'.php';

            if (file_exists($path)) {
                require_once $path;

                return true;
            }
        });
    }

    /**
     * Generate documentation with the name, version and optional overview content.
     *
     * @param \Illuminate\Support\Collection $controllers
     * @param string                         $name
     * @param string                         $version
     * @param string                         $includePath
     * @param string                         $overviewFile
     *
     * @return bool
     */
    public function generate(Collection $controllers, $name, $version, $includePath = null, $overviewFile = null)
    {
        $this->includePath = $includePath;

        $resources = $controllers->map(function ($controller) use ($version) {
            $controller = $controller instanceof ReflectionClass ? $controller : new ReflectionClass($controller);

            $actions = new Collection;

            // Spin through all the methods on the controller and compare the version
            // annotation (if supplied) with the version given for the generation.
            // We'll also build up an array of actions on each resource.
            foreach ($controller->getMethods() as $method) {
                if ($versionAnnotation = $this->reader->getMethodAnnotation($method, Annotation\Versions::class)) {
                    if (! in_array($version, $versionAnnotation->value)) {
                        continue;
                    }
                }

                if ($annotations = $this->reader->getMethodAnnotations($method)) {
                    if (! $actions->contains($method)) {
                        $actions->push(new Action($method, new Collection($annotations)));
                    }
                }
            }

            $annotations = new Collection($this->reader->getClassAnnotations($controller));
            return new RestResource($controller->getName(), $controller, $annotations, $actions);
        });

        $contents = $this->generateContentsFromResources($resources, $name, $overviewFile);

        $this->includePath = null;

        return $contents;
    }

    /**
     * Generate the documentation contents from the resources collection.
     *
     * @param \Illuminate\Support\Collection $resources
     * @param string                         $name
     * @param string                         $overviewFile
     *
     * @return string
     */
    protected function generateContentsFromResources(Collection $resources, $name, $overviewFile = null)
    {
        $typeDefinitions = '';
        $contents = '';

        $typeDefinitions .= $this->getFormat();
        $typeDefinitions .= $this->line(2);
        $typeDefinitions .= sprintf('# %s', $name);
        $typeDefinitions .= $this->line(2);
        $typeDefinitions .= $this->getOverview($overviewFile);

        $resources->each(function ($resource) use (&$contents, &$typeDefinitions, $name) {
            if ($resource->getActions()->isEmpty()) {
                return;
            }

            $contents .= $resource->getDefinition();

            if ($description = $resource->getDescription()) {
                $contents .= $this->line();
                $contents .= $description;
            }

            if (($annotations = $resource->getAnnotations()) instanceof Collection) {
                foreach($annotations as $annotation) {
                    if ($annotation instanceof NamedType) {
                        if (empty($annotation->name)) {
                            throw new \RuntimeException('NamedType has no name');
                        }

                        $this->appendTypes($typeDefinitions, new Collection([$annotation]));
                    }
                }
            }

            if (($parameters = $resource->getParameters()) && ! $parameters->isEmpty()) {
                $this->appendParameters($contents, $parameters);
            }

            $resource->getActions()->each(function ($action) use (&$contents, &$typeDefinitions, $resource) {
                $contents .= $this->line(2);
                $contents .= $action->getDefinition();

                if ($description = $action->getDescription()) {
                    $contents .= $this->line();
                    $contents .= $description;
                }

                if (($types = $action->getTypes()) && ! $types->isEmpty()) {
                    $this->appendTypes($typeDefinitions, $types);
                }

                if (($attributes = $action->getAttributes()) && ! $attributes->isEmpty()) {
                    $this->appendAttributes($contents, $attributes);
                }

                if (($parameters = $action->getParameters()) && ! $parameters->isEmpty()) {
                    $this->appendParameters($contents, $parameters);
                }

                if ($request = $action->getRequest()) {
                    $this->appendRequest($contents, $request, $resource);
                }

                if ($response = $action->getResponse()) {
                    $this->appendResponse($contents, $response, $resource);
                }

                if ($transaction = $action->getTransaction()) {
                    foreach ($transaction->value as $value) {
                        if ($value instanceof Annotation\Request) {
                            $this->appendRequest($contents, $value, $resource);
                        } elseif ($value instanceof Annotation\Response) {
                            $this->appendResponse($contents, $value, $resource);
                        } else {
                            throw new RuntimeException('Unsupported annotation type given in transaction.');
                        }
                    }
                }
            });

            $contents .= $this->line(2);
        });

        return stripslashes(trim($typeDefinitions) . "\n\n" . trim($contents));
    }

    /**
     * @param Attribute $attribute
     * @return string
     */
    protected function resolveAttributeType(Attribute $attribute) {
        if ($attribute->type) {
            return $attribute->type;
        }

        if (is_int($attribute->sample)) {
            return static::T_NUMBER;
        }

        if (is_float($attribute->sample)) {
            return static::T_NUMBER;
        }

        if (is_array($attribute->sample)) {
            return isset($attribute->sample[0])
                ? static::T_ARRAY
                : static::T_OBJECT
                ;
        }

        return static::T_STRING;
    }
    /**
     * @param Parameter $parameter
     * @return string
     */
    protected function resolveParameterType(Parameter $parameter) {
        if ($parameter->type) {
            return $parameter->type;
        }

        if (is_int($parameter->example)) {
            return static::T_NUMBER;
        }

        if (is_float($parameter->example)) {
            return static::T_NUMBER;
        }

        if (is_array($parameter->example)) {
            return isset($parameter->example[0])
                ? static::T_ARRAY
                : static::T_OBJECT
                ;
        }

        return static::T_STRING;
    }

    /**
     * Append the attributes subsection to a resource or action.
     *
     * @param string                         $contents
     * @param \Illuminate\Support\Collection $attributes
     * @param int                            $indent
     *
     * @return void
     */
    protected function appendAttributes(&$contents, Collection $attributes, $indent = 0)
    {
        $this->appendSection($contents, 'Attributes', $indent);

        $attributes->each(function ($attribute) use (&$contents, $indent) {
            $contents .= $this->line();
            $contents .= $this->tab(1 + $indent);
            $contents .= sprintf('+ %s', $attribute->identifier);

            if ($attribute->sample) {
                $contents .= sprintf(': %s', is_array($attribute->sample)
                    ? json_encode($attribute->sample)
                    : $attribute->sample)
                ;
            }

            $contents .= sprintf(
                ' (%s, %s) - %s',
                $this->resolveAttributeType($attribute),
                $attribute->required ? 'required' : 'optional',
                $attribute->description
            );
        });
    }
    /**
     * Append the types subsection to a resource or action.
     *
     * @param string                         $contents
     * @param \Illuminate\Support\Collection $types
     *
     * @return void
     */
    protected function appendTypes(&$contents, Collection $types)
    {
        $types->each(
        /**
         * @param NamedType $type
         */
            function ($type) use (&$contents) {
                $contents .= $this->line();
                $contents .= $this->tab(0);
                $contents .= sprintf(
                    "# %s (%s) \n%s\n",
                    $type->name,
                    $type->derivedFrom,
                    $type->description ?? ''
                );

                if ($type->properties !== NULL) {
                    $contents .= sprintf(
                        "# Properties\n",
                        $type->name,
                        $type->derivedFrom,
                        $type->description ?? ''
                    );
                    foreach ($type->properties as $member) {
                        $this->appendSection($contents, sprintf(
                            '`%s` (%s) - %s',
                            $member->name,
                            $member->type ?? static::T_STRING,
                            $member->sample),
                            1, 0
                        );
                    }
                }

                $contents .= $this->line(2);
            });
    }

    /**
     * Append the parameters subsection to a resource or action.
     *
     * @param string                         $contents
     * @param \Illuminate\Support\Collection $parameters
     *
     * @return void
     */
    protected function appendParameters(&$contents, Collection $parameters)
    {
        $this->appendSection($contents, 'Parameters');

        $parameters->each(function ($parameter) use (&$contents) {
            $example = $parameter->example && is_array($parameter->example)
                ? json_encode($parameter->example)
                : $parameter->example
            ;

            $type = $this->resolveParameterType($parameter);
            $contents .= $this->line();
            $contents .= $this->tab();
            $contents .= $this->getParameterContent($parameter);

            if (isset($parameter->default)) {
                $this->appendSection($contents, sprintf('Default: %s', $parameter->default), 2, 1);
            }

            if (isset($parameter->members)) {
                $this->appendSection($contents, 'Members', 2, 1);
                foreach ($parameter->members as $member) {
                    $this->appendSection($contents, sprintf('`%s` - %s', $member->identifier, $member->description), 3, 1);
                }
            }
        });
    }

    /**
     * Append a response subsection to an action.
     *
     * @param string                               $contents
     * @param \Dingo\Blueprint\Annotation\Response $response
     * @param \Dingo\Blueprint\RestResource            $resource
     *
     * @return void
     */
    protected function appendResponse(&$contents, Annotation\Response $response, RestResource $resource)
    {
        $this->appendSection($contents, sprintf('Response %s', $response->statusCode));

        if (isset($response->contentType)) {
            $contents .= ' ('.$response->contentType.')';
        }

        if (! empty($response->headers) || $resource->hasResponseHeaders()) {
            $this->appendHeaders($contents, array_merge($resource->getResponseHeaders(), $response->headers));
        }

        if (isset($response->attributes)) {
            $this->appendAttributes($contents, collect($response->attributes), 1);
        }

        if (isset($response->body)) {
            $this->appendBody($contents, $this->prepareBody($response->body, $response->contentType));
        }
    }

    /**
     * Append a request subsection to an action.
     *
     * @param string                              $contents
     * @param \Dingo\Blueprint\Annotation\Request $request
     * @param \Dingo\Blueprint\RestResource           $resource
     *
     * @return void
     */
    protected function appendRequest(&$contents, $request, RestResource $resource)
    {
        $this->appendSection($contents, 'Request');

        if (isset($request->identifier)) {
            $contents .= ' '.$request->identifier;
        }

        $contents .= ' ('.$request->contentType.')';

        if (! empty($request->headers) || $resource->hasRequestHeaders()) {
            $this->appendHeaders($contents, array_merge($resource->getRequestHeaders(), $request->headers));
        }

        if (isset($request->attributes)) {
            $this->appendAttributes($contents, collect($request->attributes), 1);
        }

        if (isset($request->body)) {
            $this->appendBody($contents, $this->prepareBody($request->body, $request->contentType));
        }
    }

    /**
     * Append a body subsection to an action.
     *
     * @param string $contents
     * @param string $body
     *
     * @return void
     */
    protected function appendBody(&$contents, $body)
    {
        $this->appendSection($contents, 'Body', 1, 1);

        $contents .= $this->line(2);

        $line = strtok($body, "\r\n");

        while ($line !== false) {
            $contents .= $this->tab(3).$line;

            $line = strtok("\r\n");

            if ($line !== false) {
                $contents .= $this->line();
            }
        }
    }

    /**
     * Append a headers subsection to an action.
     *
     * @param string $contents
     * @param array  $headers
     *
     * @return void
     */
    protected function appendHeaders(&$contents, array $headers)
    {
        $this->appendSection($contents, 'Headers', 1, 1);

        $contents .= $this->line();

        foreach ($headers as $header => $value) {
            $contents .= $this->line().$this->tab(3).sprintf('%s: %s', $header, $value);
        }
    }

    /**
     * Append a subsection to an action.
     *
     * @param string $contents
     * @param string $name
     * @param int $indent
     * @param int $lines
     *
     * @param string $prefix
     * @return void
     */
    protected function appendSection(&$contents, $name, $indent = 0, $lines = 2, $prefix = '+ ')
    {
        $contents .= $this->line($lines);
        $contents .= $this->tab($indent);
        $contents .= $prefix.$name;
    }

    /**
     * Prepare a body.
     *
     * @param string $body
     * @param string $contentType
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function prepareBody($body, $contentType)
    {
        if (is_string($body) && Str::startsWith($body, ['json', 'file'])) {
            list($type, $path) = explode(':', $body);

            if (! Str::endsWith($path, '.json') && $type == 'json') {
                $path .= '.json';
            }

            $body = $this->files->get($this->includePath.'/'.$path);

            json_decode($body);

            if (json_last_error() == JSON_ERROR_NONE) {
                return $body;
            }
        }

        if (strpos($contentType, 'application/json') === 0) {
            return json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        return $body;
    }

    /**
     * Create a new line character.
     *
     * @param int $repeat
     *
     * @return string
     */
    protected function line($repeat = 1)
    {
        return str_repeat("\n", $repeat);
    }

    /**
     * Create a tab character.
     *
     * @param int $repeat
     *
     * @return string
     */
    protected function tab($repeat = 1)
    {
        return str_repeat('    ', $repeat);
    }

    /**
     * Get the API Blueprint format.
     *
     * @return string
     */
    protected function getFormat()
    {
        return 'FORMAT: 1A';
    }

    /**
     * Get the parameters string
     *
     * @return string
     */
    protected function getParameterContent($parameter)
    {
        if ($parameter->example) {
            $content = $this->getParametrContentWithExample($parameter);
        } else {
            $content = $this->getParametrContentWithoutExample($parameter);
        }

        return $content;
    }

    /**
     * Get formatted string with parameters with example
     *
     * @return string
     */
    protected function getParametrContentWithExample($parameter)
    {
        return sprintf(
            '+ %s:%s (%s, %s) - %s',
            $parameter->identifier,
            $parameter->example ? " `{$parameter->example}`" : '',
            $parameter->members ? sprintf('enum[%s]', $parameter->type) : $parameter->type,
            $parameter->required ? 'required' : 'optional',
            $parameter->description
        );
    }

    /**
     * Get formatted string with parameters without example
     *
     * @return string
     */
    protected function getParametrContentWithoutExample($parameter)
    {
        return sprintf(
            '+ %s (%s, %s) - %s',
            $parameter->identifier,
            $parameter->members ? sprintf('enum[%s]', $parameter->type) : $parameter->type,
            $parameter->required ? 'required' : 'optional',
            $parameter->description
        );
    }

    /**
     * Get the overview file content to append.
     *
     * @param null $file
     * @return null|string
     */
    protected function getOverview($file = null)
    {
        if (null !== $file) {
            if (!file_exists($file)) {
                throw new RuntimeException('Overview file could not be found.');
            }

            $content = file_get_contents($file);

            if ($content === false) {
                throw new RuntimeException('Failed to read overview file contents.');
            }

            return $content.$this->line(2);
        }

        return null;
    }
}
