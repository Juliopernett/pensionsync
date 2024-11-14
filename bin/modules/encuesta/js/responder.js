$(document).ready(function() {

  contestada()  

  $('#responder').on('click', function () {   
      
      var rtasPre=[]
      var rtasLib=[]

      $('.opciones').each(function () {

    if ( $(this).is( ":checked" ) ){
       if($(this).attr("tipo")=="rta-pre")
       {
        rtasPre.push($(this).val())
       }
       
    }

    if($(this).attr("tipo")=="rta-libre")
       {
        rtasLib.push($(this).val())
       }



      })

      console.log(rtasPre,rtasLib)

        enviar_respuestas(rtasPre,rtasLib)
})

function traerPreguntasDeEncuesta(id_encuesta)
{  
    $.ajax({
        url: 'clases/control_listar.php',
        type: 'POST',
        dataType: "json",
        data:{opcion:'listar_preguntas',
        id_encuesta:id_encuesta},
        success: function (data)
        {   
        
            $("#datos").empty()
            for (var i = 0; i < data.length; i++) {
            if(data[i].opciones!=null)
            {

              if( data[i].opciones.length==2){
                var html='<div class="row">'
                html+='<div class="col-md-6">'
                html+='<p>'+data[i].descripcion+'</p>'
                html+='</div>'
                html+='<div class="col-md-6">'
                                

                 for (var j = 0; j < data[i].opciones.length; j++) {
            html+='<div class="col-md-2">'
            html+='<input tipo="rta-pre" id="'+data[i].opciones[j].id_opcion+'" type="radio" name="r'+data[i].id_preguna+'" value="'+data[i].opciones[j].id_opcion+'" class="opciones" required>'+data[i].opciones[j].descripcion
            html+='</div>'
                                                                    }
                                                        
                
                
                html+='</div>'      
                html+='<div><br>'

              }
              else
              {
                var html='<div class="row">'
                    html+='<div class="col-md-12">'
                    html+='<p>'+data[i].descripcion+'</p>'
                    html+='</div>'
                    html+='</div>'
                    html+='<div class="row">'
                    html+='<div class="col-md-12">'
                    if(data[i].tipo=="con respuesta predefinida")
                {
                    for (var j = 0; j < data[i].opciones.length; j++) {
            html+='<div class="col-md-3">'
            html+='<input tipo="rta-pre" id="'+data[i].opciones[j].id_opcion+'" type="radio" name="r'+data[i].id_preguna+'" value="'+data[i].opciones[j].id_opcion+'" class="opciones" required>'+data[i].opciones[j].descripcion
            html+='</div>'
                                                                    }
               }               
                    html+='</div>'
                    html+='</div><br>'

                

              }
                
             

           }
           else
                {
            var html='<div class="row">'
                    html+='<div class="col-md-12">'
                    html+='<p>'+data[i].descripcion+'</p>'
                    html+='</div>'
                    html+='</div>'
                    html+='<div class="row">'
                    html+='<div class="col-md-12">'
            html+='<textarea tipo="rta-libre" class="col-md-12 opciones" id="r'+data[i].id_preguna+'" placeholder="descripion" cols="3" required> </textarea>'
            html+='</div>'
                    html+='</div><br>'
                }
          $('#datos').append(html)
               
            } 
            
            
        }
    });
}

function contestada(id_encuesta)
{  
    $.ajax({
        url: 'clases/control_listar.php',
        type: 'POST',
        dataType: "json",
        data:{opcion:'contestada',
        id_encuesta:id_encuesta},
        success: function (data)
        { 
          if(data==null)
          {
            traerPreguntasDeEncuesta(22)
          }
          else
          {
            alert("Ya se contesto la encuesta")
          }

         }
        });
}  

function enviar_respuestas(rtasPre,rtasLib)
{
   var cantidadPre=rtasPre.length
   var cantidadLib= rtasLib.length

   if(cantidadPre==9 && cantidadLib==1)
   {

    $.ajax({
            url: 'clases/control_crud.php',
            type: 'POST',
            data: {rtasPre:rtasPre,
                   rtasLib:rtasLib,
                   opcion:'1r'
                  },
            dataType: 'json',
            success: function (data) {
                if (!data.guardado)
                {
                    bootbox.alert('Se presento un error al regisrar el dato');
                }
                    bootbox.alert("Se Guardo con exito", function(){ 
                      location.reload();
                                 
                                
                                })
                
            },
            complete: function () {
                
                //verCargas();
            }
        });
  }
  else
  {
    alert("Debes contestar todas las preguntas")
  }
}



})