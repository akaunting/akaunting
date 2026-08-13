<?php

namespace App\Http\Middleware\Firewall;

use Akaunting\Firewall\Middleware\Xss as Middleware;
use App\Traits\Firewall;

class Xss extends Middleware
{
    use Firewall;

    /**
     * Whether prepareInput() decodes the value. Toggled by check().
     */
    public bool $normalize_input = false;

    /**
     * Decoded values, keyed by input. The parent calls prepareInput() once per
     * pattern, so without this the same value is decoded four times per request.
     */
    public array $decoded = [];

    /**
     * Run the patterns twice, once against the raw value and once against the
     * decoded one, because neither pass is a superset of the other.
     *
     * Decoding is what catches java&#115;cript:, but it also strips the control
     * characters that separate <div<TAB>onclick=alert(1), and browsers accept a
     * tab there as an attribute separator. Only the raw pass sees that one.
     *
     * The raw pass runs first because it is cheaper, and the parent stops at its
     * first match, so an attack is still logged exactly once.
     */
    public function check($patterns)
    {
        $this->normalize_input = false;

        if (parent::check($patterns)) {
            return true;
        }

        $this->normalize_input = true;

        return parent::check($patterns);
    }

    /**
     * Called by the package for every request value, right before it is matched
     * against config('firewall.middleware.xss.patterns').
     */
    public function prepareInput($value)
    {
        if (! $this->normalize_input || ! is_string($value) || ($value === '')) {
            return $value;
        }

        $key = md5($value);

        return $this->decoded[$key] ??= $this->normalizeInput($value);
    }
}
