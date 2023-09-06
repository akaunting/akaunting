<?php

require_once 'vendor/autoload.php';

register_shutdown_function(function () {
    $lastError = error_get_last();

    if (!is_null($lastError)) {
        $client = Bugsnag\Client::make(getenv('BUGSNAG_API_KEY'));
        if ($client->shouldIgnoreErrorCode($lastError['type'])) {
            return;
        }
        $report = Bugsnag\Report::fromPHPError(
            $client->getConfig(),
            $lastError['type'],
            $lastError['message'],
            $lastError['file'],
            $lastError['line'],
            true
        );
        $report->setSeverity('error');
        $report->setUnhandled(true);
        $report->setSeverityReason([
            'type' => 'unhandledException',
        ]);
        $client->notify($report);
        $client->flush();
    }
});
