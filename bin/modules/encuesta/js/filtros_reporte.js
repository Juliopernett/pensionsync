$(function ()
{

estados();

$( "#b_reporte" ).on( "click", function() {
    $("#reporte").html('<iframe src="reporte.php?id_encuesta='+$("#encuestas").val()+'" style="width:100%; height:835px;" frameborder="0"></iframe>')
});

function estados(id, solicitud)
{   
    
    $.ajax({    url: "clases/control_listar.php",
              type: "POST",
              dataType: "json",
              data: {opcion:"listas_todas"},
          })
      .done(function(data) {
        $("#encuestas").empty()
        for (var i = 0; i < data.length; i++) {
            $("#encuestas").append('<option value="'+data[i].id_encuesta+'">'+data[i].descripcion+'</option>')
            
        }
     
   
            
    });    

     
}

});






