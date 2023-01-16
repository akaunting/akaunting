<x-form.group.file
    name="attachment"
    label="{{ trans('general.attachment') }}"
    singleWidthClasses
    not-required
    dropzone-class="w-full"
    multiple="multiple"
    :options="['acceptedFiles' => $file_types]"
    form-group-class="sm:col-span-6"
/>
