<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" initial-scale=1.0">
    <!--<meta name="viewport" content= "width=device-width, user-scalable=no">-->
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/navbar-1.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/stylenav.css" />
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/main.css" />
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/select2.min.css"  />
    <title>ZTRACK | LIVE TELEMATIC</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bsb-navbar bsb-navbar-hover bsb-navbar-caret">
  <div class="container">
    <a class="navbar-brand" href="index.html"> <img src="<?php echo base_url; ?>Assets/img/image.png" alt="logo__zgroup" width="135" height="44"></a>
    <button type="button" class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#!">Inicio</a>
          </li>  
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="usuarioDropdown" role="button" data-bs-toggle="dropdown" aria-current="page">Usuarios</a>
            <ul class="dropdown-menu border-0 shadow bsb-zoomIn" aria-labelledby="usuarioDropdown">
              <li><a class="nav-link active" aria-current="page" href=" <?php echo base_url; ?>Usuarios">Usuarios</a></li>
              <li><a class="nav-link active" aria-current="page "href=" <?php echo base_url; ?>Permisos">Permisos</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href= id="conceptoDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Conceptos OT</a>
            <ul class="dropdown-menu border-0 shadow bsb-zoomIn" aria-labelledby="conceptoDropdown">
              <li><a class="dropdown-item" href="<?php echo base_url; ?>ConceptosOT">Lista</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url; ?>ConceptosOT/Asignar">Asignar</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url; ?>ConceptosOT/CrearOT">Crear</a></li>
            </ul>
          </li>
   
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href= id="conceptoDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Concepto</a>
            <ul class="dropdown-menu border-0 shadow bsb-zoomIn" aria-labelledby="conceptoDropdown">
              <li><a class="dropdown-item" href="#!">Conceptos</a></li>
              <li><a class="dropdown-item" href="#!">Listar</a></li>
              <li><a class="dropdown-item" href="#!">Insumo</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#!" id="otDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">O.T.</a>
            <ul class="dropdown-menu border-0 shadow bsb-zoomIn" aria-labelledby="otDropdown">
              <li><a class="dropdown-item" href="#!">Listar</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url; ?>Otrabajo/crear">Crear</a></li>
              <li><a class="dropdown-item" href="#!">Modificar</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url; ?>Otrabajo/test">Test</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#!" id="pendientesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Pendientes</a>
            <ul class="dropdown-menu border-0 shadow bsb-zoomIn" aria-labelledby="pendientesDropdown">
              <li><a class="dropdown-item" href="#!">O.T.</a></li>
              <li><a class="dropdown-item" href="#!">Insumos</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#!" id="analisisDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Análisis</a>
            <ul class="dropdown-menu border-0 shadow bsb-zoomIn" aria-labelledby="analisisDropdown">
              <li><a class="dropdown-item" href="#!">O.T.</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url; ?>ConceptosOT/Analisis">Conceptos</a></li>
              <li><a class="dropdown-item" href="#!">Insumo</a></li>

            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link nav-icon-hover dropdown-toggle" href="javascript:void(0)" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false"><img src="<?php echo base_url; ?>Assets/img/user1.jpg" alt="" width="30" height="30" class="rounded-circle">Usuario</a>
            <ul class="dropdown-menu border-0 shadow bsb-zoomIn" aria-labelledby="accountDropdown">
                <li><a class="dropdown-item" href="#!">Perfil</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li> 
                <li><a class="dropdown-item" href="<?php echo base_url; ?>Usuarios/salir">Cerrar sesión</a></li>
            </ul>
            </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

