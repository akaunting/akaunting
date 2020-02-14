@stack('bulk_action_row_input_start')

@php
    if (is_array($path)) {
        $path = route('bulk-actions.action', $path);
    } else {
        $path = url('common/bulk-actions/' . $path);
    }

    $actions_to_show = [];
    foreach ($actions as $key => $action) {
        if ((isset($action['permission']) && !user()->can($action['permission']))) {
            continue;
        }

        $actions_to_show[$key] = true;
    }
@endphp

@if(!empty($actions_to_show))
<div class="align-items-center d-none"
     v-if="bulk_action.show"
     :class="[{'show': bulk_action.show}]">
    <div class="mr-6">
            <span class="text-white d-none d-sm-block">
                <b v-text="bulk_action.count"></b>
                <span v-if="bulk_action.count === 1">
                    {{ strtolower(trans_choice($text, 1)) }}
                </span>
                <span v-else-if="bulk_action.count > 1">
                    {{ strtolower(trans_choice($text, 2)) }}
                </span>
                {{ trans('bulk_actions.selected') }}
            </span>
    </div>

    <div class="w-25 mr-4" v-if="bulk_action.count">
        <div class="form-group mb-0">
            <select
                class="form-control form-control-sm"
                v-model="{{ !empty($attributes['v-model']) ? $attributes['v-model'] : 'bulk_action.value' }}"
                @change="onChange">
                <option value="*">{{ trans_choice('bulk_actions.bulk_actions', 2) }}</option>
                @foreach($actions as $key => $action)
                    @if(isset($actions_to_show[$key]))
                    <option
                        value="{{ $key }}"
                        @if(!empty($action['message']))
                        data-message="{{ trans_choice($action['message'], 2, ['type' => $text]) }}"
                        @endif
                    >{{ trans($action['name']) }}</option>
                    @endif
                @endforeach
            </select>

            <input type="hidden" name="bulk_action_path" value="{{ $path }}" />
        </div>
    </div>

    <div class="mr-4" v-if="bulk_action.count">
        <button type="button" class="btn btn-sm btn-outline-confirm"
                v-if="bulk_action.message.length"
                @click="bulk_action.modal=true">
            <span>{{ trans('general.confirm') }}</span>
        </button>
        <button type="button" class="btn btn-sm btn-outline-confirm"
                v-if="!bulk_action.message.length"
                @click="onAction">
            <span>{{ trans('general.confirm') }}</span>
        </button>
    </div>

    <div class="mr-4" v-if="bulk_action.count">
        <button type="button" class="btn btn-outline-clear btn-sm"
                @click="onClear">
            <span>{{ trans('general.clear') }}</span>
        </button>
    </div>
</div>

<akaunting-modal
    :show="bulk_action.modal"
    :title="'{{ trans_choice('general.items', 2) }}'"
    :message="bulk_action.message"
    @cancel="onCancel"
    v-if='bulk_action.message && bulk_action.modal'>
    <template #card-footer>
        <div class="float-right">
            <button type="button" class="btn btn-outline-secondary" @click="onCancel">
                <span>{{ trans('general.cancel') }}</span>
            </button>

            <button :disabled="bulk_action.loading" type="button" class="btn btn-success button-submit" @click="onAction">
                <div class="aka-loader d-none"></div>
                <span>{{ trans('general.confirm') }}</span>
            </button>
        </div>
    </template>
</akaunting-modal>
@else
<div class="text-white" :class="[{'show': bulk_action.show}]">{{ trans('bulk_actions.no_action') }}</div>
@endif

@stack('bulk_action_row_input_end')
