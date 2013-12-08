<?php 
$params = '';
if(count($this->params['pass'])){
	foreach ($this->params['pass'] as $k => $v) {
		$params .= "{$v} ";
	}
}
if(count($this->params['named'])){
	foreach ($this->params['named'] as $k => $v) {
		$params .= "{$v} ";
	}
}

$map = strtolower($modelClass);
$qt_found = count($$map);
?>

<div class="box-title">
	<h3>
		<i class="icon-table"></i>
		<strong><?php echo $qt_found?></strong> Resultados encontrado para <code><?php echo $params?></code>
	</h3>
</div>
