@if(!$model->customId)
    @include('charts::_partials.container.canvas2')
@endif

@push('body_scripts')
<script type="text/javascript">
    var ctx = document.getElementById("{{ $model->id }}");

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @foreach ($model->labels as $label)
                "{!! $label !!}",
                @endforeach
            ],
            datasets: [{
                label: "{!! $model->element_label !!}",
                backgroundColor: [
                @if ($model->colors)
                    @foreach ($model->colors as $color)
                    "{{ $color }}",
                    @endforeach
                @else
                    @foreach ($model->values as $dta)
                    "{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}",
                    @endforeach
                @endif
                ],
                data: [
                    @foreach ($model->values as $dta)
                    {{ $dta }},
                    @endforeach
                ]
            }]
        },
        options: {
            responsive: {{ $model->responsive || !$model->width ? 'true' : 'false' }},
            maintainAspectRatio: false,
            @if ($model->title)
            title: {
                display: true,
                text: "{!! $model->title !!}",
                fontSize: 20
            },
            @endif
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>
@endpush
