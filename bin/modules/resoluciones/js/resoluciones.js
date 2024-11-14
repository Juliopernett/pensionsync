$(document).ready(function(){
	load(1);
	$('#foot').hide()
});

function load(page){
	var q= $("#q").val();
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'buscar_novedades.php?action=ajax&page='+page+'&q='+q,
		beforeSend: function(objeto){
		$('#loader').html('<img src="../../../img/ajax-loader.gif"> Cargando...');
	},
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
			$('#loader').html('');
			
		}
	})
}






