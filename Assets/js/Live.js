console.log(empresa_id);
console.log(empresa_id);
let tblDatos;
const grafica1 = document.getElementById("graficaFinal");
carruselExtra = document.getElementById("carruselExtra");
extraerdata =[];
todo={};
//durante 2 segundos
setInterval( async function(){ $(".loader").fadeOut("fast"); }, 1000);
    //$(".loader").fadeOut("fast");

let tituloGrafica = document.getElementById('tituloGrafica');
let fechaInicial = document.getElementById('fechaInicial');
let fechaFin = document.getElementById('fechaFin');
let temp_c_f = document.getElementById('temp_c_f');
const bajarGrafica = document.getElementById('bajarGraph');

timeUser =new Date();
console.log(timeUser.getUTCHours());
function markerOnClick(e)
{
    showLocation();
}
function showLocation(){
    //removeActive()
    $(".popBtnLocation").addClass("btnActive");
    $(".popLocation").show()    
    $(".popStatus").hide()
    $(".popDetails").hide()
    $(".popAlarms").hide()
    $(".popBooking").hide()
}
var zgroup = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    minZoom :3,
    attribution: 'ZGROUP &copy; lupamape contributors'
});
var map = L.map('map',{zoom:3, center: new L.latLng([9.04,-70]),zoomControl:true,layers:[zgroup]});
var markers = new L.LayerGroup().addTo(map);
var markers1 = L.markerClusterGroup({ singleMarkerMode: true});
map.addLayer(markers1);

function dato_procesado(data,temp1){
    data2=[];
    data.forEach(function(num){
        if(num){num = temp1==1 ? (parseInt(((num*9)/5+32)*100))/100: (parseInt((((num-32)*5)/9)*100))/100;}
        data2.push(num);
    })
    return data2;
}
function c_f(temp,data=0){
    if(data==0){
        res = temp==0 ? 'C' :'F';
        temp_c_f.value=temp;
    }else{res= temp==0 ? data: parseInt((data*9)/5 +32);}
    return res;
}
temp_c_f.addEventListener('change', async function(){
    der = temp_c_f.value;
    console.log(der);
    console.log(todo);
    okey = await graficaMadurador1(todo.graph,todo.cadena,todo.temperature,der);
})
function elcolor(d){return d==1 ?'green': d==2 ?'orange': d==3 ?'white': 'black'; }
function pintarCirculo(contenedor,indice){
    estadoColor =1 ;
    console.log(contenedor.latitud);
    
    var circulo = L.circleMarker([contenedor.latitud,contenedor.longitud],{
        radius:8,
        color :elcolor(estadoColor),
        fillColor : elcolor(estadoColor),
        fillOpacity :1 
    });
    
     //var circulo = L.marker([contenedor.latitud,contenedor.longitud]) ;
    tableStatus1 =`
        <div class="card" >
        <div class="card-body">
        <h5 class="card-title text-center">${contenedor.nombre_contenedor} </h5>
        <div id="pop_${contenedor.nombre_contenedor}"></div>
        </div>
        <div class="card-body text-center border-1">
        <a href="#" class="card-link">Mas detalle</a>
        <a href="#" class="card-link">Menos detalle</a>
        </div>
    </div>
    ` ;
    tableStatus = `
        <div class="row">
        <div class="col-6" style="color:blue;"><b>Reefer ID: </b></div>
        <div class="col-6"><strong>${contenedor.nombre_contenedor} </strong></div>
        </div>
        </br>
        <div class="row">
        <div class="col-12"><button type="button" class="btn btn-success  btn-lg btn-block">Detalle</button></div>
        </div>`     
        ;
        circulo.bindPopup(tableStatus1,{maxWidth : 370});
        circulo.on('click', markerOnClick);
        markers1.addLayer(circulo);  
    
}
async function procesarFecha(){
    

    contenedor =tituloGrafica.textContent ;
    fechaInicialx=fechaInicial.value;
    console.log(fechaInicialx)
    fechaFinx=fechaFin.value;
    console.log(fechaFinx)
    if(fechaInicialx==''|| fechaFinx==''){alert("No se seleccionado las fechas");
    }else{
        console.log("vamos a analizar");
        conj= contenedor+"/"+fechaInicialx+"/"+fechaFinx;
        console.log(conj);
        if(fechaInicialx!=todo.date[0] || fechaFinx!=todo.date[1]){
            $(".loader").show();
            const response = await fetch(base_url + "Live/GraficaInicial/"+conj,{method: 'GET'});
            const data = await response.json();

            if(data=="mal"){alert("Fecha Inicial mayor a Fecha Mayor!");}
            else if(data=="rango"){alert("Búsqueda fuera de Rango , contacta al Administrador");}
            else{
                //aqui recibimos la infro procesada y lista pa mostrar en la grafica 
                console.log(data);
                todo = data ;
                graph = await graficaMadurador1(data.graph,data.cadena,data.temperature,data.temperature);
                //setInterval( async function(){ $(".loader").fadeOut("fast"); }, 1000);

            }
        }else{
            alert("Fechas Procesadas!");
        }
    }

}
// MODALES PARA TARJETAS
function ethyModal(){
    $("#ethyModal").modal("show");
}

function co2Modal(){
    $("#co2Modal").modal("show");
}

function humidityModal(){
    $("#humidityModal").modal("show");
}

function injectionModal(){
    $("#injectionModal").modal("show");
}

function supplyModal(){
    $("#supplyModal").modal("show");
}

function apertureModal(){
    $("#apertureModal").modal("show");
}

function compressorModal(){
    $("#compressorModal").modal("show");
}


function tablaDeDatos(id) {
    $('#dataModal').modal('show');
    let contenedor = id;
    let title = document.getElementById('titleTabla');
    title.textContent = id;
    const url = base_url + "Live/GraficaInicial/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let res = JSON.parse(this.responseText);
            let recorrer = res.graph;
            //console.log(recorrer);
            let datosTabla = [];
            // Obtener la longitud del array de datos
            let dataLength = recorrer['set_point'].data.length;

            // Iterar sobre los arrays de datos
            for (let i = 0; i < dataLength; i++) {
                let createdAt = recorrer['created_at'].data[i];
                let setPoint = recorrer['set_point'].data[i];
                let returnAir = recorrer['return_air'].data[i];
                let tempSupply1 = recorrer['temp_supply_1'].data[i];
                let relativeHumidity = recorrer['relative_humidity'].data[i];
                let cargo1Temp = recorrer['cargo_1_temp'].data[i];
                let ambientAir = recorrer['ambient_air'].data[i];
                let evaporationCoil = recorrer['evaporation_coil'].data[i];
                let powerState = recorrer['power_state'].data[i];
                //createdAt: 2024-08-11T22:21:23.333000	
                
                let formattedDate = new Intl.DateTimeFormat('es-ES', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                }).format(new Date(createdAt));

                // Agregar los datos al array de datos de la tabla
                datosTabla.push({
                    created_at: formattedDate,
                    set_point: setPoint,
                    return_air: returnAir,
                    temp_supply_1: tempSupply1,
                    relative_humidity: relativeHumidity,
                    cargo_1_temp: cargo1Temp,
                    ambient_air: ambientAir,
                    evaporation_coil: evaporationCoil,
                    power_state: powerState
                });
            }
            if ($.fn.DataTable.isDataTable('#tblDatos')) {
                // Destruir la instancia existente
                $('#tblDatos').DataTable().clear().destroy();
            }

            

            const buttons = [
                {
                    extend: 'excel',
                    text: '<i class="ri-file-excel-2-line"></i>',
                    className: 'btn btn-success',
                    titleAttr: 'Exportar a Excel'
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="ri-file-pdf-2-fill"></i>',
                    className: 'btn btn-danger',
                    titleAttr: 'Exportar a PDF',
                    title: contenedor,


                   
                    customize: function (doc) {
                        //Create a date string that we use in the footer. Format is dd-mm-yyyy
						//var now = new Date();
						//var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear();
                        let img_footer = 'data:image/jpg;base64,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TknZo5Wiu21T4K+NtF8dWXg2+8PXNr4lvsfZbKR0xcAgkMkmdjKcHkHFdn/wxp8Z+QvgW6cgFiEuYGOAMk4D80nUglfmQ1Tm+h4tRXqXhX9mH4n+ONEh1jQ/Cc9/pszMiTrcwqCysVYYZweCD2rA+I3we8Y/CSewh8XaFNokl8hkthM6P5qg4JBUkdafPG9r6i5JWvY4yiuk0/4c+JNV8Fan4vtNIuJfDOmTJb3epYAiikbovJ5P06VznPeqTT2Jaa3Ep0f3hTadH94UxDaKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKWkpR1oA9z/ZW034m/EXx5p3gLwN4p1fw/p1zMbrUJrG4aOK2hGPMlbHGccD1Ne2ftPfG6x+Nnxq8B/BrR9SmvPh/oeqW2n3V3NIXbUpw4WSRmP3gMEA+pJrmv2T/2pvhj8CPhb4j8P+IPDWt3Wu6+8kV9qekyKjtbkYWNXyGXAz09a4b4peOvgI/huKb4W+D/ABJ4c8ZW97Dc2+o6jemWNAjbmyCxySQK8yUXKs24PsvXuz0lJRopKa8z6r/af1Dxx49/aSj+DnhHx3a/C7w14f0e3uLbzbg2kNwzAYXIIyRwAK808L/DTxt8L/8AgoJ8L7Dx74qXxjr115c/9pKzMfKEbqiktzwBTNb/AGtPgf8AHaPw/rXxg8AaufGukRRxnUdEn2pclCGBPIOMjODnFcz46/bI8NeLP2uvBHxWt9C1K00Dw7Atu1m7K1xKFVhkdh94cVjCnV5eRR6O+nU6JSpt87l109D7amtvEHwb+Inxf+KHjH4iLrnw1jjkVfClozXJtmYAKkigkRnnGMD73NfM/wCy3rx+G/7G/wAcfih4Xt1s/FE19NHb3CqC9rFvAQL6AByfqM1514P/AG09N8OftFfEXxRd6Pean8OPHDSL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pgRWZl4mmbPO7ZkDHrXKeAfiR4n+Futyat4U1m50XU5Imt3urcjc8bcspz1BqpRbm5RWy0JUkoKMnu9T6j1bxdbeIf2c/jB4Y8L6fdWvgnw0mm2elRvAyyXbecTNdvxy8jZPsMV8bt1r6S8O/twePrP4a+NdF1bxNql9ruqi3XTL5VjCWqqT5ob5f4lOK+bpnMkjOx3MxLE+pJyTRQpzhzcyCvOM1HlYynR/eFNp0f3hXUco2iiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAClpKWgB1J3xX1h+xH8C/h78V/DPxJ1zx/pt/qVr4ato7mKPT7hon27WZwAOpOK5PxzD8DvGPivwFpPw48MeJND+26vFb6odamY+bA7Ku1CTweTzXP9YjzuCT0Ov2D5FO61PntqXt1r7U/b6/ZJ8FfAnw3oPiLwDBcw6f9tk07VI57lpvLl2hkOW6Z5FdH4U/Yt8B2/7HN5418Q2t0/j/APsKTW4wt0yLCjE+UDGODwPxqPrVNxU+7sP6rU53HsfBA5zmjHvX0f8AsU/Avwr8WtU8WeIfiEsw8DeGdNFxeNFI0RaVz8o3D0GTisP9sT4I6Z8DfjJ/Znh9JR4T1S1g1HSWlYuTDIBuXceTg/zrRVoup7LqZexl7P2nQ8Mor7K+PP7MfgTwD8evgn4U0eyuodH8VW9u+pxyXLOzl2w21jyv4V4z+198MdB+D/7QHiTwp4ZgkttGslhMEU0pkYbkBPzHk81NOvGpJJLfUqdCVNNt7HjNKMmu/wBY+BPjHQfhHpPxLvdOjj8I6pN5FtdLODIzZIBKdQDg4PtVL4aeIPBfh6+vZPGnhK48W2skarbwW+otZmF88sWX72Rxit+dNNx1MeRp66HHUuD+FfU3jfw/8G9D+BWjeMpvh1qHh/XNdv1Gj6VJrckz3Nkh/fTtkfIp+6pxz1qxoNr8C9a+Cvizx/8A8Ko1KE6Fqdrpw0//AISGU+d5ylt+7HGMfjXP9YVr8r3sbfV3e3Mj5QxSc9q+kfhzoPwq8ReE/ir49v8AwJfS6JoH2EadoK6vIrRmVirlpurdM9KxfH3gHwD4q+Bz/E7wHp2p+GP7P1hNH1LRtQvPtcbl03JJDJjP1Wr9sr2afYXsXa913PCee9GPwr6R+CP7Oeh+PvgzrOq6xNLb+MNbN1H4PhVyFma0TzLglf4twyo9xXnPwC+EUXxc8X3ttq1/JonhrRbKTVNb1BF3PBbx9VQHq7H5RnvT9tH3vIXspe75nmn8qK9s1nx58Cp7O/sNL+FesQr5TpZaxJr7m4L4+SSSPG3BPJUVaf8AZ/Pin4Y/B6bwpYSXHirxXJfC+kklIgSOGTAlYniNFXJLUe1SV5KwexbbUdbHhJX060L9a9f8YfD/AML678QPDnw6+GQk13VVcWd74gmlIi1C6P8ArHjTokMeG57hc16D4Z+D/gu4a7sfDXgHX/i19hm+yXniKTVV0uxkuBw0dspI34PAyST6UpVoxSdgVFtvU+X+tFey/FP4T6DpGgjxh4Xh1O10qx1QaVr3hrWHDXmk3Q+byzIv343GQG6g16V8E9M+Cvxh8XHTB8JNQ0rSbG1e/wBX1mfxJKY7G2QZaVhjkk8Be5NJ10o8yQKi3LlbPlD2o6+9e6+A/hb4H8dax8QPHF9Pf6B8IvDM2Y4IG8y9ujI2ILZGbgMwGSx6Cs/W/iB8E9R0jULKy+FGp6NeGJhZajH4gklkWX+FpEYbWHqBin7VN2SbD2dlq0eNUewFfQngfw18NvC37N9h498X+D7vxbql94gm0pVg1R7NY0SMMD8vBPNM8cfAXQ/FUfwq1j4ZRX9jY/EG4ksYdI1aYTSWVxHJschwAXjxzn2o9sm7Pbv6D9i7XW/Y+fs+9Hp+VfRXi68+A3wr8S3nhAeB9V8fSaXKbS/8Ryaw9q00y8SG3iQbQoOQN2c4qVf2d/DUfx++FWn2N3daz8N/HzwXNjLOdlwIWJEkDsP40YYyOvWkq0eqt2B0Xsnc+cBn0o+tbvibSbfS/HmraVArCzttVltI1JyfLWYoBn6CvpPTfgF4Gt/2uvFHgq8026u/CekaJLqaWS3TJI7pbLLjzOuCxNVKqoq77XJjSctvQ+T2z+NN5r6M0rwZ8Lfjf4F8b3fg7w7q3gbxP4X0w6wsU+om9s7yBWCujbhlH5yDnmvEPAfg3UfiN4y0LwzpCCTU9Yu47SAN0DMep9gMn8KcaiabeltwlTaatrcxOlFfRXiy6+Avwn8R3XhIeCtV+Ik+my/ZtQ8Qy6u9mJZlOJPs8acBQcgbs5xWL8M/hp4J1/S/G/xJ8TR6nYfDfQLlLez0i3nBvL64lOYrbzSOAByzYzU+2VuZp2K9i78ulzw/nrR9a+kfBOg/CL9obV38GeHvCN/8OvF91DI2jXg1R722upkUsIJkfkFgCAy96v8A7Nf7O3hb4p/DH4gjxBFcW/jK2vxpOiSJMVRLzymcRsvRtxRhzUyrqKbkrNfqCouVrPQ+XlpfavRfgT8Ml+JPxY07w/qwe10u2Mt1rMgO0wW0AJl57HI2/U1rftVeAfD3wy+OOt+H/CsEttoMMVvNbRTyGRwskYfljyeta+0XtPZ9TP2b5OfoeSAGg9ete/8Awz8L/DvRf2dtS8feMPCV34rv08RrpEUMGpPaKkZh35+Xqc1P4m+D/gbWbP4UeM/B8Wpad4Y8X63/AGNe6LqU/my2sqSqsnlygAshDcHtUe3SdmvI09i2rpnzzSN1r6Q+MWpfBH4eeO/F/hC0+FOoy3Wk3U1hDqDeIJcF14WQpj15x7V83t1q4S51zJWInHk0uNp0f3hTadH94VoZDaKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKWkpRQB+gH/AAS/m1iDwb8ZpdAtVvNaS0hNlBIoZZJtjbFIPByfWuG+Ml/8cNZ+JPwkuvi74RsvDNvHrsMenvZW8UXmuZELqdhOcDHWvlTw5428ReD/AD/7A13UdF8/HnfYLlovMx0zg84qfWviJ4q8SyWb6v4l1XVHs5PNtjd3TyGF/wC8uTwf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CiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACnKabRQB9m/BP9q74PeAf2cz8MvEHhDxBcyaluk1u60ucQm8ct03ghtuMDHtXi3x28S/BTXtF02H4VeEdZ8Nagkzm9l1S6MqSRFcKqgk4IPNeN0VzRoRhPnTf3nTLESlHlaX3H0P8dP2jtD+KfwD+FPgPTtOvrTUfCMZS6uLjb5UuYwvyY56jvSW/wC0jo9v+xn/AMKfSxv4/EK6wdTTUkIEKjzN4weu4fzr55oqvYwso9E7k+3ndy7qx9eat+1j8PPihafCzWPiJ4d1a68b+DbiM3eoaeIzHqcEf3UfPOchW+oPrWJ8YP2+/iV4u+Keoa74Q8Vaz4b8MmWM2ejLMFVY0xkOBxlsHP1r5eoqFhqad7FPE1GrXPsH4iftyadd/tBeDvi54M0a8tNbstOGn65aagVEV8mACFK89Oh9hR48+NX7LfjKbWfE3/Crtej8WamskhtVutlolyyn94QDj7xz05r4+opLDU1Zq6t5j+szd72d/I+h7P8AaQ0a1/Y0m+D62F9H4gbWW1NNQjKiBAZd4GeuRx+NbvxR/ai8G/HX4Y+AbPxzoOo3PjvwzPDFcapbFRFf2YYeZG5+8CyjPs2fWvluiq+rwvzLe9xfWJ2s9rWPsX4dftQfBn4L/Gjxd4z8FeDNW0+zutGWw0azXbi3nKkyytkkgE7eh6A+tc98Ff2/PiP4L+KFhrXjTxTrXijwxmUXmkNMGUqwONgPGVOMe1fLdFL6tS1ur3H9Zqq1nY+r9H/aq8EeF/APxz8MaPourQW/ju7e50vcUAtA4GVkx6HPSvlS3k8ueKRyWKurMc5JwQf6VHRWlOlGnfl6mU6sqlr9D6D/AGwP2jNG/aK1zwfeaJYX+mwaLo66bPHeMP3jhs7gB2r59PtSUVVOnGnFRjsiak5VJc0gp0f3hTadH94VoQNop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2nR/eFG2lUfMKAIdo9KNo9KKKwOkNo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KMD0oooA//2Q=='
                        let img_header = 'data:image/png;base64,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'
                        doc.pageMargins = [40,70,40,70];
                        doc['header']= function(){
                            return{
                                columns:[{
                                    alignment: 'center',
                                    image: img_header,
                                    width: 575,
                                    height: 50
                                }],
                                margin: [10,10,10,10]
                                
                            }
                        }
                        doc['footer']=(function() {
							return {
								columns: [
									{
                                        alignment: 'center',
                                        image: img_footer,
                                        width: 575,
                                        height: 50
									}
								],
                                margin: [10,10,10,10]
								
							}
						});

                      
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ri-printer-line"></i>',
                    className: 'btn btn-info',
                    titleAttr: 'Imprimir'
                }
            ];
            
            // TABLA
            tblDatos = $('#tblDatos').dataTable({
                pageLength:10,
                data: datosTabla,
                responsive: true,
                columns: [
                    { 'data': 'created_at' },
                    { 'data': 'set_point' },
                    { 'data': 'return_air' },
                    { 'data': 'temp_supply_1' },
                    { 'data': 'relative_humidity' },
                    { 'data': 'cargo_1_temp' },
                    { 'data': 'ambient_air' },
                    { 'data': 'evaporation_coil' },
                    { 'data': 'power_state' }
                ],
                language: 'es',
                dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons
            });   
        }
    }
}

