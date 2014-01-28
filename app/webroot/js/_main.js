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
    });
    $('.msk-max')
    .keyup(function(e) {
        Nz.msk_max(e, this);
    })
    .focus(function(e){
        Nz.msk_max(e, this);
    })
    .blur(function(e){
        Nz.msk_max(e, this);
    })
    ;    

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
    * Templates Salvos
    */
    if($('select[name*=sms_template_id]').size()){
        $('select[name*=sms_template_id]')
        .chosen()
        .change(function(){
            $('textarea[name*=template]').val($(this).val());
        });
    }

    /**
    * Grupos de sms salvos
    */
    if($('select[name*=sms_group_id]').size()){
        $('select[name*=sms_group_id]')
        .chosen()
        .change(function(){
            $('textarea[name*=contact_list]').val($(this).val());
        });
    }

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
    * Filtros do painel do index
    */
    if($('select.filter-index').size()){
        $('select.filter-index')
        .change(function(){
            var form = $(this).parents('form:eq(0)');
            form.submit();
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
                    
                    $('html, body').animate({
                        scrollTop: $('#entity-extras').offset().top
                    }, 600);                            
                }
            });        
        }

        $('html, body').animate({
            scrollTop: $('#entity-extras').offset().top
        }, 600);                            
    });

    /**
    * Selecao da cor padrao do usuario
    */
    $('.chosen-color > li > span').click(function(){
        $('.chosen-color > li > span').removeClass('img-circle');
        $('#UserTheme').val('theme-' + $(this).attr('class'));
        $(this).addClass('img-circle');
    });
});

var Nz = {
    msk_max:function(e, elem){
        var tval = $(elem).val();
        var tlength = tval.length;
        var set = $(elem).attr('maxlenth');
        var remain = parseInt(set - tlength);

        if(typeof($(elem).attr('class-label')) != 'undefined'){
            var label = $('.' + $(elem).attr('class-label'));
            label.text(remain);
        }

        if (remain <= 0 && e.which !== 0) {
            $(elem).val((tval).substring(0, set))
        }
    }
}