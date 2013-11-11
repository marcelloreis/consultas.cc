<script type='text/javascript'>
      google.load('visualization', '1', {packages:['gauge']});
      google.setOnLoadCallback(drawCharts);

      function drawCharts(){
        chartMinuts();
        chartHours();
        chartDays();
        chartTiming();
      }

      function chartMinuts() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value']
<?php foreach($imports['counters'] as $k => $v):?>
          ,['<?php echo $k?>', <?php echo $v['process_per_min']?>]
<?php endforeach?>
        ]);

        var options = {
          width: '100%', height: '100%',
          redFrom: 1700, redTo: 2000,
          yellowFrom:1400, yellowTo: 1700,
          minorTicks: 5,
          max: 2000
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_minuts'));
        chart.draw(data, options);
      }

      function chartHours() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value']
<?php foreach($imports['counters'] as $k => $v):?>
          ,['<?php echo $k?>', <?php echo $v['process_per_hour']?>]
<?php endforeach?>
        ]);

        var options = {
          width: '100%', height: '100%',
          redFrom: (1700*60), redTo: (2000*60),
          yellowFrom:(1400*60), yellowTo: (1700*60),
          minorTicks: 5,
          max: (2000*60)
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_hour'));
        chart.draw(data, options);
      }

      function chartDays() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value']
<?php foreach($imports['counters'] as $k => $v):?>
          ,['<?php echo $k?>', <?php echo $v['process_per_day']?>]
<?php endforeach?>
        ]);

        var options = {
          width: '100%', height: '100%',
          redFrom: (1700*1440), redTo: (2000*1440),
          yellowFrom:(1400*1440), yellowTo: (1700*1440),
          minorTicks: 5,
          max: (2000*1440)
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_day'));
        chart.draw(data, options);
      }



      google.load("visualization", "1", {packages:["corechart"]});
      function chartTiming() {
        var data = google.visualization.arrayToDataTable([
          ['Processo', 'Tempo']
<?php foreach($imports['timing'] as $k => $v):?>
  <?php $desc = $v['Timing']['description']?>
          ,['<?php echo $desc?>', <?php echo number_format(($v['Timing']['time']*1000), 1, '', '.')?>]
<?php endforeach?>
        ]);

        var options = {
          title: 'Tempo dos processos',
          pieHole: 0.4
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_timing'));
        chart.draw(data, options);
      }

</script>