function generarPDF(id) {
    console.log(id);
    const url = base_url + "Live/GraficaInicial/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let res = JSON.parse(this.responseText);
            let recorrer = res.graph;
            let datosTabla = [];
            let dataLength = recorrer['set_point'].data.length;

            let now = new Date();

            for (let i = 0; i < dataLength; i++) {
                let createdAt = recorrer['created_at'].data[i];
                let setPoint = recorrer['set_point'].data[i];
                let returnAir = recorrer['return_air'].data[i];
                let tempSupply1 = recorrer['temp_supply_1'].data[i];
                let relativeHumidity = recorrer['relative_humidity'].data[i];
               // let cargo1Temp = recorrer['cargo_1_temp'].data[i];
                let ambientAir = recorrer['ambient_air'].data[i];
                let evaporationCoil = recorrer['evaporation_coil'].data[i];
                //let powerState = recorrer['power_state'].data[i];

                // Convertir la fecha a un formato entendible para el usuario
                let formattedDate = new Intl.DateTimeFormat('es-ES', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                }).format(new Date(createdAt));

                // Filtrar datos de la última hora
                let createdAtDate = new Date(createdAt);
                if (createdAtDate >= new Date(now.getTime() - 60 * 60 * 1000)) {
                    datosTabla.push({
                        created_at: formattedDate,
                        set_point: setPoint,
                        return_air: returnAir,
                        temp_supply_1: tempSupply1,
                        relative_humidity: relativeHumidity,
                        //cargo_1_temp: cargo1Temp,
                        ambient_air: ambientAir,
                        evaporation_coil: evaporationCoil,
                       // power_state: powerState
                    });
                }
            }

            // Crear la estructura de la tabla
            let tableBody = [
                // Encabezados de la tabla
                [
                    { text: 'Fecha', style: 'tableHeader' },
                    { text: 'Set Point', style: 'tableHeader' },
                    { text: 'Return Air', style: 'tableHeader' },
                    { text: 'Temp Supply 1', style: 'tableHeader' },
                    { text: 'Relative Humidity', style: 'tableHeader' },
                    //{ text: 'Cargo 1 Temp', style: 'tableHeader' },
                    { text: 'Ambient Air', style: 'tableHeader' },
                    { text: 'Evaporation Coil', style: 'tableHeader' },
                    //{ text: 'Power State', style: 'tableHeader' }
                ]
            ];

            // Agregar filas de datos a la tabla
            datosTabla.forEach(function (item) {
                tableBody.push([
                    item.created_at,
                    item.set_point,
                    item.return_air,
                    item.temp_supply_1,
                    item.relative_humidity,
                   // item.cargo_1_temp,
                    item.ambient_air,
                    item.evaporation_coil,
                    //item.power_state
                ]);
            });
            // Definir el contenido del PDF
            let img_header = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABQAAAACECAYAAADP/YaeAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAIqOSURBVHhe7Z0FnBXVF8fXIneRloaldlmW7u7u7gbpDgVFFARBEASkpBVBykBBKQHpTunuhqVLzv/+zrzZnTdvXm3wZ9dzP359y3szd+69c2fm3t+ce45P7IAqJAiCIAiCIAiCIAiCIAhCzCNOtqrkEy+oGgmCIAiCIAiCIAiCIAiCEPOIH1ydfPxy1CBBEARBEARBEARBEARBEGIeCXLWFAFQEARBEARBEARBEARBEGIqIgAKgiAIgiAIgiAIgiAIQgxGBEBBEARBEARBEARBEARBiMGIACgIgiAIgiAIgiAIgiAIMRgRAAVBEARBEARBEARBEAQhBiMCoCAIgiAIgiAIgiAIgiDEYEQAFARBEARBEARBEARBEIQYjAiAgiAIgiAIgiAIgiAIghCDEQFQEARBEARBEARBEARBEGIwIgAKgiAIgiAIgiAIgiAIQgxGBEBBEARBEARBEARBEARBiMGIACgIgiAIgiAIgiAIgiAIMRgRAAVBEARBEARBEARBEAQhBiMCoCAIgiAIgiAIgiAIgiDEYEQAFARBEARBEARBEARBEIQYjAiAgiAIgiAIgiAIgiAIghCDEQFQEARBEARBEARBEARBEGIwIgAKgiAIgiAIgiAIgiAIQgxGBEBBEARBEARBEARBEARBiMGIACgIgiAIgiAIgiAIgiAIMRgRAAVBEARBEARBEARBEAQhBiMCoCAIgiAIgiAIgiAIgiDEYEQAFARBEARBEARBEARBEIQYjAiAgiAIgiAIgiAIgiAIghCDEQFQEARBEARBEARBEARBEGIwIgAKgiAIgiAIgiAIgiAIQgxGBEBBEARBEARBEARBEARBiMGIACgIgiAIgiAIgiAIgiAIMRgRAAVBEARBEARBEARBEAQhBiMCoCAIgiAIgiAIgiAIXlDTBVbbC4Lw/0YEQEEQBEEQBEEQBEEQXFCT/HLa8ETk82Rb/Xfjtvxpsa0gCBFGBEBBEARBEARBEARBEEzoglx18stelfwCK5JvQDnyzVKa4mcsoShOvhmLKfBZgnwzlSTfzIosZdS25cgvWyVtv+DqFDuwCr0TUJniBlULE/mC1d/4PaiyRvYq2nf4DduIGCgIkYoIgIIgCIIgCIIgCIIghAHxLbg6+QVWIN9MJShemgL0TvIc9GbiQPJ5NzO9kSAzvZUgk/rMyLyp/v1O4gCK9152ip8qJ8VPnYd80+anOKnz0dupCpB/0YZUolEfylG5PSXKXomFwvgZilC8tAUontomXpr8FD99QfL1L8pCol9AeZsYKCKgIEQWIgAKQgRJYMDqd0EQBEEQBEEQhGgBC3/VyC+wPPn6F6FYyYPpjQSZKG7SbJQ6SyHKVbgqla7alOo360JN2/Sk+s27qn83o+CClSlp+nwUK1FWetPPn95U+7yZwF99l5fKVW9OQ0ZOpn6fjacC5ZtQHFuebyfMTLGTBFDcZNnUZyC9ZRMTYycNJN9UuSh+hsLkl7WcJkSKECgIEUYEQOG1wFfd1ONnr+YRvsHWebwq4me3mbBnrURvZ6lIb2WuoKH+xndxslXhbaz2FQRBEARBEARBeP2wCWzZKvGS3jgpcpKPbwZKlDoXlazchLr3H0rTZs6nP9dsot37D9OZ85fo4uVrdPb8Zdq97x9avnI9fTVxFrXo0I+CC1WmNAFFqWTFxtSj/+f08dCx1KHrQArMWZreSZiZkqXPT3lL1qZ6zbpS+24fUZd+w6id+qzdpDMFF65K8ZIH0dvvZuTP+KnykG/mUuSn5oEiAgpCxIi2AiBEFh//sq+UNzOVDxV24gdXV9+Vc9jmVQDRydweIG5QVcvtXaPqkFED9YOIFSugsk3EsvlfeAWg7PGCqnlE3GxVLfOIKiBOxgmswu2O9oL4916BBuRfsjllLdeKgiq2ZbKWa00ZSjajZAXqqTZE/yzH7YntPW1L9C93/Qoio9W+7sB5tcrPDtUP4r3C8y4IgiAIgiAIwv8bWP2pz4CyFD9NPnorYRa2yCtctj59+fUM2rn3EN2+e4/cpX9fEl24dI1Wr9tMi35eQZOmL6D2XQdR1tzlKE6SbJQ1Zxlq2+VDmj53Mf21aSedPH2ert24TbfvPaRbd+7RucvX6O8tu+njzydQQL4KbBkYP3kQxUuRg3z9i2mWgDnNZRcEwVOipQAIkSSwfBtq3ucLatH3C/6Malr2G0k1OnxMyfLX4+MnzVePmvQc/sqOr9Oq/ygq3aQPC2bGNoEwlrF0C2re27s2adprODXqPoxqd/yEKrb6gArX7U4ZS7Wgd3PWYhHwzUyagGU8VmSD/Ms260cDR89wz5czqEDtLq9EBISlIUQzCG6Jctem/LW6UMNuw6jfF9Nowtyfaf6vf9FPKzfS8nXbacX67fTzyk30/c+radyspdR3+FSq3+Uzbk8IgnECq7KVINrU6lgAQuN7altX/aplv1FUrEEPh/PvjtiqHgHlW1MLlYezvFv0HUmtB3xJAeVacx+3ykcQBEEQBEEQhBgGVlllLkXxUuagt/z8yT+4FHXr+xlt2bGPXtrEPW/SixcvaeVfm6lS7Tb0bqqcVKhMXfrw0zG0at0WunHrjm2rsHTzTggdP32etu85SJu37qKff19LDVv1pHjvBZFvimAWAeOrT78spW3BQWrZEItAQfCGaCkAQjBq0Wckv4W4E3Kfbr8CQu4/oG17j1Dmsi35+BlLN6erN26/suPr3HvwkOb+tIoty4xtApGqWvuPvGuTu/fp5u27XI+zF6/RoeNnaNPOg7Ro+QYaO3MJdR48noo37EXJ8tenNzNXiBLRDaKXb47qtHD5enr+/IVHjJu9lIU5q/wiC9QVbZy2WBNq1GMYfT3rJ1q3bR+31bNnL2yPKufp2fPndOnqTW7Pb39cTt2GTKRCdbtTkrx16O0s1hZ8EHGzVWhD127ecXoOQ+4/pAlzfnZqBWoFRMfk+evRdz+v5nyd5X3/4WP6e+cByl6pHZfFKi9BEARBEARBEGIIENCw+se/GMVJHsQ++IpVaEjzFi6jW7dDbDMbopcvXcuAxt8vX7lOHwweQ+mylaS8xWvSiK+m0t79h9X8yHEOdfCf4zRt9gJq13UQlanWnLLlr8ziY/YClSlTznLkEz89+fj5U6wkARQveTZeDhw/a3mKn60yxUdE4iBEDbaoV7QFgiYETidE6yXQtvKH1scm4IbWC1jtJ0Qm0VMADKhMHT4ca7ttvLp07PRFXvIJ8Slz2Rb04t9/bb+82vTzqs0syBnbBKJSnU5DbFtEToIZ9vZ9R+jr2T9R1baDKEGumpEuvEFMzV29I12+dst2VPfpwNHTlL5EM4rnpRWcp2h1rM5WkbMW/0FnLlyxHTn8CaLbpl2HaPS3i6hU4z6Wy2whukF8+xe28y7SbFWmtzJ7JgDiOLBkHDxujsrXdX+F8Ni053Be7myVlyAIgiAIgiAIMYWaHOwDS2vjJM3GwTuq1W9PG7futs0OPEsQ//TZy9Hjp6lDt0Hkn60kNW/fl3bsOWj7hejhw0c8v7z/4CH7Dvx+4TIqXaUpJfMvQKmzFqXg/JWoQo2W1KBlD2rfYzC16TKIajfpwiJiknR56c13M9Ebfv70VpLs9M57uTkicayUecgvSxmLur2GsNBl8b0uiukCGOaJ2dV8LEjNSYMq2VB/4zueQ9oCohj3iXRs+Uf0GKF5qL+xfDu7mr9DtDXWC//G9/jdvE+MwVan16Be0VYAbDtgjNs3EZGdDh47Q1nKagJgpjIt6OHjJ7ZfXm1atGKDpQBYq+MnbkWe8Kajp87TkK/nUqbSLeidSBQB0ZbdP/2G/vXiXD5+8pTqdfmMYoXTF54rYEnpX6o5fTRmJp04c9F2xMhNX0xZYOnHDwIgfAk+evzUtqV1+nbB7x4JgLCuxHFa9x9NNwxv8KzSI9WXsWwZ4p8s/xUEQRAEQRCEmExNFlx8M2riX/xkQdS8XW86dPiEbXbgfdq+cx81atWTRbyZcxfTrTshdP/+A/ptxVoa9uU31GvAMOrQYzD1G/QFNWnTi1JlLUp5itWgHgOG0g+LltHmbXvo3IXLdFvt9+jJM7r34DGdv3SN/tq4kz4fM5XFyaLl6lH9Ft2oc+9PqG3XDykQfgJT5mQh07qeZlSdA8qRX+bS5Pt/wA/HDi0LBKFa2t/Z1Nwwa1nyzVSCfDMUofjpClL8tPnZHyODv9V3+I23yVJW20evU5YylscLF8gLkZcDK2jiHI7BwpUX4pW+PYQ9lQ+Wl/v6F6f46QuruhSw1SuvQtUL/85QmH/H8fm4fD7tj+mbvUrk1vNVkAWoMmextSdEXG4fnHcv2jMSEQHQi3ToPywAIj19+pzmLl3JgS4iw0rM1/b55987bUfwPE3/cTnFdeFPLzzgvGYt31rVcRWLjFGRYPoOy0Kr9otsARDiX8lGvenE2Uu2Pa0TLqPpC5ZTwlw1vfYtKAiCIAiCIAhC9MM3c0mKkyyILf9avt+fzpwLM37wdJ6tb7dzz0Fq2qYXde/7Ge3a+w9/hzTp23nkn70kpcpShIILVualvemCSlKiNHkJ0YK37thHDx4+sm3tPD148i8dPHKS1m3aSZNnLqTx076n1Rt3UL9PxtLbibOGCSvuCK6mCWkpclD8FDlfLe8Fk2/aApqlGwQyLF3GMuZMxTWBLyW2C1bbBWk+D5Nn42XPvPSZP9V36rd42EZt66v2wb5+Wcuof+dS+0ZGnbQ84qVS+aXJo8ql2iqjOkZAeU2UcysE2n7LXlUTv9IVIt/UKp+UyBe+HPW6aJ8aWr3QPrydOi4LnRA52eJRO6ZvYAVbG/0fzl14QXkV8XB+UC+0R6aSmniL88/taWy/qEcEQC8SrOCylPnvCoB6mvz9r+zLLqJRgiE25a3Rif3aeZvOXbpGGUo0jXAZdFj8K9eafly+znaEqElb9vxDifLU5mW55jJEpgAYK6ASB8pZu2WvbS/naeXfu9iyE/tY5SUIgiAIgiAIQgwiaxmK+14wvZ0oC9Vu0olOnD7P8wJv59dwiXXg8AkaNPRrGjtxNoXce2D7hejx42dUu2kXSpWlMEcSXrBkOS3++Q+aM/8XmrvgV7py7aZtS+sEt0jg0dMX9MTmPvCZmup+8vkESpGxAI2ftoA+Hj6J3koEAdBDIwYIgGnzh4ppr5Rk2cg3TT5VjuosAPlmLEHxU+Wl+CmyswgWL5m+rfq3CxAYhcsPsG/q3Lbf9P0jCMrCnwZxLlVuTZQLLG9rSwvhCmKWmpv7wpIxfSEW81BOFvv08nN+tr/NJMendtx4+DdEzgyFVV8tFyqWatvayhSN0M4X2gH/DmbrRxZWQ604X50QGH19AA589T4A4XsPwRRgvYVgIP+v9OuaLf9XARDWcYg2jPNgLIO3IJAFIvs6S3gAuaoPymC1lNZbILwlyVeXZi76w5az5wnlg1UfcPfAxLZ9R0xVZbZut8gSABHAJKmqz5wlK217OE/HTl+gYg16OQSVEQRBEARBEAQhBgIhJVUeeuvdTFS2egvad+ioNjFQcxlvBEBs++LFC1q/eRetWL2RXhj8mOOvp8+eU4Pm3RVd6Z+jp+izERPok8/H0SmTmyVXx4SbqAdPnrMF4MOnLxX/0qZt+9hiccHPq6lEpSYUK3kwhfqPc0eoAGgTnQz4RjLm/DmicZp85Ju1DFsChlrEsShk2C55EMVJGkixkwRQrMRZGfyN7/Cbfd4Qluz3N2JVDrewFSLQv7MdA6TOw0uQwyzzbO0K8S+oEvlmKMpiYahwaMxXETdZNod6AXxv3I4FQLU/C2cQH3HMLGVsZQvbTidc9XxlBFucM1VfiLeqH0AwfZWBbKKlAIiopjU6DKaNOw/Spl0H+TM8YN91W/fR+cvXbLcY5+nStZtUrnl/ihNUlX2kpS/RlDbv/od27D9K212AyMEH1A3vydNntpys041bd7V99jnmYWTXwWM0atqP9LZJ+PJUADxy6jyt3rSb/tqyl9Zv38+BKXYdPE4nz12iB48e27ZynzbvPkRJYQXo6c3WBCz3kuarR+u2ObdQO3bmAp0469wP34+/r+N84OvO6hiegH0hmPX5fAo/pDxJt+7eoy3q3ENgGzH5B/pg1HQaOHomjf52Ic37ZQ23zcWrN2xbhyVYLeas+j7FDow6ARB5IPDHoK9m0jM39UGfa95XE1H15diCIAiCIAiCIMREbBZa6QvROwmzULZ8FWn9ph08L/BG+NMT9oGFnnEJrzEf/D1oyJdUp3En+v3PdRQ3cQDlKlzNKz+DnL9BAHz07CXBGPBWyENa+Osa8sOSUf8iWt0s62zCiQD4TqKs5OPrH6nEThJodwy2qsOSUCyvtRDtIIS9kSAjvfVuZoqrypcwTW5KnC4fJUqXl95Vf6PMWiCUjLyteX8zyMeqXO7gQCvqOJbHQLkhwvkXtVldot0VWJ6LduXt7OsGcQ/5IV9Ec/ZTbZAobR5VL1W3tHnJV51DLENHvd5OmNlO5OTzpB8TS4lNAiDyfjNBJst6IHq0WVi0Asd7U7W7eX+UN55hf207J8dyAbenqhcEXLvj4hN1U/3BL3MZm4jtYT+OANFSAIRok6pIY8pfqzMVqN1FfXpPAUWe6u9T+RYDOLiHqwTx7qMxs+yEEizjLFy3OxVr0JOKNujhlEK1u1Kz3iPoyvXbttys04r1O7R96jvmYaREo14sEpmXvnoqAA795nsKqtCGclV7n/LU6MjtV7Jxb2rQbSh98vVc+m3tVo+W5D5/8YIqqLaLky18PuMQwKN0k74cHddZmvjdzzRnqXMrNhbUqrTnSMJWx/AE7Is2OHfpqi1X12nb3sMsFhaq051SFWrI+/ukL0M+/mX5nKQp2oQK1ulKbT8YwxaFEGzhOxEJdcGyZ2eCZUQFQD3oByL53rh917a1dYLYiaAucdUxI2sZtSAIgiAIgiAIrys1yS9zKYqdNJASpslDk6YvsM0MwicAmpNVHstWrKVcharSn2s3U/nqzWnu/F/4e0+Oh030JcAPnkD8IxYBH6up1ZPnL6l5h370VsIs5BtQVqubZZ1NWAiAEHb8s5ekouXrU9FykUOxCg0oddYiFDeZveijYS+QQWiDAOWbIphyFq1BtZt2pa4DhtPHIybR0NHf0meKj0Z8Q90/GE61mnSlnEXUvC9FDvLxzUhxkloLgahTUIHKVLhcQypUtr7HFCxTX5WhJqUNLE6+7wWzgPVOoiym/DVBzhfCKwTlgArki4Ae6piaABhWBoiQIG22ElS6Witq03UQDRgylj79cirX69ORU6nfJ19Ri44fUMnKzShl5iL0ptpeFwKNx7RbSmzLP0GqnKq81VU9G1jUpR4lyZDPlI8jEJFzqXY355G/dF0WKvX9E6TE+bE+ljPQnrmK1aTUgcVUXwhiUVIXVsPaSuWfMidfm5Z9NpKJlgIggNXWGxnLhxsf/3Is2gwYOd2t4LL4j7/pvYIN7AIkQGzx8dfzcUGaUpStUlsWq1yluT+t0rbPUNYxDxMQ+4xtATwVADsO/prLhCXEb2QKa4s31d+oX8ZSLWjw2Nkcot1d+vybeeFeOho7oAoNnfC9LSfHhIcCgmW0H/iV7RvHhLp2UvWJ5WRJrTvghw/WcrMWe7b0d+mfG6lIvR4US5Ud7YYl0MaIucgLvgR91G84H1iGW7pxH/ps/He0ZvNuqtbuI5d+9iIqAEL8K96wJ/uqdJd++HUtJcNy9nAKuIIgCIIgCIIgRBdqkl+Qmrukyk1vv5uZ3u/1Kd0J0fz1RYL25zRduX6LipetR2PGz6At2/fQo0eaD33PBMCXvAT44dMXNus/YiAAPnjyjNp0GURvQwDMUlqrn2W9TZgEQIgwsMxq120Q7di1n7bv2hcp7Nx9gGo2as/ilybyOAJhCUIXhKFCZRvQkFHTaOX6bXTo5EW6dOM+3br/jO48fMHcVH9funmfDp24yNt88sUUyleqHlu4IQ9jvvgO9ftq8jzatusAbdyxz2P+3r6PVm7YQfMWL6dPR02lsjVaU5IM+bkeZmu6eFgmjIjFqfOq49n/BpEL5YLw17LTIPp+yQrasf84nbl8i67ffUK3H2j1uv3gOV2785hOXrjBKyenf/cL1WvZm5L7F7C0mjOC39IEFqM5P/5GW3fa13OTYu3G3ZS7WE2KnTir5f4A5yBZhgL03aIVtMWQx+ad+2nFXzsoc+4KoSLrexny0Zz5v9pt5w6056q/d9Dchb/RgE/HqXNWl9sNvjfty2ITATlKdNRaAUZbARAkyBlO1L4QS2p1HEw3bofYbjHW6dT5y1SoTleHJbdhx6/pktjZqlLB2l3o/OXrthyt0/xlf/H2Wr7WeemYywE8FQB7DJ1E7wRU4jYIaxMtTwiAb2WuwJ9T5/9m28N5Wrzib25Hc1ncAfE0ad56tOeQc/PvqzdvU/riTSl7pXYuLRJ/+2sr5xeeJazw5ViwTjePLB7XbNrDQUIg7kHoQ7tZ5QnwG8oE60AWCtVnzqodKDEH/3C+XDkiAiCExyxlW9KqjbtsWzlPWP6O44Tn3AmCIAiCIAiCEM0IVvMT/2IszGQvUIX2HzlDcNkHC7uICoDY3yoPXeT7dtZCattxAD1/oc1TXYl/9r+9ZL+CD55oQUAQAESzAlTlVr9+t3A5xU4cwMEpLOtshYUAGDtpAH0yYoJ2yEhMbbsM4GWt9kKP7bjJNeu4zLnLUY8PvqC/Nu+jh8+1ej1S/7uv6gjuPQkD/36sfsM2+Hu1mp926Tec/IPLsD89PW8IdXGSZaff1+7kcmDJtDcgf6RH6h87D56kURPnUqGyDXmZtFEEDLWiVHXh5c2271EWLPOtUq8DTZ/3K527dpd9QsIh2gP1v/uGOuk8VN/jdxz/2NmrNG7KfCpdrSVbqxrrZgR92T9Hadp96LTay7EeD57+S8UrNqVYDmJbGDgPKTIXVtfDObVH2L4o7/WQFxRcuEaoxV6aTAVpz4Fj2MzuOO7Q2/Puo5e0euMe6jVwFEfChsBpXx7VhmnzkV8QVjdGnQgI7SfaCoDhhcWSMi1p+77DttNhnR4+ekKdB4+PkFCiLzH1RADE9q4EIld4JQC6qA/EKwiERep1p8dPcBk6Txu27adEeeAH0LslpBDeyjcfQE9d+EX8de1m9i8IoXL9tn22bx3T3ZD7lKtaB17KanUsV+DcfDltoS0n5wnWm6Ua92GrSVfCnzNgJYh9IRxa/a4TXgEQ1rAQF6cvXGHbwnk6e/EqlWvRn96xsCIVBCFy4ZcTFlht6y3IB8v3w9DyDc89KiaAZ4WxTXC/xSe+w29W+0RnjPVFXfH8MNbZap/ohPElpdXvr4LXoQzeEFXlddXPoqJtolu7C0L0oCb5ZatI8VLmYp9mn42ayoIaltZ6YonnLGFXWOg9RUDEZ5A67JOeNyID//r7arpniBBsTK7K8OwFfP/9S4+e/Uv3Hz8LXQYMMfDAkbOUGEs0U+dVdfTw2WexBBgC4MBPvwoVKCMrtejQx1IAhOgEX3IFSten7xevoCu3HhJmf/ceE926T3TnoWtuP3jJohlm0rAKnP79r+QfXDrUWg7nOE6yIFr0+0YW8W6rZvcedQxVHpQLgh2Eq6oNOrHlGspvrpMOlgtjeXnvj8bQ7kMnWbB89FzLD/la1UdH/x37QCjcvOsfat1tMC93thIBIcxlCC5Ff+84zIKoffmJrqr/FSnfyCMBcOueo1xffd+7j4hOXb5HQQWrhQqAqTMWoE3b99tt5zlh5+zK7Yc0bc4SCsxfyc56k/tkiuzkl7GY6qsiAEYaGLQkUA06fvZPqvldp7lLV1HC3LXslv56S3QTAAGENywPvXzddWj27fuOUOqijXlAaJWPMyDAfj17qS0X69Rr2GTeDgEzBo2ZafvWOvX/YpqlhaYr4qlzmqFkM9p/5JQtF+dp2DfzuM0iMqnCgNbqeyPhEQDRn0E/1QbPnmu+Bp0lmPlD0MaybSx/Nh9fEP4L4NqHIG8mvAGNnIF7P+7LVoT/mVKd74soL6KJ4xhYxo+XKrhXIrL6m5kq8Pfm+5W+X2QQ0QjwZnB/NR/Dm+cKAoPBeh31j6XaAe2BFyNoh3fUd7plu9W+3oI8zWW1cslgBc6JeV+APmm1vTOM9dX7APJAnVF39A3ki5d53jy3IOyYywY8PRdwcWHeF2Ww2hbHQh3M2+voLkrQn3HNoF4ReQZbgT5hdexQDGVAH0W7W+VjBe4nfD1a5esGXKvhyg/lVaA/Ig+MDcz5eAraGu5VkC/6E+qO/qX3s1jqfOj3G5wbqzycERXtjjJY5hUO3I2RBSF6UpP8MhbnQAuBeSvS/sNnWECDHz0W38KhAbL4BxNClR6rfODz3ErIM37nTOjD92fPX6Jflq+mk6fPh26Haa3m/+9fmrtgGX06chJduHqbj4dlwFdv3af0QSUpboocqo4ePiNcCIAvIlkAbNWxn6UACEu6AmXq0/K120LFLmdiWMhjDeN3RrAvBLZaTbrR2wkycf66ALh4+UbO37yPN+CYELwgXG3Zc4zyFKsVKoiZwfcI6tH3k3F0+dYDeqJOI8puLLf+NwQ2/IZPfGe/zUv+7anaH0uGO/cbrvIPdliCrAuAG3cetmzDaypTTwXAbXuOstCp74vjn75iJQAesNvOG1AvfPI5U8z6YRn7PISVo1YWlClICwoSpMYCTlZ+RpT/nAAI8aN1vy/prpM3EHo6cPQMBVduH+GJDgbn0VEATJynDp1zEx15655/KEWhBl5N1DDwS1eiKe07fNKWi2O6dSeESjTsxQNYTCjKNu1HIffU1eIkwb9eEkQkNvjjcwcmJVXbDKRHj9UV7CJduXGLslVo69XAP7yERwDEeW/cY7gqp+sgMy/VA3rUtIV8wUdkUiAI0Rn0/dRFGnHwJiOweMY9xGqf8IB7UbbybahG+4+pugVwb+DtdQiRB4JJKlX+QnW6UaPuw6jjR+P4nt71kwnUesCXVLn1h+w2IXmB+jyRxQQdzxS8gMC9xVzv8BJQrrVlGb0F7YSXN5lKt7DLH8GwUhZq6PZ5CCHijUwVKFGeWvy8rtp2ELX7YAx1HzKReg2bxO1Ts8NgCq7SnrfHfTMiIhJenJjLChBY7N1c7gdpsNQurPqaeX/0SU/6A55DEEXQV3NU6UDVVH3bf/gVvzAbMPJb6jt8Gr/kaaj6BtxbpCnWmJ8ReOa7qzfq9l7B+lREtb2xbPq5cPd8xe/BldvZ7QvwnfnYGGPAr3Khut0cttdBH89drSMFq/6MNsdLSfT/t7K4t6b3BJQhbbEmlsfWQUCvXFXf52sKLwwTq3aHwAYLeuxvlS9AfeEDGIHqrPJ1B67V8OSnlzdQ3XvQp/T+4o347asm0Ogv6DdpijShgrW7UoOuQ7lf9Rk+hftZ788nc7+r3v4jdX7ac9nQLp6Mk6Ki3dE+mUo3t8wrPOSo3MHhGIIQ7YHolTovIRIrAjBA+Hv4DME1/uW5oxNdzmnC9i9sc86LV2/T2YvX1HfIx3VGrn7/c/XflK9EbRo28ht6+gxykyYwhjx8ygLg4M8nUMMWPejU+ass/kFAuRnySBNokmazRaS1qLuZSBYAXdWpSdteHNxDPw4fK0kAZS9YlX5fu50FMk0A04QhXQSDNRtaADNCLAuGwKf+4+80QU3bXheVHqgNG7cdQG/5aYFEtCXAQfTLqq28j3F7HewD60AzOB6Ob94eZXusfp8y+2eOThzHJMbFhVWgOnbfwWPp0s17nI+5nCg78lD/sTCJZb9oA9QN26MtjMe8pfbBdqcv3aIm7QewcGq0PkRbwvJx+97jvL9xXxD5AmBB2rb7H8v2ASzs2drR2J6w/MPv+vnFJ74LUT8OGDKO4qj+a2dVqf72ZStAi/4bCfynBECIeTnURODQibOqmzlPEAeb9xkZKrJEhOgoAGI5baZSLejufdci6Z8bdnJ5vSkzjl230xB6/AS3NOu0cecBeq9APTXIr84DfUTbhVNQZwkBS0o26m351twZOC+I7OwuzV+21uu32+HFWwEQ9c1XszMdOu66PyMtW7OFJ3Got9WxBeG/AIT/Km0GcjRvI1t2H2LBLrLEcVzLY2cuoSMnz9PhE+cc6PHZN169VMCzC+4W6nb+lKap63/TzoN05sIVunknhO49eMjR1C9fv0UHj55W9+UdbOHevM8XlLPa+/SueshDABw5dQFt22df73Ch8hg4ekaE2wriHZ4HcK+wQD3/jMfAyyUEUHIlXGDfhLlqUcVWH9LwyT/Qn3/vpEPHztDVG7f5GX7/wSO6eTuEgyL9+fcu+virWeyLlS2jwvmcxXntNmSiQzsiij9EkPgW++igvfxLNafNqq8Z9wVV233k9rmM3yEgIpgUXFfA3+s/6t5/7eYduv/wEbvswAutW3fv0+nzV9jX68zFf1Dr/qMpozou9ncl4qFuEN22qLa3K9+ew1SmWT+3z1f00a9Vv7NrG/X3+Dk/sXhtt21gZRYaUUa7YxlAO63bupfP68Lf19NXMxZTmwGjKXf1jtx3Ivosw/71u3xmeWydTbsO0dotqgzqmkLgrJFTf6TGPT6nbBXacHs4658oX7aKbWjZ2q2W+bpEtdmYbxeGKz8u7+a99PtfW/k+0evzySzgYimtJ+MjFuSzVCT/ks25rWcs/IPPEXxho1/BLQ762YNHj7nfHT55jlaq8zN6+iK+r6J/uuvHUdHu+HeXwV87XJfhQuUxed4yelfdW4zHEIRoDayIspZlkSF11qJs5PLsJQSgl/TwiS4AOhexzAmbQpjDjPPkuSvU68ORtHzlhtDfzEnP+/mLFy7nqafPXKDR42fSdwt+pqfPIAlp+z548pzFyhNnLtHBI6fo/mMEBKFQATBL3ooUO0mgqmN5zyymnAiAH38+no9pTqjrjVt3CWMKnRu31KfCVdsd+Oc45SxS1cE337tpctM3MxaxOKRbv+kCEv4Nse/a7cfqfvQP/fDzGho3dT59PW0BLVT3w90Hj9ONkGd2whoIefSSGrTqS2/ZIgnrAuCcxavo/lME27DfHse8eOMBnbl8h85cuWvH+av36PrdxyxmaUtdtX110eri9XtUokozeiuhZm2og3pWrt+Bzl66wyKfvfiniWPI7+iZK7T8ry00ec7P9NWk72nGD7/Tyg3b6fSlmyxKGi35tLZ5yfntPXyaCpdrZLdkFsIcfOmt2rDHJt7Z1zMyBUD4ikyZIT+tWr+LfzMeB6C+F647tun5a/fo1v2noQKlsV0ggKI98pWsw0unQ8sFATB1Hs9FbS/5zwiAWLaAt+bf/7Ladlk6TxjEYsAUGZNBDHaiiwCISSIGgFh68f6gsW7zwiDJW5EUE4FpbgKMjFaDXwxC9X1Qt69nuV4y/Mm4uR5ba6KN0fF/WrnJtrd1wg293Ydjwh1l2Fu8EQDfyFCOrS+X/PG37VvnCQ+LfDU6e71MWhBiGrAAb9F3pO3KsE951TWCa9BqP2/AcyN9iWZ06ZpzFwpb1UQzYW7XQYF0MPmHZRairp++cMWWg/uECfryddsoXfGmfJxf12yx/RLxNHPRH1zP8D6vMIn3Vc+aht2Hqkm39cudOup55kwkxb0sm7pXDp34Pf1z/FyoFYKrBGsCiGZlm/Xn4FzhCRyFczHm20W2HMPS7ZD7lLxAPZfCIl5mBVZoY9vDPrXqP9rlsxTPQ1hDQQg5dvqC08mGVbquJi2Llq+nCi0H8MssZ+Ma1K1Cyw9se9mnel0/4+ew1X46GFes2bzHtkdY+mvLXh4HGd1OwLK/cptBti08T7fv3udj9PxsElu5Qkg0lsEb0AdhOetNwpgI0Sx/W7uVWvQZyQK01fJttHPemp247cOTVqzbzmMU/foKb34PHz9hUQvid9riTXh5vLmsOuifOE9VWg+kH39frya4nh8L/RFCO/pn1vKt1ZjJeV+JinbHfQIvOCIrQYBMmLtWuO9vgvA64puhMFv/NWjVi56rRyZ86EFEY196vAxYXcu2a8BZ4m0U8JOHbY+evkStugyiDEGlaMv2vdpGFgn3iPsPH9KSZavonhPjEuNzDUE/jOnpC4iAL9lqDL/A+g/lVsVWc5yTlAg+AJMFkm96DwOBWAiA8J3Xtd8wOnXmAnPSxplzl+i3PzdQhx6fUOe+n1EXBT7bdPlI1WetajuUyjHdvhNCLToOoFgQjgyWXTgH9Vv34aXLRks+XVwDazbtpQ8/HU/FKzah1AHFKUGqXIrclCZbCapYqx3tOniW2wESqQ5Sk3YDQgVAHAuRhSvV60hb9p4ItUDTwXHHTFlIzd4fRG26DaHWBtr3GEoDPh1Li5b9RVdvP3LYD8tyR3w9h95JGCZYQSDDUtZfV23RxD/DPgBWfMfPXafJs5ZwhN/AfJUpcbr87NsvqX9Byl6oGrXq/BHN/nEFnbt6104EBDiu+orm/7yakqbPF7oUGJ84/y07f0wXrj8kBNkw7hfZAqCfatuOfUeoY6nzZ7BWhHB77c5TGjpmDjXv+JF9e/b8jIaMnEzrt/9jEw4NZUR7qnp9MX6OycchrClzkl9ghShZBvyfEAAx0IflR7dPJ9ITF4EnkLbtO0IZSzfngZBVXt7yuguAEP1wTAygMMnAJCRP9Y60Y/9R2x7WCTeeJj2Hq8Gc5+2EiUe6Yk3pzIWrWiYWCW+HsIzNaHWHNqzWbpBLs+ydB46ywOtJ+2EbbLv/qBYxyFm6c+8BlWzsnWVhRPBUAISA6pOuDH2kBvVoL1fp8rVbVOv9T/jcWh1TEP5LQADEfcucHj15yssNI0MAxP21RZ8veJDsLMECGkvN3FkXQ1DCPfqTcXPome1tuDfp8ImzlKqItpR20Yr1tm8jnvDyJ7wCINoH4k2/4VPp9PnLthztE+5rNdp/5CAA4nhok9JN+9Ivqze7vf9Zpa17DlPOqu9THAvhxh0QyYZ/84Mtp7B07tJ1jwTAgPKt2YrKnJr1/sKpAIjnT84qHejnVZvcTs5cpT3/nKB6nT/l/Kx8wKJuZZv1c+hnGFNgbOGJAAgLNXP6/a9t/Aw3C4AQG+/dVyPmcCS4BBk+6QdKmg+uP8J3zaId8KIzvOnClRvU5/MpLESZ2xPXNSwVT53zXLA3pl9WbnIQACOSH6z2Js37lVIWtnbZguPgemjQbSgdcDMucpVwz1u0fAMvx3c2boqKdsd9Ai8DIiut2bRbBEAhZpG9CsVPnYfiJs1G46fOZ+GMxT81JX6gHkm8DFhdwM7HLdoP0OUg/mH/a7fvU7vun9Cb72amag3eZ6t7q6TnuWnHASpTrQWdv6Tdx4zHwt/OX2y9VMfULBUfPgkTLSECPlMF+mjYBPZrCMGELabwbHcXQMhCAIyXLIhyFqlOTdr25mW7jdtoNGvflwqWqU8+cdOTj19GesMvE/m8k4qj7q7ftFM9Ix3LjTYaM2EmC3GwutSPETdpIItkG7YdDA2KARHo9sOXLCCFPP6X5iz4nQUr7OcTLx0vH4YwBN5Qf7+VIBMNHD6ZVqzdTMvUOEjnd/XvMtVbsRWZLgCiTSDSzftpjYOgBhGqasMu5PNWCpWvqpOqm5F3EsJXZCWau+gPVa4wwQplRr/5e8dRSpg6d6i4iWjG7Xt8prZ9YSeMQdjE9odOXqR23T6ilFmKqHplIB/fDFzWWIkD2KLPJ34GelMdN132UtTno9F06vwNtgbULAC14yLfG3cfU91m3VmQ09sVf6fIXJSOnrlpE9jCiOwlwChzYIFq6lhX7ZYBY9tLNx9R4fJNyCdWalN7+qtzH0Rla7Sh9dsP2YmxqBfqufPgKUqVtViosMmo8+iXuZQIgOEFAw4sN3FnPXH15m1NLMkaeWLJ6yYAdvhoHPmkLE4+/uUUZcknQ1m2pkiUuzZlLtOSB4Cr1eDHXcIytjRFG3u1lAoTBEy+XVkvnDhzkS3bjO2AY6Qq3FANfi/ZtnJMT589ZV+B7ibUAPmlKdKYYMLtKh07dYGtLpxZoUQ2ngqAE7/7hX0k4o24q4Sl0X1HTFN9EBM+GcgKgjMBEIJcZAiAuM4gSCz8bZ0tZy09t3hD/MXk+W6jceP5UaZpX7rhxBoHVn6I7H3p6k26d1+NJEyp7/Cp6r6rPc9g1YOBNO6/jmjbGxO+s9oWafL3v3otAOK+C7+EuMfBojvEhYsJVwJgbPXdh6Nn2LZ0TLfv3uPI7RdVm1gNzJEmqfKH51w7EwDxfPdUALTyO+tMAIQoA3+RP/250balY4JlI6xNsUwT9UakRWfpyMlzVILdZTg+0/4fAqDVhBHLvp48feo2qBWWoXYYODbcz2dnQhT6ONoUL4udWXbo6bq6Liu2+sBh9YEu2GEptjnhXoC8nYH6w2LTSgB0l5/VfUZPuKY+GDWdz4WxrACrHCqoesC61FkKUfeX86p/nTp3me83un8uqwQREC5HrK6xqGh39IHPJnzHvxnvVYy6B1jeBdRvuD+Yt1c/sKWwCIBCjAHiQbYKFCd5dkqVpSit3rCDnqqpIpbPatF0IQL+y1F8IQKaE74CsMp7+vxfevLiJd28+4AGDZ9IcZNlpzf8/GnUeM2lknYN2Sf9u68mz6MkafPQn2u01VfmTbXjOF6v/J069mNVaIiVEP9Qfmz357rtlDV3OYqdxGY5lSKYfLOWcy+YWAiAACLPm+9msidBplC/cwBCXOoshWnBkuWh5TOnn35bTakDitov6VS8kzgLNevwgSb+GazAbt9/yWLSot/WUsbg0iyGwSIRQl6YmJc99O80gcUpa96KlCWPgbwVKEn6fKGCXOg+igW/rrMTAO/y50uq07wnveHrz9vp9dPB8X3ip6cajbrQzZDHofsCCFjHz92k9zIVZD+AEK3ey1iYNu04YidsAoh2J89fpxbvf0hvq/bU28RYL/3fOC4sJHFeeg8cSTfuPuL9Q0VABZbRzp7/O72bOpfaThPLUNb02UvRsbO3olwAxPnPWay2OtY1SwGwVLVWtjrYtyf2hxDYXLXDnQfPQ/fTuXwjhIVmoxVgvBSqXTIWt+7DESTGC4AY1KYs1IB+We16+RMu4KETv+PtfcP5RtmK100AnDL/N6rdYTDV6/IZO3aGXxU4dx4+aT79tHIjnVGTSXcJg8y+n0/lgZxVWaxAvTAYnPfrGlsu1mn6wuWWg0a87Z2xcIVtK+sEH0GeLNeNl70q5azSngearhL8UKUsrAaxkdgfXIF6uxMA0U/hzw9+kdwl9Hm0O/q01fEE4b9GVAuAuN/Dz+y5S/b3UVjVYNJsTDv2HdXuLy6OifveOIuI6bh3/fj7Ol5K17z3CHbI//FXs2nGohXssws+8M5evMIBFPRlkm0/GEPffPcLTZjzcyjwz4ZPOO82p+NnLvI91bg9+Ob7X/i5gXuLp88r+OjC8wJWjz+v3GQ5YDYmZwIgQHvlqdGR1m/bb9taSyfPXaLvflpNfUdMZQtMuG/45rtfCX57zAl+AuEXz1vrsVcuAKr+NHSCtWUTxAv4ghw5ZQELYQ27DePlkQO/nMGuIfACyCr9tnYbpSjYwOG59joIgOgXi1dsYOu+0d8uoknzltEvqzY7fXm78+Bx9r0YnnGTMyEKwuLsJX9qZZi+iKYtWE7LVR2u3rxj28I+QVhH5EljGVwJdmu37OHl/FiyasWoaT9Sy74jPRYAjfmNmb6Y5v60ivb8Yx1kDfunL97Ert/D7QtesK7fts+2lX2CoI6xIfx+olzoZ7jffDFlPm3YfoD7oVXqP3KaNp42WUdGRbvjPongSpPUvcl8v8I9zMqq8cq1WzRVjYfhL9W4/cS5v1DXIRM4qE94+pUgvHbgXpK1DMFHXu7iNenwyQsmAVCzQIKFHURACH0snKv7McC/n714SY+fwe/ev2qff2n81B/o3dS56e2EWcj3vWDavEO7f1g92/Xvegz8koWtes270TnDPR0/Y5unzzVLP4ssQhPK++QF0f0nz+jXPzZQ6WrNbcJMEMXDkklYAXoimDgRAM2ijRHtd03sGjpyUuh821zn/YeOUoFStR0i/yIPtNnsH5fbBauAqAXR6cipK1S4XEO2+NOPZwV+g8AE4dWIj58mGpq3x/lxJgDWbuZcAASw0qtc7322utP3BZoAeIvey1SIBUCIelUbdFL5wtdg2HZ3HqBuL2nY6G8prjpPuojmDuTnmzInfavu6eZgGzj2kdNXKahAFRZUsb0mAJZ+ZQJgrmJ1nAuA1Vvb+Sg0EitJVkobWIJOnr/tUE74sqxav6OdaIwlx74Zi1r34QgSowVAPLwx8Bw8drbLN6NIsHpD1LTIFkteNwHwTsgDunj1BlsMXLl+m53Iw4G4N1GPlv6xUbVVQzWp83zyhHbAZNSVFebz58/VRGiEpbCI7/CbK6uAvYdPULpiTXiyad7fCM4xLOiePHWeF9Lqjbt4e/iqMucRFXgqAN5SA3KYl7tL6NMQGNy1hyD8V4hqARD3qU4ff+1wH4a13/c/27/8uP/wMVVtN1DtYy2s4FkAp/pb9hy27RGWEHQCvv0gunC0X/UJQQeuDRC1s+dn31A3NYnV88En/GYhP1h76yRUk1xEfIeIYE5Ychona2W77QHy8MZBPo7/XoH61Lz3F+z70Jxg3WgWxVwJgADRYIs36qUm9qf4fvnr6i38UgvCFsQS/A63ByjnmBmLHc7Hv+o+iojB2NYqf2e8SgEQogYiocKNgznheT178Z8cFRYCCyxJ4bsX9cb9Pm2xxtRvxDQWOs3pybNn1GbAl/xMNh7vdRAAcSyIy/DRCAEGfjLTFW9CDbsNpb0WohYsxYqrZ3l4nnG4Vq2EqBu3Q6hU495cZpQBwXcQhRgvSq3clyAYT3CVDqovhZXBlWD3sRqP4tgYfDvDWE5v8kN5ES25TNN+tM6JoIe2jGOwAMVLU9TNKqH/9P9iGmUo0Uz1D+26Qj/D/QbXdd7qnWiW6odW4+sTZy9pKyhM/Syy210fs6P+5vsbQF1nLfrDtldY2rn/KKUv2ZRdLJj3kQAgQsyiJvlmLkFxkgRSpVqt6PKNu6ERdENFQMBC4L+8HPgxgm48fqb+fsH/hvCH3/AkXafGCxlzlCFYxSFP+HK7peaSzpIukA347GsWxSBqtOjQn3bsPmDnvxdbMep/2Ad/h4TcpyPHT9OufYfp2ImzdE8VNOTRExo7+TvKlr8SiyUQb3SxhAXAdAVZ4LNuCxuhAqBzoc0MtoXwVrdZV7pi8/FsFv/QDs079KO3EzkKQBCQYLW3bd9xkz8+BLh4SZ9/NZPb1FWZdItA4JcyhwNmqzrex4UAWL1RN16u+rY67lsJs4Ty9ruZuSywGJ085yc7sQqCJfLaukfzvQhBFP3g01HT7MQzgIAfh09eVn2kEvtCNJfNFbC8zF+6Ph07e9XUXohQ/JIatunH22Db6CIAxlNtlShdPjpw7IpDW91UlazVrIedAMiBQDKIAOg1GJBWbPEBXbvpOAg2pvOXrvMyq6gIkoDBzOskAEY0bd51iPLW6MQ+Fa3K4QwM+vDW2JWzdh7QOVlyi+8wmHQlIGISX1u1Ac67eX8jGDBWbvWh22Umv63dovKCGPn6CIDeJEzkBo+d4/W5EoSYSlQKgPA1i/s3InAaE/xvVVL3m1b9Rzncn8fNXOJUhEJemMxbuT7oOXQS+aQvG7oMF1Y8EEHwvIGQhEk6vjNa+qBuOJYRLKHD/XD1pl22nMMSrMjga9S8D/CmnSB8QbyARaI5wZUELJeum6x83AmAqDOe13U7D6HPJ/3AwhrcWuA5g7Lhd+CTsRxHGcbyRWOCAAhrOW+DSLxKARCi3pBxc2xb2CcsUUxbtLGqX3nVRlVDzzPqjH4A8RPnafC42ZbP3NWbd/PgzyjKvS4CIFYmvKnaAuce+2EJu0+60tTxo68tXwC+/9E4Pu/GsngC9nEmRCFy7huZKoSW4a0smvAFq0RzevjwMS9HNY470PbOBLtBY2aGnjdnmK8vz/OrwvVCe9XpNIRf7prTsG/mhboeQJ+EOL9+u701LRJekGJZLa4R9Cf0K/Qv7Id7C46HNklbrInqj9ZuY/oMn8LtZ6xLZLe7/gIFddHvT0Zwn0TgNHPavu8IvVeoPreFeZ/wCMqC8Drjm7EYCxiNWnan2/ceOQqAatoBAVD7JI6yG/LohfrEslvte1jeXb5+i2o17cpCHgQKCEUNWvfh54xZDENiMe/fl+w78Pslf7CvPpQD+5eu2oymzFxAf2/by5F9Dx8/Q1eua2MB5HU35AENHDKGajR8n0pWaU611XH/UWOGLbsOUorMhdg6zVEsU/9OlYv8gtw8E5wKgI4Cmg5EnQKl6tDuff/YyqiVU6/3YzV3GzFmGi9Djm1hiQdfd6WqNKUzl27bCVr3VPueuXSL8pWqx0tkzfvpIE/4AETbadhbAGpkZOGO28G2nysBcMiob6ly3bZUs3FnqtGoUyj4d/P3P6CJ0xfTxZsP2D+hvi8EQNVVaPy3C/l8xk0WSInT5qUflqx0EOoQDORrtR38Axrr4gk4Nwh+8t2iP9hC1e74algzcoKa39ryjS4CIMS9bAWq0LlrIQ7lvHHnAZWv1dZeAISg7V/Mug9HkBgrAOIhnrFkc45A5yphQBmVftIwiIkJAiDaCZHpYDnnrVCKgT/qhaU8rtLSPzdq9bdqA/UdfnMX9XbKD8t4YOqwvwEMLqu3+4gnma4Soma+yuAZGPRHpgCIdPHKDfZ/GSscEyRBiGlEpQAIi2gEa7gdYr/08ogasMJaL3c1R2su+NzC8jurpagQk5Llq8tLW81p/JyfCS8n9Mk1rFgc9vfAchn3VAwC1mx2nMBj2d+bajJutZ83oB4QCWBRaEywBsSyPVhwYZmhMbkTAAHKrk/Unb30gcBZvEFPh+WwmJDUfH8wjxOs9nPGqxIAce4g/v5tIczcvf9APesH0xuZyqtz53gcgO9Rt1RFGtGBY47LHyEMwbrQ2N9fFwGQLQBN5wXPryL11MTV1E+QBnzxrdPz7wrk6UyIKsl+EsOembi+cIxG3Yc5jLFgjVmzw8d24yK0vSuLPS4vzp0ZHMv2acSj/EzbB5Zvwz6VzQluAHAfxHaoY6kmfejOvfu2X8PSzgPH2M8zzoXV/QWgrOi31dR4ysqlCoJpvMtCc9h1EZXtbgXKZ+U+BoHu0hRrHKF7viBEF+L7F2OxpmXnQXTv0XMHAdATnqlLcNT42RQ7cQAhuiwECgg7n3/1LV9TzgRALCF+oo53/MxlSpcNlohaVFwE08AS0sLlGlDV+u1Z6OvY8xPaf+gY73vj5l3KmLMs+fgkI5833iPfFDnp4NFT9NWkuRwIw8raTSOYfAPKqXq78AOo7knxM5ci3wxFyNe/aCjxMyhS5nTI853EWSlNQFH6dflaLpuxrvrfPy5dQe9lLGjnw80I2q1+i5504+4TO0ENPvP+WLeLEqbRrOms9sXS48Tp81Hm3OXdUJFSZClqJ2xaCYAaLzkq774jp2n/0TO0/0gYB46do1MXb7AYBlHP6NMPVn3X7z6mSnU7sGCJpeWpA4vTyg177MQzPahJ3Ra9eVmzsT6eAtGt3ydj7cQyCIDovz/9sTG0H74uAuDFm4+oeJUWLE5jeyNoAwSB+Wj4JAp58m/ofgBtde7yHcpRpHrocWD9Fx8+ADOVsO7DESRGCoDaxKAafTVjCS5NvjCdJUxysGwKy22s8ooo0V0AxFud42cv0tezl1L2iu3UYKoCW7pYlcEZGGDBeg9LV50l3D+x1ANWHJiI6G/CdfCdj3956vjROMuHjJ7OqAEyJptWE2odTCortvrQ7bLwFet28LGt8ogKokIARIJDbq2Py0BX+G8TlQIggkdh2aU5we8phCgswf1zg73vzn9f/suWOsZJrw7EpIS5a1la58CNQ6t+X1JidV3jnuytJZvOqxAAAeqPZwB81sHyGi+DijXsyRaGZZr1c7BU8kQABLjP49lg9RvA+a7f9TMHUQvLr3NU6eD1+XYmACL4BtoS1ksQHKzwyViel7N6IgDGUe1VsE43y+W/f+86yH7v4nkg8EI8GTHJsbxIsMjXgyiA11UAhMj0ZqbyVLpJH8tAN1jqbnX9uMMrIUqVAYJr2wGjHcYfj58+oyptB9qJcK4EuwGjppNPmlK8dN8KvHQ0i/feCoA4l1nKtuSgL+ZkFADxEqHLJ9bLf+GLD+Ux5msFypooT23LJdoItJa+RFO78VhUtrsVuK5EABT+6+gCYNseQ9iqz1sBENZ/x89c4ii5sPqDlRtEE/jQ+2GxFgzDnHDJ4rp98kyL3gurrXY9BrPfQOyH/WGtBqs1CCYA/uhqNepIJ05p966xk+ZQjkJVKGOOUtRr0EhVln+peccPQpd+WqLyZdFEjW2s2iIM3GeNqDFR1rIUP2UOTXyx5QdxKa7692cjJ4a6y9LvR/rn/oPw+1eXg4bYlcUA6tqq6yd054EW8VcXf2AlN2PBcoKQZhTujCDISfFKTenHX9fR4t/X0aLfrFmq5nvtew3l9tX3dS4Aakt5VXew5JEqF0St24YovBDfnqjfFvy8lpKmz89tg3JnyFGGNu06QvcNx4BweOFaiGqX+nzOjfXxFFjDNe0wkG7dD/MtiDLAh+K6bUcoQercajstYMnrIgCWr92BEBwnYZrcoSROm4fSBZWgtl0/Zv+FEFH1/dC+qM9fWw5R4nT5wkRg9AV1nflmKcN9M7KJkQIgBpf11KD/TojjW01jgpPzgnW6uh1ARIToLgDCoTysNJKoCYdxguINKFvvzyfbcrROt9W5yqMGuBAXE+aCbypH8DYnZ9UOPKh0lmDZgQmEq3OKwTGsTp668QEIHzoY2Ib3nHhLVAmAD9WEE37JorKfC0J0IKoEQEyCIbKvMi+lVWPDdh9+xdce7oNYrmdO8KGFFw0O9xn1b0SBxTJOq3RBPVPgwL5CiwGUNF89nii7EsOseFUCIIClTkVVVizng3US8obYUbn1h+oepUZDhuSpAOgK1A1WdwgMYk6bd//Dvr68vbc7EwCv3LhNVdoMZFciENGsKNWkLy9vtbKUMguAaJcaHQY7iHFIiAIP0ddYLmeg39VR4wKrpbMIjmC0cH9dBMD6XT7jZZ8QJ5EnlnHjuhw59UfMtmxbagl+g0s37eN1vweuhKgidbuHLilHG+HvZPnr8ss0c0IAnZxV32eRTs/blWCHIDoFanWmYg16WFKgVhdKqo5l7JueCoDYB/cilLdc8/6WFpOfjf8udHu0K4JemBMmudXaDvLoxQLOK66zafN/s+0dlnAdo08Zr+OobHcrRAAUBJsAmDgrtej4AYU8fMoCIHiubqlY3msl+ulgaTCs/ybN+JGFEs3XXnYWKpL7F6Dlqx2j1GviH9EzdS9BhGE9ci/8B2bKVZZiJ7G3dNP92sFH2lsJMlMrVc7Dx05xVPQ167bQ9O+W0t87DtDS5X9RQN6Kqi42KykrVBnZD6BFOzglZy1eNuybJi/XTxfi8AnBs1XH/nTV5vdPT7r4d+XqTWrYsjuXyZmAByBmte3xKYs+dhaAL4imfPerSwEQ+zZs1Yvw+hCmK3hKW4E0YeZStozT83IlAHoKRCqUWWVBuw6dplJVW3B5kT+EMf8cpdW46ojdMSCknb54k3IVqc7ip7E+noJ6N2jTn27dt4+aC5+VG3efoHfTqvOVPNtrIQCifa7ffUbT1LkcPOIb+nTUFI2Rk2nY6Gk0Uz0jT5yz30cHAmDPQaMdlw6nzEl+2TBOcydme0+MEwAxaISIsvvgce1KcJLwFr79wLEUSw2EvLVo84boLgDCOTwmSphwoLNYHdsV/HY4dy1a6SZq7fVbd2jYhHn02YTvaehEa/g3BSZbrtL3v6zhQZ2ztsRvAbwUy7XQtkv1Iby9flUDRBzHGwEQ1pnwBbXWzTJ3pCMnz3PeRssKQfivEVUCICaseKmAoErGhKWnAeXa8AQZVumF6nR1sADDi6is5VpbTmQxcYblnFUUSz3tO3ySxs5Yoib8AzhoAia8riygjeAe+aoEQAgFsQM0B/+68AQhAsE40P7GFFEBEMeC/zD4hA25p0auhoT7Zu9hU7T29vJ560wAfPr0GVtAYdyx+5A1eJ4cOHJKPaPtRSwkKwGwdf8vbb/aJ7xM0wUcd3C/bNCTxRVzwpjDKCS+LgJgmwGj2S9d+uJNKbBCGyrZuA99PGY2XTP5iURCpP7IjgJ8U12zEHOT56/HZcD1V755f/p61lK6ZyovEgRmdrJvKIMrwe7sxatqonRIlf2wI3sP0/J12zmSuHH1gav8PoBFYdpSfG/DOQCZSrWgBb+ts21hn2ARi/xQXlyLVq5ZYCGbqUxLvm/pZXAGzus7qi17DZtk2zssYYKM6OO47vXto7LdrRABUBDUXCxjcYqTOIDqNelCN+8+YPHv/qMXdMP2NwQ6K/EP4PebIQ+paoOOLFDoAiAEkUw5y9F2dR9GMj7ZoI3BWA4BRB6oIY+Wv+Lpc+r3yRi2RrQTOgxoFnfZqWbjTjR1xg80+/slNPyrb6ll5w8oa55yocKTU5IHkW/q3OSX3cPxA+a22atS/PQFeV9NANTyeidhFipUph6dOHVWr5ntU0sPHz2mj4eOY4EL5XYoiwGIWc3f/5Ct2cwWgPN/XsvHdCUA1m3enW7ee2a3rxmIS6Mnz/dKAIS4p2P1O9B++5fvm3Vb9OSAHnr+6Afps5eiDdv/sTsGxLOrdx5RicrNVb9xYbHpAhZNuw/hYxvLg365etN+8kuZi8vxOgiAYfyrzpE6x/jU/36k+j6uB3UtGbdFu+K7DdsPUYbg0hwlOLRcaN80+dQ4Fc8oEQBdgkkPKjRzkePD3pzmLFnJF72nE6Xw8roJgPA59dfWvaqz7eelZRt3HlCTL3UlOUmYLA2bOI/bybwsxRMgyMKx8/Vbd205WidM+DDxgKUCluZagd/4d7Wtq4RBMgaPVhNqgKV1ydUEw+x43pwQeQ5Wic7yiWy8EQAxGZo492cKUvVExL/LpjdTVmnSvGV8nKju84LwuhJVAiAsZWCJY05/bNjJv+O+DvAyBAMoY3qq7mkt+41iUcScL8AzBFbNF67csO1hnSASjpq2kArX7cb7eGIVhTK9KgEQQCwwOtiHWAFLI7NVXEQFQEz689ToxI7+zenvnQfJvySimnpvNeZMAIxoMguAeDHZZ/hU269h6aV6vLfoi+AlnrUL2i939U6WwbM0Ye71WgIMwQgvtOYsXUk/LFtLy9ZsYWEVztXNCdZtTXpiubD3y3+BMyEK9wKUGWWYr8oAQe7Q8bOWY6vzl67xslXz+XAl2LlLWA1RqG5Xuzxd5Tflh994jAWLP5ynrkMm8LWrL1UzplPnL/MydF81luP7UZ7atH6ro4uBK9dvsw9KVz4tjeC+2rSX430VqffnU7hv6NtGZbtbIQKgIKhxf6aSLJyVrdqcLly9zRZ9O/YdpRHjZlDIo+eaOKcew1bAShABIN/LVNhO5IJfvDzFa6l5pc3XqE0bw338ubr/PFZ5hgmL2t+YvW3e9Q8lSJnTqdgFEPDizQSZ1XbBlDCV2jZZEC8RxhJbV/tp4Pdg8gso78EyYO1334zFTXlo4lPawGK09DdtFYFu8WdM3y9cRikyFnBtkWgDAlL1+u/TpZsPVJuHiUAQzbbuO0GpA4qHikxmokoAxFJfPPGB+jl0G7MYiGP+snIzlanWkt5WZTH6KkSfgHi2bNVmFnuN+8Gy7f3ewzhAib69p6D84POxM+1EOV6GrDrS3IV/sKiNbV8nARD7YPkzlvky6m9zmQCWVKN9sEy6YZs+6hybRHGI0JlLedCHw0eMEQAxmMHAAr5CzNYE5oS39MGV2vNbS6u8IhMMUF4nARARE3NUbkd5a3TmCRIErkUrHJdXGBPEJryZ9Tb4B4ilBucDv5xhy+nVJAx82384Rk2irM8vBrWJ89ZlyxlXCW92KrUaYDd4jUo8FQBPq0F8l08mUJJ8dXjZH/oYRFp36e69B+xvTI8AKAj/NaJCAMQ9O3GeOmpC6Sg2wSeg3UQ+WxX2rWVOP/6+zqm1De5XsAZCtNP9R5xbAuoJbhtgRQX/WbA6tMpT51ULgGaiQgDEcypLmZb0+9ptttzC0tUbtzn4R3gjo78qARB9ZtCYWbZfwxJeyDXoNtQj4QOg/YIrt6fjZy7YcghLKzfu4vOvjzleBwHQ03TrTgh9Mm4O993wvtByJkR5muD3Ea41rM5FRATAS9dusmsau/uGi/wuXLnOYtaef07Q0dMXLCP/IqHvDFZtpuer3bdq0xY1GTenMxeveiUAwmIV/dIqfThqukcCoKfJVbtbIQKgINQkvyxlKVbSbBRUsIqa+5xirW7Rr6spXUBx2rbnCIt8ZuFP54X6bfQ3cx387kEALFK+obpfXKPn/75kF0yYfz19/i9b/kHwM1sWIhjI5Zv3KGWWom4t5iDKQGzRIwezRZTFdlZgX9/Mpd2LJ/g9aznytfL7p9pr9PgZvAwZySwA7lFjPm15q2f+7VAH+Ak8fMreBxysw27df0J1m/d0KpThGPVb9uT9YDEG4SjEJLYBbwXAg8fO06adB2jzroO08+BxFieRh1kAhIg1buoCetMvo8N5w3H8VPtNnrXYJp6FBQxBWZeu2Ki2C7YTDT0BQVNSZCpMKzfsthPadD+E/YeMp7dswUXcCYCFyzV0KwAiIM3W3UciwQLQNRD+kC/E12t3H9CgYRNV++W0ax8+d6nzaNZ/7vpwOIkxAiAGGPCdgqWOrtKdkAfUsNuwcIlZ4QGDlNdJAOyoBk7sgFpN7IBP+rJUqF53HlS5Shu2H1CDpSZqIOq51QQGj3jDDH9Lrzr9smqTbXLjWC5uY9XxF69wHVEYqe/wKU6FxMjGUwFw7tKV7BsHfQsXMMSBDMWbOVgWWaUtuw9RumJNwzWxFoToTlQIgJicI5KmWcSC2IRIqz6pS/L1yqQqwX6+zP5p8ZJFXypsdQx8j2u2YqsP2MUBlhG6Speu3qQ+6t6lCSTOnym4F8YkARCBWLD8cZ5qI3PCC5D+I6ZRXJVneJ+zzgRAtnhgK3VYq8OK3Qrtd6tkJQAiWIQ5QcRpzFZvnrUL2i9X1Q50wiKS9B8bdqj+HuZ7MjoIgLhOMZ6AVRnGFiizsQzeEF4hClGY4RqlRZ+RaoxjvTLClWCHfvDkyTPu82awlPzMhSs8ZjSe44gIikjonwt++0s9+5uQboGL8w4BEKtAzOn8leu8tNobC0CMq61Sf1OU5qhsdytEABT+86hnvF9gRYqTPJiSZchPy/5YT5gpboUv3DS56cPPxtHT584tAB8+eUF1W3TnKKbGyLsQQkpXb0kXrt6hx2r/x08h/L1gn38P1DTGLP7BbxssDw8cOUOJ0uX1WhDyhnjvBZFvRkRPdSWeqN+yVaL4qfOqfcLEP4gvEHuad+hP9x485PuFLv7pn+fVGKxOk870pp/nS1shUsHKb+X63fYWbQ9U26mhwZLf11OitHkshVG0dY1Gnejk+Vt05vItOnXpJl25/djBGtBjAVDtd/vhS+rz8TgqWKI2FS7XmEpWbUG9Bn1JJy7c5PNnFAEhWB0+eZFKVm7m6KcO5UuUlTr1Ha7VS9VH3w/lu3zrEVWs085yP6eosiMgTOM2/eiqysgo6sF68kbIEypbsy1Hocb2aLN02UtZCoA37jygYhUauxQA0RdTZS1Gew6fdSIAVvVYAESdrUBeaNcnqgvhXOw7epYGfPq1ugbzhuatoc6d+vSD9V8ULP3ViRECIKwckuatS4vdWLLhwh0/5yceAL2qZZAYxL1OAmCPoZN4MJZA7QOwL5bQwI+MfmNzluB/D4MlTweFqDv8YmHQ/qoTlgdhGbDV4A5tjLINGu3okN+cVm3aTX5q4mIlJEY2ngqAU+YtI0RENu6LN/BNenyuJlTq7uMmDRk3lydO4e1rghBdiQoBEJE0EdjCnLAspnH3z6lq24FUvd3HDCypW/X/0vJ5gCjorkQWlA0Wv6kKN6Lmvb/gCf05F0Ig/BHW7fypnZBgBveAmCIAIi9EgJ+9ZCW9NPnpefDwMfuRfVfVFQKg1f6e4EwADLn/kCaoscWYGYtprBPGzFhE0+b/zgKQOTn4AFR16agG5uaEWr0/aByLKMZyOQPnHn4nL151dBGx9I+N/NzQnwPRQQBEALDqql+grVz1a08IrxB1+MQ5KlKvO0d1Ni5nN+JKsPtry17uQ6Om/ejAlwqMSzKWam53L4qIAIiI4dN/XEGB5VvzOdLzxHlHZHIIweZ0S42fUhVu6PFYD/0VUaXNCf21i7qvGftrVLa7FSIACv95IADCx12qPBQ7cVYOSoCZ4u37mjCTOXc5OnryAotzZtEO/v+u33lIOYqoeROWKBoFwCQBVLXB+3T11j3e7gFb/GlC3yP1acxHB08XWIpBRAkTPCKOMeotkzyIfP2LWbcH0NskXQG1r70QCaGzZKUmHIQEyTw3fvrsGQ34ZAxhSbXZagvLOGGtZ09W/g3AqnDYmOks+EH4M4pGELXa9/iUhTI7X3AKCFwB+SpSx16fUae+w6hNt49pyYotDlZ9ngqAOB4EwCr1O5HPG8nIJ74/+cRNx+X98POJdP+JVjajCAhrvvk/r6FkGQpoglWKsPKhX+UuXpvOX71rJ8Bhf0QGhriZJrAER3zWy+UMnA+cg+wFqtLfJr+CaDP8e+POw5Q2Wwk+B9gH7ZMiSxE6dPIqWyvq24O7aocGrfs4jdAMURvHy1eyHl248cBebFR//3PyCmXKWTZUmHUnAKKdYKFoBuf8xr1ntPfIaZr23S9Up3l3XubrIIyiPyKITRT5/tOJ9gKgLub0+XyK5cDamODzLmPp5hEeOHoDjvU6CoDGfWEFkFIN9uAX0FW6duMOT2JhZWHc3xk4zhdTFtj2fvUJjt6dTVpQNjiff+RGnISgVqJhL4c2iwo8FQC/XfC73WSR981ejd5Vg/nZi/+wbeU8XVaTwdJN+6o6vRrLRkF4XYhsARDbp1WTSKugUxCdTp69pCbtl9n3lhFzIBCk39Zu5WvY3aQbE+g31fUPsQt1QYTMG058rK7frkUyd5ZnTBEAkU+64k3ZH9q/psE6LP9GqAExgllBSLHa31OcCYDnL99Qz9AGlCBXTUqYu5Ylfjlr2O7vjufeKggIgjVYBQzBi7g3M3t2XtAuENoeqr5oThAjPQ0CgmAq7gRA9Mvf/nJcdu2NAIjawsJv8R8baOmff9OV67e0H2wJ12mvYZPZt3B4x0o6zoSoJ0+fsqUZXigjQIa5jLDsbdh9GJ8jq3yBK8Hu43GzuT1wrZtJqPoPAnN4kx+WpyGK9sNHT9haBeIdVnTsOnCMvvtpFfsXhZhnHsOg/XBO4A/bnHA9Qjj2VGhD38E1Zk4vVP+F2xHjdReV7W6FCICCoOGboTAHtShRuRlHAkb6YemfFDtpEA35YhILgGZfgIjce/T0ZUodUIzimizTIAI1aNE9NJCIcT8rsPz32s0QqtmoM72TyGjxFAEgSKZGJNgwYZKByORKAMyh7n/+8PsHMSpMkIK4kyprYVq+UlshZhT/8DestGfPW0oJU+Zkn4QQb956NxMDMSpFpoKKQna8l7EgL5FF/hDYCpdvTFduPeTlvEaBDWLSvqPnqGaTbizgIU+jNSC+g5iIZbg+b6emYWO/42WkRvHJYwGQP9X9uXkvXtqN8kEIQ/2TqfKu+GsHb28WKe+qE9l9wEjeB+VD/SGCsripjvntvGW2MoXtB24/eE5jJs2j1IHF2WoP7RBaPpuQGDd5NpVfFs43MH8l+m7xShbT7PN5yXX89MtpvL+eB0RDLKP9469dvI9du6oh1/QfllGiNHk4b5xv+2NmVvUOpM+/muXgwxDHWrtxLyVLny/0WM4EQLTp9ZDntHT53zRl1mKaNvenMOYsoXFT5lHvwWOpUp12vAQexzWeXw11DNWf/bJF/fw82guAGNSUadqXzrpZwnr95l3Nj52Xg4eIEh0EQG3/ilS9/cfsgNpVQhh2THgx4DPnYQSiYurCjTxalhpVaeXfu3jSZ7VUBOX3L9WcDhxz71Pr+5/XqAtF28ecT2QSEQEQYGDNkwQLh+/mBEuNJPnqehTlTxBiCpEtAOKaq9lhsJqAO4o63qarN2+rZwV8f7l/8OM5AT92PhnL87K+nsMm05mLFuIAT+TVYNLJRD4mCIDII3WRRjT1h98cAh/gefbp13NZBMW5strfG/AMsBIAz6nne6I8tdjnLcpsBc5XxtItPBIAUVa8pIHLEnNa8uffXA5Pxgp4rnf+ZLxtT/s08Et732zIE2MpswCI1KDLUJdjJ70frd7k2I/wrMHLJk8EQIxfOgway+I2BN0hX89Rky7bj7YEH3nlm0fcN68zIQqRu3FNpynaiC3xrF6qHTp+hp/Vzl4muxLsBo6Zqa7bcqpPVLVAC95jPreu8kObD1F9HH6AEYgIwWOa9R7BLy7xYtdH3SOs7ik4BspvtRICE1342fNkvIx8wJ8bdtr2Dku4L+ao3N7uvhqV7W6FCICCoFD3Z9+sZSlucs1f2wqbj9xrt+5RiSrNKE1AMVq/ZS9bBhqtACEKbtl1iBKng8imCSA68NHWrG1vuhXy0K0ACL0RJjpLlq2h5P4FWCwy5hUuYPWXKjdb+sHHnP3vKv+Mxa3bAgRUJN8UEOXCygEBL3HaPDRmQtg90Wz9t+/gcSpboyWlylSY/LOXCiVdtuJUtHxDmjBlLk369gf65tt5zKTpP9Co8TMog9oG1n9owzhJg+ibmUscxC2IZhDPsAy1a//hFJivEpfJB4Jf/AyhJEydm8pUb0UrN+y1E6BAuARAlb9xafebfv5Us0lXOnfljsmaTwtase/weSpWsSmlzlqclzQnQJAWdSwIe8UqNqHTF286LHFGuW6rnSfNXkrla7ZR7ZyXRUQfX38NdUzsDyu+ag060g9LV6tjv2TR0ZgP6nDk9FXKV7oeC9B6mcHbav+BQyc6iKLI49zVO9Rr4EhKovrxG76qPfm4GfiY8P3XputgOnXxtl25AawXJ0xfRPGTaRaeOI4zARBtdenmY6pYtxMlSJGTkmQoEEridPn4PPj4qTrHS+8QSEXzP6lA1N9ANe5W16tlv41EorUAiAETBv6rNjoOOo0JkwK8NdfeQqqBqkVeUUV0EQBhIQImzP3FtqXzBCf2GDS58sGCAX61th85dUitJzgnX7F+O/35906vwLKVsxddi743boVQsQY92VrAXD5MRmIFVOEl4e4SfM90GzKBJ3eeDjz1PobzibZCwJk31EAcwqh5Wx1sFxEBEODc9lUTAERLdpWeqQl2FzUxRJ2MEzNBiMlEtgCIZ8rE79zfMz1NuHaN9yv9/mE8phlM0nHdw+ef1b2jdf8vedJttS/yj84CIOqVpmhjmqieW2bxDxZDH46ewfXzRjBwhTMBEM/35AVcB03Ay5aA8q09EgAh2EIE2X9EW4JkTHjBk7NKB7e+afn5rMqzbO0W255hCb4EK7QYQHEMzyPULV+Nzry8yZx6Dp1sOW7QwXMtfYmmtNcisBYC3BgtDYErAbBel0/ZXybaI1WRhhzUxpwgemEpPMpszNcbnAlRN26HUOG63cknQ1l+Zueq1oGOWQRRmb5wOS+htbo+XQl2EOlctaUV7vJDX8CYA/0P26Lcup9gq/x0UI7a73/icP0hwRIPdXPnay+WOgbcrVy3sEKGYJc4bx2+z4RuH4XtboUIgIKg4RtcjXzTQHjJSC07DaJHT5/zktxJMxfS2wkyUY1GnenyjTts9aeLgBAA16vxAUfi1cUKGxBg6jbrStfveGYB+ODJc6rZpAuLH8Z8wg1Ek/SFOIKvuWy8BNhZBFX1nW+WMmobw/JdBUSzzr2H0L37D7WbhEU6pJ7Jc374mRYsWUE/Gpi/6Hdau36rwwsrJLyIRLRkCKY4FqwwcxSuTrsOnmJBzWitBlEOy0XPXwuhJerZ2XfwWKrWsDMVKluPilVoRE07fEBfTZ5H2/cfp9sP/+Xtw/aNHAEQ+72bOjeN/3ahg5h2674myq3asJtmq7noxBmLKXvBKmzJph9v+LjZ7OuOlxkb6qZHxt229xiN+eZ7at7xQypRuQkVLFOXytZoRW27DaYpak6+95/TXFb4+gu1QFT5QGC7pxpn4OcTKbZNTNXLDGBVWKB0PTp92V7I0wXIM5du0tjJ31PNxl35mKWrNqM2XT5W/X9xqJgXejwFjnf97hNq0Ko3i376cVwLgI+oaKVm5BMvLZfHCPaD6Gdsaw1VD9QlbQGb+Gfqr1FEtBUAMdDBg/uz8XNtl5jzhIkMojRiQIQBT0RwJeBYEV0EQICyBpRvw75WXKXrt+5QtXaD+JhW+QAMQsfNXmLbwzqhrANGfktZy7TkAaQ3BJRrZRlN05wGjZnpdPKL+pZs1JvumSYhVun8pevUZsCXvEwHk2KtL2gDR1gHAp6sq0ke8kXbYOAJ3ziYpGQq04IqtxlImUq3cGp1h/wiKgDiWCkKNqBVG3fZtnaejp46T7mrve+0fQQhphGZAiD8yKYu0piX+VolLN/EclRLLJZ2IsENA5YA6s8BWDCjTCg37jmYjJtfYOEhjvsN7h1w3m9OiBju7BqPzgIgRI8UhRqw+GcOrnHp2i3qqZ51GCfgfow6ot2MWOXpjlclAPoqUPeZixytoCDeYbklzjmes479Qeub8BdZrf0g9k9oTsdOX+DnhLG86FsQU+E70px+WrVJ1b0K90WrtoNgU77FAMsVBGNnLlFltX9euRIAG/fQgpygHtgP45o7Fvl+NGYWn4/wjplcCVEYF+jXDD67fTqR+6QxPXj0mFr1G0XvmOoGXqUAiDEO2h9tAeHYOC4x52MG+2Qt24qOqLGAOV2/eYcqt/6QLQjjWd13FLAsxLmC70KrNGHuz6pMqjyGcxSV7W6FCICCYCBjCRYiAvNWUHO9C2yVd+TkBQrKX0l9H0Ajx8+iJ8//pSfqB4iAugVgknRhSyB1sH3Fuu3o8o0QlwIg8kGU4VUbdpJfKvtopxEiRXaObuybvgixNaDpd9+Aci4EwNKa6GLbNrYqk3+OUnTwiONLLD2ZrQE9TfBJn6dYzVCLNbQj2g7+/q7efsA+E43CE8QuCGAQZ+88ekn/nLxIm3buo617DtLZK3fosSoGREJNYIt8ARBAsILfRyxJ1kQu7Tj68RDIAq1x/e5jKlu1eWjd4Bcyq+pbq//ew2XUj6fvC5EM+7LIeTWEtu07TBt37GU3OpdvPiCMjuArz1g3fOLfEEvn/7yarQSNgpwO6uun6jF5tmZdaS+sqv3RF9X3h09f5mPuPHCUox6jLPheP5a+veq2tHzNdkpliljtTgAsVb01L+81ls0BnBu9/6lrAsvzEZDmVVj+6URbARATolrvf0LXbt7G9eU0wYcMJhLJ89fnZSURAUtSEBnNG4spDI6iiwAIMODuOGic2yVtiByXvngzrp85D0w+sPQEkwxX6ao6N3lrdiKf9GX4fHoD9indpI/l5MaYtuw5zOKv1cQM5xHtvUC1vSfpgjqHI6cuoBKNenL9UE8s5cEba/CmmnDFy1aN+wiW5eWv1YUadf+cB/zwaQTBrVq7j1T7W0/GMRCNqAAIMHCuqCZXVhM5c5q9+E9bkJPw9TtBiE7g3uFMAMxatrVmfaKuY1foohSWdNbvOtThnosJK/xqYWKOJYxW4LdFK9bb9ghLmNzC0T2OAcGoYO2uNHD0DCrXvB/78Yql7t245+A+DUtB3H8hpvikK0Plm/enm7ftrXAwQMM9CAKCuS1AdBUAcY9De0BcevosTCDAAH3fkVMsqKFNfNKVZgEDQZPM4Fx6GwzsVQmAANbZcMthZZ11+dpNDgSD7fT+gDZBe0IMwmfR+t1p0y5H6zmkkVMWqLa1f5mJvoAXXOu27bNtFZbgV67W+x/zOAPPOeSP4+G4aN9kBerTd2pgbk4IxoL+Zx4neCIAYjvN+qw6Tfr+V9sWYQlLgZHHO6pMxrw9BeX3RIhCH4G7DKtgGYeOn6Xgyu1Dt9VxJdjhpScig5vvK6HYzp+n+YVHUNTBGAh9etws65e1G7bt53sQ9ytVNrvz7l+ORcZ2H4xha1tzghUNxiHmFRhR2e5WiAAoCDo1yS9bRYqbIgdH/523+A96pgYJj9UztGv/z3lpYsbg0ur75SzYQdSDEBjqA9Ak3EHEKlmlGZ2/cpu3sxL/NF7SwyfPqFXngWx9aMwj3EDwQ6CEbJXIl30AGsRJ/J0qF/llV/cHpwJgGbt9UJd8JWu5XbUWnnTj1h3KbRAAAQQlLIMdMnIKXbl5jx6p9tOFJCOwmoMQhVEO4O0shD+dyBIAwVsJMlGHHp/SNXVALMc1HgdA8Dp7+Q6VrtzUrm4QD8vWbEWbdh5mcQ3bGffTBT2IdOo/rpcqGgt0+u/G7WE1iHyWr9lKuYqpe/q7mVSd7MuqAyvLQmUb0P5jF1hoNIqA+nHxPY6JY1sJfwBlO3XxBlWu9z7FSmhvsRo+AVCdC1icKrTzoto7dW6KD+FP9UU/jEVfofgHoqUAiIFB5tItaNuew+r0uU5whgzBBJEB5yyNGN+rAW71Dh/zgMmqXFZENwEQg6EkeeuwA253CVYI8YKq2wbpYXlgUFa7E8qirjIXCct4EqljuZo0OQNvpFMVaUTb9x2x5WadMHnCoNJKqASYZCFS8YUrN2x7uE/b9x9RfWo59f9impqEjaC6nYbwsiW8le79+RSOCIq+sl5NpMzLlOt0/pTPlVVZIksARB/C8uavZy3lCZir9PDRY2rQdWi4JxCCEJ1wJgBi2WPTXiOoRKNe/GLBFViaiWcQngNWDvTxzAks34Z80pTiSbIlqUtS8UY9OUCFOQ37Zh5fjxC/cF9BcKs9h47T9B+Xs18uiIE5qnSg9MWbUopCDbk8JRv3piV/qHu26XKHdTOWi8Z3MsmNjgIg2h4vYMbNXGK535T5v6nz1JfKNu1LFVt9QBVbDnCgUqsPuc3ciXZmXqUAiHOTMHctWqYGvVbp7IWr9On476is6g/ZK7dT/aAFBVZoQ0Xr9aBOH31NW/b8Y9vSPp08d4nyVOvoIArjeHECq1LfEVNtW9qnfYdPEiIQF6zTlbKWa8X9Dtb4FVt/QBPn/mwZUOvoqQuUplgTizGCZwIggJUZnot4gWZOazbvodSqL3garMKIp0IUgCV/+Rb9LYWu6QtXaEtSMYC3be9KsEOgmmL1ujvcV3TghxHXrJ6Xu/wiIgAC5J1fjVEhqFolWCXjpXBhVWasEEE/Q3+roK4jRNbGi1GrtGDZWkqMAEQW48OoancrRAAUBJ2aHFkUwhlEjB4ffEGPn/7LYsif67ZTorT5OEpr5jzlacWaLWz9B/Hpxp2HFFy4GiHSq1HUQB6FytanMxeuuxQAsaT40LGzbBkGoc2YR3iIBzElRU7yC1TPzIBy6u8c2nf6NhBY0hUIq7O5HZwIgHlL1KTbIfe0G0QkJisBECCgR8K0eWjwiEl04fptXm4Lwc9suXYbqO/M31sRmQIgyps0fX6a/9Ma7gfYz1gGZwIg/Ey++W5mKlerDW3YdoCXA+sCp0PdbPWy+h5lxH4hT16q/riVCpapzz77jOeNz7vB+hO/4Vy26/6pKtsNB2tCY/4Ox8Sn2hbn4cqt+9Tn47EclCSeg/AdDgEwZU6KnzoP+/jD9eebqYTqu+U14Y/76KsV/0C0EwCxNAZvhL+cttB2ablOWC4TmQmWG1Y+5ZwR3QRAAFEMvvMuX7OPwGdO8PnC0QENExeeRKhJktXSJXPC5AVvko3H9gbUA0tM3KVRU390WmeUF5OHAaOmW/o+cpUQde/G7bt06eoNunL9Jt26E8Jvj5wJnzg3OEdRLQACDOoDyrWmPf84Ric1J1hJYmmyM2sbQYgpOBMAsSwX/qp2HzpOew6dcM4/J6hV/y/V9VeBsqnJ8ImzF205hKVfVm/m5xT722HrWkfgSgIWbNv2Or7AwPWYspAWtbPW+4PtRPw79+5z4CL4Qf3h17U0S91nFy1fz+KM1eoURNPEUj0c06o9cP+LbgIgJvwffTWLA5yYE573B9V5PKjaCK4sDp84a8mRk+dY2MhVtQM/r8zHcMarFAABxg+lmvTlyNFWCb5eUVf40UU06F9V38NLMasls0h4ZvX47BtVD/uovDpoCzw3dllEtUa6ffc+bdp1SPWNTdzvlq/bRodVW1otjULZ8ELMKgCFNwIgQD/pPHg8W+oaE64NBMDAM9zbF4neCFG4TuKptvliygLbVmEJkXfbfTiG3s4adv5cCXYX1Xhh18Fj1vcXBa7lsTOW8HWpjwejUgAEOO8fqjGQuX31hCVsW9V9Cfc29DP0N1xHznwNHz97iQVD63Mfde1uhQiAgmAA95XMpVnEKFahiZrH3WPx7s69h1Spbju2rkKAhMLlGtAvK9azheBzNa1s2KoPC0UcddcghEAY/Ec9ayHyWYl/AHnMW7ScEqbJzcKUnTASDiD6+PoX5fr4ZsDyX8c8fTOXdG5RhTawEgCL12Sf75GdMDe0EgABhCJYAnb9YASt23qA7j5+yWIbrN6M4pQZCE/sZ09tZwQWbWPU/dIsAP64bD09hBhm2w5iFXAlAAKUuWSV5rRj3wm20mPffLY87qnjn7sa4iAAAliLvqXqVlL9NuOHZXT68i2uF8oHQc6qToz6TbcOxNPo+Nlr9M2sRexD8Y0EmezOGYiHQC4pcqq/w77n5brJg6lj72G09/AZtvjTrQtdgYAfKOORU5do4LBvOHiHY5TeMAHwxPkb9AD1MbTHldtPqFT1No4CYPrCmmAdpJ7V2THmVH2T++erF/50op0AiEF0igL1aeeBY3xhver04ZczYrwAiMkr/KsMm/i9g2N1c9qy+x+2RNGtIjFQzaomEO6W/2LChyh13lhTmoGVGybI7uqMvoJgMc78N2LygKUmsxb/GemCsTG9SgEQ4Dy37DfK6aBeT5i7jZq2kPuqM6FAEGICzgRAb9IHX07npcKt+49iocqcEMjDk+sT94Hxsx2DEMFqr5LN91YdWFJbKXseJFgfw3oGIlpMEQAh8sBH4iaLwBDeJry8KVQXUZc9f/HxqgVAnB+0QZsBX6pjuA565S7df/iYvp69lK2yXPkyxnO1Ufdh/GIrIglLguGjkX3AORzDOwEQeSTJW5cFbXO6cv02VWz1oVshyIw3QhSAaOpfshmLdOYEQRnRbvWxoSvBzpP0+9qtr1QAxBgoWf569M13vzhci94mlLFFn5FOX+5GZbtbIQKgIBjAfSWoCsV7Lwcly1CAtu4+zOId0tgp81jcgKAGyyf4gJs1/xcek8yY9wtbrBmDbUD0SZ+tBG3ZecCpAAj/f4+f/csRWOEfzk4UCRdBbEmFOvhlq0y+KXOZBED1t/rOF0ILCyxO2sDSArBWmFspDLsiA5VuqXub0QegGbRrnGRBHEH3y2++p73/nOIAGLBeA7CC04F1Grh5/xmLTQh2YQTLtsdO/dFBAFz023peRqtv98D2WRcCYALnAiDywHmr0bAT/b5mK1259TB0f4hqF65ZC4D6voj0m1b1kXY9h9DSFX/z9hABUQdjvULrp9oM4t/ZK7dp8e/rOVBIqqxF2DLVPtCLqp8qs2+mkoRoz6ECoM0aEP0YbYCAHz/++hddvnlfy998PGAry/WQx7Rs5RZq3LYfB0Hha8HumGF55ypeh06e1ywM9TZFe1xV56R0jbYOAqBvxhKq39Wy8f8V/nSipQCIJR/b3Cz9jKr0wajpMV4ABBh0ZSjRlP72YKKFJa++2bWlwBi8teg7kp4+U1ewi3TgyGleahze+gIcL3XRRmqie9WWq3WCX60a7T9m/1lW+QCUG0tb5v60ym0E3fCmVy0AYjKKCxxv7N2la7fu8FIy8+BbEGISEACx1Dciqd8XU/keutTCTcLDh485kiom1FbHN4JnQ80OH9NLC4th+LZDWRFsKXQU6UWC9VvrAaN56a+reyx+wz3ir617bXuGpV9Wb3olAmD1dh/xMmdjgkUZ2sZKAEyYuxZb70U0wRILz2ZvBcARk+bbcghLl67e9FgAtLJcbN5npNN7OvaDANZGnU9YjIbnJRWW/X464Tu2OnVXX9QB/QYiDSxevU0QVvEyLUvZVixcWR0DAiBEu0eP1KjZlCDQW5URz6YSjXqzX2dz+nvHATVeacaWbOb9nIH8On40zpZDWIKVY6nGjkIUgKiFcxVy3164RPr+pzXs/xfnSxfszC5APE0//7nRUgC0ym/wuDkRFgABrjWMrUeqyeNpJxanrhLGTVv3HubzB4EZS/WtjhOV7W7eD+C6mrXYcUUKrFxFABT+q/imL0zvJMxMX0+bzyMMLPfduvsfSpGpEFtvQbyB6JImoDgNGvo1Tf1uCSVXvxktojQ/dnlo+aq/2crPSgCE/7+bd+4TorzGShTR6L9BbIHIPtNUHeJbWv8FkW+6wnaBhxzAvdUkAKIuGYJL0oivptC4iTNo7ITIAXl9/uU3lD6opKU1mU6cpNm4vd9Nk5sq1m5Hn4+dSYt+XUsbth1U96qTdOD4efU8Pk0btx+iJb+tp49HTKLeH4+lL8fPpFFfzwhl9IRZVLd5DwcBsG3Xj+iribNDt/tyvPaZp3htTegyCYBGsQ394e13M1NQwarUbcBIGqn2Hc3HmkmfjpxEAXkquKwbLAHfSpiFMuUuTx16fkZTZi+h39dsoy07D7OFHuq2+9Bp2rzzH1q2eitNmbWI2nQdTJlylmOLVPgUDM0vOcqWTStvhqLaeVb3fiz51n63P6fY3z+4LHXpN5zmLFxO6zbvp10HtPZEtOEtuw7Tyg27ac6Pv1PfwWMoqEAVXmbsaqk68k2XvTR9PGw8jZk4y9D2M2nY6GkUmL+ygyCKSNWWffH/SLQUADGIxUDj/5H+KwIggBUgfNa5i5KL5SF1O32qBlqV+Pws+M19UA1McLXBevjqq4OJ0fc/r7Hl6jxNmPuTw2TSDAaZ/iWbsz+jqzdcL38Ob6rbJep9ABqB6ImgAu5EUiQs63mvQH1uU6u8BCG6A1EN1lYRSbDwg7hxN8RxqQj80iKQgif3cQgtsEy2mtQjsjCec1iOiesd1iqYILtKsNY+fuYiP0tgGR07oIrTSbiOLgCu3+4Y+GHZmi2vRACEpZ/VElIEnXAmAELwiWiC5RgCHHgrAI6c7LgcEXl5JgC2oRcmsRMJL81c3dMhKEMYKdagF7u92L7vKIVY+I80JlhywUJq3i9r2KIP+ViJK1bgWYRxDvy8Qcw7eOwMPXmiZnMuEgTVlX/v4ujL6NfOnnMAAiD8MD61yBMCvdU5wcoE+IRD9H8ry1sIVwktfM45A20Bn5rmFHLvoVMhCucQ1/ecpatsW4cl9OHun37D27BgV6MjXbgcPivKX7F03ywAOsnvk6/nRooACJAP/Oo17TmCl3gfOXmenrl5mYvIzxCmx8xYrPpnD9WPK7gU1aKy3a3uu7iu5iz507Z1WIK4LQKg8J9E3VvgOw/iSKM2vemxusZhOXbx2i0qXLYBB1LQhQuIIRCTYB2XPGNBFoP03yAwxUoSSNO//0ULUKGmLmYBEMuLj5++RBmyl7IUiSA0+WIZp+l7p2QootUhoALB959R8GHBK0VO8oVvNVcWVhYCoIb6NwdqwGfk4Gv7dDgWW6qp73XLNRsQuxCMBe2SKVc5XkJbo1Enqt+6N9Vq2pVKVWlGWfNU5HPydqKsofmHYSu/KV/7bWzlgUho3EYnZU62rjPmge3f8PNni754aj+7vIz7GjH45kO/wf4+fhnZ8jRnkZpUrkZLqtOsG9etZpMuVLZ6C8pRuAYlSZ+fl6G/kSCjfX/DJ4JoqPPu61/c3n9eUCWKnza/2g5lti83ljjDJ2GawOJUpHwjqt6wIx+ztmrPCjVbUf5S9Sh11mL09rtZ6A11XOMxnaGVBfU39Re9bUzbiwAYCeAhj4nRdjUh+n+k8C4BvnjV2sGynn78fR1vHxEBsHanIbbcnKeewyZ7PFhEWdDeVssnzAlvUzEpzlSqBfsGdJVgAVG385BIGbQijzYDxlhOII0JPpQQydnVhBi+spDfe2oi12HQVxzEw8pJf3gTRGAsyXM2CdMFwKdPXQ+4Z/y43GMBEOcQfRCRRN210TM1Me0/Yhpvr/naFISYBV465KvZmUZMmR9usEwtW8U2NGLyAoffGnYd6vU9vMPAsQ75wP1CuuJN+J4AUR4T465DJtDo6QvZ999va7fRqo27WHD5dfUWFmk++XoOBw1JXbQxT8LdiX8AZcUgoP3ArxzK0LLvSKcWXJEFhK3gyu24vsZjfz5pHuWo0p6fP8btUV4sAYZ1mnH78PDhqBnkX6q5VwIA2rRKm4GWeSXKU9vluYc4mKpwIxo6wb6uABHj3VmvIe831XlFVHv0QfjywxJyCDUr1u+gNZt2c3/4adUmFo0xVqnZYTAfE8K3t+42UFf0o5QFG1DVtoM4v2kq36UrN/HxEMRr+V/b+QXcF5PRX0bx8wvCs7NnnA4sxLJVaMt+gI3tMHzyD5RXXZ/Ozgn6C/zVDh47226/L6YuoD7Dp1CKgvU9FgBRBgRMMeYDsKQWYxlny6TxfMxTvZMqq/1+X367kNp/+BVvg/KnL9GUI/4at/EULKE1CoCu8ivfYgC3i7GMEQH9BOOLdMWbUs33B7O/TfQnLL9G/8J5x/lf+Pt6+lr1v27qvlS8QS8WDtFfXF0DICrb3erYuK4Q6My4D+g6ZCIlzV/X4/4iCDEGCIBBlSlWsuwUXLAynbpwlS0A7z16Rk07qDlKIvuljxDuWPixEDbwfe+PRrOfQCsBEMLgH39tY5HPav94KbKTb9oCWpAE0292QExKk1eVG2OS6hQ/bUHT7zYhCsKQVZ2NOBEA8e84SQLZeisyQZ7GY6GcXNZUuVlow2/8bwO69RqsAiHCYkn2WxCoWMzKxL9DqOL8k4aBf1sJWHHV9rEN2zFqW8dzAgtKdT7SF6L46twYxSwsE0be2A95cX6muoWh6sWf9vtjW4icWHYMgQ91g3UgPrlu6jv8ju2My5LjQfjD36lz87JfBwvPnIrAimGWgBDibPv6qnqg3FiW6wMR09Ce6L84Jn6LkyzQ7pgoezxVD6u+ifLhHBjb3+pc64gAGAnoAqAzJ9VRnQaO9j4ICKwMYCHgKi1asYG3dzd4cgYEwDqd3QuAvT6f4pXwhoF8zmod2NG3uwT/Qv2+mGb7l/N07PRFyqoGet5OSKyIxYPCjpbLgszJ2dIiM9gGE608NTqqAfd0Wr5uO504e8lhmZq7BKHzjHqwrt++nyNTYoIEywhnE3MM8rOrCZQ7v4uzFq3wWAAEGFAjaqYnVjOXrt2gInW7ReqEQhBeFyDyo2/7ZCwXbiCK4T79RsbyDr/BQsnquK6AlZ05H3znqwa5OA7uwbgf+ajjIUATBB1YkgVXbk/ZK7XnSTMmsm9kKs/74f7u7XME1s/mMqAuCTCostg+MsF9D3WzP355/t5qewBBy35770FbhWfyj+eDVV6evDSBCIjzZN4fz0JP2xrHh9DCbaSeJRCGgiq0pZxV36fsqk9AIEuUuzb3z/D2ByN6/0N+sL6EqwwcD5GoEe1aD1iD33FezKKtM7CdY1uUd/vsQaRibGe/XzmXLj6cgXY354MyuRPP0Sbm/YBxfBE/uBq3v9V27rAaoznLz5Mxjbegv/A5VcdDe6A/ZSrTku856Gc4/xAItXupOu+qf3jzsiAq290Mriur/TCGish1IQjRl5ocDTheqryUOF0eWrVhBwt1sNYbOHQsCzxmAcMZWEZcqmpzevD4uYP4B/9/MDsYO/l7tZ2T5b8pgii+fxGKnzqv9e8Aggqi/mYtq4l3mUuq741CjW2btPk1qzAInJb1tuFEAHxVsAAIoSljcfYNB5+GLG6hPBZlQjkhYL2S8qpjYHk4BGIOsKJbWXp1bLUtRF0WEV3vjzohyq5V3biddAs7WHaq/GC5qp1Hi3OM8x6kxjsoN0Q7/bgGS0TOV32nt6cmUprQ90upyp6hMC81ttzOC0QAjAQwgIbvOFhDIDLYq6Zss35O31BagUkMrAwGfjnDMj8dLHvB9uEdkGBAlbNqBxoxeb5l/jolG/fxWtzBMqyG3YdZ5qczcuoC6jdiKkdls/rdSPuBY3lQFhkBJ9Af4P+l25CJlsfSwfKgqu0GubWy0MF5wGAbdccEu16Xz9jqAJY2f6qHJYKfYAnJgaOnGTio3rH/CK3ftp+W/rmRps7/nQaNnsmiY94anXiCzoNkF8fHZDRN0UY0bOI8yzro1O/6mcf10MEAGNaHVvkZGT19EVsbyTJgISaD6zu8uMzDcAxPscxH4biddr/DZBfiHMQWgIk67lNW+3iK+dga1ttGBVbHt9pOx2r78GCVtydEJK+I7GsE5wciGu7tEHAhaKA/QAjDeCC8+ToD+SFf5K8fD4E30B/DX4fwtYXVfsBqW3eENx+r/YAn27jH/liu8rPaLjLBMXDecZ/hew7OO/qZ6nfof+G9T5jrAay2M2O1H7DaVgf3ZW/3EYQYjbpufdMXYaulidPn04uXmh/ASTMWkB+LFkEOIoYVsCxLmbkwHTt92SEQiC4AdhswnC2trPZni7P0hTXRxvKY+E6RsZhW7qzlKB4Cfxi25WW0KXOSHy/9rWVfTyt0ATAZLOVs+b9iICihDDgPKDeiGsMiUK+zJogp+NO+Pfg3u98jEdUmvukKqnaqqYmpmUtT/DT5KNRHIPvgs9jPSHKVB4JeINpt5lLa0lwIgbbyhwpsvL2hbrbv9PqxAIj90hZQ+ai2Qn4eiLtcbtW22A/l1trSJiSaj8n/1o6n/Y7vVF3T5uO6Qyj3zVLWvs29Re3nq/ff14hoJwACPLgxCMEb51eNOz9yVkDY0d+gO8Pqja83wLIFYqNV3ka8FY6Ap+2NOmAyYPWbEeQVmYMvbl+8qbY4ViiZVN2DqnI7WeVhhdamVXmwi/wBxEZY6RWu253KNO3LkQzhJ6l0k75UvGFPFvsylmrO/mpwTJx3CH+xAyt7VGdsY1l+Axh4h8cyh9/oW+RnB97CR+K5EQRBEARBEAThNQECYKbibJnXZ9BIevL8JVsB/vLH35Q4XV7LZaTOwJLhWT8s0/wAGgRA8PTfl1SvRU9e7mm1L5MmH1v3mQNRaASxgAQhBlZpbC0XKtRosJgWamHlRiACEAADyvGSYgg9/x/ycxm4vCxqqXmX+jd82/EyVlVPtmLjNtHbRX3i3/g+lfod+UCcc8g7Aqg2sW9LRbZKWrRdiLTpClrvZ0SVyTdLaa1eIKgK+UJIhDUdfoeAC2HPSd3ipc6t5aGOh/38skN3gbCL8pjOpSU4rtqej6vKDUvENFbtqeBjqrKgTKlV3dW2vMQYUaRt+cCnZPw0+blMDnX1BNQF9bAs6/+PaCkAAggl/w+syuIJVnnZY72ft1jnHYbVPp6A8lnlF16sjhERrI7hgMV+3oA8IABDEIRQ5uNfjnwyKPAJMmpLYYwWGJFpERSG9X6eYJ2fPVb7CYIgCIIgCIIQzcEqrCyl2d8a/P7df/KCBbzte49SmmwlPFoGrAewwDLgJu360t0HTziYiC7+4e+bIY+odLWWHAnWvH8oWN4bWJ7FlzArLKD+TpVL/VaBrbpYfDIv58Q26QppVl9W9XRGcFUWtv4vBNo+YdFmLBPEMszvgipr1ozq/PhmLsGCnK9/Me0zUwktCjJ+1/OJbHB8u3LZxDebCMvbuDo2fmPRTt8fwp1tf/yWtZyqVymuC+oUX68b6ppZ1RnCaGBFbXsW4bC/oTyegv0A2hl9SLUbBMGw9lSgPVVZUCY+Jp8T7GewJEU5rOrpKeb2eE2ItgKgIPw/gXUgrPDsMG0jCIIgCIIgCILw2qAm/7BsipUkgCrX70C3Qh7xEuATZy5TUN6KDoFArNCX3sZOGkhZcpejHfuO2S0Dxt/nLt2g3EVrUKyELvKDqAcxCJZWbKGF5Z9a3mw5BeMEWJ9hu9AlqPgbAmFuTWCBaGNVT1dA5Pl/YlUmXfAC/De+0w0z9E99G4s8IwNnbamXy2ofM1Z5hO5r/M1QN66rfgwbodtFgNC89E+zoYvte4eyGTDWLTw4a9P/IyIACoIgCIIgCIIgCEKMpyb5ZqtAcZIFUaEy9Viog2B36fodylukBlsGOgh1DgRrARJS5+ZluSPHz2Rfgg9t0YCR3+nzlylHgcoUK7ErATCI4qfOx1ZSvEwUEV9T5tIss3JUZ3GQgzAYrQNtf8OSzLp+giC4QgRAQRAEQRAEQRAEQYjx1OTliXGTZ6fMucrQgSOn6OkLovuPn1HVhh3JJ36GMLHNGSmC2WoPvgRjJcpMRco3opt3HoQuA4YAeObCVcpVsKprAdBm0cd+41SZ4qctoFn+5cQy5TLsoy3U6k8HAiD8tbHbotfPukoQXndEABQEQRAEQRAEQRCE/wLZq1LcFDkpaYYCtGbTLhbsYME3+8ffKGHq3JoVYAqD6GYGAiD756vKQRTiJs1Gsxf8FhoMBILi5et3qWCZBvS2O4tCCHpp8mn+1nTfeIHlbZFxTeIf/p06r+aPztmSTUEQXCICoCAIgiAIgiAIgiD8J6hO8VIj4m8Qzf7hF3puE+7uPHhMA4eOoyTp85GPr7/ziMApsmtBFCDCZSpJb72biYpXakIXr95i8Q+WgPfU/yrV60hvqt8s87DBS3xVfn5Zy2g+02AJiCi9VuJfimAtEIZY/glCuBEBUBAEQRAEQRAEQRD+I/hmKERvJ8xMH342lgXAh09f0rOXRNdvh9CYiXOoQKm6LLxZi4BBFD9VTvLLXJL8Aiqw374EKYNpxvc/0XOVB8RE5Nm4bV96I0FGi/3DiIfgHrACTFuArQDh289qOz5m+kIcGMSqPoIgeIYIgIIgCIIgCIIgCILwH8E3Y3F6K0FGqtG4C917/IwePXvJIiCW8SIq8LrNe6lBq170jlMffppFnm+GouTrX5RiJcpCFWq1oWs3Q+iJykRlQa06D6Q3Eri2AAQsAMK6L2tZ8g2ooP7OGRb4AxGAWXDMTX7ZKqqyi/WfIEQEEQAFQRAEQRAEQRAE4b9Azprkm7UcxU4cQFlyl6OjJy+y5R6i+EIEhICn/kn7D5+ifKXqOA/kwcJdDl4OHDdlTkqQMgfN/fE3taeWWnUZ5NYCMBTdFyD8CiLCcHLN8hAWgrzkmKP+ivgnCBFFBEBBEARBEARBEARB+C8AARBCW6o8FDdpIE2auZAFO1j+PXxG9OAprAH5K5o0ayEH+WArPQfRDp/ZyBdReeELMGEmjgh8+Pg5evj4GVWq257eVN857GcF5x+k+fgLqsLBPvAdC4Fp8nJ5RQAUhIgjAqAgCIIgCIIgCIIg/IfwzViM3kmYhfKXqkur1u2gh0+es+UfgA8/pF37j1KyDAVYKLQS7iAM+vLy3MoUL01eiq22a9S2Dw0ZOZlSBxSjOM4CiVhiswIMqkJ+AeUpfrpCnLcWcMS6DoIgeIcIgIIgCIIgCIIgCILwn6Em+QZWoPgpc9I7ibJQnuK1aPhX39LqDdvp8PEzdOHaTQq5/5DmL/2T/NQ2lhaACl6iq373C67K1ntx1XaxEgfw97GTWIuGzlHHYL+Chck3S2nyzazIUkoTBHOK9Z8gRAYiAAqCIAiCIAiCIAjCfwlE1EXU3TT5yCdBZor/XjDlKlKNajXuRB16DqYPPx1Dpas2Z0EvVKRLnp3i4TNFMAt/mu++vCo/lRei+KYrQPGSZaM4bDFoLRp6RIocHPjD178I+WWr5Fh2QRDChQiAgiAIgiAIgiAIgvBfJFtltraLnSov+SQKYDHwrYRZKE6SAI7uGx9++FiQyxPmly+1+jt9IYoH6z8O0KHygZVeYHm1bU5rUc8bbMeJ9546VvqCHBzEodyCIHiNCICCIAiCIAiCIAiC8F8Ewh187MGCL7ACR9yNn6EIxUtbgOKnza8F5PAvpn2fRv2t/u2bqRT5YVsE7VD7heVVg+JnLG4T8UyiXjjQlh4Hk2/WsrIMWBAiAREABUEQBEEQBEEQBOG/DAuBtWxCW3XN6g7+9wIrauIg/PwFqL/Vv31DRT+TKAchERGGIRzCes9C1DPjzL8gAxFRgSjDIgAKQsQRAVAQBEEQBEEQBEEQBAMQBG2ECn36v83bGlG/B1Sg+KlzkSd+ALGM2P67YG3JMX7DvyEA8jJjd8cVBMEdIgAKgiAIgiAIgiAIghA55KxJvlnLaIFC3oM/P6PAZwOWfylykC+WDKcIpngsFir0Zce8n/q3ysMXVohuhUdBENwhAqAgCIIgCIIgCIIgCJGDLtZlLk3xU+ViIY/FPMMni33pC5Ff1rJhAqDaFv/2TVdQ+50FwmJaxGLzMQRB8BoRAAVBEARBEARBEARBiERsImCWMuSbDlZ9uSl+ylwaCCSSvjD5BVbSAo9kKKKRpSyLfb5ZSmv/zlyKfLPD36BY/wlCZCACoCAIgiAIgiAIgiAIkQyEO0VQZfILKE9+WcsoymmBRRBkBL8Fq+0QVISt/HShT/2NfyOoiIh/ghBpiAAoCIIgCIIgCIIgCELUgCXBZozCXuh3+j7mfwuCEBmIACgIgiAIgiAIgiAIgiAIMRgRAAVBEARBEARBEARBEAQhBiMCoCAIgiAIgiAIgiAIgiDEYEQAFARBEARBEARBEARBEIQYjAiAgiAIgiAIgiAIgiAIghCDEQFQEARBEARBEARBEARBEGIwIgAKgiAIgiAIgiAIgiAIQgxGBEBBEARBEARBEARBEARBiMGIACgIgiAIgiAIgiAIgiAIMRgRAAVBEARBEARBEARBEAQhBiMCoCAIgiAIgiAIgiAIgiDEYEQAFARBEARBEARBEARBEIQYjAiAgiAIgiAIgiAIgiAIghCDEQFQEARBEARBEARBEARBEGIwIgAKgiAIgiAIgiAIgiAIQgxGBEBBEARBEARBEARBEARBiMGIACgIgiAIgiAIgiAIgiAIMRgRAAVBEARBEARBEARBEAQhBiMCoCAIgiAIgiAIgiAIgiDEYEQAFARBEARBEARBEARBEIQYjAiAgiAIgiAIgiAIgiAIghCDEQFQEARBEARBEARBEARBEGIwIgAKgiAIgiAIgiAIgiAIQgxGBEBBEARBEARBEARBEARBiMGwABg7sAoJgiAIgiAIgiAIgiAIghDziJOtKvlkLdeaBEEQBEEQBEEQBEEQBEGIeQSUb0M+het1J0EQBEEQBEEQBEEQBEEQYh5FFD6J89QhQRAEQRAEQRAEQRAEQRBiHkny1qX/AVEX96Wqe8TvAAAAAElFTkSuQmCC'
            let img_footer = 'data:image/jpg;base64,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TknZo5Wiu21T4K+NtF8dWXg2+8PXNr4lvsfZbKR0xcAgkMkmdjKcHkHFdn/wxp8Z+QvgW6cgFiEuYGOAMk4D80nUglfmQ1Tm+h4tRXqXhX9mH4n+ONEh1jQ/Cc9/pszMiTrcwqCysVYYZweCD2rA+I3we8Y/CSewh8XaFNokl8hkthM6P5qg4JBUkdafPG9r6i5JWvY4yiuk0/4c+JNV8Fan4vtNIuJfDOmTJb3epYAiikbovJ5P06VznPeqTT2Jaa3Ep0f3hTadH94UxDaKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKWkpR1oA9z/ZW034m/EXx5p3gLwN4p1fw/p1zMbrUJrG4aOK2hGPMlbHGccD1Ne2ftPfG6x+Nnxq8B/BrR9SmvPh/oeqW2n3V3NIXbUpw4WSRmP3gMEA+pJrmv2T/2pvhj8CPhb4j8P+IPDWt3Wu6+8kV9qekyKjtbkYWNXyGXAz09a4b4peOvgI/huKb4W+D/ABJ4c8ZW97Dc2+o6jemWNAjbmyCxySQK8yUXKs24PsvXuz0lJRopKa8z6r/af1Dxx49/aSj+DnhHx3a/C7w14f0e3uLbzbg2kNwzAYXIIyRwAK808L/DTxt8L/8AgoJ8L7Dx74qXxjr115c/9pKzMfKEbqiktzwBTNb/AGtPgf8AHaPw/rXxg8AaufGukRRxnUdEn2pclCGBPIOMjODnFcz46/bI8NeLP2uvBHxWt9C1K00Dw7Atu1m7K1xKFVhkdh94cVjCnV5eRR6O+nU6JSpt87l109D7amtvEHwb+Inxf+KHjH4iLrnw1jjkVfClozXJtmYAKkigkRnnGMD73NfM/wCy3rx+G/7G/wAcfih4Xt1s/FE19NHb3CqC9rFvAQL6AByfqM1514P/AG09N8OftFfEXxRd6Pean8OPHDSLqWgzBfMKlNqvjpuHIPsax/gT+1R4b+B+veN/DY8PXfiX4Q+J5GD6PfMq3MKEYBHYnHGO+AeDUrD1FBpq70/4b1B4im5Jp2Wv/D+h6r/wTi+L3jD4hfFvXfA/izXL7xR4c1nSZ5bm21OUzqrDALDd03BiKsfs3aTb+H/2dP2sNLtG3WtndywQ4P8AAqED9OK5rSf2tPgv8BND1+T4JeAdUsvFmsW5tv7U1qfctqp7Lkk4GcgDHIGa8v8A2Wv2ooPgfeeLdL8VaK3irwh4sjK6taI4WXecgyLnrkEgj+VbOnOfPOEbJ209CFUhDlhOV3rd+p6v/wAEx/8Ajz+Nn/Yrf+yyV3P7HHhnxL4u/YV8daV4R1uLw7r0+tyfZ9Smuvs6RYZCSZO3Ga8zuv2rvhJ8H/h34q0D4HeCdU0rV/E0H2W71XWZ9xhiIIKqCSTgE4HTnNedeB/2itG8LfsieL/hPJY6g+uaxffaoL2IgQou5ThjnOflP51E6dSpzTUbXa38hxqU6aUG72T/ABPaf28PGFjZ/AT4ZeAdc8XWPjn4j6ZJ52o6nZSCbYoUg7nHrwOTn5ea+f8A9nH4h+G/Dek+PfB/ijU7zw5p3jDT4rNfENhGZJLB45N43KCCY26NjmvFDjk+pySetdz8NE+G08eoQ+P5PEVoz7TZXmgiJ/L/ALwkjkGGz2rtVFQpezepwyrOdXnWht/Fz4KeIfhvoOmav/wkFl4x8E6hMyWGuaTdtLbGUDlHRuYpMdjVX9nH4WyfGP41eGPDAheazluPtN6qLki2i+eT8wNv/Aq6Lx18UvA9p8MtN+Gngaz1qbw5/bKazquqa06C6unAC7Y40G2MBc/U1aX4ueA/h3D8TU+G+n61Y3HiK1t9M0i7vpAZLK14a5JYfMHdhgY6CjmqeztbUrlgp3voex/tNeEfHHxP+Cmu+MPFPhLUPD194Q1+RbH7VEF36POcRoME8Rso/wC+q+WPgz4dsvF/xe8F6HqOG0/UNYtre4B6MhcZX8cY/Gun+CPxvm8A+KL/AP4SibUtf8KaxplxpWp2DTtIzRyL8rqGONysFIrA1bWvCGi+H/BF54Nj1Wy8Z6dI8+qXl24MTSLJugaIDpgAZpQhOmnS+5hKUZtVF9x0/wC1N8Qtc8VfHbxYl5e3FvaaTfyadp9jFI0cNnBEdiIiggAACvIrq4nvrh57iaS4nflpZXLM31J5NfQ/jD4jfBT426ufFfjHT/FPhHxddBW1VfDghmtL6YAAyqJBmNmxk+9eax3XwxTRPHUa2OuvqMjx/wDCLSyzLiFAfn+044JIz0q6T5YqLjqiKq5pNqWjNn9kU7f2nPhqf+oxH/6C1fqDNpviT4X/ABs+JfxS8SfEhL/4Z2Noxm8J2jNcvbnYAN8eTsPtgZz6V+TXwP8AHll8MPi94T8WahBNdWOj3y3U0NvjzHUAjC57817/AKH+2vY+H/2pvGXj2PSbu98BeLU+z6poVwB5ksWzaGxnbuBz+BrkxVGdSd4rS39L1OvC1oU4Wk+v9fI7v9jjVbbXPhL+1DqNlbi0s7y2muIYB/yzRkkZV/I1z3/BLn/kbPiX/wBiq3/s1cT8Ef2oPCvwE+J/jUaP4evdY+FvilWgn0e9ZVuoYzngEcHAYr7jFdrb/tYfBv4K+D/FNp8EvAuq6d4h8R2ptJ9S1mcuttGQeFBJJxk4A71FSE/fjyv3rW8vUuE4e7Jy+G50nwH1i4+Cv7AvxH+IXhfFt4t1LV5bJtRjA823jEoQEHtgEn6nNfI2qfGjxn4q0m10PxT4n1fxD4aS+jvZtPu7pnDsCNxBPQkZH416r+zD+1Rpfwl8KeJfAPjrw83iz4feISXubWN8SwyH7zLnqDwccHIrN+MXiz9ny60XTrH4beC9esbwahDPe32qXZLNbA/vIUBJwSO9b06bhUkpQvd7mFSSlCPLK1uhx/7QHin4d+LvGVrefDPwxN4U0JbKOKazmbJecfecDJx/XrXM+EdK8V/EC/0zwX4fe+v5rqcm102GVhEsjfekIzhQByWPQCum/aA8RfDbxL40tbr4X+Hrrw1oK2Ucc1rdNkvOB8zgZOP6nmu8+C/xc+FXgT4T6noepQeLNL8W6yzRalruhCEyNa54t4mfJRT/ABYwTW93GmuWLflv95jbmqO8rHrPhnxBo/iL9rTwHpFpex+IJ/APhO4s31jdvW7vre3dt6k/eCMdoP8As15R+yhq994w8d/EfSNXvJ76w1/wxqkt+lxIXV5EBkSQgnGVYcGuX034ieB/hF8TPC3iv4Zxa/epYO/9o2viQxATxuCrRqYwOGQnOe9dEPix8LvhvpHjC9+G1h4jfxN4os5NOB1tohBpFvK2ZViKDMjEfKCe1c/s2k0o7pfI29or3b2JdD8RfDfxd8BPh/4U1n4iap4L1XRLi9mukttKe4ikeZwVdnWRflAHoa88+KXgfxH8BfHElidfa5N7Ypc2mtabO4W+splyrBs7sEdVPSrng+1+Cd14Z07/AISi68aWGuQptvYdLSCS3uTn/lmzjKZHFd5p37QPw98RfHHTvFPjDwnfP4P8PaXFpvh/RLV1mMPlDETzbuJDn5iDxmtPehJ8ibWv3k+7NLmdnoQ3Ud38JP2Uta0PxPNIuuePr21vdN0Sdy0lpaQ5P2t1PKGTOFHUjmtX4B/ErxD4k+EvxU8MXd6BoGjeCZxa2MMaxx7mnTMjgffc9Nx5xXKfFrxd8I/H0mu6/DqXxA1PxlegyQz6x9m+z+ZkYVgoyEA4AXpgVynwi+Jdh8PdD+Ille2txcSeJPD76TbGDGI5DIrbnz/DhT0pcvPTemt7gpcs1Z6Wsc14E+Imu/DO+utT8O3S2Ooz2j2guzEryRI4wzRkj5Hx/EOea9c/bXlmf9oZ5Ruubk6Xpb/NlzK/kIcH1JP55r5/Zd0e3PavVvjZ8YLT4hfGK08aaJaTWqWlvYLHDegEmS3RQSQOxK/lW8oP2ikl0ZhGX7tp9z66/wCEO0rWPj54e+Ilz4qbS/ibBpsGof8ACrXuwtxJdJFtjt1n+4qOMN5R+bHFeBapqGoaT+yX4xvvJbR9X8Q+Pmg1WGHMbxrGhcQNjkKGY8e1T6l8Wvgv4i+KkfxW1Cy8YReKPtceqT+Hrd4TZy3iYOVnI3rGWUHHpxXO6H8edC8YW/j3QviRYXw8P+LNXGvJc6IV+0abegkbkVhtdSp2kH0zXFCnLRtbW/4Y7JVI6pP+u5Y8aXU+tfsb/DXV7ueSXUtL8SahpVrdM581bbYrhA3XAYnHpVj4G6hefC/4T+N/ixeXlyb50bw34bSadzuu5l/fzAE8+XHxnsTXIfGL4o+H9e8H+FfAfgazv7Pwh4dM1wlxqrKbq+u5T+8nkC/KvGAFHaoPjD8VNM8YeGfAvhPwzaXWn+GfDGn+WI7rAe5vZDunuGA45PA9hXQoOUVFq13f5GHOoyck9kl8zmvhv4X134jeN9C8JaRe3a3mrXaW6mOdwEDHLyHBwAF3MT7V698Vra4/aI/aCsPA/hCRj4b8O266JYXUrFo7e0tx/pF259CQ7H1OK4X4J/FPTPhDa+MNWWzuLjxdfaU+maLcpgRWZl4mmbPO7ZkDHrXKeAfiR4n+Futyat4U1m50XU5Imt3urcjc8bcspz1BqpRbm5RWy0JUkoKMnu9T6j1bxdbeIf2c/jB4Y8L6fdWvgnw0mm2elRvAyyXbecTNdvxy8jZPsMV8bt1r6S8O/twePrP4a+NdF1bxNql9ruqi3XTL5VjCWqqT5ob5f4lOK+bpnMkjOx3MxLE+pJyTRQpzhzcyCvOM1HlYynR/eFNp0f3hXUco2iiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKAClpKWgB1J3xX1h+xH8C/h78V/DPxJ1zx/pt/qVr4ato7mKPT7hon27WZwAOpOK5PxzD8DvGPivwFpPw48MeJND+26vFb6odamY+bA7Ku1CTweTzXP9YjzuCT0Ov2D5FO61PntqXt1r7U/b6/ZJ8FfAnw3oPiLwDBcw6f9tk07VI57lpvLl2hkOW6Z5FdH4U/Yt8B2/7HN5418Q2t0/j/APsKTW4wt0yLCjE+UDGODwPxqPrVNxU+7sP6rU53HsfBA5zmjHvX0f8AsU/Avwr8WtU8WeIfiEsw8DeGdNFxeNFI0RaVz8o3D0GTisP9sT4I6Z8DfjJ/Znh9JR4T1S1g1HSWlYuTDIBuXceTg/zrRVoup7LqZexl7P2nQ8Mor7K+PP7MfgTwD8evgn4U0eyuodH8VW9u+pxyXLOzl2w21jyv4V4z+198MdB+D/7QHiTwp4ZgkttGslhMEU0pkYbkBPzHk81NOvGpJJLfUqdCVNNt7HjNKMmu/wBY+BPjHQfhHpPxLvdOjj8I6pN5FtdLODIzZIBKdQDg4PtVL4aeIPBfh6+vZPGnhK48W2skarbwW+otZmF88sWX72Rxit+dNNx1MeRp66HHUuD+FfU3jfw/8G9D+BWjeMpvh1qHh/XNdv1Gj6VJrckz3Nkh/fTtkfIp+6pxz1qxoNr8C9a+Cvizx/8A8Ko1KE6Fqdrpw0//AISGU+d5ylt+7HGMfjXP9YVr8r3sbfV3e3Mj5QxSc9q+kfhzoPwq8ReE/ir49v8AwJfS6JoH2EadoK6vIrRmVirlpurdM9KxfH3gHwD4q+Bz/E7wHp2p+GP7P1hNH1LRtQvPtcbl03JJDJjP1Wr9sr2afYXsXa913PCee9GPwr6R+CP7Oeh+PvgzrOq6xNLb+MNbN1H4PhVyFma0TzLglf4twyo9xXnPwC+EUXxc8X3ttq1/JonhrRbKTVNb1BF3PBbx9VQHq7H5RnvT9tH3vIXspe75nmn8qK9s1nx58Cp7O/sNL+FesQr5TpZaxJr7m4L4+SSSPG3BPJUVaf8AZ/Pin4Y/B6bwpYSXHirxXJfC+kklIgSOGTAlYniNFXJLUe1SV5KwexbbUdbHhJX060L9a9f8YfD/AML678QPDnw6+GQk13VVcWd74gmlIi1C6P8ArHjTokMeG57hc16D4Z+D/gu4a7sfDXgHX/i19hm+yXniKTVV0uxkuBw0dspI34PAyST6UpVoxSdgVFtvU+X+tFey/FP4T6DpGgjxh4Xh1O10qx1QaVr3hrWHDXmk3Q+byzIv343GQG6g16V8E9M+Cvxh8XHTB8JNQ0rSbG1e/wBX1mfxJKY7G2QZaVhjkk8Be5NJ10o8yQKi3LlbPlD2o6+9e6+A/hb4H8dax8QPHF9Pf6B8IvDM2Y4IG8y9ujI2ILZGbgMwGSx6Cs/W/iB8E9R0jULKy+FGp6NeGJhZajH4gklkWX+FpEYbWHqBin7VN2SbD2dlq0eNUewFfQngfw18NvC37N9h498X+D7vxbql94gm0pVg1R7NY0SMMD8vBPNM8cfAXQ/FUfwq1j4ZRX9jY/EG4ksYdI1aYTSWVxHJschwAXjxzn2o9sm7Pbv6D9i7XW/Y+fs+9Hp+VfRXi68+A3wr8S3nhAeB9V8fSaXKbS/8Ryaw9q00y8SG3iQbQoOQN2c4qVf2d/DUfx++FWn2N3daz8N/HzwXNjLOdlwIWJEkDsP40YYyOvWkq0eqt2B0Xsnc+cBn0o+tbvibSbfS/HmraVArCzttVltI1JyfLWYoBn6CvpPTfgF4Gt/2uvFHgq8026u/CekaJLqaWS3TJI7pbLLjzOuCxNVKqoq77XJjSctvQ+T2z+NN5r6M0rwZ8Lfjf4F8b3fg7w7q3gbxP4X0w6wsU+om9s7yBWCujbhlH5yDnmvEPAfg3UfiN4y0LwzpCCTU9Yu47SAN0DMep9gMn8KcaiabeltwlTaatrcxOlFfRXiy6+Avwn8R3XhIeCtV+Ik+my/ZtQ8Qy6u9mJZlOJPs8acBQcgbs5xWL8M/hp4J1/S/G/xJ8TR6nYfDfQLlLez0i3nBvL64lOYrbzSOAByzYzU+2VuZp2K9i78ulzw/nrR9a+kfBOg/CL9obV38GeHvCN/8OvF91DI2jXg1R722upkUsIJkfkFgCAy96v8A7Nf7O3hb4p/DH4gjxBFcW/jK2vxpOiSJMVRLzymcRsvRtxRhzUyrqKbkrNfqCouVrPQ+XlpfavRfgT8Ml+JPxY07w/qwe10u2Mt1rMgO0wW0AJl57HI2/U1rftVeAfD3wy+OOt+H/CsEttoMMVvNbRTyGRwskYfljyeta+0XtPZ9TP2b5OfoeSAGg9ete/8Awz8L/DvRf2dtS8feMPCV34rv08RrpEUMGpPaKkZh35+Xqc1P4m+D/gbWbP4UeM/B8Wpad4Y8X63/AGNe6LqU/my2sqSqsnlygAshDcHtUe3SdmvI09i2rpnzzSN1r6Q+MWpfBH4eeO/F/hC0+FOoy3Wk3U1hDqDeIJcF14WQpj15x7V83t1q4S51zJWInHk0uNp0f3hTadH94VoZDaKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKWkpRQB+gH/AAS/m1iDwb8ZpdAtVvNaS0hNlBIoZZJtjbFIPByfWuG+Ml/8cNZ+JPwkuvi74RsvDNvHrsMenvZW8UXmuZELqdhOcDHWvlTw5428ReD/AD/7A13UdF8/HnfYLlovMx0zg84qfWviJ4q8SyWb6v4l1XVHs5PNtjd3TyGF/wC8uTwf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CiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACnKabRQB9m/BP9q74PeAf2cz8MvEHhDxBcyaluk1u60ucQm8ct03ghtuMDHtXi3x28S/BTXtF02H4VeEdZ8Nagkzm9l1S6MqSRFcKqgk4IPNeN0VzRoRhPnTf3nTLESlHlaX3H0P8dP2jtD+KfwD+FPgPTtOvrTUfCMZS6uLjb5UuYwvyY56jvSW/wC0jo9v+xn/AMKfSxv4/EK6wdTTUkIEKjzN4weu4fzr55oqvYwso9E7k+3ndy7qx9eat+1j8PPihafCzWPiJ4d1a68b+DbiM3eoaeIzHqcEf3UfPOchW+oPrWJ8YP2+/iV4u+Keoa74Q8Vaz4b8MmWM2ejLMFVY0xkOBxlsHP1r5eoqFhqad7FPE1GrXPsH4iftyadd/tBeDvi54M0a8tNbstOGn65aagVEV8mACFK89Oh9hR48+NX7LfjKbWfE3/Crtej8WamskhtVutlolyyn94QDj7xz05r4+opLDU1Zq6t5j+szd72d/I+h7P8AaQ0a1/Y0m+D62F9H4gbWW1NNQjKiBAZd4GeuRx+NbvxR/ai8G/HX4Y+AbPxzoOo3PjvwzPDFcapbFRFf2YYeZG5+8CyjPs2fWvluiq+rwvzLe9xfWJ2s9rWPsX4dftQfBn4L/Gjxd4z8FeDNW0+zutGWw0azXbi3nKkyytkkgE7eh6A+tc98Ff2/PiP4L+KFhrXjTxTrXijwxmUXmkNMGUqwONgPGVOMe1fLdFL6tS1ur3H9Zqq1nY+r9H/aq8EeF/APxz8MaPourQW/ju7e50vcUAtA4GVkx6HPSvlS3k8ueKRyWKurMc5JwQf6VHRWlOlGnfl6mU6sqlr9D6D/AGwP2jNG/aK1zwfeaJYX+mwaLo66bPHeMP3jhs7gB2r59PtSUVVOnGnFRjsiak5VJc0gp0f3hTadH94VoQNop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2inbaNtADaKdto20ANop22jbQA2nR/eFG2lUfMKAIdo9KNo9KKKwOkNo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KNo9KKKADaPSjaPSiigA2j0o2j0oooANo9KMD0oooA//2Q=='
            let test = 'Hola Mundo';
            var docDefinition = 
                {
                    //pageMargins
                    pageMargins: [ 40, 60, 40, 60 ],
                    header: {
                        columns: [
                            {
                                image: img_header, 
                                alignment: 'center',
                                width: 575,
                                height:50,
                                
                            }
                        ],
                        margin:[10,10,10,10]
                    },
                    footer:{
                        columns: [
                            {
                                image: img_footer,
                                alignment: 'center',
                                width: 575,
                                height:50
                            }
                        ],
                        margin:[10,0,10,0]
                    },
                    content:[
                      
                        {
                            text: 'ACA VA EL GRAFICO',
                            alignment: 'center',
                            margin: [0, 20, 0, 20] // Margen inferior para separar el texto de la tabla
                        },
                        {
                            alignment: 'center',
                            table: {
                                headerRows: 1,
                                body: tableBody,
                            },
                            layout: 'exampleLayout',
                            margin: [0, 20, 0, 20] // Ajusta el margen según sea necesario
                        }   
                    ],
                    
                }
            
            pdfMake.tableLayouts = {
            exampleLayout: {
                hLineWidth: function (i, node) {
                if (i === 0 || i === node.table.body.length) {
                    return 1;
                }
                return (i === node.table.headerRows) ? 2 : 1;
                },
                vLineWidth: function (i) {
                return 1;
                },
                hLineColor: function (i) {
                return i === 1 ? 'black' : '#aaa';
                },
                paddingLeft: function (i) {
                return i === 0 ? 0 : 8;
                },
                paddingRight: function (i, node) {
                return (i === node.table.widths.length - 1) ? 0 : 8;
                }
            }
            };
            // Generar y descargar el PDF
            pdfMake.createPdf(docDefinition).download('REPORTE_' + id + '.pdf');
        }
    };
}

