<x-form id="form-email" :route="[$store_route, $transaction->id]">
    <x-tabs active="general" class="grid grid-cols-2 auto-rows-max" override="class" ignore-hash>
        <x-slot name="navs">
            <x-tabs.nav id="general">
                {{ trans('general.general') }}
            </x-tabs.nav>

            <x-tabs.nav id="attachments">
                {{ trans_choice('general.attachments', 2) }}
            </x-tabs.nav>
        </x-slot>

        <x-slot name="content">
            <x-tabs.tab id="general">
                <x-form.section>
                    <x-slot name="body">
                        <x-form.group.contact
                            name="to"
                            label="{{ trans('general.to') }}"
                            :type="$transaction->contact->type"
                            :options="$contacts"
                            :option_field="[
                                'key' => 'email',
                                'value' => 'email'
                            ]"
                            :selected="$transaction->contact?->email ? [$transaction->contact->email] : []"
                            without-remote
                            without-add-new
                            multiple
                            form-group-class="sm:col-span-6"
                        />

                        <x-form.group.text name="subject" label="{{ trans('settings.email.templates.subject') }}" value="{{ $notification->getSubject() }}" form-group-class="sm:col-span-6" />

                        <x-form.group.editor name="body" label="{{ trans('settings.email.templates.body') }}" :value="$notification->getBody()" rows="3" data-toggle="quill" form-group-class="sm:col-span-6 mb-0" />

                        <x-form.group.checkbox name="user_email" :options="['1' => trans('general.email_send_me', ['email' => user()->email])]" checkbox-class="col-span-6" form-group-class="sm:col-span-6 -mb-8" />

                        <x-form.input.hidden name="transaction_id" :value="$transaction->id" />
                    </x-slot>
                </x-form.section>
            </x-tabs.tab>

            <x-tabs.tab id="attachments">
                <x-table>
                    <x-table.thead>
                        <x-table.tr class="flex items-center px-1">
                            <x-table.th class="w-1/12">
                            </x-table.th>

                            <x-table.th class="w-1/6">
                            </x-table.th>

                            <x-table.th class="w-4/6">
                                {{ trans('general.name') }}
                            </x-table.th>

                            <x-table.th class="w-1/6">
                                {{ trans('general.size') }}
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        <x-table.tr id="method-pdf">
                            <x-table.td class="w-1/12">
                                <input type="checkbox"
                                    checked="checked"
                                    id="attachment-pdf"
                                    name="pdf"
                                    value="1"
                                    class="rounded-sm text-purple border-gray-300 cursor-pointer disabled:bg-gray-200 focus:outline-none focus:ring-transparent"
                                    data-field="attachments"
                                    @input="e => form.attachments[e.target.name] = e.target.checked | 0">
                            </x-table.td>
                            <x-table.td class="w-1/6">
                                <div class="avatar-attachment">
                                    <span class="material-icons text-base">description</span>
                                </div>
                            </x-table.td>

                            <x-table.td class="w-4/6">
                                {{ trans('general.pdf_file', ['type' => trans_choice('general.transactions', 1)]) }}
                            </x-table.td>

                            <x-table.td class="w-1/6">
                                {{ trans('general.na') }}
                            </x-table.td>
                        </x-table.tr>
                        @if ($transaction->attachment)
                            @foreach($transaction->attachment as $attachment)
                                <x-table.tr id="method-{{ $attachment->id }}">
                                    <x-table.td class="w-1/12">
                                        <input type="checkbox"
                                            id="attachment-{{ $attachment->id }}"
                                            name="{{ $attachment->id }}"
                                            class="rounded-sm text-purple border-gray-300 cursor-pointer disabled:bg-gray-200 focus:outline-none focus:ring-transparent"
                                            data-field="attachments"
                                            @input="e => form.attachments[e.target.name] = e.target.checked | 0">
                                    </x-table.td>
                                    <x-table.td class="w-1/6">
                                        @if ($attachment->aggregate_type == 'image')
                                            <div class="avatar-attachment">
                                                <img src="{{ route('uploads.get', $attachment->id) }}" alt="{{ $attachment->basename }}" class="avatar-img h-full rounded object-cover">
                                            </div>
                                        @else
                                            <div class="avatar-attachment">
                                                <span class="material-icons text-base">attach_file</span>
                                            </div>
                                        @endif
                                    </x-table.td>

                                    <x-table.td class="w-4/6">
                                        {{ $attachment->basename }}
                                    </x-table.td>

                                    <x-table.td class="w-1/6">
                                        {{ ! is_int($attachment->size) ? '0 B' : $attachment->readableSize() }}
                                    </x-table.td>
                                </x-table.tr>
                            @endforeach
                        @endif
                    </x-table.tbody>
                </x-table>
            </x-tabs.tab>
        </x-slot>
    </x-tabs>
</x-form>
