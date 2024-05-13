let tblConceptosOT;
const grafica1 = document.getElementById("badCanvas1");



function alertas(msg, icono) {
    Swal.fire({
        position: 'top-end',
        icon: icono,
        title: msg,
        showConfirmButton: false,
        timer: 3000
    })
}

document.addEventListener("DOMContentLoaded", function(){

    //w = new Chart(grafica1, {});
      $('#concepto2').select2({
        //language:"es",
        //closeOnSelect: false,
        placeholder: 'Buscar Concepto',
        minimumInputLength: 2,
        //allowClear: true,
        delay: 250,
        ajax: {
            url: base_url + 'ConceptosOT/buscarConcepto',
            dataType: 'json',
            //delay: 250, 
            data: function (params) {
                
                console.log(params);        
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
    $("#insumosL").select2({
        //language:"es",
        closeOnSelect: false,
        //placeholder: 'Buscar Concepto',
        minimumInputLength: 3,
        //allowClear: true,
        //delay: 250,
        ajax: {
            url: base_url + 'ConceptosOT/buscarInsumos',
            dataType: 'json',
            //delay: 250, 
            data: function (params) {
                
                console.log(params);        
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
   
    const language = {
        "decimal": "",
        "emptyTable": "sin registro",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }

    }
    const  buttons = [{
                //Botón para Excel
                extend: 'excel',
                footer: true,
                title: 'Archivo',
                filename: 'Export_File',

                //Aquí es donde generas el botón personalizado
                text: '<button class="btn btn-success"><i class="fa fa-file-excel-o"></i></button>'
            },
            //Botón para PDF
            {
                extend: 'pdf',
                footer: true,
                title: 'Archivo PDF',
                filename: 'reporte',
                text: '<button class="btn btn-danger"><i class="fa fa-file-pdf-o"></i></button>'
            },
            //Botón para print
            {
                extend: 'print',
                footer: true,
                title: 'Reportes',
                filename: 'Export_File_print',
                text: '<button class="btn btn-info"><i class="fa fa-print"></i></button>'
            }
        ]

    tblConceptosOT = $('#tblConceptosOT').DataTable({
        ajax: {
            url: base_url + "ConceptosOT/listar",
            dataSrc: ''
        },
        columns: [{
                'data': 'id'
            },
            {
                'data': 'codigo'
            },
            {
                'data': 'descripcion'
            },
            {
                'data': 'estado'
            },
            {
                'data': 'acciones'
            }
        ],
        language,
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons
    });
    
    tablaInsumos = $('#myTableInsumo').DataTable(
        {
            retrieve: true,
            paging: false,
            paging: false,
            searching: false,
            info: false,
            language
        });
        
})

// para borrar lo cargado de forma predefinada 
$('.form-control-concepto').on('select2:open', function (e) { 
    $('.form-control-concepto').val(null).trigger('change');
});


function btnEliminarConcepto(id) {
    Swal.fire({
        title: 'Esta seguro de eliminar?',
        text: "El Concepto no se eliminará de forma permanente",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "ConceptosOT/eliminar/" + id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    tblConceptosOT.ajax.reload();
                    alertas(res.msg, res.icono);
                }
            }
        }
    })
}


function btntReingresoConcepto(id) {
    Swal.fire({
        title: 'Esta seguro de reincorporar elemento?',
        text: "El Concepto volverá a estar activo",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "ConceptosOT/reingresar/" + id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    tblConceptosOT.ajax.reload();
                    alertas(res.msg, res.icono);
                }
            }
        }
    })
}
function registrarConcepto(e) {
    e.preventDefault();
    const Concepto = document.getElementById("descripcion_concepto");
    if (Concepto.value == "") {
        alertas('El Nombre del Concepto es requerida', 'warning');
    } else {
        const url = base_url + "ConceptosOT/registrar";
        const frm = document.getElementById("frmConceptosOT");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                $("#nuevoConcepto").modal("hide");
                frm.reset();
                tblConceptosOT.ajax.reload();
                alertas(res.msg, res.icono);
            }
        }
    }
}
function registrarConceptoVista(e) {
    e.preventDefault();
    const Concepto = document.getElementById("descripcion_concepto");
    if (Concepto.value == "") {
        alertas('El Nombre del Concepto es requerida', 'warning');
    } else {
        const url = base_url + "ConceptosOT/registrar";
        const frm = document.getElementById("frmConceptosOT");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = this.responseText;
                console.log(res)
                // Redirige a otra vista en lugar de ocultar el modal y resetear el formulario
                window.location.href = base_url + "ConceptosOT/";
            }
        }
    }
}


