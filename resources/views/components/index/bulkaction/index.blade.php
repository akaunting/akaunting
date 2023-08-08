@stack('bulk_action_row_input_start')

@if (! empty($actions))
    <div
        :class="[{'sm:flex': bulk_action.show}]"
        class="h-12 bg-purple-lighter items-center rounded-tl-xl rounded-tr-xl px-12 my-5 py-2 hidden"
        v-if="bulk_action.show"
    >
        <div class="mr-6">
            <span class="text-sm hidden sm:block">
                <span v-text="bulk_action.count"></span>
                <span v-if="bulk_action.count === 1">
                    {{ strtolower(trans_choice($text, 1)) }}
                </span>
                <span v-else-if="bulk_action.count > 1">
                    {{ strtolower(trans_choice($text, 2)) }}
                </span>
                {{ trans('bulk_actions.selected') }}:
            </span>
        </div>

        <div class="relative flex items-center ltr:mr-4 rtl:ml-4" v-if="bulk_action.count">
            @foreach ($actions as $key => $action)
                @if (! empty($action['icon']))
                    <div>
                        <x-tooltip id="{{ $key }}" placement="top" message="{{ trans($action['name']) }}">
                            <x-button @click="onChangeBulkAction('{{ $key }}')"
                                id="index-bulk-actions-{{ $key }}"
                                class="relative w-8 h-8 flex items-center px-2 mr-2 rounded-lg hover:bg-gray-200"
                                override="class"
                                data-message="{{ ! empty($action['message']) ? trans_choice($action['message'], 2, ['type' => strtolower(trans_choice($text, 2))]) : '' }}"
                                data-path="{{ (isset($path) && ! empty($path)) ? $path : '' }}"
                                data-type="{{ (isset($action['type']) && ! empty($action['type'])) ? $action['type'] : '' }}"
                            >
                                <x-icon class="text-lg" :icon="$action['icon']" />
                            </x-button>
                        </x-tooltip>
                    </div>
                @else
                    <div>
                        <x-tooltip id="{{ $key }}" placement="top" message="{{ trans($action['name']) }}">
                            <x-button @click="onChangeBulkAction('{{ $key }}')"
                                id="index-bulk-actions-{{ $key }}"
                                class="w-8 h-8 flex items-center px-2 rounded-lg hover:bg-gray-200"
                                override="class"
                                data-message="{{ ! empty($action['message']) ? trans_choice($action['message'], 2, ['type' => strtolower(trans_choice($text, 2))]) : '' }}"
                                data-path="{{ (isset($path) && ! empty($path)) ? $path : '' }}"
                                data-type="{{ (isset($action['type']) && ! empty($action['type'])) ? $action['type'] : '' }}"
                            >
                                {{ trans($action['name']) }}
                            </x-button>
                        </x-tooltip>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="ltr:mr-4 rtl:ml-4" v-if="bulk_action.count">
            <x-button @click="onClearBulkAction" class="bg-transparent" override="class">
                <x-button.hover>
                    {{ trans('general.clear') }}
                </x-button.hover>
            </x-button>
        </div>
    </div>

    <akaunting-modal
        :show="bulk_action.modal"
        :title="`{{ trans_choice($text, 2) }}`"
        :message="bulk_action.message"
        @cancel="onCancelBulkAction"
        v-if='bulk_action.message && bulk_action.modal'>
        <template #card-footer>
            <div class="flex items-center justify-end">
                <button type="button" @click="onCancelBulkAction" class="px-6 py-1.5 hover:bg-gray-200 rounded-lg ltr:mr-2 rtl:ml-2">
                    <span>{{ trans('general.cancel') }}</span>
                </button>

                <button type="button"
                    :disabled="bulk_action.loading"
                    @click="onActionBulkAction"
                    class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100"
                >
                    <i v-if="bulk_action.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                    <span :class="[{'opacity-0': bulk_action.loading}]">{{ trans('general.confirm') }}</span>
                </button>
            </div>
        </template>
    </akaunting-modal>
@else
    <div class="text-black hidden" :class="[{'sm:flex': bulk_action.show}]">
        {{ trans('bulk_actions.no_action') }}
    </div>
@endif

@stack('bulk_action_row_input_end')
