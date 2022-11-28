<x-form>
    <div class="lg:absolute lg:ltr:right-0 lg:rtl:left-0 top-4">
        <div class="relative" x-model="price_type">
            <div class="w-58 flex items-center bg-gray-200 p-1 ltr:mr-2 rtl:ml-2 rounded-lg">
                <button type="button"
                    x-on:click="price_type = 'monthly'"
                    class="w-18 flex justify-center px-2"
                    x-bind:class="price_type == 'monthly' ? 'bg-white rounded-lg' : 'btn-outline-primary'"
                >
                    {{ trans('general.monthly') }}
                </button>

                <button type="button"
                    x-on:click="price_type = 'yearly'"
                    class="w-18 flex justify-center px-2"
                    x-bind:class="price_type == 'yearly' ? 'bg-white rounded-lg' : 'btn-outline-primary'"
                >
                    {{ trans('general.yearly') }}
                </button>

                <button type="button"
                    x-on:click="price_type = 'lifetime'"
                    class="w-18 flex justify-center px-2"
                    x-bind:class="price_type == 'lifetime' ? 'bg-white rounded-lg' : 'btn-outline-primary'"
                >
                    {{ trans('general.lifetime') }}
                </button>
            </div>
        </div>
    </div>
</x-form>
