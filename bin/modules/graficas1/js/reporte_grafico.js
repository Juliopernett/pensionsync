$(document).ready(function() {
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
  
  // for (var i = 1; i < 3; i++) {
  // 		crear_grafica(i,data2)
  // 	}	
//   	SELECT COUNT(cr.ids) AS d
// FROM
// (SELECT
//     DISTINCT id_users AS ids
// FROM
//     respuesta) AS cr

	// array(4) {
 //    ["id_preguna"]=>
 //    string(1) "2"
 //    ["tipo"]=>
 //    string(25) "con respuesta predefinida"
 //    ["descripcion"]=>
 //    string(47) "La atencion recibida por los funcionarios fue ?"
 //    ["opciones"]=>
 //    array(2) {
 //      [0]=>
 //      array(5) {
 //        ["porcentaje"]=>
 //        float(50)
 //        ["id_opcion"]=>
 //        string(1) "1"
 //        ["descripcion"]=>
 //        string(5) "Buena"
 //        ["sumatoria"]=>
 //        int(2)
 //        ["total_respuestas"]=>
 //        int(0)
 //      }
 //      [1]=>
 //      array(5) {
 //        ["porcentaje"]=>
 //        float(50)
 //        ["id_opcion"]=>
 //        string(1) "2"
 //        ["descripcion"]=>
 //        string(4) "Mala"
 //        ["sumatoria"]=>
 //        int(2)
 //        ["total_respuestas"]=>
 //        int(0)
 //      }
 //    }
 //  }

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
})