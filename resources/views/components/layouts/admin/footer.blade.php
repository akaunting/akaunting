@stack('footer_start')
    <footer class="footer container">
        <div class="flex flex-col sm:flex-row items-center justify-between lg:mt-16 py-6 text-xs font-medium text-silver border-t border-gray-100">
            <div>
                {{ trans('footer.powered') }}:
                <x-link href="{{ trans('footer.link') }}" target="_blank" override="class" class="text-purple hover:text-purple-700 transition-colors duration-150">{{ trans('footer.software') }}</x-link>
                &nbsp;<span class="material-icons align-middle text-sm text-black-300">code</span>&nbsp;
                {{ trans('footer.version') }} {{ version('short') }}
            </div>
        </div>
    </footer>
@stack('footer_end')
