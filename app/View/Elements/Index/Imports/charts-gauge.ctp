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
          ,['<?php echo $k?>', <?php echo $this->AppUtils->num2qt($v['process_per_min'])?>]
<?php endforeach?>
        ]);

        var options = {
          width: '100%', height: '100%',
          redFrom: 90, redTo: 100,
          yellowFrom:75, yellowTo: 90,
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
          ,['<?php echo $k?>', <?php echo $this->AppUtils->num2qt($v['process_per_hour'])?>]
<?php endforeach?>
        ]);

        var options = {
          width: '100%', height: '100%',
          redFrom: 90, redTo: 100,
          yellowFrom:75, yellowTo: 90,
          minorTicks: 5,
          max: (2000*24)
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_hour'));
        chart.draw(data, options);
      }
</script>