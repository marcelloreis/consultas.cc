<?php 
$search_by = isset($this->params['named']['search_by'])?$this->params['named']['search_by']:'doc';
echo $this->element("Index/Entities/panel-{$search_by}", array('params' => $this->params['named']));
?>
