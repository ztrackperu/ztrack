

function obtenerInfoCard1(){
    const url = base_url + 'Profile/ProfileInformation';
    const http = new XMLHttpRequest();
    http.open('GET', url);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const data = JSON.parse(this.responseText);
            console.log(data);
            let res = document.getElementById('profileInformation');
            res.innerHTML = data.html;
        }
    }
}

function obtenerInfoCard2(){
    const url = base_url + 'Profile/ProfileUpdate';
    const http = new XMLHttpRequest();
    http.open('GET', url);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const data = JSON.parse(this.responseText);
            console.log(data);
            let res = document.getElementById('profileUpdate');
            res.innerHTML = data.html;
        }
    }
}

function editProfile(){
    //let name = document.getElementById('name');
    $('#nameInput').attr('readonly', false);
    $('#lastnameInput').attr('readonly', false);
    $('#emailInput').attr('readonly', false);
    $('#phoneInput').attr('readonly', false);
    $('#addressInput').attr('readonly', false);
    $('#companyInput').attr('readonly', false);
    $('#btnCancel').attr('hidden', false);
    $('#btnSave').attr('hidden', false);
}

function cancelEdit(){
    $('#btnCancel').attr('hidden', true);
    $('#btnSave').attr('hidden', true);
    $('#nameInput').attr('readonly', true);
    $('#lastnameInput').attr('readonly', true);
    $('#emailInput').attr('readonly', true);
    $('#phoneInput').attr('readonly', true);
    $('#addressInput').attr('readonly', true);
    $('#companyInput').attr('readonly', true);

    //LIMPIAR CAMPOS
    $('#nameInput').val('');
    $('#lastnameInput').val('');
    $('#emailInput').val('');
    $('#phoneInput').val('');
    $('#addressInput').val('');
    $('#companyInput').val('');
}

//MY SETTINGS
function btnGrafica(){
    $('#graficaModalSettings').modal('show'); 
}

function btnDatos(){
    $('#datosModalSettings').modal('show'); 
}

function obtenerDataForm(){
    const url = base_url + 'Profile/DataFormularioSettings';
    const http = new XMLHttpRequest();
    http.open('GET', url);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const data = JSON.parse(this.responseText);
            console.log(data);
            $('#formularioData').html(data.html);
        }
    }
}

$(document).ready(function() {
    $('.select2_grafica').select2({
        dropdownParent: $('#graficaModalSettings')
    });

    //$('.select3_datos').select2();
    obtenerInfoCard1();
    obtenerInfoCard2();
    obtenerDataForm();
  
    $('#valueInput').on('click', 'option', function() {
        const value = $(this).val();
        addOptionToValueInput(value);
    });

    

    // Manejar el formulario del modal GRÁFICO
    $('#modalForm').on('submit', function(event) {
        event.preventDefault();
        const values = $('#valueInput').val();
        const ValueTest = $('#valueInput').text();
        const color = $('#colorInputGrafico').val();
        //añadir valores al select, pero verificar que ya no se agregaron antes
        if (values && color) {
            values.forEach(text => {
                $('#multiselect-left').append(new Option(text, text));
            });
            // Cerrar el modal
            $('#graficaModalSettings').modal('hide');
            // Limpiar el formulario
            $('#modalForm')[0].reset();
        }
    });

    // Manejar el formulario del modal DATOS
    $('#modalFormDatos').on('submit', function(event) {
        event.preventDefault();
        const values = $('#valueInputDatos').val();
        const color = $('#colorInputDatos').val();
        if (values && color) {
            values.forEach(value => {
                $('#multiselect-right-datos').append(new Option(value, value));
            });
            // Cerrar el modal
            $('#datosModalSettings').modal('hide');
            // Limpiar el formulario
            $('#modalFormDatos')[0].reset();
        }
    });
});

function addOptionToValueInput(value) {
    const valueInput = document.getElementById('valueInput');
    const options = Array.from(valueInput.options);
    const exists = options.some(option => option.value === value);

    if (!exists) {
        const newOption = new Option(value, value);
        valueInput.add(newOption);
    }
}

/*

document.addEventListener("DOMContentLoaded", function() {
    // Función para manejar el doble clic en los elementos del multiselect
    function setupMultiselectHandlers() {
        $('#multiselect-left').on('click', 'option', function() {
            $(this).appendTo('#multiselect-right');
        });

        $('#multiselect-right').on('click', 'option', function() {
            $(this).appendTo('#multiselect-left');
        });

        $('#multiselect-left-datos').on('click', 'option', function() {
            $(this).appendTo('#multiselect-right-datos');
        });

        $('#multiselect-right-datos').on('click', 'option', function() {
            $(this).appendTo('#multiselect-left-datos');
        });
    }
    setupMultiselectHandlers();

    // Repetir la llamada a la función cada segundo
    setInterval(setupMultiselectHandlers, 1000);
});*/

document.addEventListener("DOMContentLoaded", function() {
    function test(){
        $('#multiselect-left').on('change', function(){
            const selectedColor = $(this).val();
            if(selectedColor.length > 0){
                $('#color-picker')[0].value = selectedColor[selectedColor.length - 1];
            }
        })
    }
    test();

    setInterval(test, 1000);
});

