<?php

namespace Bugsnag;

use Exception;
use InvalidArgumentException;

class SessionTracker
{
    /**
     * The current session payload version.
     *
     * @deprecated Use {HttpClient::SESSION_PAYLOAD_VERSION} instead.
     *
     * @var string
     */
    protected static $SESSION_PAYLOAD_VERSION = HttpClient::SESSION_PAYLOAD_VERSION;

    /**
     * The amount of time between each sending attempt.
     *
     * @var int
     */
    protected static $DELIVERY_INTERVAL = 30;

    /**
     * The maximum amount of sessions to hold onto.
     *
     * @var int
     */
    protected static $MAX_SESSION_COUNT = 50;

    /**
     * The key for storing session counts.
     *
     * @var string
     */
    protected static $SESSION_COUNTS_KEY = 'bugsnag-session-counts';

    /**
     * The key for storing last sent data.
     *
     * @var string
     */
    protected static $SESSIONS_LAST_SENT_KEY = 'bugsnag-sessions-last-sent';

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HttpClient
     */
    protected $http;

    /**
     * An array of session counts.
     *
     * @var array
     */
    protected $sessionCounts = [];

    /**
     * A locking function for synchronisation.
     *
     * @var callable|null
     */
    protected $lockFunction = null;

    /**
     * An unlocking function for synchronisation.
     *
     * @var callable|null
     */
    protected $unlockFunction = null;

    /**
     * A function to use when retrying a failed delivery.
     *
     * @var callable|null
     */
    protected $retryFunction = null;

    /**
     * A function to store/get data.
     *
     * @var callable|null
     */
    protected $storageFunction = null;

    /**
     * A function to store/get sessions.
     *
     * @var callable|null
     */
    protected $sessionFunction = null;

    /**
     * The last time the sessions were delivered.
     *
     * @var int
     */
    protected $lastSent = 0;

    /**
     * The current session.
     *
     * @var array
     */
    protected $currentSession = [];

    /**
     * @param Configuration   $config
     * @param HttpClient|null $http   A HttpClient instance to use. Passing null
     *                                is deprecated and $http will be required
     *                                in the next major version.
     */
    public function __construct(Configuration $config, HttpClient $http = null)
    {
        $this->config = $config;
        $this->http = $http === null
            ? new HttpClient($config, $config->getSessionClient())
            : $http;
    }

