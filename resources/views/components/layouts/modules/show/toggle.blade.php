<x-form>
    <div class="lg:absolute lg:ltr:right-0 lg:rtl:left-0 top-4">
        <label for="priceToggle" class="flex items-center cursor-pointer" x-on:click="price_type = ! price_type">
            <div class="relative">
                <input type="checkbox" id="priceToggle" class="sr-only" x-model="price_type">

                <div class="bg-purple-300 w-24 h-7 rounded-full">
                    <span id="apps-toggle-monthly" class="monthly-badge text-xs text-white float-left ml-3 mt-1.5" x-show="price_type == true">
                        {{ trans('general.monthly') }}
                    </span>

                    <span id="apps-toggle-yearly" class="yearly-badge text-xs text-white float-right mr-3 mt-1.5" x-show="price_type == false">
                        {{ trans('general.yearly') }}
                    </span>
                </div>

                <div class="dot absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition"
                    x-bind:style="price_type == true ? 'transform: translateX(333%)' : '  transform: translateX(0) '"
                ></div>
            </div>
        </label>
    </div>
</x-form>
