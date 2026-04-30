@stack('footer_start')
<footer class="footer">
    <div class="lg:absolute bottom-10 ltr:right-6 rtl:left-6 lg:ltr:right-24 lg:rtl:left-24 flex flex-col sm:flex-row items-center justify-end text-sm font-light">
        {{ trans('footer.powered') }}:&nbsp;<x-link href="{{ trans('footer.link') }}" target="_blank" override="class">{{ trans('footer.software') }}</x-link>
    </div>
</footer>
@stack('footer_end')
