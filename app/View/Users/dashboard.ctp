<?php echo $this->assign('sidebar-class-hidden', 'nav-hidden')?>
<?php echo $this->element('Index/panel')?>

<table class="table table-hover table-nomargin">
	<thead>
		<tr>
			<th>Rendering engine</th>
			<th>Browser</th>
			<th class='hidden-350'>Platform(s)</th>
			<th class='hidden-1024'>Engine version</th>
			<th class='hidden-480'>CSS grade</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Trident</td>
			<td>
				Internet
				Explorer 4.0
			</td>
			<td class='hidden-350'>Win 95+</td>
			<td class='hidden-1024'>4</td>
			<td class='hidden-480'><?php echo $this->element('Index/action')?></td>
		</tr>
		<tr>
			<td>Presto</td>
			<td>Nokia N800</td>
			<td class='hidden-350'>N800</td>
			<td class='hidden-1024'>-</td>
			<td class='hidden-480'><?php echo $this->element('Index/action')?></td>
		</tr>
		<tr>
			<td>Misc</td>
			<td>NetFront 3.4</td>
			<td class='hidden-350'>Embedded devices</td>
			<td class='hidden-1024'>-</td>
			<td class='hidden-480'><?php echo $this->element('Index/action')?></td>
		</tr>
		<tr>
			<td>Misc</td>
			<td>Dillo 0.8</td>
			<td class='hidden-350'>Embedded devices</td>
			<td class='hidden-1024'>-</td>
			<td class='hidden-480'><?php echo $this->element('Index/action')?></td>
		</tr>
		<tr>
			<td>Misc</td>
			<td>Links</td>
			<td class='hidden-350'>Text only</td>
			<td class='hidden-1024'>-</td>
			<td class='hidden-480'><?php echo $this->element('Index/action')?></td>
		</tr>
		<tr>
			<td>Misc</td>
			<td>Lynx</td>
			<td class='hidden-350'>Text only</td>
			<td class='hidden-1024'>-</td>
			<td class='hidden-480'>X</td>
		</tr>
	</tbody>
</table>

<div class="table-pagination">
    <a href="#" class='disabled'>First</a>
    <a href="#" class='disabled'>Previous</a>
    <span>
        <a href="#" class='active'>1</a>
        <a href="#">2</a>
        <a href="#">3</a>
    </span>
    <a href="#">Next</a>
    <a href="#">Last</a>
</div>
							