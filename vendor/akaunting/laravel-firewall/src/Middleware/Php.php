<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Abstracts\Middleware;

class Php extends Middleware
{
    public function match($pattern, $input)
    {
        $result = false;

        if (! is_array($input) && ! is_string($input)) {
            return false;
        }

        if (! is_array($input)) {
            return (stripos($input, $pattern) === 0);
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

            if (! $result = (stripos($value, $pattern) === 0)) {
                continue;
            }

            break;
        }

        return $result;
    }
}
