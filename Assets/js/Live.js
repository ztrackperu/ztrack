console.log(empresa_id);
const grafica1 = document.getElementById("graficaFinal");
carruselExtra = document.getElementById("carruselExtra");
extraerdata =[];
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
document.addEventListener("DOMContentLoaded", async function(){
    try{
        const response = await fetch(base_url + "Live/ListaDispositivoEmpresa",{method: 'GET'});
        const data = await response.json();
        console.log(data);
        data.data.forEach((contenedor, indice) => pintarCirculo(contenedor,indice));
        //insertar en texto la data 
        //console.log(data.text);
        carruselExtra.innerHTML  =data.text;
        //console.log(data.extraer);
        extraerdata = data.extraer ;
        //console.log(extraerdata);

    }catch(err){alert(err);}
    //cada 10 segundos ejecutar 
    setInterval( async function(){ okey =  await obtenerCambio();}, 10000);
})
function saludos(){
    console.log("oli pablito");
}
//graficaM
async function graficaM(id){
    //console.log(id);
    const response = await fetch(base_url + "Live/GraficaInicial/"+id, {method: "GET", });
    const result = await response.json();
    //console.log(result);
    graph = await graficaMadurador1(result.graph,result.cadena);
    return result;
}
async function obtenerCambio() {
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
    return result;
}
function tarjeta(res){
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
async function graficaMadurador1(info,cadena){
    //console.log(info);
    //console.log(cadena);
    dataGrafica =[];
    for (var i = 0; i < cadena.length; i++) {
        if(cadena[i]!='created_at'){
            boleto =cadena[i];
            //console.log(info[boleto]);
            if(info[boleto].config[3]==1){eje = "y";
            }else if(info[boleto].config[3]==3){eje = "y2";
            }else{eje="y1";}
            if(info[boleto].config[3]==4){fillx=true;}else{fillx=false;}
            obj = {
                label : info[boleto].config[0],
                data : info[boleto].data,
                backgroundColor: info[boleto].config[2], // Color de fondo
                borderColor: info[boleto].config[2], // Color del borde
                borderWidth: 3,// Ancho del borde
                yAxisID : eje,
                pointRadius: -0.2,
                cubicInterpolationMode: 'monotone',
                tension: 0,
                hidden :info[boleto].config[1],
                fill: fillx,
            };
            dataGrafica.push(obj);
        }
        //console.log(cadena[i]);
    }
    console.log(dataGrafica);
    if (typeof X1 !== 'undefined') {X1.destroy();}


    X1 =new Chart(grafica1, {
        type: 'line',// Tipo de gráfica
        data: {
            labels: info['created_at'].data,
            datasets: dataGrafica,
        },


        options: {
            animation: {
                onComplete: function () {
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
                x:{
                    type:'time',
                    ticks:{
                        major:{
                            enabled:true,
                            width:4
                        },
                        font :(context)=>{
                            console.log(context.tick && context.tick.major)
                            const boldedTicks = context.tick && context.tick.major ? 'bold' :'';
                            return {weight:boldedTicks}
                        },
                        //padding:15,
                    }

                },

                y: {
                    position: 'left',
                    display: true,
                    title: {
                        display: false,
                        text: 'temperature',
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
                    display: false,
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: false,
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
                    grid: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                    suggestedMin: 0,
                    suggestedMax: 100,
                },
            },
            plugins: {
                decimation:{
                    enabled:true,
                    algorithm:'lttb',
                    samples:50
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
        //plugins : [plugin,ChartDataLabels],       
    })
    $("#interfazGrafica").modal("show");
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
