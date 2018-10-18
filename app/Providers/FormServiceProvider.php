<?php

namespace App\Providers;

use Form;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Form components
        Form::component('textGroup', 'partials.form.text_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6',
        ]);

        Form::component('emailGroup', 'partials.form.email_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6',
        ]);

        Form::component('passwordGroup', 'partials.form.password_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6',
        ]);

        Form::component('numberGroup', 'partials.form.number_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6',
        ]);

        Form::component('selectGroup', 'partials.form.select_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required'], 'col' => 'col-md-6',
        ]);

        Form::component('textareaGroup', 'partials.form.textarea_group', [
            'name', 'text', 'value' => null, 'attributes' => ['rows' => '3'], 'col' => 'col-md-12',
        ]);

        Form::component('radioGroup', 'partials.form.radio_group', [
            'name', 'text', 'enable' => trans('general.yes'), 'disable' => trans('general.no'), 'attributes' => [], 'col' => 'col-md-6',
        ]);

        Form::component('checkboxGroup', 'partials.form.checkbox_group', [
            'name', 'text', 'items' => [], 'value' => 'name', 'id' => 'id', 'attributes' => ['required' => 'required'], 'col' => 'col-md-12',
        ]);

        Form::component('fileGroup', 'partials.form.file_group', [
            'name', 'text', 'attributes' => [], 'value' => null, 'col' => 'col-md-6',
        ]);

        Form::component('deleteButton', 'partials.form.delete_button', [
            'item', 'url', 'text' => '', 'value' => 'name', 'id' => 'id',
        ]);

        Form::component('deleteLink', 'partials.form.delete_link', [
            'item', 'url', 'text' => '', 'value' => 'name', 'id' => 'id',
        ]);

        Form::component('saveButtons', 'partials.form.save_buttons', [
            'cancel', 'col' => 'col-md-12',
        ]);

        Form::component('recurring', 'partials.form.recurring', [
            'page', 'model' => null,
        ]);

        Form::component('invoice_text', 'partials.form.invoice_text', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required'], 'input_name', 'input_value', 'col' => 'col-md-6',
        ]);

        Form::component('dateRange', 'partials.form.date_range', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6',
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}