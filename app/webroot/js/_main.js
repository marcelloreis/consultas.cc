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
    $('.msk-2Digits').mask('99',{completed:function(){
    	$(this).parents('form:eq(0)').find('input[name!=ddd]').focus();
    }});
    $('.msk-4Digits').mask('9999');
    $('.msk-int').keyup(function(){
            $(this).val($(this).val().replace(/[^0-9]/gi, ''));
    });
    $('.msk-alpha').keyup(function(){
            $(this).val($(this).val().replace(/[0-9]/gi, ''));
    })

	$('.map-tooltip').tooltip('hide');
	
	/**
	* Acoes em massa
	*/
	$('.bulkAction').click(function(){
		var url = $(this).attr('data-url');
		var data_id = '#' + $(this).attr('data-id');
		var data = $(':checkbox:checked', data_id).not('#check-all').serialize();

		if(url != '' && data != ''){
			location.href = url + '/?' + data;
		}
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
	$('#check-all').checkAll('.index-table input:checkbox');
});

var Nz = {

}