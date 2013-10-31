$(document).ready(function(){

	$('.select-state').change(function(){
		Nz.loadCities(this);
	});

	/**
	* Carrega os checkbox mestres
	*/
	$('#check-all').checkAll('#index-table input:checkbox');
});

var Nz = {
	loadCities:function(el){
		var divCity = $('select[name*=city]').parents('div:eq(0)');
		var state_id = $(el).val();
		var model = $(el).attr('id').replace(/StateId/gi, '');

		$.get('/cities/options/' + state_id + '/model:' + model, function(data){
		        if(data.length > 10){
					divCity.html(data);
					$('select', divCity).each(function(){
						var $el = $(this);
						var search = ($el.attr("data-nosearch") === "true") ? true : false,
						opt = {};
						if(search) opt.disable_search_threshold = 9999999;
						$el.chosen(opt);
					});		        	
		        }
		});		
	}
}