function btnEditarConcepto(id) {
    document.getElementById("title").textContent = "Modificar Concepto";
    document.getElementById("btnAccion").textContent = "Modificar";
    const url = base_url + "ConceptosOT/editar/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            console.log(res);
            document.getElementById("id").value = res.id;
            
            document.getElementById("codigo_concepto").value = res.codigo;
            document.getElementById("descripcion_concepto").value = res.descripcion;                
            $("#nuevoConcepto").modal("show");
        }
    }
}

function frmConceptosOT() {
    document.getElementById("title").textContent = "Nuevo Concepto";
    document.getElementById("btnAccion").textContent = "Registrar";
    document.getElementById("frmConceptosOT").reset();
    const url = base_url + "ConceptosOT/maximo/" ;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            console.log(res);
            //document.getElementById("id").value = res.id+1;        
            document.getElementById("codigo_concepto").value = res.codigo+1;          
        }
    }
    document.getElementById("id").value = "";
    $("#nuevoConcepto").modal("show");
}

  function tomarInsumos(){
    //var tablaInsumos = $('#myTableInsumo').DataTable({retrieve: true,paging: false});
    variable = $('#insumosL').select2('data');
    console.log(variable);
    if(variable.length!=0){
        var http = new XMLHttpRequest();
        var url = base_url + "ConceptosOT/agregarInsumo";
        //http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        http.open("POST", url, true);
        http.send(JSON.stringify({data:variable}));
        http.onreadystatechange = function() {
            if(http.readyState == 4 && http.status == 200) { 
                //limpiar select 2
                $('#insumosL').val(null).trigger('change');
                //console.log(JSON.parse(http.responseText));
                resul = JSON.parse(http.responseText);
                console.log(resul);
                //contra las filas 
                contInsumos = tablaInsumos.rows().count();
                console.log(contInsumos)
                if(contInsumos==0){
                    tablaInsumos.destroy();
                    console.log("esta vacio");
                     tablaInsumos = $('#myTableInsumo').DataTable({
                        paging: false,
                        searching: true,
                        info: false,
                        data: resul,
                        columns: [
                        { title: "Codigo", data: "IN_CODI" },
                        { title: "Descripcion", data: "IN_ARTI" },
                        { title: "Unidad", data: "IN_UVTA" },
                        { title: "Cantidad", data: "cantidad" },
                        { title: "Eliminar", data: "acciones" }
                        ]
                    });
                    //canti = table.rows().count();
                    //console.log(canti);
                }else{
                    //aqui debe agregar la data 
                    //primero comparar con la ya agregada 
                    //tomamos la agregada 
                    datosya =[]
                    let rows = tablaInsumos.rows(
                        //(idx, data) => data.location === 'Edinburgh'
                        (idx, data) => datosya.push(data.IN_CODI) 
                        //(idx, data) => datosya.push(data) 

                    );
                    //datos ya contiene la informacion de los que ya existen
                    //console.log(datosya)
                    //comparamos con los datos que tenemos
                    //console.log(resul);
                    sinrepetiones=[];
                    for(let i=0;i<resul.length;i++){
                        let element = resul[i].IN_CODI;
                        //console.log(element);
                        if(datosya.includes(element)){
                            //console.log(`coincide '${element}'`);
                        }else{
                            //console.log(`No coincide '${element}'`);
                            sinrepetiones.push(resul[i]) ;
                        }
                    }
                    console.log("hay data");
                    //aqui se guardan los elementos listo pa ser agregados 
                    console.log(sinrepetiones);
                    for(let i=0;i<sinrepetiones.length;i++){
                        //agregar fila en datatable
                        tablaInsumos.row.add(
                            //{ tamano: tamano, nombre: nombre }
                            {
                                IN_CODI: sinrepetiones[i].IN_CODI,
                                IN_ARTI: sinrepetiones[i].IN_ARTI,
                                IN_UVTA: sinrepetiones[i].IN_UVTA,
                                cantidad: sinrepetiones[i].cantidad,
                                acciones: sinrepetiones[i].acciones, 
                            }
                        
                        ).draw(false);
                    }
                }
            }
        }
    }else{
        Swal.fire({
            title: 'No se ha seleccionado ningun Insumo',
            text: "Los insumos  deben ser seleccionado",
            icon: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Entendido',
            //cancelButtonText: false
        })

    }
}

 async function fila(id){
    id1=id-1000;
    //tablaInsumos.destroy();
    datafila =[];
    /*
    $.get(base_url + "ConceptosOT/nuevaSeleccion/" + id1, (data, status) => {
        console.log(data);
        datafila.push(data);

    });
    */
   /*
    $.ajax({
        url: base_url + "ConceptosOT/nuevaSeleccion/" + id1,
        type: "GET",
        success: function (result) {
            console.log(result);
            datafila.push(result);
        },
    });
    */

    //console.log(datafila);
    //let response = await fetch(base_url + "ConceptosOT/nuevaSeleccion/"+id);
    let response = $.get(base_url + "ConceptosOT/nuevaSeleccion/" + id1, (data, status) => {
        //console.log(data);
    });
    //let userData = await response.json();
    return response;

}
async function lista1(id){
    //console.log(terrible);
    //console.log(conf);

        result1 =  await fila(id);  
        console.log(result1);        
        tablaInsumos.destroy();
        console.log("aqui esta");
        if(result1!="null"){
            res = JSON.parse(result1);
            console.log("esta vacio");
             tablaInsumos = $('#myTableInsumo').DataTable({
                paging: false,
                searching: true,
                info: false,
                data: res,
                columns: [
                { title: "Codigo", data: "IN_CODI" },
                { title: "Descripcion", data: "IN_ARTI" },
                { title: "Unidad", data: "IN_UVTA" },
                { title: "Cantidad", data: "cantidad" },
                { title: "Eliminar", data: "acciones" }
                ]
            });
        }
    
}
async function asignarConcepto(){
    variable = $('#concepto2').select2('data');
    if(variable.length!=0){
        codigo_concepto = document.getElementById("codigo_concepto").value ;
        descripcion_concepto = document.getElementById("descripcion_concepto").value ;
        console.log(variable);
        //console.log(codigo_concepto);
        //console.log(descripcion_concepto);
        if(codigo_concepto!="" && descripcion_concepto!=""){
            conf = [] ;
            /*
              Swal.fire({
                title: 'Esta seguro de reemplazar ?',
                text: "Ya tiene selecciono el concepto :"+descripcion_concepto,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Reemplazar',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("codigo_concepto").value = variable[0].id;
                    document.getElementById("descripcion_concepto").value = variable[0].text;
                    //conf.push(1);
                    //console.log(conf);
                    //lista1(variable[0].id)
                    // si reemplaza primero destruir la data 
                    //$('#myTableInsumo').DataTable().destroy();
                    //tablaInsumos.destroy(  
                  

                }
            });
            */
            document.getElementById("codigo_concepto").value = variable[0].id;
            document.getElementById("descripcion_concepto").value = variable[0].text;
            result1 = await fila(variable[0].id);
            res = JSON.parse(result1);
            console.log(res);
            tablaInsumos.destroy();
            console.log("esta vacio");
            if(res==null){
                res=[];
            }

             tablaInsumos = $('#myTableInsumo').DataTable({
                paging: false,
                searching: true,
                info: false,
                data: res,
                columns: [
                { title: "Codigo", data: "IN_CODI" },
                { title: "Descripcion", data: "IN_ARTI" },
                { title: "Unidad", data: "IN_UVTA" },
                { title: "Cantidad", data: "cantidad" },
                { title: "Eliminar", data: "acciones" }
                ]
            });

        }else{
            //reemplazar
            document.getElementById("codigo_concepto").value = variable[0].id;
            document.getElementById("descripcion_concepto").value = variable[0].text;
            result1 = await fila(variable[0].id);
            res = JSON.parse(result1);
            console.log(res);
            tablaInsumos.destroy();
            console.log("esta vacio");
             tablaInsumos = $('#myTableInsumo').DataTable({
                paging: false,
                searching: true,
                info: false,
                data: res,
                columns: [
                { title: "Codigo", data: "IN_CODI" },
                { title: "Descripcion", data: "IN_ARTI" },
                { title: "Unidad", data: "IN_UVTA" },
                { title: "Cantidad", data: "cantidad" },
                { title: "Eliminar", data: "acciones" }
                ]
            });    
        }
    }else{
        Swal.fire({
            title: 'No se ha seleccionado el Concepto',
            text: "El Concepto debe ser seleccionado",
            icon: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Entendido',
            //cancelButtonText: false
        })
    }
}

