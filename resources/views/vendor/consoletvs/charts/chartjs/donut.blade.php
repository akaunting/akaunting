@if(!$model->customId)
    @include('charts::_partials.container.canvas2')
@endif

<script type="text/javascript">
    var ctx = document.getElementById("{{ $model->id }}")

    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($model->labels as $label)
                    "{!! $label !!}",
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($model->values as $dta)
                        {{ $dta }},
                    @endforeach
                ],
                backgroundColor: [
                    @if($model->colors)
                        @foreach($model->colors as $color)
                            "{{ $color }}",
                        @endforeach
                    @else
                        @foreach($model->values as $dta)
                            "{{ sprintf('#%06X', mt_rand(0, 0xFFFFFF)) }}",
                        @endforeach
                    @endif
                ],
            }]
        },
        options: {
            responsive: {{ $model->responsive || !$model->width ? 'true' : 'false' }},
            maintainAspectRatio: false,
            legend: {
                display: true,
                fullWidth: true,
                position: 'right',
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var allData = data.datasets[tooltipItem.datasetIndex].data;
                        var tooltipLabel = data.labels[tooltipItem.index];
                        var tooltipData = allData[tooltipItem.index];
                        var total = 0;

                        var label = tooltipLabel.split(" - ");

                        for (var i in allData) {
                            total += allData[i];
                        }

                        var tooltipPercentage = Math.round((tooltipData / total) * 100);

                        return label[1] + ': ' + label[0] + ' (' + tooltipPercentage + '%)';
                    }
                }
            },
            @if($model->title)
            title: {
                display: true,
                text: "{!! $model->title !!}",
                fontSize: 20,
            }
            @endif
        }
    });
</script>
