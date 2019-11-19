<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Report as Request;
use App\Jobs\Common\CreateReport;
use App\Jobs\Common\DeleteReport;
use App\Jobs\Common\UpdateReport;
use App\Models\Common\Report;
use App\Utilities\Reports as Utility;

class Reports extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $classes = [];
        $reports = ['income-expense' => [], 'accounting' => []];

        $items = Report::collect();

        foreach ($items as $item) {
            $class = Utility::getClassInstance($item);

            if (!$class->canRead()) {
                continue;
            }

            $reports[$class->getCategory()][] = $item;

            $classes[$item->id] = $class;
        }

        return view('common.reports.index', compact('reports', 'classes'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Report $report
     * @return Response
     */
    public function show(Report $report)
    {
        $class = Utility::getClassInstance($report);

        if (!$class->canRead()) {
            abort(403);
        }

        return $class->show();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $classes = Utility::getClasses();

        $groups = Utility::getGroups();

        $periods = Utility::getPeriods();

        $basises = Utility::getBasises();

        $charts = Utility::getCharts();

        return view('common.reports.create', compact('classes', 'groups', 'periods', 'basises', 'charts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateReport($request));

        if ($response['success']) {
            $response['redirect'] = route('reports.index');

            $message = trans('messages.success.added', ['type' => trans_choice('general.reports', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('reports.create');

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Report  $report
     *
     * @return Response
     */
    public function edit(Report $report)
    {
        $classes = Utility::getClasses();

        $groups = Utility::getGroups();

        $periods = Utility::getPeriods();

        $basises = Utility::getBasises();

        $charts = Utility::getCharts();

        return view('common.reports.edit', compact('report', 'classes', 'groups', 'periods', 'basises', 'charts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Report $report
     * @param  $request
     * @return Response
     */
    public function update(Report $report, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateReport($report, $request));

        if ($response['success']) {
            $response['redirect'] = route('reports.index');

            $message = trans('messages.success.updated', ['type' => $report->name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('reports.edit', $report->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Report $report
     *
     * @return Response
     */
    public function destroy(Report $report)
    {
        $response = $this->ajaxDispatch(new DeleteReport($report));

        $response['redirect'] = route('reports.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => $report->name]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }

    /**
     * Print the report.
     *
     * @param  Report $report
     * @return Response
     */
    public function print(Report $report)
    {
        $class = Utility::getClassInstance($report);

        if (!$class->canRead()) {
            abort(403);
        }

        return $class->print();
    }

    /**
     * Export the report.
     *
     * @param  Report $report
     * @return Response
     */
    public function export(Report $report)
    {
        $class = Utility::getClassInstance($report);

        if (!$class->canRead()) {
            abort(403);
        }

        return $class->export();
    }

    /**
     * Get groups of the specified resource.
     *
     * @return Response
     */
    public function groups()
    {
        $class = request('class');

        if (!class_exists($class)) {
            return response()->json([
                'success' => false,
                'error' => true,
                'data' => false,
                'message' => "Class doesn't exist",
            ]);
        }

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => (new $class())->groups,
            'message' => '',
        ]);
    }
}
