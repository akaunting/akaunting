{{-- The report options bar. Its controls come from Report::getFilterControls(), and Update writes the same URL as the
old chip bar did: search tokens plus start_date and end_date. The height is kept while Vue mounts it. --}}
<div class="print:hidden min-h-[6.5rem] mb-6">
    <report-filter :definition="{{ json_encode($class->getFilterControls(), JSON_INVALID_UTF8_SUBSTITUTE) }}"></report-filter>
</div>
