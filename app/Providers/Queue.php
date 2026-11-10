<?php

namespace App\Providers;

use App\Notifications\Common\ImportFailed;
use App\Traits\Modules;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\ServiceProvider as Provider;

class Queue extends Provider
{
    use Modules;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Empty the model cache, when it is kept in memory. A store shared between processes is
     * left alone, as what one job cached there is still valid for the next, and so is the
     * application's own cache store: jobs may read there what an earlier one put.
     */
    protected function flushModelCache(): void
    {
        $store = config('laravel-model-caching.store');

        if (empty($store) || $store === config('cache.default')) {
            return;
        }

        if (config('cache.stores.' . $store . '.driver') !== 'array') {
            return;
        }

        app('cache')->store($store)->flush();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app('queue')->createPayloadUsing(function ($connection, $queue, $payload) {
            $company_id = company_id();

            if (empty($company_id)) {
                return [];
            }

            return ['company_id' => $company_id];
        });

        // A worker runs job after job in one process, and the model cache keeps its entries in
        // that process when its store is "array", so each job left its cached queries behind
        // for the next and the worker's memory only grew. Every job starts with an empty one.
        app('events')->listen(JobProcessing::class, function ($event) {
            // A job run in place (the sync connection) is part of the request or command around it
            if ($event->connectionName === 'sync') {
                return;
            }

            $this->flushModelCache();
        });

        app('events')->listen(JobProcessing::class, function ($event) {
            $payload = $event->job->payload();

            if (! array_key_exists('company_id', $payload)) {
                return;
            }

            try {
                $company = company($payload['company_id']);
            } catch (\Throwable $e) {
                $event->job->delete();

                logger()->warning('Company could not be resolved for queued job, job deleted.', [
                    'company_id' => $payload['company_id'],
                    'payload' => $payload,
                    'exception' => $e->getMessage(),
                ]);

                return;
            }

            if (empty($company)) {
                $event->job->delete();

                logger()->warning('Company not found, job deleted.', [
                    'company_id' => $payload['company_id'],
                    'payload' => $payload,
                ]);

                return;
            }

            $company->makeCurrent();

            // TODO: Move queue control at the beginning of the closure
            if (should_queue()) {
                $this->registerModules();
            }
        });

        app('events')->listen(JobFailed::class, function ($event) {
            if (!$event->exception instanceof \Maatwebsite\Excel\Validators\ValidationException) {
                return;
            }

            $body = $event->job->getRawBody();
            if (empty($body) || !is_string($body)) {
                return;
            }

            $payload = json_decode($body);
            if (empty($payload) || empty($payload->data) || empty($payload->data->command)) {
                return;
            }

            $excel_job = unserialize($payload->data->command);
            if (!$excel_job instanceof \Maatwebsite\Excel\Jobs\ReadChunk) {
                return;
            }

            $ref = new \ReflectionProperty($excel_job, 'import');
            $ref->setAccessible(true);

            // Get import class
            $class = $ref->getValue($excel_job);

            if (!$class instanceof \App\Abstracts\Import && !$class instanceof \App\Abstracts\ImportMultipleSheets) {
                return;
            }

            $errors = [];

            foreach ($event->exception->failures() as $failure) {
                $message = trans('messages.error.import_column', [
                    'message'   => collect($failure->errors())->first(),
                    'column'    => $failure->attribute(),
                    'line'      => $failure->row(),
                ]);

                $errors[] = $message;
            }

            if (!empty($errors)) {
                $class->user->notify(new ImportFailed($errors));
            }
        });
    }
}
