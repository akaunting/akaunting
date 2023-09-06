<?php
namespace Aws\Token;

use Aws\Exception\TokenException;
use GuzzleHttp\Promise;

/**
 * Token that comes from the SSO provider
 */
class SsoTokenProvider implements RefreshableTokenProviderInterface
{
    use ParsesIniTrait;

    const ENV_PROFILE = 'AWS_PROFILE';

    private $ssoProfileName;
    private $filename;
    private $ssoOidcClient;

    /**
     * Constructs a new SsoTokenProvider object, which will fetch a token from an authenticated SSO profile
     * @param string $ssoProfileName The name of the profile that contains the sso_session key
     * @param int    $filename Name of the config file to sso profile from
     */
    public function __construct($ssoProfileName, $filename = null, $ssoOidcClient = null) {
        $profileName = getenv(self::ENV_PROFILE) ?: 'default';
        $this->ssoProfileName = !empty($ssoProfileName) ? $ssoProfileName : $profileName;
        $this->filename =  !empty($filename)
            ? $filename :
            self::getHomeDir() . '/.aws/config';
        $this->ssoOidcClient = $ssoOidcClient;
    }

    /*
     * Loads cached sso credentials
     *
     * @return PromiseInterface
     */
    public function __invoke()
    {
        return Promise\Coroutine::of(function () {
            if (!@is_readable($this->filename)) {
                throw new TokenException("Cannot read profiles from $this->filename");
            }
            $profiles = self::loadProfiles($this->filename);
            if (!isset($profiles[$this->ssoProfileName])) {
                throw new TokenException("Profile {$this->ssoProfileName} does not exist in {$this->filename}.");
            }
            $ssoProfile = $profiles[$this->ssoProfileName];
            if (empty($ssoProfile['sso_session'])) {
                throw new TokenException(
                    "Profile {$this->ssoProfileName} in {$this->filename} must contain an sso_session."
                );
            }

            $sessionProfileName = 'sso-session ' . $ssoProfile['sso_session'];
            if (empty($profiles[$sessionProfileName])) {
                throw new TokenException(
                    "Profile {$this->ssoProfileName} does not exist in {$this->filename}"
                );
            }

            $sessionProfileData = $profiles[$sessionProfileName];
            if (empty($sessionProfileData['sso_start_url'])
                || empty($sessionProfileData['sso_region'])
            ) {
                throw new TokenException(
                    "Profile {$this->ssoProfileName} in {$this->filename} must contain the following keys: "
                    . "sso_start_url and sso_region."
                );
            }

            $tokenLocation = self::getTokenLocation($ssoProfile['sso_session']);
            if (!@is_readable($tokenLocation)) {
                throw new TokenException("Unable to read token file at $tokenLocation");
            }
            $tokenData = $this->getTokenData($tokenLocation);
            $this->validateTokenData($tokenLocation, $tokenData);
            yield new SsoToken(
                $tokenData['accessToken'],
                $tokenData['expiresAt'],
                isset($tokenData['refreshToken']) ? $tokenData['refreshToken'] : null,
                isset($tokenData['clientId']) ? $tokenData['clientId'] : null,
                isset($tokenData['clientSecret']) ? $tokenData['clientSecret'] : null,
                isset($tokenData['registrationExpiresAt']) ? $tokenData['registrationExpiresAt'] : null,
                isset($tokenData['region']) ? $tokenData['region'] : null,
                isset($tokenData['startUrl']) ? $tokenData['startUrl'] : null
            );
        });
    }

    /**
     * Refreshes the token
     * @return mixed|null
     */
    public function refresh() {
        try {
            //try to reload from disk
            $token = $this();
            if (
                $token instanceof SsoToken
                && !$token->shouldAttemptRefresh()
            ) {
                return $token;
            }
        } finally {
            //if reload from disk fails, try refreshing
            $tokenLocation = self::getTokenLocation($this->ssoProfileName);
            $tokenData = $this->getTokenData($tokenLocation);
            if (
                empty($this->ssoOidcClient)
                || empty($tokenData['startUrl'])
            ) {
                throw new TokenException(
                    "Cannot refresh this token without an 'ssooidcClient' "
                    . "and a 'start_url'"
                );
            }
            $response = $this->ssoOidcClient->createToken([
                'clientId' => $tokenData['clientId'],
                'clientSecret' => $tokenData['clientSecret'],
                'grantType' => 'refresh_token', // REQUIRED
                'refreshToken' => $tokenData['refreshToken'],
            ]);
            if ($response['@metadata']['statusCode'] == 200) {
                $tokenData['accessToken'] = $response['accessToken'];
                $tokenData['expiresAt'] = time () + $response['expiresIn'];
                $tokenData['refreshToken'] = $response['refreshToken'];
                $token = new SsoToken(
                    $tokenData['accessToken'],
                    $tokenData['expiresAt'],
                    $tokenData['refreshToken'],
                    isset($tokenData['clientId']) ? $tokenData['clientId'] : null,
                    isset($tokenData['clientSecret']) ? $tokenData['clientSecret'] : null,
                    isset($tokenData['registrationExpiresAt']) ? $tokenData['registrationExpiresAt'] : null,
                    isset($tokenData['region']) ? $tokenData['region'] : null,
                    isset($tokenData['startUrl']) ? $tokenData['startUrl'] : null                );

                $this->writeNewTokenDataToDisk($tokenData, $tokenLocation);

                return $token;
            }
        }
    }

    public function shouldAttemptRefresh()
    {
        $tokenLocation = self::getTokenLocation($this->ssoProfileName);
        $tokenData = $this->getTokenData($tokenLocation);
        return strtotime("-10 minutes") >= strtotime($tokenData['expiresAt']);
    }

    /**
     * @param $sso_session
     * @return string
     */
    public static function getTokenLocation($sso_session)
    {
        return self::getHomeDir()
            . '/.aws/sso/cache/'
            . mb_convert_encoding(sha1($sso_session), "UTF-8")
            . ".json";
    }

    /**
     * @param $tokenLocation
     * @return array
     */
    function getTokenData($tokenLocation)
    {
        return json_decode(file_get_contents($tokenLocation), true);
    }

    /**
     * @param $tokenData
     * @param $tokenLocation
     * @return mixed
     */
    private function validateTokenData($tokenLocation, $tokenData)
    {
        if (empty($tokenData['accessToken']) || empty($tokenData['expiresAt'])) {
            throw new TokenException(
                "Token file at {$tokenLocation} must contain an access token and an expiration"
            );
        }

        $expiration = strtotime($tokenData['expiresAt']);
        if ($expiration === false) {
            throw new TokenException("Cached SSO token returned an invalid expiration");
        } elseif ($expiration < time()) {
            throw new TokenException("Cached SSO token returned an expired token");
        }
        return $tokenData;
    }

    /**
     * @param array $tokenData
     * @param string $tokenLocation
     * @return void
     */
    private function writeNewTokenDataToDisk(array $tokenData, $tokenLocation)
    {
        $tokenData['expiresAt'] = gmdate(
            'Y-m-d\TH:i:s\Z',
            $tokenData['expiresAt']
        );
        file_put_contents($tokenLocation, json_encode(array_filter($tokenData)));
    }
}
