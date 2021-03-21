<?php

namespace Dingo\Api\Http\Validation;

use Illuminate\support\Str;
use Illuminate\Http\Request;
use Dingo\Api\Contract\Http\Validator;

class Domain implements Validator
{
    const PATTERN_STRIP_PROTOCOL = '/:\d*$/';

    /**
     * API domain.
     *
     * @var string
     */
    protected $domain;

    /**
     * Create a new domain validator instance.
     *
     * @param string $domain
     *
     * @return void
     */
    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Validate that the request domain matches the configured domain.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function validate(Request $request)
    {
        return ! is_null($this->domain) && $request->getHost() === $this->getStrippedDomain();
    }

    /**
     * Strip the protocol from a domain.
     *
     * @param string $domain
     *
     * @return string
     */
    protected function stripProtocol($domain)
    {
        if (Str::contains($domain, '://')) {
            $domain = substr($domain, strpos($domain, '://') + 3);
        }

        return $domain;
    }

    /**
     * Strip the port from a domain.
     *
     * @param $domain
     *
     * @return mixed
     */
    protected function stripPort($domain)
    {
        if ($domainStripped = preg_replace(self::PATTERN_STRIP_PROTOCOL, null, $domain)) {
            return $domainStripped;
        }

        return $domain;
    }

    /**
     * Get the domain stripped from protocol and port.
     *
     * @return mixed
     */
    protected function getStrippedDomain()
    {
        return $this->stripPort($this->stripProtocol($this->domain));
    }
}
