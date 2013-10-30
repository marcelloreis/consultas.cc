$(document).ready(function(){

	$('.select-state').change(function(){

	});

	/**
	* Carrega os checkbox mestres
	*/
	$('#check-all').checkAll('#index-table input:checkbox');
});

var Nz = {
	loadOptions:function(){
		console.log($(this).val())
		$('select[name*=city]').removeClass('chzn-done');
		$('select[name*=city]').parents('div:eq(0)').find('div').remove();
		$('select[name*=city]').append('<option value="1000">TESTE</option>');
		$('select[name*=city]').each(function(){
			var $el = $(this);
			var search = ($el.attr("data-nosearch") === "true") ? true : false,
			opt = {};
			if(search) opt.disable_search_threshold = 9999999;
			$el.chosen(opt);
		});
	}
}