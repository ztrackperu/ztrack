<?php

class Profile extends Controller
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo_ztrack'])) {
            header("location: " . base_url);
        }
        parent::__construct();
    }
    public function index()
    {
        // aqui debe llegar todo los datos si es user 1 sino de acuedo a loq ue esta permitido 
		$id_user = $_SESSION['id_ztrack'];
        /*
        $perm = $this->model->verificarPermisos($id_user, "Live");
        if (!$perm && $id_user != 1) {
            $this->views->getView($this, "permisos");
            exit;
        }
        */
        /*
        //pedimos la info 
        $data = $this->model->ListaContenedores($id_user);
        #echo $data ;
        $resultadoContenedores = json_decode($data);
        $resultadoContenedores = $resultadoContenedores->data;
        $this->views->getView($this, "index",json_encode($resultadoContenedores));
        */
        $this->views->getView($this, "profile");

    }

    public function Settings(){
        $this->views->getView($this, "settings");
    }
    public function Password(){
        $this->views->getView($this, "password");
    }

    public function ProfileInformation(){
      $informacion = array(
        "id" => 1,
        "username" => "gperez",
        "nombre" => "Gian",
        "apellido" => "Perez",
        "email" => "gperez@gmail.com",
        "telefono" => "123456789",
        "direccion" => "Calle 123",
        "empresa" => "Ztrack",
        "country" => "pe"
      );
      $html = "";
      /*
      $html .= "<h1>{$informacion['nombre']}<h1>
                <h2>{$informacion['apellido']}<h2>
                ";
      */

      $html .= "
            <img src='https://via.placeholder.com/150' alt='profile' class='img-fluid rounded-circle'>
            <h4 id='userName'>{$informacion['username']}</h4>
            <span class='fi fi-{$informacion['country']}'></span>
            <div class='mt-4'>
                <h6 class='font-bold'>Name</h6>
                <p id='name'>{$informacion['nombre']}</p>
            </div>
            <div class='mt-2'>
                <h6 class='font-bold'>LastName:</h6>
                <p id='lastname'>{$informacion['apellido']}</p>
            </div>
            <div class='mt-2'>
                <h6 class='font-bold'>Email:</h6>
                <p id='email'>{$informacion['email']}</p>
            </div>
            <div class='mt-2'>
                <h6 class='font-bold'>Phone:</h6>
                <p id='phone'>{$informacion['telefono']}</p>
            </div>
            <div class='mt-2'>
                <h6 class='font-bold'>Address:</h6>
                <p id='address'>{$informacion['direccion']}</p>
            </div>
            <div class='mt-2'>
                <h6 class='font-bold'>Company:</h6>
                <p id='company'>{$informacion['empresa']}</p>
            </div>
            ";
      $r = array(
        "html" => $html
      );

    echo json_encode($r);

      
    }

    public function ProfileUpdate(){
        $informacion = array(
            "id" => 1,
            "username" => "gperez",
            "nombre" => "Gian",
            "apellido" => "Perez",
            "email" => "gperez@gmail.com",
            "telefono" => "123456789",
            "direccion" => "Calle 123",
            "empresa" => "Ztrack",
            
          );
        $html= "";
        $html .= "
            <div class='d-flex justify-content-between'>
                        <h4 class='font-bold'>Edit Profile</h4>
                        <button type='button' class='btn btn-outline-dark' onclick='editProfile()'>
                            <i class='ri-pencil-fill'></i>
                        </button>
                    </div>
                    <form id='formProfile' class='py-2'>
                        <div class='form-group'>
                            <div class='mt-2'>
                                <label for='username'>Name</label>
                                <input type='text' class='form-control' id='nameInput' name='name' placeholder='{$informacion['nombre']}' readonly>
                               
                            </div>
                            <div class='mt-2'>
                                <label for='username'>Lastname</label>
                                <input type='text' class='form-control' id='lastnameInput' name='lastname' placeholder='{$informacion['apellido']}' readonly>
                            </div>
                            <div class='mt-2'>
                                <label for='username'><i class='ri-mail-fill'></i> Email</label>
                                <input type='email' class='form-control' id='emailInput' name='email' placeholder='{$informacion['email']}' readonly>
                            </div>
                            <div class='mt-2'>
                                <label for='username'><i class='ri-phone-fill'></i> Phone</label>
                                <input type='tel' class='form-control' id='phoneInput' name='phone' placeholder='{$informacion['telefono']}' readonly>
                            </div>
                            <div class='mt-2'>
                                <label for='username'><i class='bi bi-geo-alt'></i> Address</label>
                                <input type='text' class='form-control' id='addressInput' name='address' placeholder='{$informacion['direccion']}' readonly>
                            </div>
                            <div class='mt-2'>
                                <label for='username'><i class='bi bi-building'></i> Company</label>
                                <input type='text' class='form-control' id='companyInput' name='company' placeholder='{$informacion['empresa']}' readonly>
                            </div>
                            <div class='mt-4 d-flex gap-2 justify-content-center'>
                                <button type='button' class='btn btn-danger col-4' id='btnCancel' hidden onclick='cancelEdit()'>Cancel</button>
                                <button type='submit' class='btn btn-primary col-4' id='btnSave' hidden>Save</button>
                            </div>

                            <div class='mt-2 border rounded px-3 py-3'>
                                <h5 clas='fw-bold'>Equipos Asignados</h5>
                                <div class='table-responsive'>
                                    <table class='table table-bordered text-center'>
                                        <thead>
                                            <th scope='col'>PWR</th>
                                            <th scope='col'>Reefer ID</th>
                                            <th scope='col'>Tipo</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><i class='bi bi-plug-fill text-success'></i></td>
                                                <td>ZGRU1090804</td>
                                                <td>Madurador</td>
                                            </tr>
                                            <tr>
                                                <td><i class='bi bi-plug-fill text-danger'></i></td>
                                                <td>ZGRU2232647</td>
                                                <td>Madurador</td>
                                            </tr>
                                            <tr>
                                                <td><i class='bi bi-plug-fill text-danger'></i></td>
                                                <td>ZGRU2009227</td>
                                                <td>Madurador</td>
                                            </tr>
                                             <tr>
                                                <td><i class='bi bi-plug-fill text-success'></i></td>
                                                <td>ZGRU2008220</td>
                                                <td>Madurador</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
        ";
        $r = array(
            "html" => $html
          );
    
        echo json_encode($r);
    }
    public function DataFormularioSettings(){
        $html = "";
        $html .= "
                <div class='mt-2 d-flex justify-content-center align-items-center gap-5'>
                    <label id='test' for='typeInput'>Tipo:</label>
                    <select class='form-select w-50' id='typeInput' name='type'>
                        <option value='1' selected>Genset</option>
                        <option value='2'>Reefer</option>
                        <option value='3'>Madurador</option>
                        <option value='4'>Tunel</option>
                    </select>
                </div>
                <div class='mt-4 d-flex justify-content-center align-items-center gap-5'>
                    <label for='gmtInput'>GMT:</label>
                    <select class='form-select w-50' id='gmtInput' name='gmt'>
                        <option value='1' selected>GMT-5</option>
                    </select>
                </div>
                 <!--two sides-->
                <div class='mt-4 d-flex justify-content-center align-items-center gap-4 flex-column flex-md-row'>
                    <div class='d-flex justify-content-center'>
                        <label for='grafico'>Gráfica:</label>
                    </div>
                    <div class='w-100 w-md-25 d-flex justify-content-center align-items-center flex-wrap'>
                        <select multiple size='10' id='multiselect-left' class='rounded border-muted w-100'>
                            <!-- Left side options -->   
                        </select>
                    </div>
                    <div class='w-100 w-md-25 d-flex justify-content-center align-items-center flex-wrap mt-3 mt-md-0'>
                       <sl-color-picker id='color-picker' size='small' inline></sl-color-picker>
                    </div>
                    <div class='d-flex justify-content-center align-items-center mt-3 mt-md-0'>
                        <button class='btn border-0' type='button' data-bs-toggle='modal' data-bs-target='#addModal' onclick='btnGrafica()'>
                            <i class='bi bi-plus-circle-fill'></i>
                        </button>
                    </div>
                </div>
                <div class='mt-4 d-flex justify-content-center align-items-center gap-4 flex-column flex-md-row'>
                    <div class='d-flex justify-content-center'>
                        <label for='datos'>Datos:</label>
                    </div>
                     <div class='w-100 w-md-25 d-flex justify-content-center align-items-center flex-wrap'>
                        <select multiple size='10' id='multiselect-left-datos' class='rounded border-muted w-100'>
                            <!-- Left side options -->
                        </select>
                    </div>
                    <div class='w-100 w-md-25 d-flex justify-content-center align-items-center flex-wrap mt-3 mt-md-0'>
                        <select multiple size='10' id='multiselect-right-datos' class='rounded border-muted w-100'>
                            <!-- Right side options -->
                        </select>
                    </div>
                    <div class='d-flex justify-content-center align-items-center mt-3 mt-md-0'>
                        <button class='btn border-0' type='button' onclick='btnDatos()'>
                            <i class='bi bi-plus-circle-fill'></i>
                        </button>
                    </div>
                </div>
                <div class='mt-4 d-flex justify-content-center align-items-center gap-5'>
                    <label for='tmpInput'>Temperatura:</label>
                    <select class='form-select w-50' id='tmpInput' name='gmt'>
                        <option value='1' selected>C°</option>
                        <option value='2' selected>F°</option>
                        <option value='3' selected>K°</option>
                    </select>
                </div>
                <div class='mt-4 d-flex justify-content-center'>
                    <button onclick='test()' class='btn btn-primary mt-4'>Guardar</button>
                </div>
            ";
        $response = array(
            'html' => $html
        );
    
        echo json_encode($response);
    }

    public function obtenerValoresSelect(){

        $val = array(
            ["value" => "1", "text" => "Humidity"],
            ["value" => "2", "text" => "Return"],
            ["value" => "3", "text" => "Supply"],
            ["value" => "4", "text" => "Ethylene"],
            ["value" => "5", "text" => "Cargo"],
            ["value" => "6", "text" => "Evaporador"],
            ["value"=>"7", "text" => "ambiente"]
        );
    }
}

