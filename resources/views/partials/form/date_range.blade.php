<button type="button" class="btn btn-sm btn-default btn-filter date-range-btn">
    <span>
      <i class="fa fa-{{ $icon }}"></i> {{ $text }}
    </span>
    <i class="fa fa-caret-down"></i>
    {!! Form::hidden($name, null, []) !!}
</button>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('.date-range-btn').daterangepicker(
            {
                ranges   : {
                    'Today'       : [moment(), moment()],
                    'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                    'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate  : moment()
            },
            function (start, end) {
                $('input[name={{ $name }}]').val(start.format('YYYY-MM-DD') + '_' + end.format('YYYY-MM-DD'));

                date_format = convertDateFormat('{{ setting('general.date_format') }}', ' ');
                $('.date-range-btn span').html(start.format(date_format) + ' - ' + end.format(date_format));
            }
        );

        @if(request($name))
        var setDate = '{{ request($name) }}';
        var setDates = setDate.split('_');

        date_format = convertDateFormat('{{ setting('general.date_format') }}', ' ');

        start_date = moment(setDates[0], 'YYYY-MM-DD'); //new Date(setDates[0]);
        finish_date = moment(setDates[1], 'YYYY-MM-DD'); //new Date(setDates[1]);

        $('.date-range-btn span').html(start_date.format(date_format) + ' - ' + finish_date.format(date_format));
        @endif
    });
</script>
@endpush
