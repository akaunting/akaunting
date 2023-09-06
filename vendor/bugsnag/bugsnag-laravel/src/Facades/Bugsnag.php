<?php

namespace Bugsnag\BugsnagLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void build(string|null $repository = null, string|null $revision = null, string|null $provider = null, string|null $builderName = null)
 * @method static void clearBreadcrumbs()
 * @method static void flush()
 * @method static string getApiKey()
 * @method static array getAppData()
 * @method static string getBuildEndpoint()
 * @method static \Bugsnag\Configuration getConfig()
 * @method static array getDeviceData()
 * @method static array getDiscardClasses()
 * @method static array getFilters()
 * @method static int|null getMemoryLimitIncrease()
 * @method static array getMetaData()
 * @method static array getNotifier()
 * @method static string getNotifyEndpoint()
 * @method static \Bugsnag\Pipeline getPipeline()
 * @method static array getRedactedKeys()
 * @method static string getSessionEndpoint()
 * @method static \Bugsnag\SessionTracker getSessionTracker()
 * @method static string getStrippedFilePath(string $file)
 * @method static bool isBatchSending()
 * @method static string isInProject(string $file)
 * @method static void leaveBreadcrumb(string $name, string|null $type = null, array $metaData = [])
 * @method static void notify(\Bugsnag\Report $report, callable|null $callback = null)
 * @method static void notifyError(string $name, string $message, callable|null $callback = null)
 * @method static void notifyException(\Throwable $throwable, callable|null $callback = null)
 * @method static void registerCallback(callable $callback)
 * @method static void registerDefaultCallbacks()
 * @method static void registerMiddleware(callable $middleware)
 * @method static \Bugsnag\Client setAppType(string|null $type)
 * @method static \Bugsnag\Client setAppVersion(string|null $appVersion)
 * @method static \Bugsnag\Client setAutoCaptureSessions(bool $track)
 * @method static \Bugsnag\Client setBatchSending(bool $batchSending)
 * @method static \Bugsnag\Client setBuildEndpoint(string $endpoint)
 * @method static \Bugsnag\Client setDiscardClasses(array $discardClasses)
 * @method static \Bugsnag\Client setErrorReportingLevel(int|null $errorReportingLevel)
 * @method static \Bugsnag\Client setFallbackType(string|null $type)
 * @method static \Bugsnag\Client setFilters(array $filters)
 * @method static \Bugsnag\Client setHostname(string|null $hostname)
 * @method static \Bugsnag\Client setMemoryLimitIncrease(int|null $value)
 * @method static \Bugsnag\Client setMetaData(array $metaData, bool $merge = true)
 * @method static \Bugsnag\Client setNotifier(array $notifier)
 * @method static \Bugsnag\Client setNotifyEndpoint(string $endpoint)
 * @method static \Bugsnag\Client setNotifyReleaseStages(array|null $notifyReleaseStages = null)
 * @method static \Bugsnag\Client setProjectRoot(string|null $projectRoot)
 * @method static \Bugsnag\Client setProjectRootRegex(string|null $projectRootRegex)
 * @method static \Bugsnag\Client setReleaseStage(string|null $releaseStage)
 * @method static \Bugsnag\Client setRedactedKeys(array $redactedKeys)
 * @method static \Bugsnag\Client setSendCode(bool $sendCode)
 * @method static \Bugsnag\Client setSessionEndpoint(string $endpoint)
 * @method static \Bugsnag\Client setStripPath(string|null $stripPath)
 * @method static \Bugsnag\Client setStripPathRegex(string|null $stripPathRegex)
 * @method static bool shouldCaptureSessions()
 * @method static bool shouldIgnoreErrorCode(int $code)
 * @method static bool shouldNotify()
 * @method static bool shouldSendCode()
 * @method static void startSession()
 *
 * @see \Bugsnag\Client
 */
class Bugsnag extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bugsnag';
    }
}
