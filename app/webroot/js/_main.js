$(document).ready(function(){
	if($("#flot-audience").length > 0){
                $("#flot-audience").bind("plothover", function (event, pos, item) {
                        if (item) {
                                if (previousPoint != item.dataIndex) {
                                        previousPoint = item.dataIndex;

                                        $("#tooltip").remove();
                                        var y = item.datapoint[1].toFixed();

                                        showTooltip(item.pageX, item.pageY,
                                         item.series.label + " = " + y);
                                }
                        }
                        else {
                                $("#tooltip").remove();
                                previousPoint = null;
                        }
                });
    }

    /**
	* Configuracao das mascaras dos formularios
	*/
    // $('.msk-money').maskMoney({decimal:",", thousands:"."});
    $.mask.definitions['~']='[9]';
    $('.msk-phone').mask('9999-9999');
    $('.msk-phone-9').mask('~9999-9999');
    $('.msk-phone-ddd').mask('(99) 9999-9999');
    $('.msk-zipcode').mask('99999-999');
    $('.msk-cpf').mask('999.999.999-99');
    $('.msk-cnpj').mask('99.999.999/9999-99');
    $('.msk-card').mask('9999 9999 9999 9999');
    $('.msk-hour').mask('99:99:99');
    $('.msk-2Digits').mask('99');
    $('.msk-4Digits').mask('9999');
    $('.msk-int').keyup(function(){
            $(this).val($(this).val().replace(/[^0-9]/gi, ''));
    })
    $('.msk-alpha').keyup(function(){
            $(this).val($(this).val().replace(/[0-9]/gi, ''));
    })

	$('.map-tooltip').tooltip('hide');
	
	/**
	* Anula todos os links que tenham # em seu href
	*/
	$('a[href=#]').click(function(){
		return false;
	});

	/**
	* Ativa todos os popovers que forem manuais
	*/
	$('a[rel=popover][data-trigger=manual]').popover('show');

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