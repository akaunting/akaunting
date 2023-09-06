<?php

namespace Bugsnag\Middleware;

use Bugsnag\Client;
use Bugsnag\Report;
use Bugsnag\SessionTracker;

class SessionData
{
    /**
     * @var \Bugsnag\Client
     *
     * @deprecated This will be removed in the next major version.
     *             The constructor parameter will also change to {@see SessionTracker}
     */
    protected $client;

    /**
     * @var \Bugsnag\SessionTracker
     */
    private $sessionTracker;

    /**
     * @param \Bugsnag\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->sessionTracker = $client->getSessionTracker();
    }

    /**
     * Attaches session information to the Report, if the SessionTracker has a
     * current session. Note that this is not the same as the PHP session, but
     * refers to the current request.
     *
     * If the SessionTracker does not have a current session, the report will
     * not be changed.
     *
     * @param \Bugsnag\Report $report
     * @param callable $next
     *
     * @return void
     */
    public function __invoke(Report $report, callable $next)
    {
        $session = $this->sessionTracker->getCurrentSession();

        if (isset($session['events'])) {
            if ($report->getUnhandled()) {
                $session['events']['unhandled'] += 1;
            } else {
                $session['events']['handled'] += 1;
            }

            $report->setSessionData($session);
            $this->sessionTracker->setCurrentSession($session);
        }

        $next($report);
    }
}
