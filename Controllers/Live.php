<?php

class Live extends Controller
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
        $this->views->getView($this, "index");

    }
    public function ProcesarFecha($param){
        if($param!=""){
            $pros = explode(",",$param);
            $dateInicial = new DateTime($pros[1]);
            $dateFinal = new DateTime($pros[2]);
            if($dateFinal<$dateInicial){
                $dif="mal";
            }else{
                //si paso 2 años decir que deb contactarse con el administrador
                $interval = $dateInicial->diff($dateFinal);
                $colosal = $interval->format('%Y');
                if($colosal>=2){
                    $dif="rango";
                }else{
                    $dif="ok";
                    //$dif = $this->GraficaInicial($param);
                    //enviar informacion pa su procesamiento 


                }
                //$dif =$colosal;
            }
            //$interval = $datetime1->diff($datetime2);
            $resultadoMadurador ="esta es la cadena , Dispositivo : ".$pros[0]." ,FechaInicial : ".$pros[1]." ,FechaFinal : ".$pros[2]." ,condicion : ".$dif;

        }else{
            $resultadoMadurador ="";
        }
        echo json_encode($dif , JSON_UNESCAPED_UNICODE);

    }

    public function Validar($val){
        $respuesta = 'mal';
        if($val != ''){
            $usuario = $_SESSION['usuario_ztrack'];
            $data = $this->model->getHash($usuario); 
            $resultadoPass = json_decode($data);
            $resultadoPass = $resultadoPass->data->password;
            if (password_verify($val,$resultadoPass)) {
            $respuesta = 'ok';

            }
        }
        echo json_encode($respuesta , JSON_UNESCAPED_UNICODE);

    }
    public function GraficaInicial($param){
    
        if($param!=""){
            $pros = explode(",",$param);
            $telemetria = $pros[0];
            $fechaI =(isset($pros[1])) ? $pros[1] :"0" ;
            $fechaF =(isset($pros[2])) ? $pros[2] :"0" ;
            // consultar para nombre_contenedor y ultima fecha 
            $consultaUltima = $this->model->ContenedorData($telemetria);
            $resultadoL = json_decode($consultaUltima);
            $resultadoL = $resultadoL->data;
            $ultimaFecha = $resultadoL[0]->ultima_fecha;
            if($fechaI=="0" && $fechaF=="0"){
                $cadena = array(
                    'device'=>$telemetria,
                    'ultima'=>gmtFecha($ultimaFecha),
                    'utc'=>$_SESSION['utc']
                );
            }else{
                if(fechaGrafica($fechaI,$fechaF)=="ok"){
                    $cadena = array(
                        'device'=>$telemetria,
                        'ultima'=>gmtFecha($ultimaFecha),
                        //'fechaI'=>$fechaI.":00",
                        //'fechaF'=>$fechaF.":00"
                        'fechaI'=> validateDate($fechaI),
                        'fechaF'=> validateDate($fechaF),
                        'utc'=>$_SESSION['utc']
                        
                    );
                    //validateDate($fechaI, $format = 'Y-m-d H:i:s')
                }else{
                    $cadena = array();
                }
            }
            if(count($cadena)!=0){
                //hacer peticion de data en el servidor 
                $dataMadurador = $this->model->DatosGraficaTabla($cadena);
                $resultadoMadurador = json_decode($dataMadurador);
                $resultadoMadurador = $resultadoMadurador->data;
            }else{
                $resultadoMadurador =fechaGrafica($fechaI,$fechaF);
            }
        }else{
            $resultadoMadurador ="";
        }
        echo json_encode($resultadoMadurador , JSON_UNESCAPED_UNICODE);

    }

    //LiveData
    public function LiveData()
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
        //forma de recibir un json desde js     
        $datosRecibidos = file_get_contents("php://input");
        //$resultado = $_POST['data'];
        //echo json_encode($datosRecibidos, JSON_UNESCAPED_UNICODE);
        $resultado1 = json_decode($datosRecibidos);
        //enviar el resultado1 a api para procesar si existe algun cambio
        $VerificarLive = $this->model->VerificarLive($resultado1);
        $resultado = $resultado1->data;
        echo json_encode($VerificarLive, JSON_UNESCAPED_UNICODE);
        */
        $datosW =$_SESSION['data'] ;
        $resultado1 = array('data'=>$datosW);
        $VerificarLive = $this->model->VerificarLive($resultado1);
        $Verificar = json_decode($VerificarLive);
        $Verificar = $Verificar->data;
        //$resultado = $VerificarLive->data;
        /*
        $text ="";
        $datosW =$_SESSION['data'] ;
        foreach ($datosW as $dat) {
            $text.=$dat->telemetria_id.",";
        }
        */
        $d =0 ;
        foreach ($datosW as $clave => $valor) {
            // $array[3] se actualizará con cada valor de $array...
            //echo "{$clave} => {$valor} ";
            //print_r($array);
            foreach ($Verificar as $dat) {
                if($valor->telemetria_id==$dat->telemetria_id){
                    //va haber reemplazo en session en la fecha pa continuar actualizando
                    $_SESSION['data'][$clave]->ultima_fecha =$dat->ultima_fecha ;
                    $dat->ultima_fecha = fechaPro($dat->ultima_fecha);
                    //echo $dat->ultima_fecha;
                    $dat->temp_supply_1 =tempNormal($dat->temp_supply_1) ; 
                    $dat->return_air =tempNormal($dat->return_air) ; 
                    $dat->set_point =tempNormal($dat->set_point) ; 
                    $dat->relative_humidity =porNormal($dat->relative_humidity) ; 
                    $dat->humidity_set_point =porNormal($dat->humidity_set_point) ; 
                    $dat->evaporation_coil =tempNormal($dat->evaporation_coil) ; 
                    $dat->cargo_1_temp =tempNormal($dat->cargo_1_temp) ; 
                    $dat->cargo_2_temp =tempNormal($dat->cargo_2_temp) ; 
                    $dat->cargo_3_temp =tempNormal($dat->cargo_3_temp) ; 
                    $dat->cargo_4_temp =tempNormal($dat->cargo_4_temp) ; 
                    $d++;
                }
            }
        }        
        //echo json_encode($_SESSION['data'][0]->telemetria_id, JSON_UNESCAPED_UNICODE);
        echo json_encode($Verificar , JSON_UNESCAPED_UNICODE);
        die();
    } 
    public function ListaDispositivoEmpresa()
    {
        $data = $this->model->ListaDispositivoEmpresa($_SESSION['empresa_id']);
        $data = json_decode($data);
        $data = $data->data;
        $text ="";
        $data2 =[];
        $url = base_url;
        $fecha=[];
        
        foreach($data as $val){
            $tipo = $val->extra_1;
            $enlace = ContenedorPlantilla($val,$url, $tipo) ;
            $fecha =  determinarEstado($val->ultima_fecha ,$id =1,$fecha);
            $text.=$enlace['text'];
            array_push($data2 ,array(
                'latitud'=>$enlace['latitud'],
                'longitud'=>$enlace['longitud'],
                'nombre_contenedor'=> $enlace['nombre_contenedor'],
            ));
        }
        //$data->text = $text;
        $data1 =array(
            //'data'=>tarjetamadurador($val)
            'data'=>$data2,
            'text'=>$text,
            'extraer'=>$_SESSION['data'],
            'estadofecha'=>$fecha
        );
        echo json_encode($data1, JSON_UNESCAPED_UNICODE);
        die();

    }   
    public function obtenerFormulario($id){
        $data = $this->model->ListaDispositivoEmpresa($_SESSION['empresa_id']);
        $data = json_decode($data);
        $data = $data->data;
        $text = "";
        foreach($data as $val){
            if($val->telemetria_id == $id){
                $enlace = formularioPlantilla($val,$id);
                $text.=$enlace['text'];
                break;
            }
        }
        $data1 =array(
            'text'=>$text
        );
        echo json_encode($data1, JSON_UNESCAPED_UNICODE);
        die();

    }

    public function ListaD() {
        $data = $this->model->ListaDispositivoEmpresa($_SESSION['empresa_id']);
        $data = json_decode($data);
        $data = $data->data;
        $estados = array();
        
        foreach($data as $val2) {
            $estado = $this->determinarEstado($val2->ultima_fecha);
            $estados[] = (object) ['estado' => $estado];
        }
        
        $n_array = array(
            'estados' => $estados
        );
        
        echo json_encode($n_array, JSON_UNESCAPED_UNICODE);
        die();
    }
    //FUNCION PARA DEFINIR SI EL DISPOSITIVO ESTÁ ON, WAIT O OFF
    // fecha (hoy) hasta (30min atras) = ON
    // fecha desde (30min atras) hasta (24 horas atras) = WAIT
    // fecha desde (24 horas atras) = OFF
    // $ult_fecha = fechaPro($val->ultima_fecha);
    private function determinarEstado($ultima_fecha) {
        
        $fechaActual = new DateTime();
        $fechaUltima = new DateTime($ultima_fecha);
        $diferencia = $fechaActual->getTimestamp() - $fechaUltima->getTimestamp();
        
        //tiempo en segundos
        if ($diferencia >= 1800) { 
            return 'Online';
        } elseif ($diferencia <= 86400) { 
            return 'Wait';
        } else {
            return 'Offline';
        }
    }
}

