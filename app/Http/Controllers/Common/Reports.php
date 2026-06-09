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
        $icons = $categories = [];

        $reports = Report::orderBy('name')->get();

        // Pre-compute which module aliases are enabled once, so the loop
        // never re-reads module JSON files from disk (150+ modules were causing 256s+ timeouts).
        $moduleStatusCache = [];

        foreach ($reports as $report) {
            $alias = $report->alias ?? 'core';

            // Check module enabled status from cache
            if ($alias !== 'core') {
                if (! array_key_exists($alias, $moduleStatusCache)) {
                    $moduleStatusCache[$alias] = Utility::isModuleEnabled($report->class);
                }

                if (! $moduleStatusCache[$alias]) {
                    continue;
                }
            }

            // Permission check (no disk I/O, just DB/memory)
            if (Utility::cannotRead($report->class)) {
                continue;
            }

            if (! class_exists($report->class)) {
                continue;
            }

            // Read icon & category from class properties via reflection
            // instead of instantiating (which triggers setGroups() event dispatch for every single report).
            $ref = new \ReflectionClass($report->class);

            $iconProp = $ref->getProperty('icon');
            $icons[$report->id] = $iconProp->getDefaultValue() ?? 'donut_small';

            $categoryProp = $ref->getProperty('category');
            $categoryKey = $categoryProp->getDefaultValue() ?? 'reports.income_expense';
            $category = trans($categoryKey);

            if (empty($categories[$category])) {
                $descKey = null;

                if ($ref->hasProperty('category_description')) {
                    $descKey = $ref->getProperty('category_description')->getDefaultValue();
                }

                $categories[$category] = [
                    'name' => $category,
                    'description' => $descKey ? trans($descKey) : '',
                    'reports' => [$report],
                ];
            } else {
                $categories[$category]['reports'][] = $report;
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
