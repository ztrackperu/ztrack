

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



$(document).ready(function(){
   obtenerInfoCard1();
   obtenerInfoCard2();
    
});