$(document).ready(function () {
    dataGrafico();
    $('.btn-unasist').click(function () {
        dataGrafico();
    });
    $('.btn-asist').click(function () {
        dataGrafico();
    })
});

function dataGrafico() {
    //obtener la info por ajax
    $.ajax({
        url: URL + '/check_assist/' + $('#id-curso').attr('data-id'),
        type: 'GET',
        dataType: "json",
        success: function (response) {
            //crear el json de alumnos en clases
            var assist = {};
            var as = [];
            assist.as = as;
            //console.log(assist.as);
            //recorro el json y de response y creo un json nuevo a partir del llamado class
            $.each(response, function (i, item) {
                assist.as.push(response[i].class);
            });
            //Creo el json con el total de asistencia por clases(sumo las asistencias de cada clase)
            result = {};
            for (var i = 0; i < as.length; ++i) {
                if (!result[as[i]])
                    result[as[i]] = 0;
                ++result[as[i]];
            }

            console.log(result);
            //Extraigo los valores de las claves del json y los convierto en array, porque así lo requiere el plugin chartjs

            var data_clases = [];

            for (var x in result) {
                data_clases.push(result[x]);
            }
            console.log(data_clases);
            //json de clases        
            var clase = response[0].course.num_class;
            //console.log(clase);
            //creo el arreglo con el total de clases del curso porque así lo requiere el plugin chartjs
            var clases = [];
            for (i = 1; i < clase + 1; i++) {
                //console.log('clase'+' '+ i);
                clases.push('clase' + ' ' + i);
            }
            console.log(clases);

//        Grafico
            var ctx = document.getElementById("mybarChart");
            var mybarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: clases,
                    datasets: [{
                            label: '# Asistentes',
                            backgroundColor: "#26B99A",
                            data: data_clases
                        }]
                },

                options: {
                    scales: {
                        yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                    }
                }
            });

        }
    });
}

