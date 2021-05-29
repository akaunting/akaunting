<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Report as Request;
use App\Jobs\Common\CreateReport;
use App\Jobs\Common\DeleteReport;
use App\Jobs\Common\UpdateReport;
use App\Models\Common\Report;
use App\Transformers\Common\Report as Transformer;

class Reports extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $reports = Report::collect();

        return $this->response->paginator($reports, new Transformer());
    }

    /**
     * Display the specified resource.
     *
     * @param  Report  $report
     * @return \Dingo\Api\Http\Response
     */
    public function show(Report $report)
    {
        return $this->item($report, new Transformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        $report = $this->dispatch(new CreateReport($request));

        return $this->response->created(route('api.reports.show', $report->id), $this->item($report, new Transformer()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $report
     * @param  $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(Report $report, Request $request)
    {
        $report = $this->dispatch(new UpdateReport($report, $request));

        return $this->item($report->fresh(), new Transformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Report  $report
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Report $report)
    {
        try {
            $this->dispatch(new DeleteReport($report));

            return $this->response->noContent();
        } catch(\Exception $e) {
            $this->response->errorUnauthorized($e->getMessage());
        }
    }
}
