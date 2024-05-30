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
                    'ultima'=>$ultimaFecha,
                );
            }else{
                if(fechaGrafica($fechaI,$fechaF)=="ok"){
                    $cadena = array(
                        'device'=>$telemetria,
                        'ultima'=>$ultimaFecha,
                        //'fechaI'=>$fechaI.":00",
                        //'fechaF'=>$fechaF.":00"
                        'fechaI'=>$fechaI,
                        'fechaF'=>$fechaF
                    );
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
        foreach($data as $val){
            //validacion de informacion 
            $temp1 =tempNormal($val->temp_supply_1) ; 
            $return =tempNormal($val->return_air) ; 
            $s_temp =tempNormal($val->set_point) ; 
            $humd =porNormal($val->relative_humidity) ; 
            $s_humd =porNormal($val->humidity_set_point) ; 
            $evap =tempNormal($val->evaporation_coil) ; 
            $cargo1 =tempNormal($val->cargo_1_temp) ; 
            $cargo2 =tempNormal($val->cargo_2_temp) ; 
            $cargo3 =tempNormal($val->cargo_3_temp) ; 
            $cargo4 =tempNormal($val->cargo_4_temp) ; 
            //$ultima = explode("T",$val->ultima_fecha) ;
            //$fech = explode("-",$ultima[0]);
            //$fech1 = $fech[2]."/".$fech[1]."/".$fech[0] ; 
            //$fechita =$ultima[1]." del  ".$fech1;
            $fechita =fechaPro($val->ultima_fecha);
;
            
$valR ='"'.$val->nombre_contenedor.'"';
$text.="
<div class='swiper-slide'>
    <div class='card'  >
        <div class='card-body'>
            <div class='container '>
                <div class='row ' style='padding-right:5px ; padding-left:5px;'>
                    <div class='col-2' style='padding-right:5px ; padding-left:5px;'>
                        <img class ='mt-1 p-1'src='{$url}/Assets/img/on.png' alt='logo' width='40' height='40'>
                    </div>
                    <div class='col-8 '><h4 id='nombre_contenedor_{$val->telemetria_id}' class='text-center mt-3'> {$val->nombre_contenedor}</h4></div>
                    <div class='col-2 text-right' style='padding-right:5px ; padding-left:5px'>
                        <img class ='mt-1 p-1 'src='{$url}/Assets/img/conf.png' alt='logo' width='40' height='40'>
                    </div>
                    <div class='col-12 '><h5 class='text-center mt-2'>{$val->descripcionC}</h5> </div>
                    <div class='col-4  mt-1 p-1'><h6 class='text-center'>Fecha :</h6></div>
                    <div class='col-8 mt-1 p-1'><h6 id='fechita_{$val->telemetria_id}' class='text-left'> {$fechita}</h6></div>
                    <div class='col-3 p-2'><button type='button' onclick='graficaM({$valR})' class='mt-1 btn btn-block btn-outline-primary'>Gr</button></div>
                    <div class='col-3 p-2'><button type='button' class='mt-1 btn btn-block btn-outline-secondary'>Se</button></div>
                    <div class='col-3 p-2'><button type='button' class='mt-1 btn btn-block btn-outline-success'>Su</button> </div>
                    <div class='col-3 p-2'><button type='button' class='mt-1 btn btn-block btn-outline-info'>In</button></div>
                    <div class='col-4 border'><p class='mt-1'>Params</p></div>
                    <div class='col-4 border'><p class='mt-1'>Value</p></div>
                    <div class='col-4 border'><p class='mt-1'>Control</p></div>
                    <div class='col-8  align-self-center'>
                        <div class='row '>
                            <div class='col-6 border'><p class='mt-2'>Supply:</p></div>
                            <div class='col-6 '><h6 class='mt-2' id='temp1_{$val->telemetria_id}'>{$temp1}</h6></div>
                            <div class='col-6 border'><p class='mt-2'>Return :</p> </div>
                            <div class='col-6 border'><h6 class='mt-2' id='return_{$val->telemetria_id}'>{$return}</h6></div>
                        </div>
                    </div>
                    <div class='col-4 border'>
                        <div class='row '>
                                <div class='col-12 '><h6 class='mt-2'>Temp :</h6></div>
                                <div class='col-8 p-1'>  <input type='text' class='form-control' id='s_temp_{$val->telemetria_id}' value='{$s_temp} ' readonly></div>
                                <div class='col-4 align-self-center p-1'  >C°</div>
                            </div>
                        </div>
                    <div class='col-8 border'>
                        <div class='row '>
                            <div class='col-6 border'><p class='mt-2'>Humedad:</p></div>
                            <div class='col-6 border'><h6 class='mt-2' id='humd_{$val->telemetria_id}'>{$humd}</h6></div>
                            <div class='col-6 border'><p class='mt-2'>Evap :</p> </div>
                            <div class='col-6 border'><h6 class='mt-2' id='evap_{$val->telemetria_id}'>{$evap}</h6></div>
                        </div>
                    </div>
                    <div class='col-4 border'>
                        <div class='row '>
                            <div class='col-12 '><h6 class='mt-2'>Set Humd :</h6></div>
                            <div class='col-9 p-1'>  <input type='text' class='form-control' id='s_humd_{$val->telemetria_id}' value='{$s_humd} ' readonly ></div>
                            <div class='col-3 align-self-center p-1 '>%</div>
                        </div>
                    </div>
                    <div class='col-4  align-self-center'>
                        <div class='row'>
                            <div class='col-12 '><p class='mt-2'>USDA :</p></div>
                        </div>
                    </div>
                    <div class='col-4 border'>
                        <div class='row'>
                            <div class='col-12 border'><h6 class='mt-2' id='cargo1_{$val->telemetria_id}'>{$cargo1}</h6></div> 
                            <div class='col-12 border'><h6 class='mt-2' id='cargo2_{$val->telemetria_id}'>{$cargo2}</h6></div>
                            <div class='col-12 border'><h6 class='mt-2' id='cargo3_{$val->telemetria_id}'>{$cargo3}</h6></div>
                            <div class='col-12 border'><h6 class='mt-2' id='cargo4_{$val->telemetria_id}'>{$cargo4}</h6></div>                         
                        </div>
                    </div>
                    <div class='col-4 border'>
                        <div class='row border'>
                            <div class='col-12 '><h6 class='mt-2'>Defrost :</h6></div>
                            <div class='col-12 p-1'><button type='button' class='mt-1 btn btn-block btn-success'>ACTIVE</button> </div>
                        </div>
                        <div class='row border'>
                            <div class='col-12 '><h6 class='mt-2'>Ethylene :</h6></div>
                            <div class='col-7 p-1'>  <input type='text' class='form-control' ></div>
                            <div class='col-5 align-self-center p-1 '>ppm</div>
                        </div>
                    </div>
                    <div class='col-8 border'>
                        <div class='row '>
                            <div class='col-6 border'><h5 class='mt-2'>CO2 :</h5></div>
                            <div class='col-6 border'><h4 class='mt-2'> 0.10 %</h4></div>
                            <div class='col-6 border'><h5 class='mt-2'> Ambiente :</h5> </div>
                            <div class='col-6 border'><h4 class='mt-2'>+23.12 C°</h4></div>
                        </div>
                    </div>
                    <div class='col-4 border'>
                        <div class='row '>
                            <div class='col-12 '><h6 class='mt-2'>CO2 :</h6></div>
                            <div class='col-9 p-1'>  <input type='text' class='form-control' ></div>
                            <div class='col-3 align-self-center p-1 '>%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
</div>
" ;
array_push($data2 ,array(
    'latitud'=>$val->latitud,
    'longitud'=>$val->longitud,
    'nombre_contenedor'=> $val->nombre_contenedor,
));
        }
        //$data->text = $text;
        $data1 =array(
            'data'=>$data2,
            'text'=>$text,
            'extraer'=>$_SESSION['data']
        );
        echo json_encode($data1, JSON_UNESCAPED_UNICODE);
        die();

    }    

}