$(document).ready(function(){
    
    $('#btnAccess').click(function(){
        $('#accessModal').modal('hide');
    })
    // INICIALIZANDO TOOLTIP PARA QUE SE PUEDA VISUALIZAR EN LA PÁGINA
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
       
    })
    
    // SEARCH INPUT PARA BUSCAR LOS EQUIPOS DENTRO DE LOS SLIDER
    document.getElementById('search-button').addEventListener('click', function() {
        var searchTerm = document.getElementById('example-search-input').value.toLowerCase();
        var slides = document.querySelectorAll('.swiper-slide');
        slides.forEach(function(slide) {
            if (slide.getAttribute('data-title').toLowerCase().includes(searchTerm)) {
                slide.style.display = 'flex';
            } else {
                slide.style.display = 'none';
            }
        });
        //sirve para refrescar el efecto swiper
        swiper.update();
    });

    // REFRESH BUTTON
    document.getElementById('refresh-button').addEventListener('click', function(){
        var slides = document.querySelectorAll('.swiper-slide');
        slides.forEach(function(slide) {
            slide.style.display = 'flex';
        });
        //sirve para refrescar el efecto swiper
        swiper.update();
    })
})
document.addEventListener("DOMContentLoaded", async function(){
    try{
        const response = await fetch(base_url + "Live/ListaDispositivoEmpresa",{method: 'GET'});
        const data = await response.json();
        console.log(data);
        data.data.forEach((contenedor, indice) => pintarCirculo(contenedor,indice));
        //insertar en texto la data 
        //console.log(data.text);
        carruselExtra.innerHTML  =data.text;
        cardOnline.innerHTML = data.estadofecha[0];
        cardWait.innerHTML = data.estadofecha[1];
        cardOffline.innerHTML = data.estadofecha[2];

        d1 = data.estadofecha[0];
        d2 = data.estadofecha[1];
        d3 = data.estadofecha[2];

        //console.log(data.extraer);
        extraerdata = data.extraer ;
        //console.log(extraerdata);
       
        //myDoughnutChart.update();
        // CREANDO GRÁFICO DOUGHNUT PARA LAS ALARMAS
        var ctx = document.getElementById('grfAlarma').getContext('2d');
        var myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Online', 'Wait', 'Offline'],
                datasets: [{
                    label: 'Equipos',
                    data: [d1,d2,d3], 
                    backgroundColor: [
                        'rgb(0, 116, 75)',
                        'rgb(255, 193, 0)',
                        'rgb(233, 26, 51)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Estado de los Equipos'
                    }
                }
            }
        });

    }catch(err){alert(err);}
    //cada 10 segundos ejecutar 
    setInterval( async function(){ okey =  await obtenerCambio();}, 10000);
})
function saludos(){
    console.log("oli pablito");
}
let estado = 0;

