function verCargas2(id)
{  
    $.ajax({
        url: 'clases/control_listar.php',
         type: "POST",
         dataType: "html",
         data: {opcion:"2", estado:id},
        success: function (data)
        {
            $('#ver_cargas2').html(data);
           
            $('#myTable1').DataTable({
                sPaginationType: "bootstrap", 
                //aLengthMenu: [6],
                order: [[ 3, "desc" ]],
                language: {sProcessing: "Procesando...",
                    sLengthMenu: "Mostrar _MENU_ registros",
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "Ningún dato disponible en esta tabla",
                    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sInfoPostFix: "",
                    sSearch: "Buscar por cualquier parametro:",
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

$(document).ready(function($){
    var id = $(this).val();                      
        console.log(id)                
            $('#tabla').show();
            verCargas2(id);
    });

function listar_seguimiento(id)
{   
    verCargas(id);
        
}
    
function verCargas(id)
{  
    //alert( 'El servidor devolvio "' + id + '"' );
    $("#reporte").html('<iframe src="reporte.php?id_estado='+id+'" style="width:100%; height:835px;" frameborder="0"></iframe>')

}


