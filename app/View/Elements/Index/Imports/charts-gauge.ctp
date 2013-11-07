<script type='text/javascript'>
      google.load('visualization', '1', {packages:['gauge']});
      google.setOnLoadCallback(drawCharts);

      function drawCharts(){
      	chartMinuts();
      	chartHours();
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
</script>