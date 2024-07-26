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
<div class='swiper-slide' data-title='card_{$val->nombre_contenedor}'>
    <div class='card'  >
        <div class='card-body'>
            <div class='container '>
                <div class='row py-4' style='padding-right:5px ; padding-left:5px;'>
                    <div class='col-2' style='padding-right:5px ; padding-left:5px;' data-toggle='tooltip' data-placement='top' title='Turn ON/OFF'>         
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' strokeWidth={1.5} stroke='currentColor' width='35px' height='35px' class='mt-2 text-danger'>
                            <path strokeLinecap='round' strokeLinejoin='round' d='M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9' />
                        </svg>
                    </div>
                    <div class='col-8 '><h4 id='nombre_contenedor_{$val->telemetria_id}' class='text-center mt-3 fw-bold'> {$val->nombre_contenedor}</h4></div>
                    <div class='col-2 text-right' style='padding-right:5px ; padding-left:5px' data-toggle='tooltip' data-placement='top' title='Settings'>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' strokeWidth={1.5} stroke='currentColor' width='35px' height='35px' class='mt-2 text-gray-600'>
                            <path strokeLinecap='round' strokeLinejoin='round' d='M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z' />
                            <path strokeLinecap='round' strokeLinejoin='round' d='M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z' />
                        </svg>
                    </div>
                    <div class='col-12 '><h5 class='text-center mt-2'>{$val->descripcionC}</h5> </div>
                    <div class='col-4  mt-1 p-1'><h6 class='text-center'>Fecha :</h6></div>
                    <div class='col-8 mt-1 p-1'><h6 id='fechita_{$val->telemetria_id}' class='text-left'> {$fechita}</h6></div>
                    <div class='col-3 p-2'><button type='button' onclick='graficaM({$valR})' class='mt-1 btn btn-block btn-outline-primary' data-toggle='tooltip' data-placement='top' title='Gráfico'><i class='ri-line-chart-line'></i></button></div>
                    <div class='col-3 p-2'><button type='button' class='mt-1 btn btn-block btn-outline-secondary' data-toggle='tooltip' data-placement='top' title='Datos'><i class='ri-layout-horizontal-line'></i></button></div>
                    <div class='col-3 p-2'><button type='button' class='mt-1 btn btn-block btn-outline-success' data-toggle='tooltip' data-placement='top' title='Correo'><i class='ri-mail-line'></i></button> </div>
                    <div class='col-3 p-2'><button type='button' class='mt-1 btn btn-block btn-outline-info' data-toggle='tooltip' data-placement='top' title='Reporte'><i class='ri-file-chart-line'></i></button></div>
                    <div class='col-4 border text-center fw-bold'><p class='mt-1'>Params</p></div>
                    <div class='col-4 border-top border-bottom text-center fw-bold'><p class='mt-1'>Value</p></div>
                    <div class='col-4 border-start border-end border-top text-center fw-bold'><p class='mt-1'>Control</p></div>
                    <div class='col-8  align-self-center'>
                        <div class='row '>
                            <div class='col-6 border-start border-end'><p class='mt-2'><i class='ri-windy-line'></i>Supply:</p></div>
                            <div class='col-6 '><h6 class='mt-2' id='temp1_{$val->telemetria_id}'>{$temp1}</h6></div>
                            <div class='col-6 border'><p class='mt-2'><i class='ri-text-wrap'></i>Return :</p> </div>
                            <div class='col-6 border-top border-bottom'><h6 class='mt-2' id='return_{$val->telemetria_id}'>{$return}</h6></div>
                        </div>
                    </div>
                    <div class='col-4 border'>
                        <div class='row '>
                                <div class='col-12 '><h6 class='mt-2'>Temp :</h6></div>
                                <div class='col-8 p-1'>  <input type='text' class='form-control text-center' id='s_temp_{$val->telemetria_id}' value='{$s_temp} ' readonly></div>
                                <div class='col-4 align-self-center p-1'  >C°</div>
                            </div>
                        </div>
                    <div class='col-8'>
                        <div class='row'>
                            <div class='col-6 border-start border-end'><p class='mt-2'><i class='ri-water-percent-line'></i>Humedad:</p></div>
                            <div class='col-6'><h6 class='mt-2' id='humd_{$val->telemetria_id}'>{$humd}</h6></div>
                            <div class='col-6 border'><p class='mt-2'><i class='bi bi-safe2'></i>Evap :</p> </div>
                            <div class='col-6 border-top border-bottom'><h6 class='mt-2' id='evap_{$val->telemetria_id}'>{$evap}</h6></div>
                        </div>
                    </div>
                    <div class='col-4 border-start border-end'>
                        <div class='row '>
                            <div class='col-12 '><h6 class='mt-2'>Set Humd :</h6></div>
                            <div class='col-9 p-1'>  <input type='text' class='form-control text-center' id='s_humd_{$val->telemetria_id}' value='{$s_humd} ' readonly ></div>
                            <div class='col-3 align-self-center p-1 '>%</div>
                        </div>
                    </div>
                    <div class='col-4 border-start'>
                        <div class='row'>
                            <div class='col-12'><p class='mt-2 text-center py-5'><i class='ri-sip-fill'></i>USDA :</p></div>
                        </div>
                    </div>
                    <div class='col-4 border-start'>
                        <div class='row px-2'>
                            <div class='col-12 border mt-1'><h6 class='mt-2' id='cargo1_{$val->telemetria_id}'>{$cargo1}</h6></div> 
                            <div class='col-12 border mt-1'><h6 class='mt-2' id='cargo2_{$val->telemetria_id}'>{$cargo2}</h6></div>
                            <div class='col-12 border mt-1'><h6 class='mt-2' id='cargo3_{$val->telemetria_id}'>{$cargo3}</h6></div>
                            <div class='col-12 border mt-1'><h6 class='mt-2' id='cargo4_{$val->telemetria_id}'>{$cargo4}</h6></div>                         
                        </div>
                    </div>
                    <div class='col-4'>
                        <div class='row border text-center'>
                            <div class='col-12 '><h6 class='mt-2'>Defrost :</h6></div>
                            <div class='col-12 p-1'><button type='button' class='mt-1 btn btn-block btn-success'>ACTIVE</button> </div>
                        </div>
                        <div class='row border-start border-end'>
                            <div class='col-12 '><h6 class='mt-2'>Ethylene :</h6></div>
                            <div class='col-7 p-1'>  <input type='text' class='form-control' ></div>
                            <div class='col-5 align-self-center p-1 '>ppm</div>
                        </div>
                    </div>
                    <div class='col-8'>
                        <div class='row '>
                            <div class='col-6 border'><p class='mt-2'><i class='ri-cloud-line'></i>CO2 :</p></div>
                            <div class='col-6 border-top'><h6 class='mt-2'> 0.10 %</h6></div>
                            <div class='col-6 border-start border-bottom'><p class='mt-2'><i class='bi bi-thermometer-half'></i>Ambiente :</p> </div>
                            <div class='col-6 border-top border-bottom border-start'><h6 class='mt-2'>+23.12 C°</h6></div>
                        </div>
                    </div>
                    <div class='col-4 border'>
                        <div class='row'>
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