function bloqueoInput(id){
    $('#btnToSave1_'+id).attr('hidden', true);
    $('#btnToSave2_'+id).attr('hidden', true);
    // Cambiar el icono del candado
    let candadoIcon = $('#candado_'+id);
    candadoIcon.removeClass('ri-lock-unlock-fill'); 
    candadoIcon.addClass('ri-lock-2-fill');
    // Cambiar el color del icono del candado
    let btnIconAccess = $('#btnIconAccess_'+id);
    btnIconAccess.removeClass('btn-outline-secondary');
    btnIconAccess.addClass('btn-outline-success');

    $('#sp_etileno_'+id).attr('readonly', true);
    $('#sp_co2_'+id).attr('readonly', true);
    $('#sp_humd_'+id).attr('readonly', true);
    //falta injection hours
    $('#sp_temp_'+id).attr('readonly', true);
    
    
}

function mostrarContenido(id){
    let flechaMostrar = $('#icon_mostrar_contenido_'+id);
    let textFlecha = flechaMostrar[0];

    let comparar = textFlecha.className;

    if(comparar.includes('ri-arrow-down-circle-line')){
        $('#row_injection_'+id).attr('hidden', false);
        $('#row_aperture_'+id).attr('hidden', false);
        $('#row_compressor_'+id).attr('hidden', false);

        flechaMostrar.removeClass('ri-arrow-down-circle-line');
        flechaMostrar.addClass('ri-arrow-up-circle-line');

    }else{
        flechaMostrar.removeClass('ri-arrow-up-circle-line');
        flechaMostrar.addClass('ri-arrow-down-circle-line');
        $('#row_injection_'+id).attr('hidden', true);
        $('#row_aperture_'+id).attr('hidden', true);
        $('#row_compressor_'+id).attr('hidden', true);
    }
}


