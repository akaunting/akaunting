<?php

namespace Akaunting\SignedUrl;

use Spatie\UrlSigner\MD5UrlSigner;

class SignedUrl extends MD5UrlSigner
{

    /**
     * The key that is used to generate secure signatures.
     *
     * @var string
     */
    protected $signatureKey;

    /**
     * The URL's query parameter name for the expiration.
     *
     * @var string
     */
    protected $expiresParameter;

    /**
     * The URL's query parameter name for the signature.
     *
     * @var string
     */
    protected $signatureParameter;

    public function __construct()
    {
        $this->signatureKey = config('signed-url.signatureKey');
        $this->expiresParameter = config('signed-url.parameters.expires');
        $this->signatureParameter = config('signed-url.parameters.signature');
    }

    /**
     * Get a secure URL to a controller action.
     *
     * @param string             $url
     * @param \DateTime|int|null $expiration Defaults to the config value
     *
     * @return string
     */
    public function sign($url, $expiration = null)
    {
        $url .= '?company_id=' . session('company_id');

        $expiration = $expiration ? $expiration : config('signed-url.default_expiration_time_in_days');

        return parent::sign($url, $expiration);
    }
}
