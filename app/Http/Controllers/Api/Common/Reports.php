<?php

namespace App\Http\Controllers\Api\Common;

use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Report as Request;
use App\Http\Resources\Common\Report as Resource;
use App\Jobs\Common\CreateReport;
use App\Jobs\Common\DeleteReport;
use App\Jobs\Common\UpdateReport;
use App\Models\Common\Report;

class Reports extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $reports = Report::collect();

        return Resource::collection($reports);
    }

    /**
     * Display the specified resource.
     *
     * @param  Report  $report
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Report $report)
    {
        return new Resource($report);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $report = $this->dispatch(new CreateReport($request));

        return $this->created(route('api.reports.show', $report->id), new Resource($report));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $report
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Report $report, Request $request)
    {
        $report = $this->dispatch(new UpdateReport($report, $request));

        return new Resource($report->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        try {
            $this->dispatch(new DeleteReport($report));

            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
