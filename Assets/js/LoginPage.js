utc = document.getElementById("utc");
timeUser =new Date();
console.log(timeUser);
console.log(timeUser.getUTCHours());
//getUTCDate()
console.log(timeUser.getTimezoneOffset());
function frmLogin(e) {
    e.preventDefault();
    const usuario = document.getElementById("usuario");
    const clave = document.getElementById("clave");
    if (usuario.value == "") {
        clave.classList.remove("is-invalid");
        usuario.classList.add("is-invalid");
        usuario.focus();
    } else if (clave.value == "") {
        usuario.classList.remove("is-invalid");
        clave.classList.add("is-invalid");
        clave.focus();
    } else {
        const url = base_url + "Usuarios/validar";
        console.log(url);
        utc.value =timeUser.getTimezoneOffset() ;
        const frm = document.getElementById("frmLogin");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) { 
                const res = JSON.parse(this.responseText);
                //console.log('aca toy');
                //console.log(res);
                if (res.icono == "success") {
                    alertas(res.msg, res.icono);
                    // Verificar el estado y redirigir en consecuencia
                    setTimeout(function () {
                        if (res.estado == 1) {
                            window.location = base_url + "Live";
                        } else if (res.estado == 0) {
                            window.location = base_url + "AdminPage";
                        }
                    }, 1000);
                } else {
                    alertas(res.msg, res.icono);
                }
            }
        }
    }
}
function alertas(msg, icono) {
    Swal.fire({
        position: 'top-end',
        icon: icono,
        title: msg,
        showConfirmButton: false,
        timer: 3000
    })
}
