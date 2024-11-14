function verCargas(id_encuesta)
{  
    $.ajax({
        url: 'clases/control_listar.php',
        data:{opcion:'preguntasxencuesta',id_encuesta:id_encuesta},
        type:'POST',
        dataType:'html',
        success: function (data)
        {
            $('#ver_cargas').html(data);
           
            $('#myTable').DataTable({
                sPaginationType: "bootstrap", 
                aLengthMenu: [6],
                //ordering: false,
                language: {sProcessing: "Procesando...",
                    sLengthMenu: "Mostrar _MENU_ registros",
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "Ningún dato disponible en esta tabla",
                    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sInfoPostFix: "",
                    sSearch: "Buscar:",
                    sUrl: "",
                    sInfoThousands: ",",
                    sLoadingRecords: "Cargando...",
                    oPaginate: {
                        sFirst: "Primero",
                        sLast: "Último",
                        sNext: "Siguiente",
                        sPrevious: "Anterior"
                    },
                    oAria: {
                        sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                        sSortDescending: ": Activar para ordenar la columna de manera descendente"
                    }
                }});
            $('.dataTables_filter label').css('display', 'block !important');
            $('.dataTables_filter label input[type="search"]').addClass('form form-control');
            $('input[name="myTable_length"]').addClass('form form-control');
           // pag_data_table();
        },
         complete: function () {
                //loadingstop();
               
            }
    });
}

$(document).ready(function ()
{
    traerEncuestas()
    traerCalificaciones()

    $("#encuestas").change(function(){
        verCargas($(this).val())
    })

$('#form_calificacion').submit(function (e)
    {
        e.preventDefault();        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: {descripcion:$("#descripcion_calificacion").val(),
                opcion:'1c'},
            dataType: 'json',
            success: function (data) {
                if (!data.guardado)
                {
                    bootbox.alert('Se presento un error al regisrar el dato');
                }
                    bootbox.alert("Se Guardo con exito", function(){ 
                    $("#descripcion_calificacion").val("")
                    traerCalificaciones()
                    
                                
                                })
                
            }
        });
    });
 

function traerEncuestas()
{  
    $.ajax({
        url: 'clases/control_listar.php',
        type: 'POST',
        dataType: "json",
        data:{opcion:'listas_todas'},
        success: function (data)
        {   
           if(data!=null){          
            var primera=data[0].id_encuesta
            for (var i = 0; i < data.length; i++) {

               $("#encuestas").append('<option value="'+data[i].id_encuesta+'">'+data[i].descripcion+'</option>')
            } 
            $("#encuestas").val(primera).trigger("change")

                         }           
            
        }
    });
}

function traerCalificaciones()
{  
    $.ajax({
        url: 'clases/control_listar.php',
        type: 'POST',
        dataType: "json",
        data:{opcion:'listar_calificaciones'},
        success: function (data)
        {               
            $("#calificaciones").empty()
            for (var i = 0; i < data.length; i++) {

               $("#calificaciones").append('<option value="'+data[i].id_calificacion+'">'+data[i].descripcion+'</option>')
            } 
            
            
        }
    });
}


 });

function asignar_calificacion(id_pregunta)
{   
    
    $.ajax({    url: "clases/control_crud.php",
              type: "POST",
              dataType: "json",
              data: {opcion:"2p",
              id_pregunta:id_pregunta,
              id_calificacion:$("#calificaciones").val()},
              success: function (data)
        {               
            if (!data.guardado)
                {
                    bootbox.alert('Se presento un error al regisrar el dato');
                }
                    bootbox.alert("Se Guardo con exito", function(){ 
                                        
                                
                                })            
            
        }
          })
         

        
}

function editar(id_pregunta)
{   
    
    $.ajax({    url: "clases/control_crud.php",
              type: "POST",
              dataType: "json",
              data: {opcion:"3p",id:id},
          })
      .done(function(data) {
      //console.log(data) 
    $("#descripcion").val(data.descripcion_estado);
    $("#descripcion_sol").val(data.descripcion_solicitud);
    $("#id_sol").val(solicitud);
    $("#id").val(id); 
   
            
    });    

     
}









