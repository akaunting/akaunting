<?php

namespace App\Http\Controllers\Common;

use App\Abstracts\Http\Controller;
use Akaunting\Module\Module;

class Import extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  $group
     * @param  $type
     * @param  $route
     *
     * @return Response
     */
    public function create($group, $type, $route = null)
    {
        $path = company_id() . '/' . $group . '/' . $type;

        $module = module($group);

        if ($module instanceof Module) {
            $title_type = trans_choice($group . '::general.' . str_replace('-', '_', $type), 2);
            $sample_file = url('modules/' . $module->getStudlyName() . '/Resources/assets/' . $type . '.xlsx');
        } else {
            $title_type = trans_choice('general.' . str_replace('-', '_', $type), 2);
            $sample_file = url('public/files/import/' . $type . '.xlsx');
        }

        $form_params = [
            'id' => 'import',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true,
        ];

        if (!empty($route)) {
            $form_params['route'] = $route;
        } else {
            $form_params['url'] = $path . '/import';
        }

        return view('common.import.create', compact('group', 'type', 'path', 'route', 'form_params', 'title_type', 'sample_file'));
    }
}
