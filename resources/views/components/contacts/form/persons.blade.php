<x-form.section column-number="overflow-x-scroll large-overflow-unset">
    <x-slot name="head">
        <x-form.section.head
            title="{{ trans_choice($textSectionPersonsTitle, 2) }}"
            description="{{ trans($textSectionPersonsDescription) }}"
        />
    </x-slot>

    <x-slot name="body">
        <x-table class="flex flex-col divide-y divide-gray-200">
            <x-table.thead>
                <x-table.tr>
                    <x-table.th class="w-4/12">
                        {{ trans('general.name') }}
                    </x-table.th>

                    <x-table.th class="w-4/12">
                        {{ trans('general.email') }}
                    </x-table.th>

                    <x-table.th class="w-4/12">
                        {{ trans('general.phone') }}
                    </x-table.th>

                    <x-table.th class="w-6 none-truncate text-right align-top group" override="class"></x-table.th>
                </x-table.tr>
            </x-table.thead>

            <x-table.tbody>
                <x-table.tr class="relative flex items-start px-1 group/actions border-b" v-for="(row, index) in form.contact_persons" ::index="index">
                    <x-table.td class="w-4/12">
                        <x-form.group.text 
                            name="contact_persons[][name]" 
                            data-item="name" 
                            v-model="row.name" 
                            @change="forceUpdate()" 
                            placeholder="{{ trans('general.name') }}"
                            v-error="form.errors.has('contact_persons.' + index + '.name')"
                            v-error-message="form.errors.get('contact_persons.' + index + '.name')"
                        />
                    </x-table.td>

                    <x-table.td class="w-4/12">
                        <x-form.group.text 
                            name="contact_persons[][email]" 
                            data-item="email" 
                            v-model="row.email" 
                            @change="forceUpdate()" 
                            placeholder="{{ trans('general.email') }}" 
                            v-error="form.errors.has('contact_persons.' + index + '.email')" 
                            v-error-message="form.errors.get('contact_persons.' + index + '.email')"
                        />
                    </x-table.td>

                    <x-table.td class="w-4/12">
                        <x-form.group.text 
                            name="contact_persons[][phone]" 
                            data-item="phone" 
                            v-model="row.phone" 
                            @change="forceUpdate()" 
                            placeholder="{{ trans('general.phone') }}"
                            v-error="form.errors.has('contact_persons.' + index + '.phone')" 
                            v-error-message="form.errors.get('contact_persons.' + index + '.phone')"
                        />
                    </x-table.td>

                    <x-table.td class="w-6 mt-2.5 none-truncate text-right align-top group" override="class">
                        <button type="button" @click="onDeletePerson(index)" class="w-6 h-7 flex items-center rounded-lg p-0 group-hover:bg-gray-100 mt-4">
                            <span class="w-full material-icons-outlined text-lg text-gray-300 group-hover:text-gray-500">delete</span>
                        </button>
                    </x-table.td>
                </x-table.tr>

                <x-table.tr id="addItem" override="class" class="flex items-center">
                    <x-table.td colspan="3" override="class" class="w-full ltr:text-left rtl:text-right cursor-pointer whitespace-nowrap text-sm font-normal text-black truncate">
                        <div id="person-button-add" class="w-full border-b">
                            <x-button type="button" @click="onAddPerson" override="class" class="w-full h-10 flex items-center justify-center text-purple font-medium disabled:bg-gray-200 hover:bg-gray-100">
                                <span class="material-icons-outlined text-base font-bold ltr:mr-1 rtl:ml-1">add</span>

                                {{ trans('general.form.add', ['field' => trans_choice('general.contact_persons', 1)]) }}
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            </x-table.tbody>
        </x-table>
    </x-slot>
</x-form.section>
