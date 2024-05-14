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
    margin: 0 auto;
    padding: 0 15px;
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
            <div class="container-fluid px-4">         
               <div class="row mt-4 sm-mt-1">
                   <div class="col-sm-12 col-md-12 col-lg-8">
                        <!-- bar chart -->
                        <div class="row  mt-2">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
 

                    <section id="cards">
        <div class="wrapper">
            <h2>
                <strong>Equipos disponibles ztrack</strong>
            </h2>
            <!-- Slider main container -->
            <div class="swiper">
                <div class="card-slider">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        <div class="swiper-slide">
                            <div class="card" >
                                <div class="card-body">
                                <div class="row ">
                                    <div class="col-2 ">
                                    <img class ="mt-1 p-1 "src="<?php echo base_url.'Assets'; ?>/img/on.png" alt="logo" width="45" height="45">
                                    </div>
                                    <div class="col-8 "><h3 class="text-center mt-3">ZGRU7020127</h3></div>
                                    <div class="col-2 ">
                                     <img class ="mt-1 p-1 "src="<?php echo base_url.'Assets'; ?>/img/conf.png" alt="logo" width="50" height="50">
                                    </div>

                                    <div class="col-12 "><h5 class="text-center mt-2">METRO WONG GUTIERREZ</h5> </div>

                                    <div class="col-4  mt-1 p-1"><h6 class="text-center">Fecha :</h6></div>
                                    <div class="col-8 mt-1 p-1"><h6 class="text-left"> 14:24:12  14/05/2024</h6></div>
                                    <div class="col-3 p-2"><button type="button" class="mt-1 btn btn-block btn-outline-primary">Pe</button></div>
                                    <div class="col-3 p-2"><button type="button" class="mt-1 btn btn-block btn-outline-secondary">Se</button></div>
                                    <div class="col-3 p-2"><button type="button" class="mt-1 btn btn-block btn-outline-success">Suc</button> </div>
                                    <div class="col-3 p-2"><button type="button" class="mt-1 btn btn-block btn-outline-info">Info</button></div>

                                    <div class="col-4 border"><h6 class="mt-1">Parametro</h6></div>
                                    <div class="col-4 border"><h6 class="mt-1">Valor</h6></div>
                                    <div class="col-4 border"><h6 class="mt-1">Control</h6></div>
                                    <div class="col-8  align-self-cente">
                                        <div class="row ">
                                            <div class="col-6 border"><h5 class="mt-2">Supply:</h5></div>
                                            <div class="col-6 "><h4 class="mt-2">-20.15 C°</h4></div>
                                            <div class="col-6 border"><h5 class="mt-2">Return :</h5> </div>
                                            <div class="col-6 border"><h4 class="mt-2">-21.10 C°</h4></div>
                                        </div>
                                    </div>
                                    <div class="col-4 border">
                                    <div class="row ">
                                            <div class="col-12 "><h6 class="mt-2">Set Temp :</h6></div>
                                            <div class="col-9 p-1">  <input type="text" class="form-control" ></div>
                                            <div class="col-3 align-self-center p-1 ">C°</div>
                                        </div>
                                    </div>
                                    <div class="col-8 border">
                                        <div class="row ">
                                            <div class="col-6 border"><h5 class="mt-2">Humedity:</h5></div>
                                            <div class="col-6 border"><h4 class="mt-2"> 83.00 %</h4></div>
                                            <div class="col-6 border"><h5 class="mt-2">Evap :</h5> </div>
                                            <div class="col-6 border"><h4 class="mt-2">-19.12 C°</h4></div>
                                        </div>
                                    </div>
                                    <div class="col-4 border">
                                    <div class="row ">
                                            <div class="col-12 "><h6 class="mt-2">Set Humd :</h6></div>
                                            <div class="col-9 p-1">  <input type="text" class="form-control" ></div>
                                            <div class="col-3 align-self-center p-1 ">%</div>
                                        </div>
                                    </div>

                                    <div class="col-4  align-self-center">
                                        <div class="row">
                                            
                                                <div class="col-12 "><h4 class="mt-2">USDA :</h4></div>
                                            </div>
                                        </div>
                                    <div class="col-4 border">
                                    <div class="row">
                                            <div class="col-3 border"><h5 class="mt-2">1</h5></div>
                                            <div class="col-9 border"><h6 class="mt-2">-12.20 C°</h6></div>
                                            <div class="col-3 border"><h5 class="mt-2">2</h5></div>
                                            <div class="col-9 border"><h6 class="mt-2">-13.20 C°</h6></div>
                                            <div class="col-3 border"><h5 class="mt-2">3</h5></div>
                                            <div class="col-9 border"><h6 class="mt-2">-15.20 C°</h6></div>
                                            <div class="col-3 border"><h5 class="mt-2">4</h5></div>
                                            <div class="col-9 border"><h6 class="mt-2">-17.20 C°</h6></div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-4 border">
                                        <div class="row border">
                                            <div class="col-12 "><h6 class="mt-2">Defrost :</h6></div>
                                            <div class="col-12 p-1"><button type="button" class="mt-1 btn btn-block btn-success">ACTIVE</button> </div>
                                        </div>
                                        <div class="row border">
                                            <div class="col-12 "><h6 class="mt-2">Ethylene :</h6></div>
                                            <div class="col-7 p-1">  <input type="text" class="form-control" ></div>
                                            <div class="col-5 align-self-center p-1 ">ppm</div>

                                        </div>
                                    </div>
                                    <div class="col-8 border">
                                        <div class="row ">
                                            <div class="col-6 border"><h5 class="mt-2">CO2 :</h5></div>
                                            <div class="col-6 border"><h4 class="mt-2"> 0.10 %</h4></div>
                                            <div class="col-6 border"><h5 class="mt-2"> Ambiente :</h5> </div>
                                            <div class="col-6 border"><h4 class="mt-2">+23.12 C°</h4></div>
                                        </div>
                                    </div>
                                    <div class="col-4 border">
                                    <div class="row ">
                                            <div class="col-12 "><h6 class="mt-2">CO2 :</h6></div>
                                            <div class="col-9 p-1">  <input type="text" class="form-control" ></div>
                                            <div class="col-3 align-self-center p-1 ">%</div>
                                        </div>
                                    </div>


