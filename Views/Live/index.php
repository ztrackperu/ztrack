<?php include "Views/templates/navbar.php"; ?>
<link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/swiper-bundle.min.css" />
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');
/* -----Root CSS Variable------ */
*,
::after,
::before {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
/* -----CSS Global----- */
body {
    font-family: 'Poppins', sans-serif;
    /*background-color: #343434; */
}
/* -----Wrapper------- */
.wrapper {
    max-width: 1670px;
    margin: 0 1px;
    padding: 0 1px;
}
/* ------- Card Slider ---------- */
#cards {
    padding: 45px 0;
}

.swiper {
    padding-top: 30px;
}

.card-content {
    text-align: center;
    position: relative;
    cursor: pointer;
}

.card-body {
    margin: 0 2px;
    padding: 0 2px;
}
.card-content img {
    border-radius: 0.5rem;
}
/* ----- Swiper arrow and pagination --------- */
.arrow {
    display: flex;
    align-items: center;
    padding-top: 20px;
}
.prevArrowBtn,
.nextArrowBtn {
    z-index: 1000;
    border: 2px solid #DDD;
    color: #FFF;
    background-color: #343434;
    font-size: .80rem;
    text-align: center;
    cursor: pointer;
    display: block;
    width: 2.75rem;
    height: 2.75rem;
    line-height: 2.625rem;
}
.nextArrowBtn {
    margin-left: 10px;
}
/* ---- Styling swiper bullets ----- */
.swiper-pagination-bullets.swiper-pagination-horizontal {
    width: 60%;
    display: flex;
    position: static;
    margin: 0 auto;
}
.swiper-pagination-bullet {
    width: 100%;
    height: 5px;
    border-radius: 0;
}
.swiper-pagination-bullet-active {
    background-image: linear-gradient(162.41deg, #43baff 1.77%, #7141b1 92.78%);
}

.swiper-pagination-bullet:hover {
    background-image: linear-gradient(162.41deg, #43baff 1.77%, #7141b1 92.78%);
    opacity: 1;
    outline: none;
}


 
</style>

<div>
    <div>
        <div>
            <div class="container-fluid px-3">         
               <div class="row mt-1 sm-mt-1 " style='padding-top:10px ;'>
                   <div class="col-sm-12 col-md-12 col-lg-8">
                        <!-- bar chart -->
                        <div class="row  mt-2">
                            
                                <div class="card" style='padding-right:5px ; padding-left:5px;'>
                                    <div class="card-body">
 

                    <section id="cards" style='padding-top:15px ;' >
        <div class="wrapper">
            <h2>
                <strong>Equipos disponibles ztrack</strong>
            </h2>
            <!-- Slider main container -->
            <div class="swiper" style='padding-top:15px ;' >
                <div class="card-slider">
                    <!-- Additional required wrapper -->
                    <div  id="carruselExtra" class="swiper-wrapper">
                        <!-- Slides -->
                        <!--<div id="carruselExtra"></div>-->

                    </div>
                    <!-- Swiper wrapper ends -->
                    <div class="arrow">
                        <div class="prevArrowBtn"> 
                            Pass
                        </div>
                        <div class="nextArrowBtn">
                            Next
                        </div>
                        <ul class="swiper-pagination"></ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
                    

                    </div>
                  
                </div>
              </div>
            </div>
            <!-- boxes ends -->

            <!-- map -->
            <div class="col-lg-4 sm-mt-3 ">
                <div class="row mt-2">
                    <div class="col-md-4 col-lg-4 col-xl-4 sm-mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                        <h6>Online</h6>
                        <h4>20</h4>
                        </div>
                    </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 sm-mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                        <h6>Wait</h6>
                        <h4>12</h4>
                        </div>
                    </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-xl-4 sm-mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                        <h6>Offline</h6>
                        <h4>123</h4>
                        </div>
                    </div>
                    </div>
                </div>

            
                <div class="card mt-2">
                    <div class="card-body">
                        <div id="map" style="width: 100%; height: 600px"></div> 
                    </div>
                </div>
            </div>
            <!-- map ends -->
          </div>
    </div>




           <!-- Success Alert -->
           <div id="noti"></div>        
           <div class="alert alert-success alert-dismissible fade show">
                <strong>Success!</strong> Your message has been sent successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <!-- Error Alert -->
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Error!</strong> A problem has been occurred while submitting your data.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <!-- Warning Alert -->
            <div class="alert alert-warning alert-dismissible fade show">
                <strong>Warning!</strong> There was a problem with your network connection.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
</div>
<!-- MODAL -->
    <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Ingresar Correo y Contraseña</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmRegistrar">
                            <div class="form-group">
                                <label for="correo">Correo</label>
                                <input type="hidden" id="id" name="id">
                                <input type="hidden" id="correo_admin" value="zgroupsistemas@gmail.com">
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="pass_email" name="pass_email" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" type="submit" onclick="registrarRespuesta(event)" id="btnAccion" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </div>
            </div>
    </div>
<script src="<?php echo base_url; ?>Assets/js/swiper-bundle.min.js"></script>
<script>
// Initialize swiper.js for project slider
const swiper = new Swiper(".card-slider", {
  // Default parameters
  slidesPerView: 1,
  spaceBetween: 30,
  loop: true,
  navigation: {
    nextEl: ".nextArrowBtn",
    prevEl: ".prevArrowBtn",
  },
  pagination: {
    el: ".swiper-pagination",
    renderBullet: function (index, className) {
      return '<li class="' + className + '"></li>';
    },
    clickable: true,
  },
  // Responsive breakpoints
  breakpoints: {
    // when window width is >= 576px
    600: {
      slidesPerView: 1,
    },
    1240: {
      slidesPerView: 2,
      spaceBetween: 10,
    },
    // when window width is >= 768px
    1900: {
      slidesPerView: 3,
      spaceBetween: 15,
    },
  },
});

</script>





<div id="interfazGrafica" class="modal animated bounceIn show" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl  modal-dialog-centered">
    <div class="modal-content ">
      <div class="modal-header">
        <div style="margin-top:10px; margin-left:15px">
            <h1 align="center" id="tituloGrafica">TEST123456-7</h1>
        </div>
        <div style="padding: 5px; margin-top:10px; margin-right:10px">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

      </div>
      <div class="modal-body" style="padding: 5px ;margin-top:1px;">
                <div class="row justify-content-center " style="padding: 5px; margin-top:1px;">
                    <div class="col-8 col-lg-3 align-self-end" style="margin-top:5px;" >
                        <h5 >Search by Date :</h5>
                    </div>
                    <div class="col-4 col-lg-2 align-self-end" style="padding-right: 15px; margin-top:5px;">
                        <select class="form-select" aria-label="Default select example" id="temp_c_f">
                            <option value=0 selected>C°</option>
                            <option value=1 >F°</option>
                        </select>
                    </div>

                    <div class="col-6 col-lg-2" style="padding-left: 15px; margin-top:10px;">
                        <h5 ><strong>From :</strong></h5>
                        <input class='form-control'  id="fechaInicial" type="datetime-local">	
                    </div>
                    <div class="col-6 col-lg-2" style="padding-right: 15px;margin-top:10px;">
                        <h5 ><strong>To :</strong></h5>
                        <input class='form-control' id="fechaFin" type="datetime-local">
                    </div>
                    <div class="col-12 col-lg-2 align-self-center d-grid" style="margin-top:5px;">
                        <button type="button"  id="fechaPer" onclick="procesarFecha()" class="btn btn-primary btn-lg">Search </button>
                    </div>
                </div>
                <!--<div class="container "> -->
                <div id="legend-container" class="container" style="padding-left: 2px;padding-right: 2px;"></div> 
                <!--</div> -->
                
                <canvas align ="center" id="graficaFinal" style="" width="1200" height="700"></canvas>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <!--<button type="button" class="btn btn-primary" id="a">Graph</button>-->
        <a id="bajarGraph" class="btn btn-outline-success btn-lg btn-block">DOWNLOAD GRAPH</a>

      </div>
    </div>
  </div>
</div>


<?php include "Views/templates/footerLive.php"; ?>
<?php include "Views/templates/footer.php"; ?>