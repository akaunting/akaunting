{{ trans('portal.redirect_description', ['name' => $setting['name']]) }}

<div class="mt-3">
    <x-form id="redirect-form" :url="$confirm_url">
        <x-button
            id="button-confirm"
            kind="primary"
            override="class"
            class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100"
            @click="onRedirectConfirm"
        >
            <span>
                {{ trans('general.confirm') }}
            </span>
        </x-button>
    </x-form>
</div>
