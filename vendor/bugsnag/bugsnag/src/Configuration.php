<?php

namespace Bugsnag;

use Bugsnag\Internal\FeatureFlagDelegate;
use InvalidArgumentException;

class Configuration implements FeatureDataStore
{
    /**
     * The default endpoint for event notifications.
     */
    const NOTIFY_ENDPOINT = 'https://notify.bugsnag.com';

    /**
     * The default endpoint for session tracking.
     */
    const SESSION_ENDPOINT = 'https://sessions.bugsnag.com';

    /**
     * The default endpoint for build notifications.
     */
    const BUILD_ENDPOINT = 'https://build.bugsnag.com';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * If batch sending is enabled.
     *
     * @var bool
     */
    protected $batchSending = true;

    /**
     * Which release stages should be allowed to notify.
     *
     * @var string[]|null
     */
    protected $notifyReleaseStages;

    /**
     * The strings to filter out from metaData.
     *
     * @deprecated Use redactedKeys instead
     *
     * @var string[]
     */
    protected $filters = [
        'password',
        'cookie',
        'authorization',
        'php-auth-user',
        'php-auth-pw',
        'php-auth-digest',
    ];

    /**
     * The project root regex.
     *
     * @var string
     */
    protected $projectRootRegex;

    /**
     * The strip path regex.
     *
     * @var string
     */
    protected $stripPathRegex;

    /**
     * If code sending is enabled.
     *
     * @var bool
     */
    protected $sendCode = true;

    /**
     * The notifier to report as.
     *
     * @var string[]
     */
    protected $notifier = [
        'name' => 'Bugsnag PHP (Official)',
        'version' => '3.29.1',
        'url' => 'https://bugsnag.com',
    ];

    /**
     * The fallback app type.
     *
     * @var string|null
     */
    protected $fallbackType;

    /**
     * The application data.
     *
     * @var string[]
     */
    protected $appData = [];

    /**
     * The device data.
     *
     * @var string[]
     */
    protected $deviceData = [];

    /**
     * The meta data.
     *
     * @var array[]
     */
    protected $metaData = [];

    /**
     * The associated feature flags.
     *
     * @var FeatureFlagDelegate
     */
    private $featureFlags;

    /**
     * The error reporting level.
     *
     * @var int|null
     */
    protected $errorReportingLevel;

    /**
     * Whether to track sessions.
     *
     * @var bool
     */
    protected $autoCaptureSessions = false;

    /**
     * A client to use to send sessions.
     *
     * @var \GuzzleHttp\ClientInterface|null
     *
     * @deprecated This will be removed in the next major version.
     */
    protected $sessionClient;

    /**
     * @var string
     */
    protected $notifyEndpoint = self::NOTIFY_ENDPOINT;

    /**
     * @var string
     */
    protected $sessionEndpoint = self::SESSION_ENDPOINT;

    /**
     * @var string
     */
    protected $buildEndpoint = self::BUILD_ENDPOINT;

    /**
     * The amount to increase the memory_limit to handle an OOM.
     *
     * The default is 5MiB and can be disabled by setting it to 'null'
     *
     * @var int|null
     */
    protected $memoryLimitIncrease = 5242880;

    /**
     * An array of classes that should not be sent to Bugsnag.
     *
     * This can contain both fully qualified class names and regular expressions.
     *
     * @var array
     */
    protected $discardClasses = [];

    /**
     * An array of metadata keys that should be redacted.
     *
     * @var string[]
     */
    protected $redactedKeys = [];

    /**
     * Create a new config instance.
     *
     * @param string $apiKey your bugsnag api key
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function __construct($apiKey)
    {
        if (!is_string($apiKey)) {
            throw new InvalidArgumentException('Invalid API key');
        }

        $this->apiKey = $apiKey;
        $this->fallbackType = php_sapi_name();
        $this->featureFlags = new FeatureFlagDelegate();

        // Add PHP runtime version to device data
        $this->mergeDeviceData(['runtimeVersions' => ['php' => phpversion()]]);
    }

    /**
     * Get the Bugsnag API Key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets whether errors should be batched together and send at the end of each request.
     *
     * @param bool $batchSending whether to batch together errors
     *
     * @return $this
     */
    public function setBatchSending($batchSending)
    {
        $this->batchSending = $batchSending;

        return $this;
    }

    /**
     * Is batch sending is enabled?
     *
     * @return bool
     */
    public function isBatchSending()
    {
        return $this->batchSending;
    }

    /**
     * Set which release stages should be allowed to notify Bugsnag.
     *
     * Eg ['production', 'development'].
     *
     * @param string[]|null $notifyReleaseStages array of release stages to notify for
     *
     * @return $this
     */
    public function setNotifyReleaseStages(array $notifyReleaseStages = null)
    {
        $this->notifyReleaseStages = $notifyReleaseStages;

        return $this;
    }