function btnEliminarInsumo(cod){
    //console.log(cod);
    
    Swal.fire({
        title: 'Esta seguro de Eliminar Insumo ?',
        text: "Insumo a Eliminar :"+cod,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            //identifico el elemento a eliminar 
            //$('#myTableInsumo').DataTable();
            trama = $('#myTableInsumo').DataTable()
            .row( 
                (idx, data) => data.IN_CODI===cod
             ).remove().draw();
             console.log(trama)
        }
    })
}

function procesarConceptoInsumo(){
    codigo_concepto = document.getElementById("codigo_concepto").value ;
    descripcion_concepto = document.getElementById("descripcion_concepto").value ;
    if(codigo_concepto!="" && descripcion_concepto!=""){
        var tablaInsumos = $('#myTableInsumo').DataTable({retrieve: true,paging: false});
        contInsumos = tablaInsumos.rows().count();
        if(contInsumos!=0){
            console.log("tenemos datos proceder");
            datosya=[];
            let rows = tablaInsumos.rows(
                //(idx, data) => data.location === 'Edinburgh'
                // identifica cada fila y lo gurada en el array datosya
                (idx, data) => datosya.push(data) 
                //(idx, data) => datosya.push(data) 

            );
            console.log(datosya);
            //crear el array 
            nuevojson =[];
            for(let i=0;i<datosya.length;i++){
                objet=[];
                objet['IN_CODI'] =datosya[i].IN_CODI ;
                objet['IN_ARTI'] =datosya[i].IN_ARTI ;
                objet['IN_UVTA'] =datosya[i].IN_UVTA ;
                objet['cantidad'] =4 ;


                
                //capturamos el valor de el input ingresado
                iden = "insumo_"+datosya[i].IN_CODI;
                console.log(iden);
                //insumo_INDND1120
                //valor1 = document.getElementById("insumo_INDND0270").value ;
                //console.log(valor1);

                //valor = document.getElementById("'"+iden+"'").value ;
                //objet['real'] =valor ;
                /*
                $("#"+iden).on('input',function (event){
                    console.log(event.target.value)
                    
              });
              */
             //valinput = $("'"+iden+"'").val();
             //INDND1509
             //CASACA TERMICAS MOD. CLASICO
             //valinput = $("#insumo_INDND1119").val();
             //aqui identificamos el valor del input
            //tomo el valor del input identificado
             valinput = $("#"+iden).val();

             //valinput = $("#".iden).val();

             console.log(valinput);
             objet['valor'] =valinput ;
              //creo el objeto pestandar de la API
             var objetivo = {
                IN_CODI: datosya[i].IN_CODI ,
                IN_ARTI: datosya[i].IN_ARTI,
                IN_UVTA: datosya[i].IN_UVTA,
                cantidad: valinput
              };
              //AÑADO CADA UNO DE ESOS OBJETOS A UNA LISTA
                nuevojson.push(objetivo) ;
                
            }
            console.log(nuevojson);
            //este enviar a actualizar en la base de datos
            var http = new XMLHttpRequest();
            var url = base_url + "ConceptosOT/registrarAsignacion";
            //http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            http.open("POST", url, true);
            http.send(JSON.stringify({data:nuevojson,id:codigo_concepto}));
            http.onreadystatechange = function() {
                if(http.readyState == 4 && http.status == 200) { 
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    setTimeout(function(){
                        window.location = base_url + "AdminPage";       
                    }, 1000);

                }
            }
        }else{
            Swal.fire({
                title: 'No se ha seleccionado Insumos para el concepto',
                text: "Los Insumos deben ser asignados al concepto",
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Entendido',
                //cancelButtonText: false
            })
        }
    }else{
        Swal.fire({
            title: 'No se ha seleccionado el Concepto',
            text: "El Concepto debe ser seleccionado",
            icon: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Entendido',
            //cancelButtonText: false
        })
    }
}


