

function obtenerData(){
    const url = base_url + 'Profile/ProfileInformation';
    const http = new XMLHttpRequest();
    http.open('GET', url);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const data = JSON.parse(this.responseText);
            console.log(data);
            $('#userName').html(data.Username);
            $('#fullname').html(data.FullName);
            $('#email').html(data.Email);
            $('#phone').html(data.Number);
            $('#address').html(data.Address);
            $('#company').html(data.Company);
            $('#city').html(data.City);
            $('#country').html(data.Country);

        }
    }   
}

$(document).ready(function(){
    obtenerData();
});