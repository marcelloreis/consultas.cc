$(document).ready(function(){

	/**
	* Sistema de selecao de estados/cidades
	*/
	$('.select-state').change(function(){
		Nz.loadCities(this);
	});

	/**
	* Controle de busca por endereco
	*/
	$('.zipcode-field').keyup(function(){
		$zipcode = $(this);
		if($zipcode.val() != ''){
			$('.address-fields').css('opacity', '0.4');
			$('.address-fields').fadeOut('middle');
			$(':input', '.address-fields').attr('disabled', 'disabled');
		}else{
			$('.address-fields').css('opacity', '1');
			$('.address-fields').fadeIn('middle');
			$(':input', '.address-fields').removeAttr('disabled');
		}
		console.log($zipcode.val());
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
	},
	lockAddress:function(){

	}
}