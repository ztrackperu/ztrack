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

    /*public function ProfileInformation(){

        $informationArray = array(
            "Username" => "Gian",
            "Email" => "giantest@gmail.com",
            "Name" => "Name",
            "Lastname" => "Lastname",
            "Phone" => "Phone",
            "Address" => "Address",
            "City" => "City",
            "Country" => "Country",
            "PostalCode" => "PostalCode",
            "ProfilePicture" => "ProfilePicture"
        );
        
        foreach()
        $text = "";
        $text = 
        " 
        <img src='https://via.placeholder.com/150' alt='profile' class='img-fluid rounded-circle'>
        <h4>Username</h4>
        
        ";
        $data = array("text" => $text);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);

    }
    */

    public function profileInformation(){
        //array con objeto

        $informationArray = array(
            "Username" => "garce",
            "FullName" => "Gian Arce",
            "Email" => "garce_test@gmail.com",
            "Number" => "123456789",
            "Address" => "Calle 123",
            "Company" => "Ztrack",
            "City" => "Lima",
            "Country" => "Peru",

        );
        echo json_encode($informationArray, JSON_UNESCAPED_UNICODE);
    }
}

