$(document).ready(function(){
    /**
    * Graficos de importacao
    */
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
    if($('.msk-money').size()){
        $('.msk-money').maskMoney({decimal:",", thousands:"."});
    }
    $.mask.definitions['~']='[9]';
    $('.msk-phone').mask('9999-9999');
    $('.msk-phone-9').mask('~9999-9999');
    $('.msk-phone-ddd').mask('(99) 99999?-9999');
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
    * Cidades por demanda
    */
    if($('select[name*=state_id]').size()){
        $('select[name*=state_id]')
        .chosen()
        .change(function(){
            var form = $(this).parents('form:eq(0)');
            var state_id = $(this).val();
            var select_city = form.find('select[name*=city_id]');
            var box_select_city = select_city.parents('.controls:eq(0)');

            select_city.remove();
            $.get('/cities/options/' + state_id, function(data){
                box_select_city.html(data);
                $('select', box_select_city).chosen();
            });
            
        });
    }

	/**
	* Carrega os checkbox mestres
	*/
	$('#check-all').checkAll('.index-table input:checkbox');

    /**
    * Carrega as informacoes extras da entidade encontrada
    */
    $('.load-assoc').click(function(){
        var class_target = $(this).attr('class-box');

        $('html, body').animate({
            scrollTop: $('#entity-extras').offset().top
        }, 600);                            
        
        if(!$('#' + class_target).find('a').size()){
            var li = $(this).parents('li:eq(0)');
            var data = $(':hidden', li).serialize();
            var url = $(this).attr('rel');

            $.ajax({
                url: url,
                data: data,
                beforeSend:function(){
                    $('#assoc-loading').removeClass('hide');
                    $('#assoc-loading-msg').html(' Buscando informações...');
                },
                success: function(data){
                    $('#' + class_target).html(data);
                    $('#assoc-loading').addClass('hide');
                    $('#assoc-loading-msg').html('');
                }
            });        
        }
    });
});

var Nz = {

}