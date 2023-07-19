<?php

namespace App\View\Components\Script\Exceptions;

use App\Abstracts\View\Component;
use App\Utilities\Info;
use App\Traits\Trackers as Base;
use Illuminate\Support\Str;

class Trackers extends Component
{
    use Base;

    public $channel;

    public $action;

    public $ip;

    public $tags;

    public $params;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $channel = null, string $action = null, string $ip = null, $tags = [], $params = []
    ) {
        $this->channel = $this->getChannel($channel);
        $this->action = $this->getAction($action);
        $this->ip = $this->getIp($ip);
        $this->tags = $this->getTags($tags);
        $this->params = $this->getParams($params);
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.script.exceptions.trackers');
    }

    public function getChannel($channel)
    {
        if (! empty($channel)) {
            return $channel;
        }

        return config('logging.default');
    }

    public function getAction($action)
    {
        if (! empty($action)) {
            return $action;
        }

        switch ($this->channel) {
            case 'bugsnag':
                $action = config('bugsnag.api_key');
                break;
            case 'sentry':
                $action = config('sentry.dsn');
                break;
        }

        return $action;
    }

    public function getIp($ip)
    {
        if (! empty($ip)) {
            return $ip;
        }

        return Info::ip();
    }

    public function getTags($tags)
    {
        if (! empty($tags)) {
            return $tags;
        }

        return static::getTrackerTags();
    }

    public function getParams($params)
    {
        if (! empty($params)) {
            return $params;
        }

        switch ($this->channel) {
            case 'bugsnag':
                $params = [
                    'app_version' => version('short'),
                ];
                break;
            case 'sentry':
                $params = [
                    'release' => version('short'),
                    'traces_sample_rate' => static::sentryTracesSampleRate(),
                    'replays_session_sample_rate' => static::sentryReplaysSessionSampleRate(),
                    'replays_on_error_sample_rate' => static::sentryReplaysOnErrorSampleRate(),
                ];
                break;
        }

        return $params;
    }

    public static function sentryTracesSampleRate()
    {
        $user_agent = request()->userAgent();

        $filter_agents = explode(',', env('SENTRY_TRACES_FILTER_AGENTS'));

        foreach ($filter_agents as $filter_agent) {
            if (! Str::contains($user_agent, $filter_agent)) {
                continue;
            }

            return 0.0;
        }

        return (float) config('sentry.traces_sample_rate', 1.0);
    }

    public static function sentryReplaysSessionSampleRate()
    {
        return (float) config('sentry.replays_session_sample_rate', 0.1);
    }

    public static function sentryReplaysOnErrorSampleRate()
    {
        return (float) config('sentry.replays_on_error_sample_rate', 1.0);
    }
}