    /**
     * Should we notify Bugsnag based on the current release stage?
     *
     * @return bool
     */
    public function shouldNotify()
    {
        if (!$this->notifyReleaseStages) {
            return true;
        }

        return in_array($this->getAppData()['releaseStage'], $this->notifyReleaseStages, true);
    }

    /**
     * Set the strings to filter out from metaData arrays before sending then.
     *
     * Eg. ['password', 'credit_card'].
     *
     * @deprecated Use redactedKeys instead
     *
     * @param string[] $filters an array of metaData filters
     *
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Get the array of metaData filters.
     *
     * @deprecated Use redactedKeys instead
     *
     * @return string[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Set the project root.
     *
     * @param string|null $projectRoot the project root path
     *
     * @return void
     */
    public function setProjectRoot($projectRoot)
    {
        $projectRootRegex = $projectRoot ? '/^'.preg_quote($projectRoot, '/').'[\\/]?/i' : null;
        $this->setProjectRootRegex($projectRootRegex);
    }

    /**
     * Set the project root regex.
     *
     * @param string|null $projectRootRegex the project root path
     *
     * @return void
     */
    public function setProjectRootRegex($projectRootRegex)
    {
        if ($projectRootRegex && @preg_match($projectRootRegex, '') === false) {
            throw new InvalidArgumentException('Invalid project root regex: '.$projectRootRegex);
        }

        $this->projectRootRegex = $projectRootRegex;
        $this->setStripPathRegex($projectRootRegex);
    }

    /**
     * Is the given file in the project?
     *
     * @param string $file
     *
     * @return bool
     */
    public function isInProject($file)
    {
        return $this->projectRootRegex && preg_match($this->projectRootRegex, $file);
    }

    /**
     * Set the strip path.
     *
     * @param string|null $stripPath the absolute strip path
     *
     * @return void
     */
    public function setStripPath($stripPath)
    {
        $stripPathRegex = $stripPath ? '/^'.preg_quote($stripPath, '/').'[\\/]?/i' : null;
        $this->setStripPathRegex($stripPathRegex);
    }

    /**
     * Set the regular expression used to strip paths from stacktraces.
     *
     * @param string|null $stripPathRegex
     *
     * @return void
     */
    public function setStripPathRegex($stripPathRegex)
    {
        if ($stripPathRegex && @preg_match($stripPathRegex, '') === false) {
            throw new InvalidArgumentException('Invalid strip path regex: '.$stripPathRegex);
        }

        $this->stripPathRegex = $stripPathRegex;
    }

    /**
     * Set the stripped file path.
     *
     * @param string $file
     *
     * @return string
     */
    public function getStrippedFilePath($file)
    {
        return $this->stripPathRegex ? preg_replace($this->stripPathRegex, '', $file) : $file;
    }

    /**
     * Set if we should we send a small snippet of the code that crashed.
     *
     * This can help you diagnose even faster from within your dashboard.
     *
     * @param bool $sendCode whether to send code to Bugsnag
     *
     * @return $this
     */
    public function setSendCode($sendCode)
    {
        $this->sendCode = $sendCode;

        return $this;
    }

    /**
     * Should we send a small snippet of the code that crashed?
     *
     * @return bool
     */
    public function shouldSendCode()
    {
        return $this->sendCode;
    }

    /**
     * Sets the notifier to report as to Bugsnag.
     *
     * This should only be set by other notifier libraries.
     *
     * @param string[] $notifier an array of name, version, url.
     *
     * @return $this
     */
    public function setNotifier(array $notifier)
    {
        $this->notifier = $notifier;

        return $this;
    }

    /**
     * Get the notifier to report as to Bugsnag.
     *
     * @return string[]
     */
    public function getNotifier()
    {
        return $this->notifier;
    }

    /**
     * Set your app's semantic version, eg "1.2.3".
     *
     * @param string|null $appVersion the app's version
     *
     * @return $this
     */
    public function setAppVersion($appVersion)
    {
        $this->appData['version'] = $appVersion;

        return $this;
    }

    /**
     * Set your release stage, eg "production" or "development".
     *
     * @param string|null $releaseStage the app's current release stage
     *
     * @return $this
     */
    public function setReleaseStage($releaseStage)
    {
        $this->appData['releaseStage'] = $releaseStage;

        return $this;
    }

    /**
     * Set the type of application executing the code.
     *
     * This is usually used to represent if you are running plain PHP code
     * "php", via a framework, eg "laravel", or executing through delayed
     * worker code, eg "resque".
     *
     * @param string|null $type the current type
     *
     * @return $this
     */
    public function setAppType($type)
    {
        $this->appData['type'] = $type;

        return $this;
    }

