<div>
    <div class="d-none">
        @if (!empty($setting['name']))
            <h2>{{ $setting['name'] }}</h2>
        @endif

        @if (!empty($setting['description']))
            <div class="well well-sm">{{ $setting['description'] }}</div>
        @endif
    </div>
    <br>

    <div class="buttons">
        <div class="pull-right">
            <x-form id="redirect-form" :url="$confirm_url">
                <button @click="onRedirectConfirm" type="button" id="button-confirm" class="btn disabled:bg-green-100">
                    {{ trans('general.confirm') }}
                </button>
            </x-form>
        </div>
    </div>
</div>
