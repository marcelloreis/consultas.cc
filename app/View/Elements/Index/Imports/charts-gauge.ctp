<script type='text/javascript'>
      google.load('visualization', '1', {packages:['gauge']});
      google.setOnLoadCallback(drawCharts);

      function drawCharts(){
        chartMinuts();
        chartHours();
        chartDays();
        chartTiming();
        chartNext();
      }

      function chartMinuts() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value']
<?php foreach($imports['statistics'] as $k => $v):?>
          ,['<?php echo $k?>', <?php echo $v['process_per_min']?>]
<?php endforeach?>
        ]);

        var options = {
          width: '100%', height: '100%',
          redFrom: 0, redTo: 800,
          yellowFrom: 800, yellowTo: 1100,
          greenFrom: 8000, greenTo: 11000,
          minorTicks: 5,
          max: 11000
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_minuts'));
        chart.draw(data, options);
      }

      function chartHours() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value']
<?php foreach($imports['statistics'] as $k => $v):?>
          ,['<?php echo $k?>', <?php echo $v['process_per_hour']?>]
<?php endforeach?>
        ]);

        var options = {
          width: '100%', height: '100%',
          redFrom: 0, redTo: (800*60),
          yellowFrom: (800*60), yellowTo: (1100*60),
          minorTicks: 5,
          max: (11000*60)
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_hour'));
        chart.draw(data, options);
      }

      function chartDays() {
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value']
<?php foreach($imports['statistics'] as $k => $v):?>
          ,['<?php echo $k?>', <?php echo $v['process_per_day']?>]
<?php endforeach?>
        ]);

        var options = {
          width: '100%', height: '100%',
          redFrom: 0, redTo: (800*1440),
          yellowFrom: (800*1440), yellowTo: (1100*1440),
          minorTicks: 5,
          max: (11000*1440)
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_day'));
        chart.draw(data, options);
      }

      function chartNext() {
        <?php 
        $timing = array_flip($imports['timing']);
        ?>
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Commit', <?php echo number_format(($imports['timing_commit']['Timing']['time'] * 1000), 2)?>]
        ]);

        var options = {
          width: '100%', height: '100%',
          redFrom: 5, redTo: 7,
          yellowFrom: 4, yellowTo: 5,
          minorTicks: 1,
          max: 7
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_next'));
        chart.draw(data, options);
      }


      google.load("visualization", "1", {packages:["corechart"]});
      function chartTiming() {
        var data = google.visualization.arrayToDataTable([
          ['Processo', 'Tempo']
<?php foreach($imports['timing'] as $k => $v):?>
          ,['<?php echo $v?>', <?php echo substr(($k * 1000), 0, strrpos(($k * 1000), '.')+3)?>]
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