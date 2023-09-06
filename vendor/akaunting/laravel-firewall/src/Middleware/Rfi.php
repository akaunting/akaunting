<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Abstracts\Middleware;

class Rfi extends Middleware
{
    public function match($pattern, $input)
    {
        $result = false;

        if (! is_array($input) && ! is_string($input)) {
            return false;
        }

        if (! is_array($input)) {
            if (! $result = preg_match($pattern, $this->applyExceptions($input))) {
                return false;
            }

            return $this->checkContent($result);
        }

        foreach ($input as $key => $value) {
            if (empty($value)) {
                continue;
            }

            if (is_array($value)) {
                if (! $result = $this->match($pattern, $value)) {
                    continue;
                }

                break;
            }

            if (! $this->isInput($key)) {
                continue;
            }

            if (! $result = preg_match($pattern, $this->applyExceptions($value))) {
                continue;
            }

            if (! $this->checkContent($result)) {
                continue;
            }

            break;
        }

        return $result;
    }

    protected function applyExceptions($string)
    {
        $exceptions = config('firewall.middleware.' . $this->middleware . '.exceptions');

        $domain = $this->request->getHost();

        $exceptions[] = 'http://' . $domain;
        $exceptions[] = 'https://' . $domain;
        $exceptions[] = 'http://&';
        $exceptions[] = 'https://&';

        return str_replace($exceptions, '', $string);
    }

    protected function checkContent($value)
    {
        $contents = @file_get_contents($value);

        if (!empty($contents)) {
            return (strstr($contents, '<?php') !== false);
        }

        return false;
    }
}
