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
        $perm = $this->model->verificarPermisos($id_user, "AdminPage");
        if (!$perm && $id_user != 1) {
            $this->views->getView($this, "permisos");
            exit;
        }
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
        echo $par;

    }

}