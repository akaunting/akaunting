<div>
    <div class="hidden">
        @if (!empty($setting['name']))
            <h2>{{ $setting['name'] }}</h2>
        @endif

        @if (!empty($setting['description']))
            <div>{{ $setting['description'] }}</div>
        @endif
    </div>
    <br>

    <div class="buttons">
        <div class="pull-right">
            <x-form id="redirect-form" :url="$confirm_url">
                <button @click="onRedirectConfirm" type="button" id="button-confirm" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100">
                    <span>
                        {{ trans('general.confirm') }}
                    </span>
                </button>
            </x-form>
        </div>
    </div>
</div>