function accessModal(id){
    let candadoIcon = $('#candado_'+id);
    let textCandado = candadoIcon[0];
    
    // Obtener la lista de clases como una cadena
    let comparar = textCandado.className;
    
    //comparar = 'ri-lock-unlock-fill';
    
    if(estado == 0){
        // Verificar si la clase está incluida en la lista
        if(comparar.includes('ri-lock-2-fill')){
            let title = document.getElementById('title');
            title.textContent = id;
            const url = base_url + "Live/obtenerFormulario/" + id;
            const http = new XMLHttpRequest();
            http.open("GET",url,true);
            http.send();
            http.onreadystatechange = function(){
                if(http.readyState == 4 && http.status == 200){
                    const response = JSON.parse(http.responseText);
                    console.log(response);
                    console.log(url);
                    let formulario = document.getElementById('formularioDeAcceso');
                    formulario.innerHTML = response.text;
                }
            }
            $('#accessModal').modal('show');
        }else{
            bloqueoInput(id);
        }
        
    }else{
        if(comparar.includes('ri-lock-unlock-fill')){
            estado = 0;
            bloqueoInput(id);
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Parece que existe otro proceso ejecutándose!',
            });
        }
    }

}

function btnAccess(id){
    validacion =false
    //aqui hcaer la validacion 
    //cpturamos el text del input 
    let val = $('#access_'+id).val();
    console.log(val);
    const url = base_url + "Live/Validar/" + val;
    const http = new XMLHttpRequest();
    http.open("GET",url,true);
    http.send();
    http.onreadystatechange = function(){
        if(http.readyState == 4 && http.status == 200){
            const response = JSON.parse(http.responseText);
            console.log(response);
            if(response == 'ok'){
                validacion = true;
                if(validacion){

                    $('#sp_etileno_'+id).attr('readonly', false);
                    $('#sp_co2_'+id).attr('readonly', false);
                    $('#sp_humd_'+id).attr('readonly', false);
                    //falta injection hours
                    $('#sp_temp_'+id).attr('readonly', false);
                
                    $('#btnToSave1_'+id).attr('hidden', false);
                    $('#btnToSave2_'+id).attr('hidden', false);
                
                    // Cambiar el icono del candado
                    let candadoIcon = $('#candado_'+id);
                    candadoIcon.removeClass('ri-lock-2-fill');
                    candadoIcon.addClass('ri-lock-unlock-fill');
                
                    // Cambiar el color del icono del candado
                    let btnIconAccess = $('#btnIconAccess_'+id);
                    btnIconAccess.removeClass('btn-outline-success');
                    btnIconAccess.addClass('btn-outline-secondary');
                
                
                    //ENVIAR CONTRASEÑA PARA QUE EL BACK EVALUE SI PROCEDE O NO A PODER EDITAR LA TARJETA
                
                    // Cerrar el modal
                    $('#accessModal').modal('hide');   
                    estado= 1;
                    }
            }

        }
    }

 
    
}

