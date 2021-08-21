<?php

namespace App\Providers;

use Form as Facade;
use Illuminate\Support\ServiceProvider as Provider;

class Form extends Provider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Form components
        Facade::component('moneyGroup', 'partials.form.money_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('dateTimeGroup', 'partials.form.date_time_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('dateGroup', 'partials.form.date_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('timeGroup', 'partials.form.time_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('textGroup', 'partials.form.text_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('emailGroup', 'partials.form.email_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('passwordGroup', 'partials.form.password_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('numberGroup', 'partials.form.number_group', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('multiSelectGroup', 'partials.form.multi_select_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required'], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('multiSelectAddNewGroup', 'partials.form.multi_select_add_new_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required'], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('multiSelectRemoteGroup', 'partials.form.multi_select_remote_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required'], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('multiSelectRemoteAddNewGroup', 'partials.form.multi_select_remote_add_new_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required', 'path' => ''], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('selectGroup', 'partials.form.select_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required'], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('selectAddNewGroup', 'partials.form.select_add_new_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required', 'path' => ''], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('selectGroupGroup', 'partials.form.select_group_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required'], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('selectGroupAddNewGroup', 'partials.form.select_group_add_new_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required'], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('selectRemoteGroup', 'partials.form.select_remote_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required'], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('selectRemoteAddNewGroup', 'partials.form.select_remote_add_new_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required', 'path' => ''], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('textareaGroup', 'partials.form.textarea_group', [
            'name', 'text', 'icon', 'value' => null, 'attributes' => ['rows' => '3'], 'col' => 'col-md-12', 'group_class' => null
        ]);

        Facade::component('textEditorGroup', 'partials.form.text_editor_group', [
            'name', 'text', 'icon', 'value' => null, 'attributes' => ['rows' => '3'], 'col' => 'col-md-12', 'group_class' => null
        ]);

        Facade::component('radioGroup', 'partials.form.radio_group', [
            'name', 'text', 'value' => null, 'enable' => trans('general.yes'), 'disable' => trans('general.no'), 'attributes' => [], 'col' => 'col-md-6',
        ]);

        Facade::component('checkboxGroup', 'partials.form.checkbox_group', [
            'name', 'text', 'items' => [], 'value' => 'name', 'id' => 'id', 'selected'=>[], 'attributes' => ['required' => 'required'], 'col' => 'col-md-12',
        ]);

        Facade::component('fileGroup', 'partials.form.file_group', [
            'name', 'text', 'icon', 'attributes' => [], 'value' => null, 'col' => 'col-md-6',
        ]);

        Facade::component('deleteButton', 'partials.form.delete_button', [
            'item', 'url', 'text' => '', 'value' => 'name', 'id' => 'id',
        ]);

        Facade::component('deleteLink', 'partials.form.delete_link', [
            'item', 'url', 'text' => '', 'value' => 'name', 'id' => 'id',
        ]);

        Facade::component('saveButtons', 'partials.form.save_buttons', [
            'cancel', 'col' => 'col-md-12',
        ]);

        Facade::component('recurring', 'partials.form.recurring', [
            'page', 'model' => null, 'col' => 'col-md-6',
        ]);

        Facade::component('invoice_text', 'partials.form.invoice_text', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required'], 'input_name', 'input_value', 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('dateRange', 'partials.form.date_range', [
            'name', 'text', 'icon', 'attributes' => ['required' => 'required'], 'value' => null, 'col' => 'col-md-6',
        ]);

        Facade::component('bulkActionRowGroup', 'partials.form.bulk_action_row_group', [
            'text', 'actions', 'path', 'attributes' => []
        ]);

        Facade::component('bulkActionAllGroup', 'partials.form.bulk_action_all_group', [
            'attributes' => []
        ]);

        Facade::component('bulkActionGroup', 'partials.form.bulk_action_group', [
            'id', 'name', 'attributes' => []
        ]);

        Facade::component('enabledGroup', 'partials.form.enabled_group', [
            'id', 'name', 'value'
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
