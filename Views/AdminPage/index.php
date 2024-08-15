<?php include "Views/templates/navbar.php"; ?>

     
<div class="container-fluid px-3 py-3">
    <h3 class="text-uppercase fw-bold">Panel de Control</h3>
    <div class="row mt-1 sm-mt-1 border py-2 rounded" style='padding-top:10px ;'>
        <div class="col-sm-12 col-md-12 col-lg-8" id="PanelControlInformation">
            <!-- CONTENIDO PRINCIPAL-->
        </div>
        <div class="col-sm-12 col-md-12 col-lg-4">
            <div>
                <div class="card">
                <div class="card-body">
                    <canvas id="graficoTipoDispositivo" style="width:250px; height:250px"></canvas>
                </div>
                </div>
            </div>
            <div class="mt-2">
                <div class="card">
                <div class="card-body">
                    <canvas id="graficoEstadoDispositivo" style="width:250px; height:250px"></canvas>
                </div>
                </div>
            </div>
            <div class="mt-2">
                <div class="card">
                <div class="card-body">
                    <canvas id="graficoConsumoEnergetico" style="width:250px; height:250px"></canvas>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
        
<?php include "Views/templates/footer.php"; ?>