function guardarDatos(id){
    $('#sp_etileno_'+id).attr('readonly', true);
    $('#sp_co2_'+id).attr('readonly', true);
    $('#sp_humd_'+id).attr('readonly', true);
    //falta injection hours
    $('#sp_temp_'+id).attr('readonly', true);
    $('#btnToSave1_'+id).attr('hidden', true);
    $('#btnToSave2_'+id).attr('hidden', true);
    // Cambiar el icono del candado
    let candadoIcon = $('#candado_'+id);
    candadoIcon.removeClass('ri-lock-unlock-fill');
    candadoIcon.addClass('ri-lock-2-fill');

    // Cambiar el color del icono del candado
    let btnIconAccess = $('#btnIconAccess_'+id);
    btnIconAccess.removeClass('btn-outline-secondary');
    btnIconAccess.addClass('btn-outline-success');
    estado = 0;
   
}

//graficaM
async function graficaM(id){
    //console.log(id);
    $(".loader").show();
    
    tituloGrafica.textContent =id;
    const response = await fetch(base_url + "Live/GraficaInicial/"+id, {method: "GET", });
    const result = await response.json();
    //reemplazar el nombre de la grafica 

    console.log(result);
    todo = result ;
    fechaFin.value =result.date[1] ;
    fechaInicial.value =result.date[0] ;
    //setInterval( function(){ $(".loader").fadeOut("fast"); }, 1000);

    graph = await graficaMadurador1(result.graph,result.cadena,result.temperature,result.temperature);

    return result;
}
async function obtenerCambio() {
    //$(".loader").show();
    const response = await fetch(base_url + "Live/LiveData", {method: "GET", });
    const result = await response.json();
    if(result.length!=0){
        result.forEach(function(res){
            tarjeta(res);
            //$('#fechita_'+res.telemetria_id).text(res.ultima_fecha);
            console.log(res.telemetria_id);
        })
    }
    console.log(result);
    //setInterval(  function(){ $(".loader").fadeOut("fast"); }, 1000);

    return result;
}






