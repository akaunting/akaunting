<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\EmailTemplate as Request;
use App\Jobs\Setting\UpdateEmailTemplate;
use App\Models\Setting\EmailTemplate;
use App\Traits\Modules;
use Illuminate\Support\Str;

class EmailTemplates extends Controller
{
    use Modules;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function edit()
    {
        $templates = [];

        EmailTemplate::all()->reject(function($template) {
            if (Str::startsWith($template->class, 'App')) {
                return false;
            }

            $class = explode('\\', $template->class);

            return $this->moduleIsDisabled(Str::kebab($class[1]));
        })->each(function ($template) use (&$templates) {
            $templates[$template->group][$template->id] = $template;
        });

        ksort($templates);

        return view('settings.email-templates.edit', compact('templates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $template = EmailTemplate::find($request->id);

        $response = $this->ajaxDispatch(new UpdateEmailTemplate($template, $request));

        if ($response['success']) {
            $response['redirect'] = url()->previous();;

            $message = trans('messages.success.updated', ['type' => trans($template->name)]);

            flash($message)->success();
        } else {
            $response['redirect'] = url()->previous();;

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function get()
    {
        $template = EmailTemplate::find(request()->id);

        $template->tags = trans('settings.email.templates.tags', ['tag_list'=> implode(', ', app($template->class)->getTags())]);

        return response()->json([
            'errors' => false,
            'success' => true,
            'data'    => $template,
        ]);
    }
}
