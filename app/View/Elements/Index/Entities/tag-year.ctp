<?php
$yearDiff = date('Y') - $year;
switch ($yearDiff) {
    case 0:
        $yearClass = 'btn-success';
        break;
    case 1:
    case 2:
        $yearClass = 'btn-warning';
        break;
    default:
        $yearClass = 'btn-red';
        break;
}

echo $this->Html->link($year, '#', array('class' => 'btn ' . $yearClass))
?>