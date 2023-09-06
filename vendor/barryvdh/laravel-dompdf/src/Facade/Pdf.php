<?php

namespace Barryvdh\DomPDF\Facade;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

/**
 * @method static \Barryvdh\DomPDF\PDF setPaper($paper, $orientation = 'portrait')
 * @method static \Barryvdh\DomPDF\PDF setBaseHost(string $baseHost)
 * @method static \Barryvdh\DomPDF\PDF setProtocol(string $protocol)
 * @method static \Barryvdh\DomPDF\PDF setHttpContext($httpContext)
 * @method static \Barryvdh\DomPDF\PDF setCallbacks(array $callbacks)
 * @method static \Barryvdh\DomPDF\PDF setWarnings($warnings)
 * @method static \Barryvdh\DomPDF\PDF setOption(array|string $attribute, $value = null)
 * @method static \Barryvdh\DomPDF\PDF setOptions(array $options)
 * @method static \Barryvdh\DomPDF\PDF loadView($view, $data = array(), $mergeData = array(), $encoding = null)
 * @method static \Barryvdh\DomPDF\PDF loadHTML($string, $encoding = null)
 * @method static \Barryvdh\DomPDF\PDF loadFile($file)
 * @method static \Barryvdh\DomPDF\PDF addInfo($info)
 * @method static string output($options = [])
 * @method static \Barryvdh\DomPDF\PDF save()
 * @method static \Illuminate\Http\Response download($filename = 'document.pdf')
 * @method static \Illuminate\Http\Response stream($filename = 'document.pdf')
 *
 */
class Pdf extends IlluminateFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'dompdf.wrapper';
    }

    /**
     * Resolve a new instance
     * @param string $method
     * @param array<mixed> $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::$app->make(static::getFacadeAccessor());

        switch (count($args)) {
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                $callable = [$instance, $method];
                if (! is_callable($callable)) {
                    throw new \UnexpectedValueException("Method PDF::{$method}() does not exist.");
                }
                return call_user_func_array($callable, $args);
        }
    }
}