    /**
     * Set the fallback application type.
     *
     * This is should be used only by libraries to set an fallback app type.
     *
     * @param string|null $type the fallback type
     *
     * @return $this
     */
    public function setFallbackType($type)
    {
        $this->fallbackType = $type;

        return $this;
    }

    /**
     * Get the application data.
     *
     * @return array
     */
    public function getAppData()
    {
        return array_merge(array_filter(['type' => $this->fallbackType, 'releaseStage' => 'production']), array_filter($this->appData));
    }

    /**
     * Set the hostname.
     *
     * @param string|null $hostname the hostname
     *
     * @return $this
     */
    public function setHostname($hostname)
    {
        $this->deviceData['hostname'] = $hostname;

        return $this;
    }

    /**
     * Adds new data fields to the device data collection.
     *
     * @param array $data an associative array containing the new data to be added
     *
     * @return $this
     */
    public function mergeDeviceData($data)
    {
        $this->deviceData = array_merge_recursive($this->deviceData, $data);

        return $this;
    }

    /**
     * Get the device data.
     *
     * @return array
     */
    public function getDeviceData()
    {
        return array_merge($this->getHostname(), array_filter($this->deviceData));
    }

    /**
     * Get the hostname if possible.
     *
     * @return array
     */
    protected function getHostname()
    {
        $disabled = explode(',', ini_get('disable_functions'));

        if (function_exists('php_uname') && !in_array('php_uname', $disabled, true)) {
            return ['hostname' => php_uname('n')];
        }

        if (function_exists('gethostname') && !in_array('gethostname', $disabled, true)) {
            return ['hostname' => gethostname()];
        }

        return [];
    }

    /**
     * Set custom metadata to send to Bugsnag.
     *
     * You can use this to add custom tabs of data to each error on your
     * Bugsnag dashboard.
     *
     * @param array[] $metaData an array of arrays of custom data
     * @param bool    $merge    should we merge the meta data
     *
     * @return $this
     */
    public function setMetaData(array $metaData, $merge = true)
    {
        $this->metaData = $merge ? array_merge_recursive($this->metaData, $metaData) : $metaData;

        return $this;
    }

    /**
     * Get the custom metadata to send to Bugsnag.
     *
     * @return array[]
     */
    public function getMetaData()
    {
        return $this->metaData;
    }

    /**
     * Add a single feature flag to all future reports.
     *
     * @param string $name
     * @param string|null $variant
     *
     * @return void
     */
    public function addFeatureFlag($name, $variant = null)
    {
        $this->featureFlags->add($name, $variant);
    }

    /**
     * Add multiple feature flags to all future reports.
     *
     * @param FeatureFlag[] $featureFlags
     * @phpstan-param list<FeatureFlag> $featureFlags
     *
     * @return void
     */
    public function addFeatureFlags(array $featureFlags)
    {
        $this->featureFlags->merge($featureFlags);
    }

    /**
     * Remove the feature flag with the given name from all future reports.
     *
     * @param string $name
     *
     * @return void
     */
    public function clearFeatureFlag($name)
    {
        $this->featureFlags->remove($name);
    }

    /**
     * Remove all feature flags from all future reports.
     *
     * @return void
     */
    public function clearFeatureFlags()
    {
        $this->featureFlags->clear();
    }

    /**
     * @internal
     *
     * @return FeatureFlagDelegate
     */
    public function getFeatureFlagsCopy()
    {
        return clone $this->featureFlags;
    }

    /**
     * Set Bugsnag's error reporting level.
     *
     * If this is not set, we'll use your current PHP error_reporting value
     * from your ini file or error_reporting(...) calls.
     *
     * @param int|null $errorReportingLevel the error reporting level integer
     *
     * @return $this
     */
    public function setErrorReportingLevel($errorReportingLevel)
    {
        if (!$this->isSubsetOfErrorReporting($errorReportingLevel)) {
            $missingLevels = implode(', ', $this->getMissingErrorLevelNames($errorReportingLevel));
            $message =
                'Bugsnag Warning: errorReportingLevel cannot contain values that are not in error_reporting. '.
                "Any errors of these levels will be ignored: {$missingLevels}.";

            error_log($message);
        }

        $this->errorReportingLevel = $errorReportingLevel;

        return $this;
    }

    /**
     * Check if the given error reporting level is a subset of error_reporting.
     *
     * For example, if $level contains E_WARNING then error_reporting must too.
     *
     * @param int|null $level
     *
     * @return bool
     */
    private function isSubsetOfErrorReporting($level)
    {
        if (!is_int($level)) {
            return true;
        }

        $errorReporting = error_reporting();

        // If all of the bits in $level are also in $errorReporting, ORing them
        // together will result in the same value as $errorReporting because
        // there are no new bits to add
        return ($errorReporting | $level) === $errorReporting;
    }

