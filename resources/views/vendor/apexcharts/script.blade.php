<script type="text/javascript"><!--
    var options = {!! json_encode($chart->getOptions()) !!};

    var chart_{{ $chart->getId() }} = new ApexCharts(document.querySelector("#{!! $chart->getId() !!}"), options);

    chart_{{ $chart->getId() }}.render();
//--></script>
