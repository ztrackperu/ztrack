console.log(empresa_id);

var zgroup = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    minZoom :3,
    attribution: 'ZGROUP &copy; lupamape contributors'
});
  var map = L.map('map',{zoom:3, center: new L.latLng([11.04,-70]),zoomControl:false,layers:[zgroup]});
  var markers;
markers = new L.LayerGroup().addTo(map);
var markers1 = L.markerClusterGroup({ singleMarkerMode: true});

function elcolor(d){
    return d==1 ?'green':
           d==2 ?'orange':
           d==3 ?'white':
           'black';
}

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
