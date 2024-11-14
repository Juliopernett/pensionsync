function verCargas(id)
{  
    $.ajax({
        url: 'clases/control_listar.php',
         type: "POST",
         dataType: "html",
         data: {opcion:"3", id:id},
        success: function (data)
        {
            $('#ver_cargas').html(data);
           
            $('#myTable').DataTable({
                sPaginationType: "bootstrap", 
                //aLengthMenu: [6],
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

function verCargas2()
{  
    $.ajax({
        url: 'clases/control_listar.php',
         type: "POST",
         dataType: "html",
         data: {opcion:"2"},
        success: function (data)
        {
            $('#ver_cargas2').html(data);
           
            $('#myTable1').DataTable({
                sPaginationType: "bootstrap", 
                //aLengthMenu: [6],
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

function editar(id, solicitud)
{   
    
    $.ajax({    url: "clases/control_crud.php",
              type: "POST",
              dataType: "json",
              data: {opcion:"2",id:id},
          })
      .done(function(data) {
      //console.log(data) 
    $("#descripcion").val(data.descripcion_estado);
    $("#descripcion_sol").val(data.descripcion_solicitud);
    $("#id_sol").val(solicitud);
    $("#id").val(id); 
    //console.log(data.estado_solicitud)
           if(data.estado_solicitud == 'Inactivo'){
            parent.$('#botones').hide();
           } 
    });         
}


function listar_seguimiento(id)
{   
    verCargas(id);
        
}

verCargas2()


/*$(function ()
{
$('#form_solicitud').submit(function (e)
    {
        e.preventDefault();        
        var data = new FormData($("#form_solicitud")[0]);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                if (!data.guardado)
                {
                    bootbox.alert('Se presento un error al regisrar el dato');
                }
                    bootbox.alert("Se Guardo con exito", function(){ 
                                 $('#descripcion_solicitud').val("")                     
                                 $('#id_tiposolicitud').val("-1")
                                
                                })
                
            },
            complete: function () {
                verCargas();
            }
        });
    });
 });*/





