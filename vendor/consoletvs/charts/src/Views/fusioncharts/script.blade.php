<script {!! $chart->displayScriptAttributes() !!}>
    function {{ $chart->id }}_create(data) {
        {{ $chart->id }}_rendered = true;
        document.getElementById("{{ $chart->id }}_loader").style.display = 'none';
        @if ($chart->type)
            let {{ $chart->id }}_type = {{ $chart->type }}
        @else
            let {{ $chart->id }}_type = data[0].renderAs;
        @endif
        if (!{!! json_encode($chart->keepType) !!}.includes({{ $chart->id }}_type)) {
            {{ $chart->id }}_type = "{{ $chart->comboType }}"
        }
        FusionCharts.ready(function () {
            window.{{ $chart->id }} = new FusionCharts({
                type: {{ $chart->id }}_type,
                renderAt: "{{ $chart->id }}",
                dataFormat: 'json',
                {!! $chart->formatContainerOptions('js', true) !!}
                dataSource: {
                    categories: [{
                        category: {!! $chart->formatLabels() !!}
                    }],
                    dataset: data,
                    chart: {!! $chart->formatOptions(true) !!}
                }
            }).render();
        });
    }
    @if ($chart->api_url)
    let {{ $chart->id }}_refresh = function (url) {
        document.getElementById("{{ $chart->id }}").style.display = 'none';
        document.getElementById("{{ $chart->id }}_loader").style.display = 'flex';
        if (typeof url !== 'undefined') {
            {{ $chart->id }}_api_url = url;
        }
        fetch({{ $chart->id }}_api_url)
            .then(data => data.json())
            .then(data => {
                document.getElementById("{{ $chart->id }}_loader").style.display = 'none';
                document.getElementById("{{ $chart->id }}").style.display = 'block';
                let chartData = {{ $chart->id }}.getChartData("json");
                chartData.dataset = data;
                {{ $chart->id }}.setChartData(chartData, "json");
        });
    };
    @endif
    @include('charts::init')
</script>
