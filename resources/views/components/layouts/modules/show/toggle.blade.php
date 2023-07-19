<x-form>
    <div>
        <div class="relative" x-model="price_type">
            <div class="w-58 flex items-center">
                <button type="button"
                    x-on:click="price_type = 'monthly'"
                    class="w-18 flex justify-center text-base px-2 py-1 rounded-tl-lg rounded-bl-lg"
                    x-bind:class="price_type == 'monthly' ? 'bg-black-700 text-white' : 'bg-gray-200 btn-outline-primary'"
                >
                    {{ trans('general.monthly') }}
                </button>

                <button type="button"
                    x-on:click="price_type = 'yearly'"
                    class="w-18 flex justify-center text-base px-2 py-1 border-r border-l border-gray-300"
                    x-bind:class="price_type == 'yearly' ? 'bg-black-700 text-white' : 'bg-gray-200 btn-outline-primary'"
                >
                    {{ trans('general.yearly') }}
                </button>

                <button type="button"
                    x-on:click="price_type = 'lifetime'"
                    class="w-18 flex justify-center text-base px-2 py-1 rounded-tr-lg rounded-br-lg"
                    x-bind:class="price_type == 'lifetime' ? 'bg-black-700 text-white' : 'bg-gray-200 btn-outline-primary'"
                >
                    {{ trans('general.lifetime') }}
                </button>
            </div>
        </div>
    </div>
</x-form>
