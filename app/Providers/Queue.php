<?php

namespace App\Providers;

use App\Notifications\Common\ImportFailed;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\ServiceProvider as Provider;

class Queue extends Provider
{
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

        app('events')->listen(JobProcessing::class, function ($event) {
            $payload = $event->job->payload();

            if (!array_key_exists('company_id', $payload)) {
                $event->job->delete();

                throw new \Exception('Missing company. Payload: ' . json_encode($payload));
            }

            $company = company($payload['company_id']);

            if (empty($company)) {
                $event->job->delete();

                throw new \Exception('Company not found. Payload: ' . json_encode($payload));
            }

            $company->makeCurrent();
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
