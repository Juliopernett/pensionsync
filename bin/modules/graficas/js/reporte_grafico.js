$(document).ready(function() {

    /*variables necesarias*/
    arrayEstados=[]
    arrayFinal=[]
    /*variables necesarias*/
    traerDatos(22)
  data2=[
                {
                    "name": "Chrome",
                    "y": 62.74,
                    //"drilldown": "Chrome"
                },
                {
                    "name": "Firefox",
                    "y": 10.57,
                    //"drilldown": "Firefox"
                }
            ]
    
//   	SELECT COUNT(cr.ids) AS d
// FROM
// (SELECT
//     DISTINCT id_users AS ids
// FROM
//     respuesta) AS cr

	

 function traerDatos()
{  
    $.ajax({
        url: 'clases/control_listar.php',
        type: 'POST',
        dataType: "json",
        data:{opcion:'datos',id_encuesta:22},
        success: function (datos)
        { 
          if(datos!=null)
          {
            for (var i = 0; i < datos.length; i++) {
                data=[]
             $(".graficas").append('<div id="container'+datos[i].id_preguna+'" style="min-width: 310px; height: 400px; margin: 0 auto"></div>')
             for (var j = 0; j < datos[i].opciones.length; j++) {
                  data.push({"name":datos[i]['opciones'][j].descripcion,"y":datos[i]['opciones'][j].porcentaje})
                          }
             crear_grafica(datos[i].id_preguna,datos[i].descripcion,data)
             console.log(data)
            }
          }

        }
          });
} 

function crear_grafica(id_pregunta,pregunta,data) {
// Create the chart
var identificador=''
Highcharts.chart('container'+id_pregunta, {
    chart: {
        type: 'column'
    },
    title: {
        text: pregunta
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        type: 'respuestas'
    },
    yAxis: {
        title: {
            text: 'porcentaje de usuarios'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
    },

    "series": [
        {
            "name": "Respuestas",
            "colorByPoint": true,
            "data": data
        }
    ]
});
}


function estados(id, solicitud)
{   
    
    $.ajax({    url: "clases/control_listar.php",
              type: "POST",
              dataType: "json",
              data: {opcion:"estados"},
        success: function (datos)
        { 
          if(datos!=null)
          {
            arrayEstados=datos
          }
//$("#estados").append('<option value="'+data[i].id_estado+'">'+data[i].descripcion+'</option>')
        }
          })       

     
}

function crear_grafica_solicitud(id, solicitud)
{   
    
    $.ajax({    url: "clases/control_listar.php",
              type: "POST",
              dataType: "json",
              data: {opcion:"estados"},
        success: function (datos)
        { 
          if(datos!=null)
          {
            for (var i = 0; i < datos.length; i++) {
                data=[]
             $(".graficas").append('<div id="container'+datos[i].id_preguna+'" style="min-width: 310px; height: 400px; margin: 0 auto"></div>')
             for (var j = 0; j < datos[i].opciones.length; j++) {
                  data.push({"name":datos[i]['opciones'][j].descripcion,"y":datos[i]['opciones'][j].porcentaje})
                          }
             crear_grafica(datos[i].id_preguna,datos[i].descripcion,data)
             console.log(data)
            }
          }
//$("#estados").append('<option value="'+data[i].id_estado+'">'+data[i].descripcion+'</option>')
        }
          })       

     
}

$("#datos_solicitudes" ).on( "click", function() {

    $.ajax({    url: "clases/control_listar.php",
              type: "POST",
              dataType: "json",
              data: {opcion:"datos_solicitudes",fecha_ini:$("#fecha_inicial").val(),fecha_fin:$("#fecha_final").val()},
        success: function (datos)
        { 
          if(datos!=null)
          {

            //arrayEstados=datos
          }
//$("#estados").append('<option value="'+data[i].id_estado+'">'+data[i].descripcion+'</option>')
        }
          }) 

    })

})