let previousValues = {};
let co2Values = {};
let humidityValues = {};
let supplyValues = {};
let injectionValues = {};
let compressorValues = {};
let apertureValues = {};
function tarjeta(res){
    let iconSuccess = "<i class='bi bi-arrow-up-short me-2 align-items-center mb-1 text-success value-icon'></i>";
    let iconDown = "<i class='bi bi-arrow-down-short me-2 align-items-center mb-1 text-danger value-icon'></i>";
    
    //en caso no haya valor
    if (previousValues[res.telemetria_id] === undefined) {
        previousValues[res.telemetria_id] = res.ethylene;
    }

    let evaluacionEti;
    if (res.ethylene > previousValues[res.telemetria_id]) {
        evaluacionEti = iconSuccess;
    } else if (res.ethylene < previousValues[res.telemetria_id]) {
        evaluacionEti = iconDown;
    }

    // Actualiza el icono
    $('#eti_icon_' + res.telemetria_id).html(evaluacionEti);

    // Actualiza los valores anteriores
    //previousValues[res.telemetria_id] = res.ethylene;

    if (co2Values[res.telemetria_id] === undefined){
        co2Values[res.telemetria_id] = res.co2_reading;
    }

    let evaluacionCo2;
    if (res.co2_reading > co2Values[res.telemetria_id]){
        evaluacionCo2 = iconSuccess;
    }else if (res.co2_reading < co2Values[res.telemetria_id]){
        evaluacionCo2 = iconDown;
    }

    $('#co2_icon_' + res.telemetria_id).html(evaluacionCo2);

    //co2Values[res.telemetria_id] = res.co2_reading;

    
    if(humidityValues[res.telemetria_id] === undefined){
        humidityValues[res.telemetria_id] = res.relative_humidity;
    }

    let evaluacionHumidity;
    if (res.relative_humidity > humidityValues[res.telemetria_id]){
        evaluacionHumidity = iconSuccess;
    }else if(res.relative_humidity < humidityValues[res.telemetria_id]){
        evaluacionHumidity = iconDown;
    }

    $('#humidity_icon_'+res.telemetria_id).html(evaluacionHumidity);

    //humidityValues[res.telemetria_id] = res.relative_humidity;

    if(supplyValues[res.telemetria_id] === undefined){
        supplyValues[res.telemetria_id] = res.temp_supply_1;
    }

    let evaluacionTmp;
    if(res.temp_supply_1 > supplyValues[res.telemetria_id]){
        evaluacionTmp = iconSuccess;
    }else if(res.temp_supply_1 < supplyValues[res.telemetria_id]){
        evaluacionTmp = iconDown;
    }

    $('#tmp_icon_'+res.telemetria_id).html(evaluacionTmp);

    if(injectionValues[res.telemetria_id] === undefined){
        injectionValues[res.telemetria_id] = res.ripener_prueba;
    }

    let evaluacionInj;
    if(res.ripener_prueba > injectionValues[res.telemetria]){
        evaluacionInj = iconSuccess;
    }else if(res.ripener_prueba < injectionValues[res.telemetria_id]){
        evaluacionInj = iconDown;
    }   
    $('#inj_icon_'+res.telemetria_id).html(evaluacionInj);

    if(compressorValues[res.telemetria_id] === undefined){
        compressorValues[res.telemetria_id] = res.compress_coil_1;
    }

    let evaluacionComp;
    if(res.compress_coil_1 > compressorValues[res.telemetria_id]){
        evaluacionComp = iconSuccess;
    }else if(res.compress_coil_1 < compressorValues[res.telemetria_id]){
        evaluacionComp = iconDown;
    }
    $('#comp_icon_'+res.telemetria_id).html(evaluacionComp);


    if(apertureValues[res.telemetria_id] === undefined){
        apertureValues[res.telemetria_id] = res.avl;
    }

    let apertureComp;
    if(res.avl > apertureValues[res.telemetria_id]){
        apertureComp = iconSuccess;
    }else if(res.avl < apertureValues[res.telemetria_id]){
        apertureComp = iconDown;
    }
    $('#aperture_icon_'+res.telemetria_id).html(apertureComp);
    //supplyValues[res.telemetria_id] = res.temp_supply_1;
    //$('#eti_icon_'+res.telemetria_id).html(iconSuccess);
    $('#fechita_'+res.telemetria_id).text(res.ultima_fecha);
    $('#temp1_'+res.telemetria_id).text(res.temp_supply_1);
    $('#return_'+res.telemetria_id).text(res.return_air);
    $('#s_temp_'+res.telemetria_id).val(res.set_point);
    $('#humd_'+res.telemetria_id).text(res.relative_humidity);
    $('#evap_'+res.telemetria_id).text(res.evaporation_coil);
    $('#s_humd_'+res.telemetria_id).val(res.humidity_set_point);
    $('#cargo1_'+res.telemetria_id).text(res.cargo_1_temp);
    $('#cargo2_'+res.telemetria_id).text(res.cargo_2_temp);
    $('#cargo3_'+res.telemetria_id).text(res.cargo_3_temp);
    $('#cargo4_'+res.telemetria_id).text(res.cargo_4_temp);
    $('#etileno_'+res.telemetria_id).text(res.ethylene);
    $('#sp_etileno_'+res.telemetria_id).val(res.sp_ethyleno);
    $('#co2_'+res.telemetria_id).text(res.co2_reading);
    $('#sp_co2_'+res.telemetria_id).val(res.set_point_co2);
    $('#h_inyeccion_'+res.telemetria_id).text(res.ripener_prueba);
    $('#n_apertura_'+res.telemetria_id).text(res.avl);
    $('#compresor_'+res.telemetria_id).text(res.compress_coil_1);
    $('#defrost_prueba_'+res.telemetria_id).text(res.defrost_prueba);
}
//console.log(extraerdata);
async function cargar_circulos(tipo_usuario1,empresa_general1)
{
    tableStatus ='';
    genialtotal = await analizarTabla(tipo_usuario1,empresa_general1);
    //console.log(genialtotal);
    if(genialtotal==1){
        ocultarR();
    }else{
        if(genialtotal==2){
        ocultarM();
        }else{
        if(genialtotal==3){
            ocultarG1();
        }else{
            console.log("No hay dispositivos");
            ocultar();
        } 
        }
    }
    saltarA('#inicio');
    textazo = tipo_usuario1 +" , "+empresa_general1;
    //console.log(textazo);
    const config = {
        method: 'get',
        dataType: 'json',
        url: '../../ztrack1/controllers/principalController.php?option=circulos&id='+textazo
    }
    const buena =  await axios(config);
    const info = buena.data;
    //console.log(info); 
    for(var i = 0 ; i<info.contenedores.length ; i++){
        var undia = moment().add(-24,'hours').format("YYYY-MM-DD HH:mm:ss");
        var mediahora = moment().add(-30,'minutes').format("YYYY-MM-DD HH:mm:ss");
         var valorFecha = info.contenedores[i].ultima_fecha;
         if(undia > valorFecha){
            estadoColor = 3;
         }else{
            if(mediahora > valorFecha){
                estadoColor = 2;        
            }else{
                estadoColor = 1;            
            }
         }
        var circulo = L.circleMarker([info.contenedores[i].latitud,info.contenedores[i].longitud],{
            radius:8,
            color :elcolor(estadoColor),
            fillColor : elcolor(estadoColor),
            fillOpacity :1 
        });
        tableStatus = `
        <div class="row">
           <div class="col-3" style="color:#1a2c4e;"><b>Reefer ID: </b></div>
           <div class="col-3"><h4><strong>${info.contenedores[i].nombre_contenedor} </strong></h4></div>
           <div class="col-3" style="color:#1a2c4e;"><b> </b> </div>
           <div class="col-3"><b> </b></div>
        </div>
        <div class="row">
           <div class="col-3" style="color:#1a2c4e;"><b>Event Time :  </b></div>
           <div class="col-5" style="color:blue;"><h5>${info.contenedores[i].ultima_fecha} </h5></div>
           <div class="col-1" style="color:#1a2c4e;"> <b> </b></div>
           <div class="col-3"><b> </b></div>
        </div>
       <div class="row">
           <div class="col-3" style="color:#1a2c4e;"><b>Locate : </b></div>
           <div class="col-5"><b>${info.contenedores[i].latitud},${info.contenedores[i].longitud} </b></div>
           <div class="col-1" style="color:#1a2c4e;"> <b> </b></div>
           <div class="col-3"><b> </b></div>
        </div>
       <div class="row">
           <div class="col-3" style="color:#1a2c4e;"><b>Setpoint :</b></div>
           <div class="col-3"><b>${info.contenedores[i].set_point} C°</b></div>
           <div class="col-3" style="color:#1a2c4e;"> <b>Evaporation :  </b></div>
           <div class="col-3"><b> ${info.contenedores[i].evaporation_coil} C°</b></div>
        </div>
       <div class="row">
           <div class="col-3" style="color:#1a2c4e;"><b>Return Temp</b></div>
           <div class="col-3"><b>${info.contenedores[i].return_air} C° </b></div>
           <div class="col-3" style="color:#1a2c4e;"> <b>Ambient :  </b></div>
           <div class="col-3"><b>${info.contenedores[i].ambient_air} C° </b></div>
        </div>
       <div class="row">
           <div class="col-3" style="color:1a2c4e;"><b>Supply Temp</b></div>
           <div class="col-3"><b>${info.contenedores[i].temp_supply_1} C°</b></div>
           <div class="col-3" style="color:1a2c4e;"> <b>Humedite :  </b></div>
           <div class="col-3"><b> ${info.contenedores[i].relative_humidity} %</b></div>    
        </div>
        `; 
        circulo.bindPopup(tableStatus);
        circulo.on('click', markerOnClick);
        markers1.addLayer(circulo);    
    }
    for(var i = 0 ; i<info.generadores.length ; i++){
        var undia = moment().add(-24,'hours').format("YYYY-MM-DD HH:mm:ss");
        var mediahora = moment().add(-30,'minutes').format("YYYY-MM-DD HH:mm:ss");
         var valorFecha = info.generadores[i].ultima_fecha;
         if(undia > valorFecha){
            estadoColor = 3;
         }else{
            if(mediahora > valorFecha){
                estadoColor = 2;

            }else{
                estadoColor = 1;
            }
         }     
        var circulo = L.circleMarker([info.generadores[i].latitud,info.generadores[i].longitud],{
            radius:8,
            color :elcolor(estadoColor),
            fillColor : elcolor(estadoColor),
            fillOpacity :1  
        });
        tableStatus = `
        <div class="row">
           <div class="col-3" style="color:#1a2c4e;"><b>Genset ID: </b></div>
           <div class="col-3"><b>${info.generadores[i].nombre_generador} </b></div>
           <div class="col-3" style="color:#1a2c4e;"><b> </b> </div>
           <div class="col-3"><b> </b></div>
        </div>
        <div class="row">
           <div class="col-3" style="color:#1a2c4e;"><b>Event Time :  </b></div>
           <div class="col-5" style="color:blue;"><b>${info.generadores[i].ultima_fecha}  </b></div>
           <div class="col-1" style="color:#1a2c4e;"> <b> </b></div>
           <div class="col-3"><b> </b></div>
        </div>
       <div class="row">
           <div class="col-3" style="color:#1a2c4e;"><b>Event : </b></div>
           <div class="col-5"><b>${info.generadores[i].latitud},${info.generadores[i].longitud}</b></div>
           <div class="col-1" style="color:#1a2c4e;"> <b> </b></div>
           <div class="col-3"><b> </b></div>
        </div>
       <div class="row">
           <div class="col-3" style="color:#1a2c4e;"><b>Battery :</b></div>
           <div class="col-3"><b>${info.generadores[i].battery_voltage} </b></div>
           <div class="col-3" style="color:#1a2c4e;"> <b>Frequency :  </b></div>
           <div class="col-3"><b> ${info.generadores[i].running_frequency}</b></div>
        </div>
       <div class="row">
           <div class="col-3" style="color:#1a2c4e;"><b>Voltage</b></div>
           <div class="col-3"><b>${info.generadores[i].voltage_measure} </b></div>
           <div class="col-3" style="color:#1a2c4e;"> <b>RPM :  </b></div>
           <div class="col-3"><b>${info.generadores[i].rpm} </b></div>
        </div>
       <div class="row">
           <div class="col-3" style="color:1a2c4e;"><b> Fuel</b></div>
           <div class="col-3"><b>${info.generadores[i].fuel_level} </b></div>
           <div class="col-3" style="color:1a2c4e;"> <b>Set POint :  </b></div>
           <div class="col-3"><b> ${info.generadores[i].set_point} </b></div>    
        </div>
        `;
        circulo.bindPopup(tableStatus);
        circulo.on('click', markerOnClick);
        markers1.addLayer(circulo);       
    }   
}

