<x-layouts.admin>
    <x-slot name="title">{{ trans('settings.email.email_templates') }}</x-slot>

    <x-slot name="content">
        <div class="flex flex-col lg:flex-row">
            <div class="relative flex flex-col lg:flex-row mt-4 gap-12 sm:divide-x-2 lg:w-full">
                <div class="w-full lg:w-1/3">
                    @foreach ($templates as $group => $template)
                        <div>
                            <div class="pb-2">
                                <x-form.section.head title="{{ trans_choice($group, 2) }}" description="" />
                            </div>

                            <div class="flex flex-col">
                                <div class="mb-3">
                                    @foreach ($template as $item)
                                        <x-button class="text-xs truncate text-left" @click="onEditEmailTemplate({{ $item->id }}, $event)" override="class">
                                            <x-button.hover color="to-purple">
                                                {{ trans($item->name) }}
                                            </x-button.hover>
                                        </x-button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="w-full lg:w-2/3 flex-col xl:m-0 lg:pl-12 justify-evenly xl:w-2/3">
                    @php
                        $first = Arr::first($templates);
                        $template = Arr::first($first);
                    @endphp

                    <x-form id="setting" method="PATCH" route="settings.email-templates.update" :model="$template">
                        <div class="mb-14">
                            <div class="border-b-2 border-gray-200 pb-2">
                                <h2 class="lg:text-lg font-medium text-black" v-if="template_title" v-html="template_title"></h2>
                                <h2 class="lg:text-lg font-light text-black" v-else>
                                    {{ trans($template->name) }}
                                </h2>
                            </div>

                            <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
                                <x-form.group.text name="subject" label="{{ trans('settings.email.templates.subject') }}" form-group-class="sm:col-span-6" />

                                <div class="sm:col-span-6 required" v-if='form.body != null'>
                                    <x-form.label for="body">
                                        {{ trans('settings.email.templates.body') }}
                                    </x-form.label>

                                    <akaunting-html-editor
                                        name="body"
                                        v-model='form.body'
                                        :model='form.body'
                                    ></akaunting-html-editor>
                                </div>

                                <div class="sm:col-span-6 required" v-if='form.body == null'>
                                    <x-form.group.editor name="body" label="{{ trans('settings.email.templates.body') }}" value="{!! $template->body !!}" v-model='form.body' rows="5" />
                                </div>

                                <div class="sm:col-span-6">
                                    <div class="bg-gray-200 rounded-md p-3">
                                        <small v-html='tags' v-if='tags != null'></small>
                                        <small v-if='tags == null'>
                                            {!! trans('settings.email.templates.tags', ['tag_list' => implode(', ', app($template->class)->getTags())]) !!}
                                        </small>
                                    </div>
                                </div>

                                <x-form.input.hidden name="id" :value="$template->id" />
                            </div>
                        </div>

                        @can('update-settings-email-templates')
                        <x-form.section>
                            <x-slot name="foot">
                                <x-form.buttons :cancel="url()->previous()" />
                            </x-slot>
                        </x-form.section>
                        @endcan

                        <x-form.input.hidden name="_prefix" value="email" />
                    </x-form>
                </div>
            </div>
        </div>
    </x-slot>

    <x-script folder="settings" file="settings" />
</x-layouts.admin>