function btnAsignarInsumoConcepto(id){
    const url = base_url + "ConceptosOT/editar/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            console.log(res);
            document.getElementById("codigo_concepto").value = res.codigo;
            document.getElementById("descripcion_concepto").value = res.descripcion;       
            //window.location = base_url + "ConceptosOT/asignar";   

            /*
            document.getElementById("id").value = res.id;
            
            document.getElementById("codigo_concepto").value = res.codigo;
            document.getElementById("descripcion_concepto").value = res.descripcion;                
            $("#nuevoConcepto").modal("show");
            */
        }
    }

}

//generarGrafica

async function generarGrafica(){
    if (typeof w !== 'undefined') {
        w.clear();
        w.destroy();
    }
    variable = $('#concepto2').select2('data');
    //variableP = $('#periodoC').value();
    variableP = document.getElementById('periodoC').value;
    if(variable.length!=0){
        if(variableP="TOTAL"){
            //pedir el total
            const json = JSON.stringify({ concepto: variable[0].text });
            const res = await axios.post(base_url + "ConceptosOT/ConceptoPeriodo", json, {
                headers: {
                    // Overwrite Axios's automatically set Content-Type
                    'Content-Type': 'application/json'
                }
            });
            console.log (res.data); // '{"answer":42}'
            res1 = res.data;
            /*
            const config = {
                method: 'get',
                dataType: 'json',
                url: base_url + "ConceptosOT/ConceptoPeriodo"
            }
             const buena =  await axios(config);
             const res = buena.data;   
             console.log(res.data);
             */

             w = new Chart(grafica1, {
                type: 'bar',
                data: {
                    labels: res1.periodo,
                    datasets: [{
                      label: '# Grafica : '+variable[0].text ,
                      data: res1.data,
                      borderWidth: 1
                    }]
                },
        
        
                borderWidth: 1,
                options: {
                  scales: {
                    y: {
                      beginAtZero: true
                    }
                  }
                },
            })
        }else{
            //pedir por periodo
        }
        console.log(variable);
        console.log(variableP);



    }
    /*
    const config = {
        method: 'get',
        dataType: 'json',
        url: base_url + "ConceptosOT/ConceptoPeriodo"
    }
     const buena =  await axios(config);
     const res = buena.data;   
     console.log(res.data);
     w = new Chart(grafica1, {
        type: 'bar',
        data: {
            labels: res.periodo,
            datasets: [{
              label: '# Grafica Total',
              data: res.data,
              borderWidth: 1
            }]
        },


        borderWidth: 1,
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        },
    })
    */
}

