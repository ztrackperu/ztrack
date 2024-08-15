


function graficoTipoDispositivo(){
    var ctx = document.getElementById('graficoTipoDispositivo').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Reefer', 'Madurador', 'Tunel'],
            datasets: [{
                label: 'Tipo de dispositivo',
                data: [12, 19, 3],
                backgroundColor: [
                    '#c5e69b',
                    '#eedd0e',
                    '#4a7c8a'
                ],
                borderWidth: 1
            }],
            
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                   display:false
                },
                y: {
                    display:false
                }
            }
        }
    });
}

function graficoEstadoDispositivo(){
    var ctx = document.getElementById('graficoEstadoDispositivo').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['ON', 'OFF'],
            datasets: [{
                //label: 'Estado de dispositivo',
                data: [20, 10],
                backgroundColor: [
                    'rgba(154,196,21)',
                    'rgba(196,21,21)'
                ],
                borderWidth: 1
            }],
            
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                   display:false
                },
                y: {
                    display:false
                }
            },
            plugins:{
                title:{
                    display:true,
                    text: 'Estado de dispositivos',
                },
                font:{
                    size: 25
                }
            }
        }
    });
}

function graficoConsumoEnergetico(){
    //type bar
    var ctx = document.getElementById('graficoConsumoEnergetico').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        plugins: [ChartDataLabels],
        data: {
            labels: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],
            datasets: [{
                label: 'Consumo energetico',
                data: [12, 19, 3, 5, 2, 3, 10],
                backgroundColor: [
                    '#263dbc',
                    '#96c30b',
                    '#a31270',
                    '#b4b4b4',
                    '#ff0000',
                    '#ff6600',
                    '#ffcc00'
                ],
                borderWidth: 1
            }],
            
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        },
        plugins:{
            dataLabels:{
                color: '#fff',
                display: function(context){
                    return context.dataset.data[context.dataIndex] > 5;
                }
            }
        },
        plugins: [ChartDataLabels]
    });

}

function PanelControlInformation(){
    const url = base_url + "AdminPage/PanelControlInformation";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            /* 
             $array = array(
            "online" => array(
                "count" => 10,
                "details" => array(
                    array("id" => "ZGRU1090804", "set_point" => 22.2, "return" => 20, "supply" => 23, "humidity" => 80)
                )
            ),
            "wait" => 35,
            "offline" => 55,
            "alarmas" => 63,
            "usuarios" => 20,
            "dias_restantes" => 5,
            "reefer" => 12,
            "ripener" => 3,
            "genset" => 1,
        );
            */
            console.log(res);
            $('#PanelControlInformation').html(res.html);
        }
    }
}

$(document).ready(function () {
    graficoTipoDispositivo();
    graficoEstadoDispositivo();
    graficoConsumoEnergetico();
    PanelControlInformation();
});


/*function alertas(msg, icono) {
    Swal.fire({
        position: 'top-end',
        icon: icono,
        title: msg,
        showConfirmButton: false,
        timer: 3000
    })
}
//noti
noti = document.getElementById("noti");

document.addEventListener("DOMContentLoaded", function(){
    $(document).ready(function () {
        //registrarRespuesta();
        const url = base_url + "AdminPage/ListaNotificaciones";
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    //const datos = JSON.parse(this.responseText);
                    //console.log(datos);
                    const datos = this.responseText;
                    noti.innerHTML = datos;

                    //estaba comentado antes
                    //
                    
                    if(datos === true){
                        $('#emailModal').modal('show'); // Muestra el modal
                    //}else{
                        $('#emailModal').modal('hide'); // Oculta el modal
                    }
                    console.log(datos);
                    //
                } else {
                    console.error("Error al obtener datos:", this.status);
                }
            }
        };
        http.onerror = function () {
            console.error("Error de red al realizar la solicitud.");
        };
        http.send();
    });

})

$(document).ready(function () {
        //registrarRespuesta();
        const url = base_url + "AdminPage/validarCamposCorreoYClave";
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    const datos = JSON.parse(this.responseText);
                    console.log(datos);
                    if(datos === true){
                        $('#emailModal').modal('show'); // Muestra el modal
                    }else{
                        $('#emailModal').modal('hide'); // Oculta el modal
                    }
                    console.log(datos);
                } else {
                    console.error("Error al obtener datos:", this.status);
                }
            }
        };
        http.onerror = function () {
            console.error("Error de red al realizar la solicitud.");
        };
        http.send();
});

function registrarRespuesta() {
    
    const url = base_url + "AdminPage/validarCorreo";
    const frm = document.getElementById("frmRegistrar");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                if(res.icono=="success"){
                    $("#emailModal").modal("hide");
                }
                console.log(res);
                frmRegistrar.reset();
                alertas(res.msg, res.icono);
        }
    }
}
*/