</div>
                                </div>
                              </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card" >
                                <div class="card-body">
                                  <h5 class="card-title">Card title 2</h5>
                                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                  <li class="list-group-item">Cras justo odio</li>
                                  <li class="list-group-item">Dapibus ac facilisis in</li>
                                  <li class="list-group-item">Vestibulum at eros</li>
                                </ul>
                                <div class="card-body">
                                  <a href="#" class="card-link">Card link</a>
                                  <a href="#" class="card-link">Another link</a>
                                </div>+
                              </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card" >
                                <div class="card-body">
                                  <h5 class="card-title">Card title 6</h5>
                                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                  <li class="list-group-item">Cras justo odio</li>
                                  <li class="list-group-item">Dapibus ac facilisis in</li>
                                  <li class="list-group-item">Vestibulum at eros</li>
                                </ul>
                                <div class="card-body">
                                  <a href="#" class="card-link">Card link</a>
                                  <a href="#" class="card-link">Another link</a>
                                </div>
                              </div>
                        </div> 
                        <div class="swiper-slide">
                            <div class="card" >
                                <div class="card-body">
                                  <h5 class="card-title">Card title 3</h5>
                                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                  <li class="list-group-item">Cras justo odio</li>
                                  <li class="list-group-item">Dapibus ac facilisis in</li>
                                  <li class="list-group-item">Vestibulum at eros</li>
                                </ul>
                                <div class="card-body">
                                  <a href="#" class="card-link">Card link</a>
                                  <a href="#" class="card-link">Another link</a>
                                </div>
                              </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card" >
                                <div class="card-body">
                                  <h5 class="card-title">Card title 4</h5>
                                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                  <li class="list-group-item">Cras justo odio</li>
                                  <li class="list-group-item">Dapibus ac facilisis in</li>
                                  <li class="list-group-item">Vestibulum at eros</li>
                                </ul>
                                <div class="card-body">
                                  <a href="#" class="card-link">Card link</a>
                                  <a href="#" class="card-link">Another link</a>
                                </div>
                              </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card" >
                                <div class="card-body">
                                  <h5 class="card-title">Card title 5</h5>
                                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                  <li class="list-group-item">Cras justo odio</li>
                                  <li class="list-group-item">Dapibus ac facilisis in</li>
                                  <li class="list-group-item">Vestibulum at eros</li>
                                </ul>
                                <div class="card-body">
                                  <a href="#" class="card-link">Card link</a>
                                  <a href="#" class="card-link">Another link</a>
                                </div>
                              </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card" >
                                <div class="card-body">
                                  <h5 class="card-title">Card title 7</h5>
                                  <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                  <li class="list-group-item">Cras justo odio</li>
                                  <li class="list-group-item">Dapibus ac facilisis in</li>
                                  <li class="list-group-item">Vestibulum at eros</li>
                                </ul>
                                <div class="card-body">
                                  <a href="#" class="card-link">Card link</a>
                                  <a href="#" class="card-link">Another link</a>
                                </div>
                              </div>
                        </div>
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

<?php include "Views/templates/footerLive.php"; ?>
<?php include "Views/templates/footer.php"; ?>