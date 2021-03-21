<script {!! $chart->displayScriptAttributes() !!}>
    function {{ $chart->id }}_create(data) {
        {{ $chart->id }}_rendered = true;
        document.getElementById("{{ $chart->id }}_loader").style.display = 'none';
        window.{{ $chart->id }} = echarts.init(document.getElementById("{{ $chart->id }}"),'{{ $chart->theme }}');
		window.{{ $chart->id }}.setOption({
            series: data,
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
                {{ $chart->id }}.setOption({series: data});
            });
    };
    @endif
    @include('charts::init')
</script>
