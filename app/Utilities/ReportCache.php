<?php

namespace App\Utilities;

use App\Abstracts\Report as ReportClass;
use App\Models\Common\Report;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ReflectionClass;
use RuntimeException;
use Throwable;

/**
 * Serves the report pages (show, print, PDF, export) from the cache.
 *
 * An entry holds the whole report object, serialised right after load() and before any render
 * changes it. It is served while its stamp still matches: the report token, rotated by the refresh
 * button and by UpdateReport; the user; today's date; the settings; and the core version and
 * report class code that built it. Data entered meanwhile shows after a refresh, a settings
 * change or the next day. Any cache failure falls back to a live load.
 */
class ReportCache
{
    /**
     * Filter combinations kept per report and user; older ones are forgotten, so that entries
     * nobody reads again cannot pile up on the file store, which never prunes them.
     */
    const VARIANTS = 10;

    /**
     * Get the loaded report class, from the cache when a valid copy exists.
     */
    public static function getClassInstance(Report $model): ReportClass|false
    {
        if (! $class = Reports::getClassInstance($model, false)) {
            return false;
        }

        $entry = static::getEntry($model);

        if ($entry && ($cached = static::fetch($model, $entry))) {
            return $cached;
        }

        $query = request()->query();

        static::load($class, (bool) $entry);

        if ($entry && static::isCacheable($class)) {
            static::store($class, $entry, $query);
        }

        return $class;
    }

    /**
     * Invalidate the cached copies of a report, for every user and filter combination.
     */
    public static function clear(int $company_id, int $report_id): void
    {
        try {
            // Deleted rather than overwritten, which works even when the store rejects writes:
            // getEntry() then makes a new token, so no stored copy's stamp matches any more
            Cache::forget(static::getTokenKey($company_id, $report_id));
        } catch (Throwable $e) {
            static::log('error', $e, $company_id, $report_id);
        }
    }

    /**
     * Build the entry's keys and stamp, before load() changes the request or the settings.
     */
    protected static function getEntry(Report $model): ?array
    {
        $user = user();

        if (! config('report.cache.enabled') || ! $model->exists || ! $user) {
            return null;
        }

        try {
            // The identity parts are hashed with the app key, so the cache file names cannot be guessed
            $slot = 'reports.' . hash_hmac('sha256', serialize([$model->company_id, $model->id, $user->id]), config('app.key'));

            $token_key = static::getTokenKey($model->company_id, $model->id);

            return [
                'slot' => $slot,
                'key' => static::getKey($slot, $model->class, request()->query(), session()->getOldInput()),
                'token_key' => $token_key,
                'stamp' => [
                    'token' => Cache::rememberForever($token_key, fn () => Str::random(20)),
                    'user' => $user->id,
                    'date' => Date::today()->toDateString(),
                    'settings' => md5(serialize(static::getSettings())),
                    'core' => version('short'),
                    'code' => md5(serialize(static::getCodeVersions($model->class))),
                ],
            ];
        } catch (Throwable $e) {
            static::log('warning', $e, $model->company_id, $model->id);

            return null;
        }
    }

    /**
     * One entry per filter combination: the class, the locale, the query and any flashed old input.
     */
    protected static function getKey(string $slot, string $class, array $query, array $old): string
    {
        return $slot . '.' . md5(serialize([
            $class,
            app()->getLocale(),
            static::sortRecursive(Arr::except($query, ['_token', '_method'])),
            $old,
        ]));
    }

    protected static function fetch(Report $model, array $entry): ?ReportClass
    {
        try {
            $payload = Cache::get($entry['key']);

            if (! is_array($payload) || ($payload['stamp'] ?? null) !== $entry['stamp']) {
                return null;
            }

            $class = unserialize($payload['report']);

            if (! $class instanceof ReportClass || get_class($class) !== $model->class) {
                return null;
            }
        } catch (Throwable $e) {
            static::log('warning', $e, $model->company_id, $model->id);

            return null;
        }

        $class->model = $model;

        // What load() did to the request (AddSearchString folds query keys and old input into
        // search), so links and render-time lookups see the request a live load would leave
        if (! empty($payload['request'])) {
            request()->merge($payload['request']);
        }

        return $class;
    }

