<?php 
/**
* Adiciona os CSSs e Scripts de acordo com as views invocadas
*/
$this->append('css-on-demand');
echo $this->Html->css(array('plugins/chosen/chosen'));
$this->end();

$this->append('scrips-on-demand');
echo $this->Html->script(array('plugins/chosen/chosen.jquery.min'));
$this->end();
?>