    /**
     * Get a list of error level names that are in $level but not error_reporting.
     *
     * For example, if error_reporting is E_NOTICE and $level is E_ERROR then
     * this will return ['E_ERROR']
     *
     * @param int $level
     *
     * @return string[]
     */
    private function getMissingErrorLevelNames($level)
    {
        $missingLevels = [];
        $errorReporting = error_reporting();

        foreach (ErrorTypes::getAllCodes() as $code) {
            // $code is "missing" if it's in $level but not in $errorReporting
            if (($code & $level) && !($code & $errorReporting)) {
                $missingLevels[] = ErrorTypes::codeToString($code);
            }
        }

        return $missingLevels;
    }

    /**
     * Should we ignore the given error code?
     *
     * @param int $code the error code
     *
     * @return bool
     */
    public function shouldIgnoreErrorCode($code)
    {
        // If the code is not in error_reporting then it is either totally
        // disabled or is being suppressed with '@'
        if (!(error_reporting() & $code)) {
            return true;
        }

        // Filter the error code further against our error reporting level, which
        // can be lower than error_reporting
        if (isset($this->errorReportingLevel)) {
            return !($this->errorReportingLevel & $code);
        }

        return false;
    }

    /**
     * Set event notification endpoint.
     *
     * @param string $endpoint
     *
     * @return $this
     */
    public function setNotifyEndpoint($endpoint)
    {
        $this->notifyEndpoint = $endpoint;

        return $this;
    }

    /**
     * Get event notification endpoint.
     *
     * @return string
     */
    public function getNotifyEndpoint()
    {
        return $this->notifyEndpoint;
    }

    /**
     * Set session delivery endpoint.
     *
     * @param string $endpoint
     *
     * @return $this
     */
    public function setSessionEndpoint($endpoint)
    {
        $this->sessionEndpoint = $endpoint;

        return $this;
    }

    /**
     * Get session delivery endpoint.
     *
     * @return string
     */
    public function getSessionEndpoint()
    {
        return $this->sessionEndpoint;
    }

    /**
     * Set the build endpoint.
     *
     * @param string $endpoint the build endpoint
     *
     * @return $this
     */
    public function setBuildEndpoint($endpoint)
    {
        $this->buildEndpoint = $endpoint;

        return $this;
    }

    /**
     * Get the build endpoint.
     *
     * @return string
     */
    public function getBuildEndpoint()
    {
        return $this->buildEndpoint;
    }

    /**
     * Set session tracking state.
     *
     * @param bool $track whether to track sessions
     *
     * @return $this
     */
    public function setAutoCaptureSessions($track)
    {
        $this->autoCaptureSessions = $track;

        return $this;
    }

    /**
     * Whether should be auto-capturing sessions.
     *
     * @return bool
     */
    public function shouldCaptureSessions()
    {
        return $this->autoCaptureSessions;
    }

    /**
     * Get the session client.
     *
     * @return \GuzzleHttp\ClientInterface
     *
     * @deprecated This will be removed in the next major version.
     */
    public function getSessionClient()
    {
        if (is_null($this->sessionClient)) {
            $this->sessionClient = Client::makeGuzzle($this->sessionEndpoint);
        }

        return $this->sessionClient;
    }

    /**
     * Set the amount to increase the memory_limit when an OOM is triggered.
     *
     * This is an amount of bytes or 'null' to disable increasing the limit.
     *
     * @param int|null $value
     *
     * @return $this
     */
    public function setMemoryLimitIncrease($value)
    {
        $this->memoryLimitIncrease = $value;

        return $this;
    }

    /**
     * Get the amount to increase the memory_limit when an OOM is triggered.
     *
     * This will return 'null' if this feature is disabled.
     *
     * @return int|null
     */
    public function getMemoryLimitIncrease()
    {
        return $this->memoryLimitIncrease;
    }

    /**
     * Set the array of classes that should not be sent to Bugsnag.
     *
     * @param array $discardClasses
     *
     * @return $this
     */
    public function setDiscardClasses(array $discardClasses)
    {
        $this->discardClasses = $discardClasses;

        return $this;
    }

    /**
     * Get the array of classes that should not be sent to Bugsnag.
     *
     * This can contain both fully qualified class names and regular expressions.
     *
     * @return array
     */
    public function getDiscardClasses()
    {
        return $this->discardClasses;
    }

    /**
     * Set the array of metadata keys that should be redacted.
     *
     * @param string[] $redactedKeys
     *
     * @return $this
     */
    public function setRedactedKeys(array $redactedKeys)
    {
        $this->redactedKeys = $redactedKeys;

        return $this;
    }

    /**
     * Get the array of metadata keys that should be redacted.
     *
     * @return string[]
     */
    public function getRedactedKeys()
    {
        return $this->redactedKeys;
    }
}
