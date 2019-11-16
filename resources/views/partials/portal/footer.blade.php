@stack('footer_start')
    <footer class="footer pt-0">
        <div class="text-sm float-left text-muted footer-texts">
            {{ trans('footer.powered') }}: <a class="text-success" href="{{ trans('footer.link') }}" target="_blank">{{ trans('footer.software') }}</a>
        </div>
    </footer>
@stack('footer_end')
