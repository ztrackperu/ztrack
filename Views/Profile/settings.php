<?php include "Views/templates/navbar.php"; ?>
<div class="container mt-4 py-2"> 
    <div class="card rounded-3">
        <div class="card-body py-5" id="formularioData">
            <!-- FORM SETTINGS-->
        </div>
    </div>
</div>
<div class="modal fade" id="graficaModalSettings" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleSettings">Gráfica</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modalForm">
                    <div class="mb-3">
                        <label for="valueInput" class="form-label">Lista</label>
                        <select class="select2_grafica" style="width:100%" id="valueInput" multiple="multiple">
                            <option value="#e26464">Humidity</option>
                            <option value="#e26444">Return</option>
                            <option value="#e26424">Supply</option>
                            <option value="#e26404">Ethylene</option>
                            <option value="#e26384">Cargo</option>
                            <option value="#e26364">Evaporador</option>
                            <option value="#e26404">Ambiente</option>
                        </select>
                        <label for="colorInputGrafico" class="form-label">Color</label>
                        <input type="color" class="form-control" id="colorInputGrafico" disabled>
                        <!--
                        <select class="form-select mt-2" style="width:100%" id="valueInput2" multiple>
                            <option value="1">Humidity</option>
                            <option value="2">Return</option>
                            <option value="3">Supply</option>
                            <option value="4">Ethylene</option>
                            <option value="5">Cargo</option>
                            <option value="6">Evaporador</option>
                            <option value="7">Ambiente</option>
                        </select>  -->
                    </div>
                    <button type="submit" class="btn btn-primary">Añadir</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="datosModalSettings" tabindex="-1" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleSettings">da2</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="modalFormDatos">
                    <div class="mb-3">
                        <label for="valueInputDatos" class="form-label">Valor</label>
                        <select class="form-select" id="valueInputDatos" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>  
                    </div>
                    <div class="mb-3">
                        <label for="colorInputDatos" class="form-label">Color</label>
                        <input type="color" class="form-control" id="colorInputDatos" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Añadir</button>
                </form> 
            </div>
        </div>
    </div>
</div>
<?php include "Views/templates/footer.php"; ?>