    /**
     * Load the report, reading from the primary database when the result is going to be cached,
     * so a refresh right after a write cannot store what a lagging replica still shows.
     */
    protected static function load(ReportClass $class, bool $cached): void
    {
        $connection = DB::connection();
        $config = 'database.connections.' . $connection->getName();

        $replica = $cached && (array) config($config . '.read.host') != (array) config($config . '.write.host');

        if (! $replica) {
            $class->load();

            return;
        }

        $connection->useWriteConnectionWhenReading();

        try {
            $class->load();
        } finally {
            $connection->useWriteConnectionWhenReading(false);
        }
    }

    /**
     * A report rendered through its own show view is cached only when it opts in, since only
     * the core show view carries the refresh button.
     */
    protected static function isCacheable(ReportClass $class): bool
    {
        return $class->cacheable ?? (($class->views['show'] ?? null) === 'components.reports.show');
    }

    protected static function store(ReportClass $class, array $entry, array $query): void
    {
        try {
            // A refresh during the load rotated the token: this copy could never be served
            if (Cache::get($entry['token_key']) !== $entry['stamp']['token']) {
                return;
            }

            $class->cached_at = Date::now();

            $request = array_filter(
                array: request()->query(),
                callback: fn ($value, $key) => ! array_key_exists($key, $query) || $query[$key] !== $value,
                mode: ARRAY_FILTER_USE_BOTH,
            );

            $payload = [
                'stamp' => $entry['stamp'],
                'request' => $request,
                'report' => serialize($class),
            ];

            // Print, PDF and Export links carry the query load() left behind, without old input,
            // so the copy also goes under that key when load() changed the query
            $keys = array_unique([
                $entry['key'],
                static::getKey($entry['slot'], get_class($class), request()->query(), []),
            ]);

            foreach ($keys as $key) {
                if (! Cache::put(key: $key, value: $payload, ttl: config('report.cache.ttl'))) {
                    throw new RuntimeException('The cache store did not save the report');
                }
            }

            $variants = array_values(array_diff(Cache::get($entry['slot'], []), $keys));
            array_push($variants, ...$keys);

            foreach (array_splice($variants, 0, -static::VARIANTS) as $key) {
                Cache::forget($key);
            }

            Cache::forever($entry['slot'], $variants);
        } catch (Throwable $e) {
            $class->cached_at = null;

            static::log('warning', $e, $class->model->company_id, $class->model->id);
        }
    }

    protected static function getTokenKey(int $company_id, int $report_id): string
    {
        return 'reports.' . $company_id . '.' . $report_id . '.token';
    }

    protected static function getSettings(): array
    {
        $settings = Arr::where(
            array: Arr::dot(setting()->all()),
            callback: fn ($value, $key) => ! Str::is(config('report.cache.ignored_settings', []), $key)
        );

        ksort($settings);

        return $settings;
    }

    /**
     * When the report class, its parents and their traits were last changed, so a pulled or
     * edited report without a version bump is rebuilt instead of unserialised into a new shape.
     */
    protected static function getCodeVersions(string $class): array
    {
        $versions = [];

        $names = array_merge(
            [$class],
            class_parents($class),
            class_uses_recursive($class),
        );

        foreach ($names as $name) {
            if ($file = (new ReflectionClass($name))->getFileName()) {
                $versions[$name] = filemtime($file);
            }
        }

        return $versions;
    }

    protected static function sortRecursive(array $values): array
    {
        ksort($values);

        return array_map(
            callback: fn ($value) => is_array($value)
                ? static::sortRecursive($value)
                : $value,
            array: $values,
        );
    }

    protected static function log(string $level, Throwable $e, ?int $company_id, ?int $report_id): void
    {
        // A failed database store puts the whole payload in the query exception's bindings
        $message = $e instanceof QueryException ? $e->getPrevious()->getMessage() : $e->getMessage();

        try {
            Log::log($level, 'Report cache: ' . get_class($e) . ': ' . Str::limit($message, 300), [
                'company_id' => $company_id,
                'report_id' => $report_id,
            ]);
        } catch (Throwable) {
            // Logging must not break a report page or a committed save either
        }
    }
}