async function graficaMadurador1(info,cadena,temp,temp1){
    //$(".loader").show();
    console.log(temp);
    //console.log(info);
    //console.log(cadena);
    cambioTemp=1;
    if(temp!=temp1){cambioTemp=2;}
    dataGrafica =[];
    for (var i = 0; i < cadena.length; i++) {
        if(cadena[i]!='created_at'){
            boleto =cadena[i];
            //console.log(info[boleto]);
            if(info[boleto].config[3]==1){
                eje = "y";
            }else if(info[boleto].config[3]==3){eje = "y2";
            }else{eje="y1";}
            if(info[boleto].config[3]==4){fillx=true;}else{fillx=false;}
            nombrelabel =info[boleto].config[0] ;
            if(nombrelabel.includes('Set') || info[boleto].config[3]==4){
                displayX =false;
            }else{displayX ='auto';}
            obj = {
                label : info[boleto].config[0],
                data : datito = (info[boleto].config[3]==1 && cambioTemp==2)? dato_procesado(info[boleto].data,temp1):info[boleto].data ,
                backgroundColor: info[boleto].config[2], // Color de fondo
                borderColor: info[boleto].config[2], // Color del borde
                borderWidth: 3,// Ancho del borde
                yAxisID : eje,
                pointRadius: 0,
                cubicInterpolationMode: 'monotone',
                tension: -0.2,
                hidden :info[boleto].config[1],
                fill: fillx,
                spanGaps: true,
                datalabels: {
                    display: displayX,
                    clip :'true',
                    clamp :'true',
                    align: 'start',  
                    //anchor:'start' 
                  },
            };
            dataGrafica.push(obj);
        }
        //console.log(cadena[i]);
    }
    console.log(dataGrafica);
    if (typeof X1 !== 'undefined') {X1.destroy();}
    const getOrCreateLegendList = (chart, id) => {
        const legendContainer = document.getElementById(id);
        let listContainer = legendContainer.querySelector('div');  
        if (!listContainer) {
          listContainer = document.createElement('div');
          listContainer.className = "row justify-content-center row-cols-4 row-cols-sm-4 row-cols-md-4 ";
          listContainer.style.display = 'flex';
          listContainer.style.flexDirection = 'row';
          listContainer.style.margin = 0;
          listContainer.style.padding = 0;
          legendContainer.appendChild(listContainer);
        } 
        return listContainer;
      };  
      const htmlLegendPlugin = {
        id: 'htmlLegend',
        afterUpdate(chart, args, options) {
          const ul = getOrCreateLegendList(chart, options.containerID);
          // Remove old legend items
          while (ul.firstChild) {
            ul.firstChild.remove();
          }
          // Reuse the built-in legendItems generator
          const items = chart.options.plugins.legend.labels.generateLabels(chart);
          items.forEach(item => {
            const sdiv = document.createElement('div');
            sdiv.style.paddingLeft = '2px';
            sdiv.style.paddingRight = '2px';
            sdiv.className = "col-4 col-lg-1 col-md-2 col-sm-3";
            //sdiv.addClass('col-xs-6 col-1 ');
            //sdiv.class = 'col-xs-6 col-1 ';
            sdiv.id = item.text;
            cambio ="'"+item.text+"'";
            //papa=document.querySelector('Set Point');
            //papa.className='col-xs-6 col-1';
            //$(cambio).addClass('col-xs-6 col-1 ');
            //$(cambio).addClass('col-xs-6 col-1 ');
            //sdiv[item.text].addClass('col-xs-6 col-1 ');
            //style="padding-left: 2px;padding-right: 2px;"
            //sdiv.style.flexDirection = 'row';
            //sdiv.style.marginLeft = '10px';
            const li = document.createElement('li');
            li.style.alignItems = 'center';
            li.style.cursor = 'pointer';
            li.style.display = 'flex';
            li.style.flexDirection = 'row';
            li.style.marginLeft = '10px'; 
            li.onclick = () => {
              const {type} = chart.config;
              if (type === 'pie' || type === 'doughnut') {
                // Pie and doughnut charts only have a single dataset and visibility is per item
                chart.toggleDataVisibility(item.index);
              } else {
                chart.setDatasetVisibility(item.datasetIndex, !chart.isDatasetVisible(item.datasetIndex));
              }
              chart.update();
            };
            // Color box
            const boxSpan = document.createElement('span');
            boxSpan.style.background = item.fillStyle;
            boxSpan.style.borderColor = item.strokeStyle;
            boxSpan.style.borderWidth = item.lineWidth + 'px';
            boxSpan.style.display = 'inline-block';
            boxSpan.style.flexShrink = 0;
            boxSpan.style.height = '20px';
            boxSpan.style.marginRight = '10px';
            boxSpan.style.width = '20px';
            // Text
            const textContainer = document.createElement('p');
            textContainer.style.color = item.fontColor;
            textContainer.style.margin = 0;
            textContainer.style.padding = 0;
            textContainer.style.textDecoration = item.hidden ? 'line-through' : '';
            tx = item.text;
            tx1=tx.split(' ');
            //const text = document.createTextNode(item.text);
            if(tx1[0].length >7){
                tx1[0]=tx1[0].substr(-20, 8);
            }
            const text = document.createTextNode(tx1[0]);   
            textContainer.appendChild(text);   
            li.appendChild(boxSpan);
            li.appendChild(textContainer);
            sdiv.appendChild(li);
            ul.appendChild(sdiv);
            //ul.appendChild(li);
          });
        }
      };
      const textCenter = {
        id:'textCenter',
        afterDatasetsDraw(chart,args,plugins){
            const {ctx,chartArea:{top,bottom,left,right,width,height}}=chart;
            ctx.save();
            ctx.font = 'bold 15px sans-serif';
            ctx.fillStyle ='grey';
            ctx.fillText(tituloGrafica.textContent,(width*45)/100 ,(height*9)/10);
        }
      }
      const plugin = {
        id : 'customCanvasBackgroundColor',
        beforeDraw : (chart ,args ,options) => {
          const {ctx} = chart;
          ctx.save();
          ctx.globalCompositeOperation = 'destination-over';
          ctx.fillStyle = options.color || '#000000';
          ctx.fillRect(0,0,chart.width,chart.height);
          ctx.restore();
        }
      }
    X1 =new Chart(grafica1, {
        type: 'line',// Tipo de gráfica
        data: {
            labels: info['created_at'].data,
            datasets: dataGrafica,
        },
        options: {
            animation: {
                onComplete: function () {
                    bajarGrafica.href= X1.toBase64Image();
                    bajarGrafica.download = tituloGrafica.textContent+"_"+fechaInicial.value+"_"+fechaFin.value;                   
                },
            },
            responsive : true,
            //aspectRatio:3|1,
            backgroundColor: '#fff',
            interaction :{
                mode : 'index',
                intersect :false,
            },
            stacked :false,
            scales: {
                //min:3,
                x:{
                    type:'time',
                    //display: false,
                    //position: 'right',
                    //beginAtZero: true,
                    title: {
                        display: true,
                        text: 'ZTRACK - Live Telematic',
                        color: '#212529',
                        font: { 
                            size: 20,
                            style: 'normal',
                            lineHeight: 1.1
                        },
                        padding: {top: -5, left: 0, right: 0, bottom: 0}
                      },
                    //offset:true,
                    alignToPixels:true,
                    time:{
                        minUnit:'minute',
                    },
                    clip :false,
                    ticks:{
                        major:{
                            enabled:true,
                            width:4
                        },
                        font :(context)=>{
                            //console.log(context.tick && context.tick.major)
                            const boldedTicks = context.tick && context.tick.major ? 'bold' :'';
                            return {weight:boldedTicks}
                        },
                        //padding:15,
                    }
                },
                y: {
                    type: 'linear',
                    position: 'left',
                    display: true,
                    title: {
                        display: false,
                        text: 'temperature',
                        color: '#1a2c4e',
                        //reverse:true,
                        font: {     
                            size: 20,
                            style: 'normal',
                            lineHeight: 1.2
                        },
                        padding: {top: 30, left: 0, right: 0, bottom: 0}
                    },
                    ticks:{
                        color:"blue",
                        callback :(value,index,ticks) =>{
                            return `${value}${c_f(temp1)}\u00B0`;
                        }
                    },
                    suggestedMin: c_f(temp1,10),
                    suggestedMax: c_f(temp1,20)
                },
                y1: {
                    type: 'linear',
                    display: false,
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: false,
                        text: 'Ethylene(ppm)',
                        color: '#1a2c4e',
                        font: { 
                            size: 20,
                            style: 'bold',
                            lineHeight: 1.2
                        },
                        padding: {top: 30, left: 0, right: 0, bottom: 0}
                      },
                      suggestedMin: 0,
                      suggestedMax: 10,
                      grid: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                      },
                 },
                y2: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: false,
                        text: 'Percentage (%)',
                        color: '#1a2c4e',
                        font: {                      
                            size: 20,
                            style: 'normal',
                            lineHeight: 1.2
                        },
                        padding: {top: 30, left: 0, right: 0, bottom: 0}
                    },
                    ticks:{
                        color:"red",
                        callback :(value,index,ticks) =>{
                            return `${value}\u2052`;
                        }
                    },
                    grid: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                    suggestedMin: 0,
                    suggestedMax: 100,
                },
            },
            plugins: {
                htmlLegend: {
                    // ID of the container to put the legend in
                    containerID: 'legend-container',
                  },
                datalabels: {
                    color: function(context) {
                      return context.dataset.backgroundColor;
                    },
                    font: {
                      weight: 'bold'
                    },          
                    padding: 6,
                  },
                title: {
                    display: false,
                    text: "prueba",
                    color: '#1a2c4e',
                    font: {                        
                        size: 30,
                        style: 'normal',
                        lineHeight: 1.2
                    },
                    padding: {top: 30, left: 0, right: 0, bottom: 0}
                },
                zoom: {
                    limits: {
                        x: {min: 'original', max: 'original',minRange:2}
                     },
                    pan :{
                        enabled :true,
                        mode: 'x',
                    },


                     //minRange:10000,
                    zoom: {
                        wheel: {
                            enabled: true,
                            speed:0.05
                        },
                        pinch: {
                            enabled: true
                        },
                        drag:{
                            enabled:false
                        },

                        mode: 'x',

                        //scaleMode :'x',
                    }
                },
                customCanvasBackgroundColor : {
                    color :'#fff',
                },
                legend : {
                    display:false,
                    position :'top',
                    align : 'center',
                    labels : {
                        boxWidth :20 ,
                        boxHeight : 20,
                        color :'#1a2c4e',
                        padding :15 ,
                        textAlign : 'left',
                        font: {
                            size: 12,
                            style: 'normal',
                            lineHeight: 1.2
                          },
                        title : {
                            text :'Datos Graficados:',
                        },
                    },
                },

            }           
        },
        plugins : [plugin,ChartDataLabels,htmlLegendPlugin,textCenter],       
    })
    $("#interfazGrafica").modal("show");
    //setInterval(  function(){ $(".loader").fadeOut("fast"); }, 1000);

}

async function graficaMadurador(info){
    console.log(info);
    CondicionanteZ = info.telemetria_id[0];

    const config1 = {method: 'get',dataType: 'json', url: '../../ztrack4/controllers/principalController.php?option=consultaTelemetriaMadurador&id='  + CondicionanteZ }

    const dataTelemetria =  await axios(config1);
    console.log(dataTelemetria.data.nombre_contenedor);
    console.log("olita");
    console.log(dataTelemetria.data);

    textotemperatura="Temperature( C°)" ;
    tel= info.telemetria_id[0];

    if(tel==4584 ||tel==4586 ||tel==4587  || tel==4588 ||tel==4589 ||tel==33 || tel==258 ||tel==259 ||tel==260  || tel==4500 ||tel==4487 ) {
        textotemperatura="Temperature( F°)";
    }
    ///if (typeof X1 !== 'undefined' && typeof X2 !== 'undefined') {X1.destroy();X2.destroy();}
    if (typeof X1 !== 'undefined') {X1.destroy();}
 
   longitudA = info.fecha.length ;
   bloques =6;
   general =[];
   generalF =[];
   generalH =[];

   generalA =[];
   generalT =[];
   generalE =[];
   generalET =[];
   generalSE =[];
   generalC =[];
   generalSP =[];
   generalI =[];
   generalUSDA =[];
   generalUSDA2 =[];
   generalUSDA3 =[];
   generalUSDA4 =[];
   generalO =[];

   generalPWD =[];

   longitudB = Math.trunc(longitudA/bloques);
   plano1 = info.returnAir;
   plano2 = info.relativeHumidity;
   plano3 = info.ambienteAir;
   plano4 = info.tempSupply;
   plano5 = info.evaporationCoil;
   plano6 = info.D_ethylene;
   plano7 = info.sp_ethylene;
   plano8 = info.co2;
   plano9 = info.setPoint;
   plano10 = info.inyeccionEtileno;
   plano11 = info.inyeccion_pwm;
   plano12 = info.cargo_1_temp
   plano13 = info.objetivo
   plano14 = info.cargo_2_temp
   plano15 = info.cargo_3_temp
   plano16 = info.cargo_4_temp
   
   planoTelemetria = info.telemetria_id;


   // CONFIGURACION NORMAL
   g1 = true;
   g2 = true;
   g3 = true;
   g4 = true;
   g5 = true;
   g6 = false;
   g7 = false;
   g8 = true;
   g9 = true;
   g10 = true;
   g11 =false;
   g12 = true;
   g13 = true;
   g14 =true;
   g15 = true;
   g16 = true;

extra1 =dataTelemetria.data.extra_1;
divece =dataTelemetria.data.nombre_contenedor;
descrip = dataTelemetria.data.descripcionC;
 
if(extra1==1){
    nombreMadurador="Ripener Monitoring   Data"+divece+"("+descrip+")";
}else if (extra1==2){
    nombreMadurador="Tunel Monitoring Data"+divece+"("+descrip+")";
    g1 = false;
    g2 = true;
    g3 = true;
    g4 = true;
    g5 = true;
    g6 = true;
    g7 = true;
    g8 = true;
    g9 = true;
    g10 = true;
    g11 =true;
    g12 =false;
    g13 = false;
    g14 =false;
    g15 = false;
    g16 = false;

}else{
    nombreMadurador="Reefer Monitoring Data  "+divece+"("+descrip+")";
    g1 = false;
    g2 = false;
    g3 = true;
    g4 = false;
    g5 = false;
    g6 = true;
    g7 = true;
    g8 = true;
    g9 = false;
    g10 = true;
    g11 =true;
    g12 = true;
    g13 = true;
    g14 =true;
    g15 = true;
    g16 = true;
}


   planoF = info.fecha;

   for(let i=1 ;i<=bloques ;i++){
     if(i==bloques){
        general[i]=plano1.slice((longitudB*(i-1)),(longitudA+1));
        generalH[i]=plano2.slice((longitudB*(i-1)),(longitudA+1));

        generalA[i]=plano3.slice((longitudB*(i-1)),(longitudA+1));
        generalT[i]=plano4.slice((longitudB*(i-1)),(longitudA+1));
        generalE[i]=plano5.slice((longitudB*(i-1)),(longitudA+1));
        generalET[i]=plano6.slice((longitudB*(i-1)),(longitudA+1));
        generalSE[i]=plano7.slice((longitudB*(i-1)),(longitudA+1));
        generalC[i]=plano8.slice((longitudB*(i-1)),(longitudA+1));
        generalSP[i]=plano9.slice((longitudB*(i-1)),(longitudA+1));
        generalI[i]=plano10.slice((longitudB*(i-1)),(longitudA+1));

        generalUSDA[i]=plano12.slice((longitudB*(i-1)),(longitudA+1));
        generalO[i]=plano13.slice((longitudB*(i-1)),(longitudA+1));

        generalUSDA2[i]=plano14.slice((longitudB*(i-1)),(longitudA+1));
        generalUSDA3[i]=plano15.slice((longitudB*(i-1)),(longitudA+1));
        generalUSDA4[i]=plano16.slice((longitudB*(i-1)),(longitudA+1));

        //PWD
        generalPWD[i]=plano11.slice((longitudB*(i-1)),(longitudA+1));
        //general.push([plano1.slice((longitudB*(i-1)),(longitudA+1))]);
        generalF[i]=planoF.slice((longitudB*(i-1)),(longitudA+1));
        //generalF.push([planoF.slice((longitudB*(i-1)),(longitudA+1))]);
       // general.j = plano1.slice((longitudB*(i-1)),(longitudA+1));

     }else{
        general[i] = plano1.slice((longitudB*(i-1)),(longitudB*(i)));
        generalH[i] = plano2.slice((longitudB*(i-1)),(longitudB*(i)));

        generalA[i] = plano3.slice((longitudB*(i-1)),(longitudB*(i)));
        generalT[i] = plano4.slice((longitudB*(i-1)),(longitudB*(i)));
        generalE[i] = plano5.slice((longitudB*(i-1)),(longitudB*(i)));
        generalET[i] = plano6.slice((longitudB*(i-1)),(longitudB*(i)));
        generalSE[i] = plano7.slice((longitudB*(i-1)),(longitudB*(i)));
        generalC[i] = plano8.slice((longitudB*(i-1)),(longitudB*(i)));

        generalSP[i] = plano9.slice((longitudB*(i-1)),(longitudB*(i)));
        generalI[i] = plano10.slice((longitudB*(i-1)),(longitudB*(i)));

        generalUSDA[i] = plano12.slice((longitudB*(i-1)),(longitudB*(i)));
        generalO[i] = plano13.slice((longitudB*(i-1)),(longitudB*(i)));

        generalUSDA2[i] = plano14.slice((longitudB*(i-1)),(longitudB*(i)));
        generalUSDA3[i] = plano15.slice((longitudB*(i-1)),(longitudB*(i)));
        generalUSDA4[i] = plano16.slice((longitudB*(i-1)),(longitudB*(i)));
        
        //general.push([plano1.slice((longitudB*(i-1)),(longitudB*(i)))]);
        //PWD
        generalPWD[i]=plano11.slice((longitudB*(i-1)),(longitudB*(i)));

        generalF[i]=planoF.slice((longitudB*(i-1)),(longitudB*(i)));
        //generalF.push([planoF.slice((longitudB*(i-1)),(longitudB*(i)))]);
        //general.push({id:i , data:plano1.slice((longitudB*(i-1)),(longitudB*(i)))});
        //general.i = plano1.slice((longitudB*(i-1)),(longitudB*(i)));
     }
   }
   /*
   console.log(general);
   console.log(generalF);
   console.log(general[1]);
   console.log(generalF[1]);
   */

   function saveImage (base64,dispositivo,semana) {
    //console.log(base64);
    var data = base64.replace(/^data:image\/png;base64,(.+)$/, '$1');
    //console.log(data);
    //var data =base64;
    var request = new XMLHttpRequest();
    var dispositivo1 = dispositivo;
    var semana1 = semana;
    request.open('POST', 'saveImageOnServer.php', true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send('imageData=' + base64);
    //request.send('nombre_dispositivo=' + dispositivo1);
    //request.send('semana=' + semana);
}


   
    X1 =new Chart(grafica1, {
        type: 'line',// Tipo de gráfica
        data: {
            labels: info.fecha,
            datasets: [
                {
                    label : " Return ",
                    data : general[1],
                    backgroundColor: '#ec7063', // Color de fondo
                    borderColor: '#ec7063', // Color del borde
                    borderWidth: 3,// Ancho del borde
                    yAxisID : 'y',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4,
                    hidden :g1,
                    datalabels: {
                        display: 'auto',
                        clip :'true',
                        clamp :'true',
                        align: 'end',   
                      },
                },
                {
                    label : "Humidity",
                    data : generalH[1],
                    backgroundColor: '#e4c1f4', // Color de fondo
                    borderColor: '#e4c1f4', // Color del borde4476c6
                    borderWidth: 3,// Ancho del borde
                    yAxisID : 'y2',
                    pointRadius : 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4,
                    hidden :g2,
                    datalabels: {
                        display: 'auto',
                        clip :'true',
                        clamp :'true',
                        align: 'end',   
                      },
                },
                {
                    label : " Ambient",
                    data : generalA[1],
                    backgroundColor: '#9ccc65', // Color de fondo
                    borderColor: '#9ccc65', // Color del borde
                    borderWidth: 3,// Ancho del borde
                    yAxisID : 'y',
                    pointRadius : 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4,
                    hidden :g3,
                    datalabels: {
                        display: 'auto',
                        clip :'true',
                        clamp :'true',
                        align: 'end',   
                      },
                },
                {
                    label : " Supply",
                    data : generalT[1],
                    backgroundColor: '#27ae60', // Color de fondo
                    borderColor: '#27ae60', // Color del borde
                    borderWidth: 3,// Ancho del borde
                    yAxisID : 'y',
                    pointRadius : 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4,
                    hidden :g4,
                    datalabels: {
                        display: 'auto',
                        clip :'true',
                        clamp :'true',
                        align: 'end',   
                      },
                },
                {
                    label : " Evap",
                    data :generalE[1] ,
                    backgroundColor: '#95a5a6', // Color de fondo
                    borderColor: '#95a5a6', // Color del borde
                    borderWidth: 3,
                    yAxisID : 'y',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 ,
                    hidden :g5,
                    datalabels: {
                        //display: 'false', 
                        labels: {
                            title: null
                          } 
                      },
                },
                {
                    label : " Ethylene",
                    data :generalET[1] ,
                    backgroundColor: '#973d37', // Color de fondo
                    borderColor: '#973d37', // Color del borde
                    borderWidth: 3,
                    yAxisID : 'y1',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 ,
                    hidden :g6,
                    datalabels: {
                        display: 'auto',
                        clip :'true',
                        clamp :'true',
                        align: 'end',   
                      },
                },
                {
                    label : " SP Ethy",
                    data : generalSE[1] ,
                    backgroundColor: '#d80014', // Color de fondo 973d37
                    borderColor: '#d80014', // Color del borde 95a5z6 d85494
                    borderWidth: 3,
                    yAxisID : 'y1',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 , 
                    hidden :g7,
                    datalabels: {
                       //display: 'false',
                       labels: {
                           title: null
                         } 
                     },    
                },
                {
                    label : " Co2",
                    data : generalC[1],
                    backgroundColor: '#d85494', // Color de fondo 973d37
                    borderColor: '#d85494', // Color del borde 95a5z6 d85494
                    borderWidth: 3,
                    yAxisID : 'y2',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 ,
                    hidden :g8,
                    datalabels: {
                       display: 'auto',
                       clip :'true',
                       clamp :'true',
                       align: 'end',   
                     },
                },
                {
                    label : " SetPoint",
                    data : generalSP[1] ,
                    backgroundColor: '#f1c40f', // Color de fondo
                    borderColor: '#f1c40f', // Color del borde
                    borderWidth: 3,
                    yAxisID : 'y',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 ,
                    hidden :g9,
                    datalabels: {
                        //display: 'false', 
                        labels: {
                            title: null
                          }  
                      },
                },
                {
                    label : " PWD",
                    data :  generalPWD[1] ,
                    backgroundColor: '#270e60', // Color de fondo
                    borderColor: '#270e60', // Color del borde
                    borderWidth: 1,
                    yAxisID : 'y2',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 ,
                    //fill: true, 
                    hidden :g10, 
                    datalabels: {
                        //display: 'false',  
                        labels: {
                            title: null
                          } 
                      },
                },
                {
                    label : " Inyected",
                    data :  generalI[1] ,
                    backgroundColor: '#f7f2e2', // Color de fondo
                    borderColor: '#f7f2e2', // Color del borde
                    borderWidth: 1,
                    yAxisID : 'y2',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 ,
                    hidden :g11,
                    fill: true,  
                    datalabels: {
                        //display: 'false',  
                        labels: {
                            title: null
                          } 
                      },
                },
                {
                    label : " USDA",
                    data :generalUSDA[1] ,
                    backgroundColor: '#973d37', // Color de fondo
                    borderColor: '#973d37', // Color del borde
                    borderWidth: 3,
                    yAxisID : 'y',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 ,
                    hidden :g12,
                    datalabels: {
                        display: 'auto',
                        clip :'true',
                        clamp :'true',
                        align: 'end',   
                      },
                },
                {
                    label : " OBJETIVO CARGA ",
                    data :generalO[1] ,
                    backgroundColor: '#d80014', // Color de fondo
                    borderColor: '#d80014', // Color del borde
                    borderWidth: 3,
                    yAxisID : 'y',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 ,
                    hidden :g13,
                    datalabels: {
                        //display: 'false',  
                        labels: {
                            title: null
                          } 
                      }, 
                },
                {
                    label : " USDA 2",
                    data :generalUSDA2[1] ,
                    backgroundColor: '#973d37', // Color de fondo
                    borderColor: '#973d37', // Color del borde
                    borderWidth: 3,
                    yAxisID : 'y',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 ,
                    hidden :g14,
                    datalabels: {
                        display: 'auto',
                        clip :'true',
                        clamp :'true',
                        align: 'end',   
                      },
                },
                {
                    label : " USDA 3",
                    data :generalUSDA3[1] ,
                    backgroundColor: '#973d37', // Color de fondo
                    borderColor: '#973d37', // Color del borde
                    borderWidth: 3,
                    yAxisID : 'y',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 ,
                    hidden :g15,
                    datalabels: {
                        display: 'auto',
                        clip :'true',
                        clamp :'true',
                        align: 'end',   
                      },
                },
                {
                    label : " USDA 4",
                    data :generalUSDA4[1] ,
                    backgroundColor: '#973d37', // Color de fondo
                    borderColor: '#973d37', // Color del borde
                    borderWidth: 3,
                    yAxisID : 'y',
                    pointRadius: 0,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4 ,
                    hidden :g16,
                    datalabels: {
                        display: 'auto',
                        clip :'true',
                        clamp :'true',
                        align: 'end',   
                      },
                },
                           
            ]
        },

        options: {
            animation: {
                onComplete: function () {
                        var today = moment().format("DD-MM-YYYY_HH-mm-ss");
                        //var dispositivoGrafica = info.madurador.nombre_contenedor;  
                        console.log(X1.toBase64Image()) ;     
                        bajarGrafica.href= X1.toBase64Image();
                        //bajarGrafica.download =''+dispositivoGrafica+'_'+today;
                        bajarGrafica.download ='datos'+today;
                        //var imagen = X1.toBase64Image();
                        //var imagen = grafica1.toDataURL("image/png");
                        //console.log(imagen);
                        saveImage(X1.toBase64Image(),'ZGRU1200200',47);
                        //console.log(bajarGrafica);
                          //bajarGrafica.click(); 

                          //var data = imagen.replace(/^data:image\/png;base64,(.+)$/, '$1');
                          //console.log(data);
                          //var data =base64;
                          //var request = new XMLHttpRequest();
                         // var dispositivo1 = dispositivo;
                          //var semana1 = semana;
                          //request.open('POST', 'saveImageOnServer.php', true);
                          //request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                          //request.send('imageData=' + data);
             
                },
              },

            responsive : true,
            backgroundColor: '#fff',
            interaction :{
                mode : 'index',
                intersect :false,
            },
            stacked :false,
            scales: {

                y: {
                    position: 'left',
                    display: true,
                    title: {
                        display: true,
                        text: textotemperatura,
                        color: '#1a2c4e',
                        font: {     
                            size: 20,
                            style: 'normal',
                            lineHeight: 1.2
                        },
                        padding: {top: 30, left: 0, right: 0, bottom: 0}
                    },
                    suggestedMin: 0,
                    suggestedMax: 20
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Ethylene(ppm)',
                        color: '#1a2c4e',
                        font: { 
                            size: 20,
                            style: 'normal',
                            lineHeight: 1.2
                        },
                        padding: {top: 30, left: 0, right: 0, bottom: 0}
                      },
                      suggestedMin: 0,
                      suggestedMax: 10,
                      grid: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                      },
                 },
                y2: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Percentage (%)',
                        color: '#1a2c4e',
                        font: {                      
                            size: 20,
                            style: 'normal',
                            lineHeight: 1.2
                        },
                        padding: {top: 30, left: 0, right: 0, bottom: 0}
                    },
                    grid: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                    suggestedMin: 0,
                    suggestedMax: 100,
                },
            },
            plugins: {
                datalabels: {
                    color: function(context) {
                      return context.dataset.backgroundColor;
                    },
                    font: {
                      weight: 'bold'
                    },          
                    padding: 6,
    
                  },
                title: {
                    display: true,
                    text: nombreMadurador,
                    color: '#1a2c4e',
                    font: {                        
                        size: 30,
                        style: 'normal',
                        lineHeight: 1.2
                    },
                    padding: {top: 30, left: 0, right: 0, bottom: 0}
                },
                zoom: {
                    pan :{
                        enabled :true,
                        mode: 'x',
                    },
                    zoom: {
                        wheel: {
                            enabled: true,
                        },
                        pinch: {
                            enabled: true
                        },
                        mode: 'x',
                        drag :{
                            enabled: false,
                        },
                        scaleMode :'x',
                    }
                },
                customCanvasBackgroundColor : {
                    color :'#fff',
                },
                legend : {
                    position :'right',
                    align : 'center',
                    labels : {
                        boxWidth :20 ,
                        boxHeight : 20,
                        color :'#1a2c4e',
                        padding :15 ,
                        textAlign : 'left',
                        font: {
                            size: 12,
                            style: 'normal',
                            lineHeight: 1.2
                          },
                        title : {
                            text :'Datos Graficados:',
                        },
                    },
                },

            }           
        },
        plugins : [plugin,ChartDataLabels],

            
    })
    for(let j=2;j<=bloques;j++){
    setTimeout(function(){
    const data =X1.data;
    //X1.data.labels = generalF[1].concat(generalF[2]);
    data.datasets[0].data =  data.datasets[0].data.concat(general[j]);
    data.datasets[1].data =  data.datasets[1].data.concat(generalH[j]);

    data.datasets[2].data =  data.datasets[2].data.concat(generalA[j]);
    data.datasets[3].data =  data.datasets[3].data.concat(generalT[j]);
    data.datasets[4].data =  data.datasets[4].data.concat(generalE[j]);
    data.datasets[5].data =  data.datasets[5].data.concat(generalET[j]);
    data.datasets[6].data =  data.datasets[6].data.concat(generalSE[j]);
    data.datasets[7].data =  data.datasets[7].data.concat(generalC[j]);
    data.datasets[8].data =  data.datasets[8].data.concat(generalSP[j]);
    data.datasets[9].data =  data.datasets[9].data.concat(generalPWD[j]);
    data.datasets[10].data =  data.datasets[10].data.concat(generalI[j]);
    data.datasets[11].data =  data.datasets[11].data.concat(generalUSDA[j]);
    data.datasets[12].data =  data.datasets[12].data.concat(generalO[j]);
    //data.datasets[10].data =  data.datasets[10].data.concat(generalI[j]);
    data.datasets[13].data =  data.datasets[13].data.concat(generalUSDA2[j]);
    data.datasets[14].data =  data.datasets[14].data.concat(generalUSDA3[j]);
    data.datasets[15].data =  data.datasets[15].data.concat(generalUSDA4[j]);
    X1.update();
    }, 100);
    }

    console.timeEnd('loop');
}



