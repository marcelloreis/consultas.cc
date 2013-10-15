<?php 
$title = isset($title) && !empty($title)?__(ucfirst($title)):__(ucfirst($this->params['controller']));
?>
<header class="panel-heading"><?php echo $title?></header>