<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url.'Assets'; ?>/css/styleLogin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!--<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>-->
    <link href='<?php echo base_url.'Assets'; ?>/css/boxicons.min.css' rel='stylesheet'>
    <title>Bienvenido a mi Formulario</title>
</head>
<body>
    <div class="container-form sign-up">
        <div class="welcome">
            <div class="message">
                <h2>Bienvenido a ZTRACK</h2>
                <p>Si ya tienes una cuenta por favor inicia sesion aqui</p>
                <button class="sign-up-btn">Iniciar Sesion</button>
            </div>
        </div>
        <form class="formularioInformacion">
            <h2 class="create-account">Te escuchamos!</h2>
            <div class="iconos">
                <div class="border-icon-instagram">
                    <a href="#" target="_blank"><i class='bx bxl-instagram'></i></a>
                </div>
                <div class="border-icon-youtube">
                    <a href="https://www.youtube.com/@zgroupsac/videos" target="_blank"><i class='bx bxl-youtube'></i></a>
                </div>
                <div class="border-icon-facebook">
                    <a href="https://www.facebook.com/ZgroupSac/?locale=es_LA" target="_blank"><i class='bx bxl-facebook-circle' ></i></a>
                </div>
            </div>
            <p class="cuenta-gratis">Solicite información de Ztrack</p>
            <input type="text" placeholder="Nombre">
            <input type="email" placeholder="Email">
            <input type="password" placeholder="información solicitada">
            <button type="button" class="btn-solicitar-info">Solicitar</button>
        </form>
    </div>
    <div class="container-form sign-in">
        <form class="formulario" id="frmLogin" onsubmit="frmLogin(event);">
            <div class="logo">
            <br><br>
            <img class="formulario_logo "src="<?php echo base_url.'Assets'; ?>/img/logo.png" alt="logo" width="240" height="60">
            <p>By ZGROUP</p>
            </div>
            <div class="iconos">
                <div class="border-icon-instagram">
                    <a href="#"><i class='bx bxl-instagram'></i></a>
                </div>
                <div class="border-icon-youtube">
                    <a href="https://www.youtube.com/@zgroupsac/videos" target="_blank"><i class='bx bxl-youtube'></i></a>
                </div>
                <div class="border-icon-facebook">
                    <a href="https://www.facebook.com/ZgroupSac/?locale=es_LA" target="_blank"><i class='bx bxl-facebook-circle' ></i></a>
                </div>
            </div>
            <br><br>
            <input type="text" placeholder="Usuario" id="usuario" name="usuario" autofocus required>
            <input type="password" placeholder="Contraseña" id="clave" name="clave" required>
            <input type="hidden"  id="utc" name="utc" >

            <button class="btn-solicitar" type="submit">Ingresar</button>
            <p class="cuenta-gratis">¿Aun no tienes una cuenta?</p>
        </form>
        <div class="welcome-back">
            <div class="message">
                <h2>Bienvenido a ZTRACK</h2>
                <p>Necesitas información de nuestro servicio por favor registrese aqui</p>
                <button class="sign-in-btn">Solicitar</button>
                
            </div>
        </div>
    </div>
    <script src="<?php echo base_url; ?>Assets/js/jquery.min.js"></script>
  <!--<script src="<?php echo base_url; ?>Assets/admin/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>-->
  <script src="<?php echo base_url; ?>Assets/js/sweetalert2.all.min.js"></script>
  <script>
        const base_url = '<?php echo base_url; ?>';
       console.log(base_url);
    </script>
      <script src="<?php echo base_url.'Assets'; ?>/js/LoginPage.js "></script>
    <script src="<?php echo base_url.'Assets'; ?>/js/scriptLogin.js"></script>
</body>
</html>