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
            datasets: [
                @for ($i = 0; $i < count($model->datasets); $i++)
                {
                    fill: true,
                    label: "{!! $model->datasets[$i]['label'] !!}",
                    lineTension: 0.3,
                    @if ($model->colors and count($model->colors) > $i)
                    borderColor: "{{ $model->colors[$i] }}",
                    backgroundColor: "{{ $model->colors[$i] }}",
                    @else
                    @php $c = sprintf('#%06X', mt_rand(0, 0xFFFFFF)); @endphp
                    borderColor: "{{ $c }}",
                    backgroundColor: "{{ $c }}",
                    @endif
                    data: [
                        @foreach ($model->datasets[$i]['values'] as $dta)
                        {{ $dta }},
                        @endforeach
                    ],
                },
                @endfor
            ]
        },
        options: {
            responsive: {{ $model->responsive || !$model->width ? 'true' : 'false' }},
            maintainAspectRatio: false,
            @if ($model->title)
            title: {
                display: true,
                text: "{!! $model->title !!}",
                fontSize: 20,
            },
            @endif
            scales: {
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true,
                    }
                }]
            }
        }
    });
</script>
@endpush
