<?php
namespace Aws\Token;

/**
 * Token that comes from the SSO provider
 */
class SsoToken extends Token
{
    private $refreshToken;
    private $clientId;
    private $clientSecret;
    private $registrationExpiresAt;
    private $region;
    private $startUrl;

    /**
     * Constructs a new SSO token object, with the specified AWS
     * token
     *
     * @param string $token   Security token to use
     * @param int    $expires UNIX timestamp for when the token expires
     * @param int    $refreshToken An opaque string returned by the sso-oidc service
     * @param int    $clientId  The client ID generated when performing the registration portion of the OIDC authorization flow
     * @param int    $clientSecret The client secret generated when performing the registration portion of the OIDC authorization flow
     * @param int    $registrationExpiresAt The expiration time of the client registration (clientId and clientSecret)
     * @param int    $region The configured sso_region for the profile that credentials are being resolved for
     * @param int    $startUrl The configured sso_start_url for the profile that credentials are being resolved for
     */
    public function __construct(
        $token,
        $expires,
        $refreshToken = null,
        $clientId = null,
        $clientSecret = null,
        $registrationExpiresAt = null,
        $region = null,
        $startUrl = null
    ) {
        parent::__construct($token, $expires);
        $this->refreshToken = $refreshToken;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->registrationExpiresAt = $registrationExpiresAt;
        $this->region = $region;
        $this->startUrl = $startUrl;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        if (isset($this->registrationExpiresAt)
            && time() >= $this->registrationExpiresAt
        ) {
            return false;
        }
        return $this->expires !== null && time() >= $this->expires;
    }

    /**
     * @return string|null
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @return string|null
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string|null
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return int|null
     */
    public function getRegistrationExpiresAt()
    {
        return $this->registrationExpiresAt;
    }

    /**
     * @return string|null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string|null
     */
    public function getStartUrl()
    {
        return $this->startUrl;
    }
}
