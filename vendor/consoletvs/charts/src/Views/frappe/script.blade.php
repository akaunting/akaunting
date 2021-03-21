<script {!! $chart->displayScriptAttributes() !!}>
    function {{ $chart->id }}_getType(data) {
        var special_datasets = {!! json_encode($chart->special_datasets) !!};
        for (var i = 0; i < special_datasets.length; i++) {
            for (var k = 0; k < data.length; k++) {
                if (special_datasets[i] == data[k].chartType) {
                    return special_datasets[i];
                }
            }
        }
        return 'axis-mixed';
    }
    function {{ $chart->id }}_create(data) {
        {{ $chart->id }}_rendered = true;
        document.getElementById("{{ $chart->id }}_loader").style.display = 'none';
        window.{{ $chart->id }} = new frappe.Chart("#{{ $chart->id }}", {
            {!! $chart->formatContainerOptions('js') !!}
            labels: {!! $chart->formatLabels() !!},
            type: {{ $chart->id }}_getType(data),
            data: {
                labels: {!! $chart->formatLabels() !!},
                datasets: data
            },
            {!! $chart->formatOptions(false, true) !!}
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
                {{ $chart->id }}.update({labels: {!! $chart->formatLabels() !!}, datasets: data});
            });
    };
    @endif
    @include('charts::init')
</script>
