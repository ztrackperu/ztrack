<?php

class AdminPage extends Controller
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
		$id_user = $_SESSION['id_ztrack'];
        /*
        $perm = $this->model->verificarPermisos($id_user, "AdminPage");
        if (!$perm && $id_user != 1) {
            $this->views->getView($this, "permisos");
            exit;
        }*/
        $this->views->getView($this, "index");
    }

    public function validarCamposCorreoYClave()
    {
        $id_user = $_SESSION['id_usuario'];
        $res = $this->model->validarCamposCorreoYClave($id_user);
        echo json_encode($res);
        die();
    }
    public function validarCorreo()
    {
        $id = $_SESSION['id_usuario'];
        $email = strClean($_POST['email']);
        $pass_email = strClean($_POST['pass_email']);
        //echo $id." , ".$email." , ".$pass_email;
        if (empty($email) || empty($pass_email)) {
            $msg = array('msg' => 'Ingrese correctamente su email o pass', 'icono' => 'warning');
        }
        else{
            // enviar correo 
        $testCorreo = envio_correo($email ,$pass_email,"ztrack@zgroup.com.pe");
        if($testCorreo=="ok"){
            //vamos a guardar los datos 
            $res = $this->model->actualizarcorreo($email ,$pass_email,$id);
            if($res=="ok"){
                $msg = array('msg' => 'Correo Validado', 'icono' => 'success');

            }else{
                $msg = array('msg' => 'respuesta inseperada', 'icono' => 'warning');
            }

        }else{
            $msg = array('msg' => 'Error al validar correo', 'icono' => 'warning');
        }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die(); 

    }


    public function registrar()
    {
        $id = $_SESSION['id_usuario'];
        $correo_usuario = strClean($_POST['correo']);
        $clave_correo = strClean($_POST['password']);
        $email_existente = strClean($_POST['correo_admin']);
        $usuario_activo = $_SESSION['id_usuario'];

        if (empty($correo_usuario) || empty($clave_correo)) {
            $msg = array('msg' => 'Ingrese todos sus datos', 'icono' => 'warning');
        } else {
            $data = $this->model->insertarRespuesta($id, $correo_usuario,$usuario_activo);

            if ($data == "ok") {
                $evento = "RESPONDIDO";
                $id_consulta = $this->model->IdRespuesta($correo_usuario);
                $id = $id_consulta['id'];
                $data2 = $this->model->h_respuesta($id, $id, $correo_usuario,$usuario_activo, $evento);
                $msg = array('msg' => 'Respuesta enviada', 'icono' => 'success');
                
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $correo_usuario; // Reemplaza con tu dirección de correo electrónico de Gmail
                $mail->Password = $clave_correo; // Reemplaza con tu contraseña de Gmail
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                // Configuración del correo electrónico
                $mail->setFrom('zgroupsistemas@gmail.com', 'ZTRACK');
                $mail->addAddress($email_existente); // Reemplaza con la dirección de correo electrónico del destinatario
                $mail->send();
            } else {
                $msg = array('msg' => 'Error al registrar', 'icono' => 'error');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    //ListaNotificaciones
    public function ListaNotificaciones()
    {
        /*
        $data = $this->model->ListaNotificaciones();
        $resultado = json_decode($data);
        $resultado1 = $resultado->data;
        $par="";
        foreach($resultado1 as $det){
            //$par.=$det->numOT.",";
            $par .= '<div class="alert alert-success alert-dismissible fade show">
            <strong>'.$det->asunto.'</strong> Solicitud: '.$det->numSolicitud.' /  OT: '.$det->numOT.' / Trabajo: '.$det->trabajo.' / Inicio: '.$det->fechaN.'
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>' ; 
        }
        //echo json_encode($resultado1);
        echo $par;*/

    }

    
    

    public function PanelControlInformation() {
        $array = array(
            "online" => array(
                "count" => 10,
                "details" => array(
                    array("id" => "ZGRU1090804", "set_point" => 22.2, "return" => 20, "supply" => 23, "humidity" => 80)
                )
            ),
            "wait" => 35,
            "offline" => 85,
            "alarmas" => 63,
            "usuarios" => 20,
            "dias_restantes" => 5,
            "reefer" => 12,
            "ripener" => 3,
            "genset" => 1,
            "ultimo_acceso" => array(
                "details" => array(
                    array("id" => 1, "usuario" => "gperez", "tipo_movimiento" => "cambio de set point", "dispositivo" => "ZGRU1090804", "fecha" => "15/08/2024", "estado" => "aceptado")
                )
            )
        );
    
        $html = "";
        $html .= "
            <div class='row'>
                <div class='col-4 col-lg-4 mt-2'>
                    <div class='card'>
                        <button type='button' class='btn btn-outline-success px-2 py-3'>
                            <h5 class='text-center button_pannel_frow'>ONLINE: ({$array['online']['count']})</h5>
                        </button>
                    </div>
                </div>
                <div class='col-4 col-lg-4 mt-2'>
                    <div class='card'>
                        <button type='button' class='btn btn-outline-warning px-2 py-3'>
                            <h5 class='text-center button_pannel_frow'>WAIT: ({$array['wait']})</h5>
                        </button>
                    </div>
                </div>
                <div class='col-4 col-lg-4 mt-2'>
                    <div class='card'>
                        <button type='button' class='btn btn-outline-danger px-2 py-3'>
                            <h5 class='text-center button_pannel_frow'>OFFLINE: ({$array['offline']})</h5>
                        </button>
                    </div>
                </div>
            </div>
            <div class='row mt-2'>
                <div class='col-4 col-lg-4 mt-2'>
                    <div class='card'>
                        <button type='button' class='btn btn-outline-secondary px-2 py-3'>
                            <div class='text-center'>
                                <i class='ri-notification-fill fs-3'></i>
                                <h5 class='button_pannel_srow'>ALARMAS: ({$array['alarmas']})</h5>
                            </div>
                        </button>
                    </div>
                </div>
                <div class='col-4 col-lg-4 mt-2'>
                    <div class='card'>
                        <button type='button' class='btn btn-outline-primary px-2 py-3'>
                            <div class='text-center'>
                                <i class='bi bi-people-fill fs-3'></i>
                                <h5 class='button_pannel_srow'>USUARIOS: ({$array['usuarios']})</h5>
                            </div>
                        </button>
                    </div>
                </div>
                <div class='col-4 col-lg-4 mt-2'>
                    <div class='card'>
                        <button type='button' class='btn btn-outline-info px-2 py-3'>
                            <div class='text-center'>
                                <i class='bi bi-person-vcard fs-3'></i>
                                <h5 class='button_pannel_srow'>MEMBRESÍA: ({$array['dias_restantes']})</h5>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-4 col-lg-4 mt-2'>
                    <div class='card border-secondary'> 
                        <button type='button' class='btn px-2 py-3 border-0'>
                            <div class='text-center'>
                              <svg version='1.0' xmlns='http://www.w3.org/2000/svg'
                                    class='icon_trow' viewBox='0 0 100.000000 56.000000'
                                    preserveAspectRatio='xMidYMid meet'>

                                    <g transform='translate(0.000000,56.000000) scale(0.100000,-0.100000)' fill='#000000' stroke='none'>
                                    <path d='M7 533 c-4 -3 -7 -49 -7 -100 0 -85 2 -94 18 -91 14 3 17 16 20 81 l3 77 84 0 85 0 0 -50 c0 -38 -4 -50 -15 -50 -8 0 -15 -7 -15 -15 0 -9 7 -18
                                    15 -21 12 -5 15 -25 15 -90 0 -68 -3 -84 -15 -84 -8 0 -15 -7 -15 -15 0 -8 7 -15 15 -15 11 0 15 -12 15 -50 l0 -50 -85 0 -84 0 -3 78 c-2 60 -6 77 -18 77
                                    -12 0 -15 -17 -15 -95 l0 -95 495 0 495 0 3 197 2 198 -22 5 c-13 3 -139 30 -282 60 l-258 55 -212 0 c-117 0 -216 -3 -219 -7z m413 -253 l0 -220 -85 0
                                    -85 0 0 45 c0 31 5 47 15 51 19 8 19 34 0 34 -12 0 -15 16 -15 84 0 65 3 85 15 90 8 3 15 12 15 21 0 8 -7 15 -15 15 -11 0 -15 12 -15 50 l0 50 85 0 85 0
                                    0 -220z m281 165 c118 -25 224 -48 237 -52 22 -5 22 -7 22 -169 l0 -164 -250 0 -250 0 0 215 c0 174 3 215 13 215 8 0 110 -20 228 -45z'/>
                                    <path d='M530 255 c0 -140 2 -165 15 -165 13 0 15 25 15 165 0 140 -2 165 -15
                                    165 -13 0 -15 -25 -15 -165z'/>
                                    <path d='M647 394 c-4 -4 -7 -74 -7 -156 0 -120 3 -148 14 -148 22 0 26 24 26
                                    154 0 83 -4 128 -13 140 -7 9 -16 14 -20 10z'/>
                                    <path d='M750 230 c0 -113 3 -140 14 -140 22 0 26 23 26 140 0 117 -4 140 -26
                                    140 -11 0 -14 -27 -14 -140z'/>
                                    <path d='M860 220 c0 -104 3 -130 14 -130 21 0 26 23 26 130 0 107 -5 130 -26
                                    130 -11 0 -14 -26 -14 -130z'/>
                                    <path d='M4 289 c-10 -17 13 -36 27 -22 12 12 4 33 -11 33 -5 0 -12 -5 -16
                                    -11z'/>
                                    </g>
                                </svg>
                                <h5 class='text-uppercase button_pannel_trow'>Reefer: ({$array['reefer']})</h5>
                            </div>
                        </button>
                    </div>
                </div>
                <div class='col-4 col-lg-4 mt-2'>
                    <div class='card border-success'>
                        <button type='button' class='btn px-2 py-3 border-0'>
                            <div class='text-center'>
                                <svg class='icon icon_trow'>
                                <use xlink:href='sprite.svg#ripener_icon'></use>
                                </svg>
                                <h5 class='text-success text-uppercase button_pannel_trow'>Ripener: ({$array['ripener']})</h5>
                            </div>
                        </button>
                    </div>
                </div>
                <div class='col-4 col-lg-4 mt-2'>
                    <div class='card'>
                        <button type='button' class='btn btn-outline-primary px-2 py-3'>
                            <div class='text-center'>
                                <i class='bi bi-lightning-charge-fill fs-3'></i>
                                <h5 class='text-uppercase button_pannel_trow'>Genset: ({$array['genset']})</h5>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        ";
        foreach ($array['online']['details'] as $detail) {
            $html .= "
            <div class='table-responsive mt-4'>
                <table class='table table-bordered text-center'>
                    <thead class='text-uppercase'>
                        <th>PWR</th>
                        <th>Reefer ID</th>
                        <th>Set Point</th>
                        <th>Return</th>
                        <th>Supply</th>
                        <th>Humidity</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class='bi bi-plugin text-success fs-4'></i></td>
                            <td>
                                <div class='dropright'>
                                    {$detail['id']} 
                                    <button type='button' class='btn border-0' id='dropdownLink' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='ri-more-2-line'></i>
                                    </button>
                                    <ul class='dropdown-menu px-0 py-0' aria-labelledby='dropdownLink'>
                                        <li><a class='dropdown-item'><i class='ri-bar-chart-2-line'></i>Gráfico</a></li>
                                        <li><a class='dropdown-item'><i class='ri-file-chart-2-line'></i>Datos</a></li>
                                        <li><a class='dropdown-item'><i class='ri-file-chart-line'></i>Reporte</a></li>

                                    <ul>
                                </div>
                            </td>
                            <td>{$detail['set_point']}</td>
                            <td>{$detail['return']}</td>
                            <td>{$detail['supply']}</td>
                            <td>{$detail['humidity']}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            ";
        }
        foreach ($array['ultimo_acceso']['details'] as $detail) {
            $html .= "
            <div class='table-responsive mt-2'>
                <h5 class='fw-bold text-uppercase'>Movimientos de Usuarios</h5>
                <table class='table table-bordered text-center'>
                    <thead class='text-uppercase'>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Tipo de Movimiento</th>
                        <th>Dispositivo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{$detail['id']}</td>
                            <td>{$detail['usuario']}</td>
                            <td>{$detail['tipo_movimiento']}</td>
                            <td>{$detail['dispositivo']}</td>
                            <td>{$detail['fecha']}</td>
                            <td>{$detail['estado']}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            ";
        }
        $response = array(
            'html' => $html
        );
    
        echo json_encode($response);
    }

}

/* 
<div class='table-responsive mt-4'>
                <table class='table table-bordered'>
                    <thead class='text-uppercase'>
                        <th>Reefer ID</th>
                        <th>Set Point</th>
                        <th>Return</th>
                        <th>Supply</th>
                        <th>Humidity</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class='table-responsive mt-2'>
                <h5 class='fw-bold text-uppercase'>Movimientos de Usuarios</h5>
                <table class='table table-bordered'>
                    <thead class='text-uppercase'>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Tipo de Movimiento</th>
                        <th>Dispositivo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </thead>
                </table>
            </div>*/