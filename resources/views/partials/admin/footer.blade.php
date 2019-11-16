@stack('footer_start')
    <footer class="footer">
        <div class="row">
            <div class="col-md-6">
                <div class="text-sm float-left text-muted footer-texts">
                    {{ trans('footer.powered') }}: <a class="text-success" href="{{ trans('footer.link') }}" target="_blank">{{ trans('footer.software') }}</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-sm float-right text-muted footer-texts">
                    {{ trans('footer.version') }} {{ version('short') }}
                </div>
            </div>
        </div>
    </footer>
@stack('footer_end')