    /**
     * @param Configuration $config
     *
     * @return void
     *
     * @deprecated Change the Configuration via the Client object instead.
     */
    public function setConfig(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function startSession()
    {
        $currentTime = date('Y-m-d\TH:i:00');

        $session = [
            'id' => uniqid('', true),
            'startedAt' => $currentTime,
            'events' => [
                'handled' => 0,
                'unhandled' => 0,
            ],
        ];

        $this->setCurrentSession($session);
        $this->incrementSessions($currentTime);
    }

    /**
     * @param array $session
     *
     * @return void
     */
    public function setCurrentSession(array $session)
    {
        if (is_callable($this->sessionFunction)) {
            call_user_func($this->sessionFunction, $session);
        } else {
            $this->currentSession = $session;
        }
    }

    /**
     * @return array
     */
    public function getCurrentSession()
    {
        if (is_callable($this->sessionFunction)) {
            $currentSession = call_user_func($this->sessionFunction);

            if (is_array($currentSession)) {
                return $currentSession;
            }

            return [];
        }

        return $this->currentSession;
    }

    /**
     * @return void
     */
    public function sendSessions()
    {
        $locked = false;
        if (is_callable($this->lockFunction) && is_callable($this->unlockFunction)) {
            call_user_func($this->lockFunction);
            $locked = true;
        }

        try {
            $this->deliverSessions();
        } finally {
            if ($locked) {
                call_user_func($this->unlockFunction);
            }
        }
    }

    /**
     * @param callable $lock
     * @param callable $unlock
     *
     * @return void
     */
    public function setLockFunctions($lock, $unlock)
    {
        if (!is_callable($lock) || !is_callable($unlock)) {
            throw new InvalidArgumentException('Both lock and unlock functions must be callable');
        }

        $this->lockFunction = $lock;
        $this->unlockFunction = $unlock;
    }

    /**
     * @param callable $function
     *
     * @return void
     */
    public function setRetryFunction($function)
    {
        if (!is_callable($function)) {
            throw new InvalidArgumentException('The retry function must be callable');
        }

        $this->retryFunction = $function;
    }

    /**
     * @param callable $function
     *
     * @return void
     */
    public function setStorageFunction($function)
    {
        if (!is_callable($function)) {
            throw new InvalidArgumentException('Storage function must be callable');
        }

        $this->storageFunction = $function;
    }

    /**
     * @param callable $function
     *
     * @return void
     */
    public function setSessionFunction($function)
    {
        if (!is_callable($function)) {
            throw new InvalidArgumentException('Session function must be callable');
        }

        $this->sessionFunction = $function;
    }

    /**
     * @param string $minute
     * @param int $count
     * @param bool $deliver
     *
     * @return void
     */
    protected function incrementSessions($minute, $count = 1, $deliver = true)
    {
        $locked = false;

        if (is_callable($this->lockFunction) && is_callable($this->unlockFunction)) {
            call_user_func($this->lockFunction);
            $locked = true;
        }

        try {
            $sessionCounts = $this->getSessionCounts();

            if (array_key_exists($minute, $sessionCounts)) {
                $sessionCounts[$minute] += $count;
            } else {
                $sessionCounts[$minute] = $count;
            }

            $this->setSessionCounts($sessionCounts);

            if (count($sessionCounts) > self::$MAX_SESSION_COUNT) {
                $this->trimOldestSessions();
            }

            $lastSent = $this->getLastSent();

            if ($deliver && ((time() - $lastSent) > self::$DELIVERY_INTERVAL)) {
                $this->deliverSessions();
            }
        } finally {
            if ($locked) {
                call_user_func($this->unlockFunction);
            }
        }
    }

    /**
     * @return array
     */
    protected function getSessionCounts()
    {
        if (is_callable($this->storageFunction)) {
            $sessionCounts = call_user_func($this->storageFunction, self::$SESSION_COUNTS_KEY);

            if (is_array($sessionCounts)) {
                return $sessionCounts;
            }

            return [];
        }

        return $this->sessionCounts;
    }

    /**
     * @param array $sessionCounts
     *
     * @return void
     */
    protected function setSessionCounts(array $sessionCounts)
    {
        if (is_callable($this->storageFunction)) {
            call_user_func($this->storageFunction, self::$SESSION_COUNTS_KEY, $sessionCounts);
        }

        $this->sessionCounts = $sessionCounts;
    }

    /**
     * @return void
     */
    protected function trimOldestSessions()
    {
        $sessions = $this->getSessionCounts();

        // Sort the session counts so that the oldest minutes are first
        // i.e. '2000-01-01T00:00:00' should be after '2000-01-01T00:01:00'
        uksort($sessions, function ($a, $b) {
            return strtotime($b) - strtotime($a);
        });

        $sessionCounts = array_slice($sessions, 0, self::$MAX_SESSION_COUNT);

        $this->setSessionCounts($sessionCounts);
    }

    /**
     * @param array $sessions
     *
     * @return array
     */
    protected function constructPayload(array $sessions)
    {
        $formattedSessions = [];
        foreach ($sessions as $minute => $count) {
            $formattedSessions[] = ['startedAt' => $minute, 'sessionsStarted' => $count];
        }

        return [
            'notifier' => $this->config->getNotifier(),
            'device' => $this->config->getDeviceData(),
            'app' => $this->config->getAppData(),
            'sessionCounts' => $formattedSessions,
        ];
    }

    /**
     * @return void
     */
    protected function deliverSessions()
    {
        $sessions = $this->getSessionCounts();

        $this->setSessionCounts([]);

        if (count($sessions) === 0) {
            return;
        }

        if (!$this->config->shouldNotify()) {
            return;
        }

        $payload = $this->constructPayload($sessions);

        $this->setLastSent();

        try {
            $this->http->sendSessions($payload);
        } catch (Exception $e) {
            error_log('Bugsnag Warning: Couldn\'t notify. '.$e->getMessage());

            if (is_callable($this->retryFunction)) {
                call_user_func($this->retryFunction, $sessions);
            } else {
                foreach ($sessions as $minute => $count) {
                    $this->incrementSessions($minute, $count, false);
                }
            }
        }
    }

    /**
     * @return void
     */
    protected function setLastSent()
    {
        $time = time();

        if (is_callable($this->storageFunction)) {
            call_user_func($this->storageFunction, self::$SESSIONS_LAST_SENT_KEY, $time);
        } else {
            $this->lastSent = $time;
        }
    }

    /**
     * @return int
     */
    protected function getLastSent()
    {
        if (is_callable($this->storageFunction)) {
            $lastSent = call_user_func($this->storageFunction, self::$SESSIONS_LAST_SENT_KEY);

            // $lastSent may be a string despite us storing an integer because
            // some storage backends will convert all values into strings
            // note: some invalid integers pass 'is_numeric' (e.g. bigger than
            // PHP_INT_MAX) but these get cast to '0', which is the default anyway
            if (is_numeric($lastSent)) {
                return (int) $lastSent;
            }

            return 0;
        }

        return $this->lastSent;
    }
}
