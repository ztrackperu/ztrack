const url = base_url + "ConceptosOT/nuevaSeleccion/" + id1;
const http = new XMLHttpRequest();
http.open("GET", url, true);
http.send();
http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        console.log(res);
        datafila.push(res);

        /*
        tablaInsumos.row.add([{
            'Codigo': res['IN_CODI'],
            'Descripcion': res['IN_ARTI'],
            'Unidad': res['IN_UVTA'],
            'Cantidad': res['cantidad'],
            'Eliminar': res['acciones'],
        }]).draw();
        if(res.insumos!=null){
            console.log(res.insumos);
            if(res.insumos.length!=0){
                //tablaInsumos.destroy();
                console.log("esta vacio");       
                var tablaInsumos = $('#myTableInsumo').DataTable({
                    paging: false,
                    searching: true,
                    info: false,
                    data: res.insumos,
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
        */
    }
    console.log(datafila);
    res =datafila[0];
    console.log(res);
    /*
    for(let i=0;i<res.length;i++){
        tablaInsumos.row.add([{
            'Codigo': res[i]['IN_CODI'],
            'Descripcion': res[i]['IN_ARTI'],
            'Unidad': res[i]['IN_UVTA'],
            'Cantidad': res[i]['cantidad'],
            'Eliminar': res[i]['acciones'],
        }]).draw();
    }
    */
    for(let i=0;i<res.length;i++){
        tablaInsumos.row.add(
            //{ tamano: tamano, nombre: nombre }
            {
                IN_CODI: res[i].IN_CODI,
                IN_ARTI: res[i].IN_ARTI,
                IN_UVTA: res[i].IN_UVTA,
                cantidad: res[i].cantidad,
                acciones: res[i].acciones, 
            }
        
        ).draw();
    }

};














if (this.readyState == 4 && this.status == 200) {
    const res = JSON.parse(this.responseText);
    console.log(res);
    tablaInsumos.row.add([{
        'Codigo': res['IN_CODI'],
        'Descripcion': res['IN_ARTI'],
        'Unidad': res['IN_UVTA'],
        'Cantidad': res['cantidad'],
        'Eliminar': res['acciones'],
    }]).draw();
    if(res.insumos!=null){
        console.log(res.insumos);
        if(res.insumos.length!=0){
            //tablaInsumos.destroy();
            console.log("esta vacio");       
            var tablaInsumos = $('#myTableInsumo').DataTable({
                paging: false,
                searching: true,
                info: false,
                data: res.insumos,
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
}






if (this.readyState == 4 && this.status == 200) {
    const res = JSON.parse(this.responseText);
    console.log(res);
    
    tablaInsumos.row.add([{
        'Codigo': res['IN_CODI'],
        'Descripcion': res['IN_ARTI'],
        'Unidad': res['IN_UVTA'],
        'Cantidad': res['cantidad'],
        'Eliminar': res['acciones'],
    }]).draw();
    
    //tablaInsumos.row.add(res).draw();  
    /*
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
    */
    //console.log(res.insumos);
    if(res.insumos!=null){
        console.log(res.insumos);
        if(res.insumos.length!=0){
            //tablaInsumos.destroy();
            console.log("esta vacio");
            
            var tablaInsumos = $('#myTableInsumo').DataTable({
                paging: false,
                searching: true,
                info: false,
                data: res.insumos,
                columns: [
                { title: "Codigo", data: "IN_CODI" },
                { title: "Descripcion", data: "IN_ARTI" },
                { title: "Unidad", data: "IN_UVTA" },
                { title: "Cantidad", data: "cantidad" },
                { title: "Eliminar", data: "acciones" }
                ]
            });
            
        /*
        datz= res.insumos.length;
        console.log(datz);
            for(let i=0;i<res.insumos.length;i++){
                if(typeof tablaInsumos === 'undefined'){
                    var tablaInsumos = $('#myTableInsumo').DataTable({retrieve: true,paging: false});
               }
               var table = $('#myTableInsumo').DataTable({
                paging: false,
                searching: true,
                info: false,
                data: resul,
                columns: [
                { title: "Codigo", data: "IN_CODI" },
                { title: "Descripcion", data: "IN_ARTI" },
                { title: "Unidad", data: "IN_UVTA" },
                { title: "Cantidad", data: "IN_CODI" },
                { title: "Eliminar", data: "IN_CODI" }
                ]
            });
                console.log("oki");
                tablaInsumos.row.add(
                    //{ tamano: tamano, nombre: nombre }
                    {
                        IN_CODI: res.insumos[i].IN_CODI,
                        IN_ARTI: res.insumos[i].IN_ARTI,
                        IN_UVTA: res.insumos[i].IN_UVTA,
                        cantidad: res.insumos[i].IN_UVTA,
                        acciones: res.insumos[i].IN_UVTA, 
                    }
                
                ).draw(false);
            }

            */

        }
}

    //agregar los resultados del concepto

    /*
    document.getElementById("id").value = res.id;
    
    document.getElementById("codigo_concepto").value = res.codigo;
    document.getElementById("descripcion_concepto").value = res.descripcion;                
    $("#nuevoConcepto").modal("show");
    */
}