@stack('footer_start')

    <footer class="footer">
        <div class="flex flex-col sm:flex-row items-center justify-between mt-10 lg:mt-20 py-7 text-sm font-light">
            <div>
                {{ trans('footer.powered') }}:
                <a href="{{ trans('footer.link') }}" target="_blank">{{ trans('footer.software') }}</a>
                &nbsp;<span class="material-icons align-middle text-black-300">code</span>&nbsp;
                {{ trans('footer.version') }} {{ version('short') }}
            </div>
        </div>
    </footer>

@stack('footer_end')
