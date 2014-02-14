<?php
header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=testes.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table style="width:300px">
<tr>
  <td>Jill</td>
  <td>Smith</td>
  <td>50</td>
</tr>
<tr>
  <td>Eve</td>
  <td>Jackson</td>
  <td>94</td>
</tr>
</table> 