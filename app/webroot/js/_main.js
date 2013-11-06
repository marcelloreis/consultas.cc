$(document).ready(function(){
	if($("#flot-audience").length > 0){
		var data = [[1262304000000, 1300], [1264982400000, 2200], [1267401600000, 3600], [1270080000000, 5200], [1272672000000, 4500], [1275350400000, 3900], [1277942400000, 3600], [1280620800000, 4600], [1283299200000, 5300], [1285891200000, 7100], [1288569600000, 7800], [1291241700000, 8195]];

		$.plot($("#flot-audience"), [{ 
			label: "Visits", 
			data: data,
			color: "#3a8ce5"
		}], {
			xaxis: {
				min: (new Date(2009, 12, 1)).getTime(),
				max: (new Date(2010, 11, 2)).getTime(),
				mode: "time",
				tickSize: [1, "month"],
				monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			},
			series: {
				lines: {
					show: true, 
					fill: true
				},
				points: {
					show: true,
				}
			},
			grid: { hoverable: true, clickable: true },
			legend: {
				show: false
			}
		});

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