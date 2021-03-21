<?php

namespace Enlightn\Enlightn\Reporting;

class API
{
    /**
     * @var \Enlightn\Enlightn\Reporting\Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $report
     * @return void
     */
    public function sendReport(array $report)
    {
        $this->client->post('report', $report);
    }
}
