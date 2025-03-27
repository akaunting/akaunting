<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Report as Request;
use App\Http\Requests\Common\ReportShow as ShowRequest;
use App\Jobs\Common\CreateReport;
use App\Jobs\Common\DeleteReport;
use App\Jobs\Common\UpdateReport;
use App\Models\Common\Report;
use App\Utilities\Reports as Utility;

class Reports extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Add CRUD permission check
        $this->middleware('permission:create-common-reports')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-common-reports')->only('index', 'show', 'export');
        $this->middleware('permission:update-common-reports')->only('edit', 'update', 'enable', 'disable');
        $this->middleware('permission:delete-common-reports')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $totals = $icons = $categories = [];

        $reports = Report::orderBy('name')->get();

        foreach ($reports as $report) {
            if (Utility::cannotShow($report->class)) {
                continue;
            }

            $class = Utility::getClassInstance($report, false);

            if (empty($class)) {
                continue;
            }

            $icons[$report->id] = $class->getIcon();

            if (empty($categories[$class->getCategory()])) {
                $categories[$class->getCategory()] = [
                    'name' => $class->getCategory(),
                    'description' => $class->getCategoryDescription(),
                    'reports' => [$report],
                ];
            } else {
                $categories[$class->getCategory()]['reports'][] = $report;
            }
        }

        return $this->response('common.reports.index', compact('categories', 'icons'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Report $report
     * @param  ShowRequest $request
     * @return Response
     */
    public function show(Report $report, ShowRequest $request)
    {
        if (Utility::cannotShow($report->class)) {
            abort(403);
        }

        $class = Utility::getClassInstance($report);

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

        return view('common.reports.create', compact('classes'));
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

            $message = trans('messages.success.created', ['type' => trans_choice('general.reports', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('reports.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Report  $report
     *
     * @return Response
     */
    public function duplicate(Report $report)
    {
        $clone = $report->duplicate();

        $message = trans('messages.success.duplicated', ['type' => trans_choice('general.reports', 1)]);

        flash($message)->success();

        return redirect()->route('reports.edit', $clone->id);
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

        $class = Utility::getClassInstance($report, false);

        return view('common.reports.edit', compact('report', 'classes', 'class'));
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

            flash($message)->error()->important();
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

            flash($message)->error()->important();
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
        if (Utility::cannotShow($report->class)) {
            abort(403);
        }

        return Utility::getClassInstance($report)->print();
    }

    /**
     * Download PDF file of the report.
     *
     * @param  Report $report
     * @return Response
     */
    public function pdf(Report $report)
    {
        if (Utility::cannotShow($report->class)) {
            abort(403);
        }

        return Utility::getClassInstance($report)->pdf();
    }

    /**
     * Export the report.
     *
     * @param  Report $report
     * @return Response
     */
    public function export(Report $report)
    {
        if (Utility::cannotShow($report->class)) {
            abort(403);
        }

        return Utility::getClassInstance($report)->export();
    }

    /**
     * Get fields of the specified resource.
     *
     * @return Response
     */
    public function fields()
    {
        $class = request('class');

        if (!class_exists($class)) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => 'Class does not exist',
                'html' => '',
            ]);
        }

        $fields = (new $class())->getFields();

        $html = view('components.reports.fields', compact('fields'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => '',
            'html' => $html,
        ]);
    }
}
