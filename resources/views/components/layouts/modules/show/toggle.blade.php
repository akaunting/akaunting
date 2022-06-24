

<x-form>
    <div class="lg:absolute lg:ltr:right-0 lg:rtl:left-0 top-4" x-on:click="price_type = ! price_type">
        <div class="relative" x-model="price_type">
            <div class="w-36 flex items-center bg-gray-200 p-1 ltr:mr-2 rtl:ml-2 rounded-lg">
                <button type="button"
                    class="w-18 flex justify-center px-2"
                    x-bind:class="price_type == true ? 'btn-outline-primary' : 'bg-white rounded-lg'"
                >
                    {{ trans('general.monthly') }}
                </button>

                <button type="button"
                    class="w-18 flex justify-center px-2"
                    x-bind:class="price_type == false ? 'btn-outline-primary' : 'bg-white rounded-lg'"
                >
                    {{ trans('general.yearly') }}
                </button>
            </div>
        </div>
    </div>
</x-form>