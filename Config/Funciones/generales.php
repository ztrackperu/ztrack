<?php 
//envios de correo
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP; 

    //genrar pdf
    use Dompdf\Dompdf;

    function tempNormal($val){ 
        if($val>=0 && $val<120){
            $valor="+".$val ;
        }elseif($val>-40 && $val<0){
            if($val==-38.5){
                $valor="NA";
            }else{
                $valor=$val ;
            }
        }else{
            $valor="NA";
        }
        return $valor;
    }
    function validateDate($date, $format = 'Y-m-d\TH:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        if($d && $d->format($format) == $date){
            return $date;
        }else{
            return $date.":00";   
        }
        //return $d && $d->format($format) == $date;
    }
    function porNormal($val){
        if($val>=0 && $val<100){$valor=$val ;}else{$valor="NA";}
        return $valor;
    }
    function gmtFecha($val){
        if($_SESSION['utc']!=300){
            $val1 =  strtotime($val);
            $dif =300-$_SESSION['utc'];
            $minutes = $dif." minutes";
            $puntoA1 = strtotime($minutes,$val1);
            $val = date('Y-m-d\TH:i:s', $puntoA1);
        }
        return $val;
    }
    function fechaPro($val){
        //echo $val;
        //previa validacion de GMT  "Y-m-d\TH:i:s
        if($_SESSION['utc']!=300){
            $val1 =  strtotime($val);
            $dif =300-$_SESSION['utc'];
            $minutes = $dif." minutes";
            $puntoA1 = strtotime($minutes,$val1);
            $val = date('Y-m-d\TH:i:s', $puntoA1);
        }
        $ultima = explode("T",$val) ;
        $fech = explode("-",$ultima[0]);
        //echo $ultima[0];
        //echo " luis ";
        //echo $fech;
        $fech1 = $fech[2]."/".$fech[1]."/".$fech[0] ; 
        //echo $fech1;
        $fechita =$ultima[1]." del  ".$fech1;           

        //$fech1 = $fech[2]."/".$fech[1]."/".$fech[0] ; 
        //$fechita =$ultima[1]." del  ".$fech1;
        return $fechita;
    }
    function fechaGrafica($dateI,$dateF){
        // Crear objetos DateTime a partir de las cadenas de fecha
        $dateInicial = new DateTime($dateI);
        $dateFinal = new DateTime($dateF);
        //$actual = new DateTime("now");
        if($dateFinal<$dateInicial ){
            $dif="mal";
        }else{
            //si paso 2 años decir que deb contactarse con el administrador
            $interval = $dateInicial->diff($dateFinal);
            $colosal = $interval->format('%Y');
            if($colosal>=2){ $dif="rango";
            }else{ $dif="ok";}
        }
        return $dif;

    }
    function procesarNumOT($numot){
        $digitos = 10;
        $num = strval($numot);
        $can = strlen($num);
        $falta = $digitos - $can - 1;
        $respuesta = "1";
        for( $i = 0; $i < $falta; $i++) {
            $respuesta .= "0";
        }
        $cadena =$respuesta.$numot;
        return $cadena;
    }
    
    function  procesarPdf($htmlContent,$numot){
        // Crea el PDF con Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'landscape');
        header('Content-Type: application/pdf');
        $dompdf->render();
        $dompdf->stream($numot.".pdf", ['Attachment' => false]);
        return $dompdf ;
    }
    function  aspectoPDFoT($data){
        $trato = json_decode($data);
        // Crea el diseño en html
        $aspecto = '<h1>'.$data.'</h1>';
        
        return $aspecto ;
    }
    //funciones que complementan la division de texto sin explode 
    function strrevpos($instr, $needle)
    {
        $rev_pos = strpos (strrev($instr), strrev($needle));
        if ($rev_pos===false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    };
    function after_last ($this1, $inthat)
    {
        if (!is_bool(strrevpos($inthat, $this1)))
        return substr($inthat, strrevpos($inthat, $this1)+strlen($this1));
    };
    function before_last ($this1, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $this1));
    };
    //fin de funciones que dividen texto 

    function envio_correo($remitente,$passRemitente,$destinatario)
    { 
        $mail = new PHPMailer(true);
        $correoEnvio = $destinatario;    
        $asunto = "Bienvenido" ;
        $mensaje = "<h2> Test Ok  </h2>";
        $mensaje .= "<h3>CONEXION ESTABLECIDA</h3>";
        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->SMTPDebug = 0;
            $mail->isSMTP();    
            $mail->From = $remitente; 
            //$mail->From = "desarrollo@zgroup.com.pe";                                   //Send using SMTP
            $mail->Host       = "smtp.gmail.com";                   //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            //$mail->Username   = 'desarrollo@zgroup.com.pe';                     //SMTP username
            //$mail->Password   = 'Des5090100';                               //SMTP password
            $mail->Username   = $remitente;                     //SMTP username
            $mail->Password   = $passRemitente;                               //SMTP password
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            //Agregar destinatario
            $mail->AddAddress($correoEnvio);
            $mail->Subject = utf8_decode($asunto);
            $mail->Body =utf8_decode($mensaje);
            $mail->isHTML(true);
            //$mail->AddAttachment('./excel/'.$nombreContenedor.'_'.$fechaZ.'.xlsx', $nombreContenedor.'_'.$fechaZ.'.xlsx');
            //Avisar si fue enviado o no y dirigir al index
            if ($mail->Send()) {
                $data ='ok';  
            } else {
                $data ='<script type="text/javascript">alert("NO ENVIADO, intentar de nuevo");</script>';
            }    
        }catch (Exception $e) {
            $data = "Se ha producido un mensaje de error . Mailer Error: {$mail->ErrorInfo}"; 
        }
        return $data;
    }

    function envio_correoTest($remitente,$passRemitente,$destinatario)
    { 
        $mail = new PHPMailer(true);
        $correoEnvio = $destinatario;    
        $asunto = "TEST" ;
        $mensaje = "<h2> Test Ok  </h2>";
        $mensaje .= "<h3>CONEXION ESTABLECIDA</h3>";
        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->SMTPDebug = 0;
            $mail->isSMTP();    
            $mail->From = $remitente; 
            //$mail->From = "desarrollo@zgroup.com.pe";                                   //Send using SMTP
            $mail->Host       = "smtp.gmail.com";                   //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            //$mail->Username   = 'desarrollo@zgroup.com.pe';                     //SMTP username
            //$mail->Password   = 'Des5090100';                               //SMTP password
            $mail->Username   = $remitente;                     //SMTP username
            $mail->Password   = $passRemitente;                               //SMTP password
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            //Agregar destinatario
            $mail->AddAddress($correoEnvio);
            $mail->Subject = utf8_decode($asunto);
            $mail->Body =utf8_decode($mensaje);
            $mail->isHTML(true);
            //$mail->AddAttachment('./excel/'.$nombreContenedor.'_'.$fechaZ.'.xlsx', $nombreContenedor.'_'.$fechaZ.'.xlsx');
            //Avisar si fue enviado o no y dirigir al index
            if ($mail->Send()) {
                $data ='ok';  
            } else {
                $data ='<script type="text/javascript">alert("NO ENVIADO, intentar de nuevo");</script>';
            }    
        }catch (Exception $e) {
            $data = "Se ha producido un mensaje de error . Mailer Error: {$mail->ErrorInfo}"; 
        }
        return $data;
    }

    function ContenedorPlantilla($val,$url,$tipo){      
        if($tipo==0){
            $result = ContenedorReefer($val,$url);
        }elseif($tipo==1){
            $result = ContenedorMadurador($val,$url);
        }elseif($tipo==2){
            $result = ContenedorTunel($val,$url);
        }
        return $result;
    }
#posiciones on,wait,off
     function determinarEstado($ultima_fecha ,$id,$est) {
        if($est==[]){
            $est=[0,0,0];
        }
        date_default_timezone_set('UTC');
        $hoy = date("Y-m-d H:i:s");                   
        $fechaActual = new DateTime($hoy);
        $fechaUltima = new DateTime($ultima_fecha);
        #$diferencia = $fechaActual->getTimestamp() - $fechaUltima->getTimestamp();
        $diferencia = $fechaActual->diff($fechaUltima);
        
        // Convertir la diferencia en minutos
        $diferenciaEnMinutos = ($diferencia->days * 24 * 60) + ($diferencia->h * 60) + $diferencia->i;
        
        //tiempo en segundos
        if ($diferenciaEnMinutos <= 30+300) { 
            $est[0]=$est[0]+1;
            #return 'Online';
        } elseif ($diferenciaEnMinutos <= 1440+300) { 
            $est[1]=$est[1]+1;
            #return 'Wait';
        } else {
            $est[2]=$est[2]+1;
            #return 'Offline';
        }
        //array_push($est,$ultima_fecha,$diferenciaEnMinutos,$fechaActual);
        return $est;
    }
    const datosDepurar = [
        32752,-32752, 3275.2, -3275.2, 327.52,-327.52, 32767, -32767, 3276.7, -3276.7, 327.67, -327.67,32766, -32766 , 3276.6, -3276.6, 327.66, -327.66,
        32765, -32765, 3276.5, -3276.5, 327.65, -327.65,32764, -32764, 3276.4, -3276.4, 327.64, -327.64,32763, -32763, 3276.3, -3276.3, 327.63, -327.63,
        32762, -32762, 3276.2, -3276.2, 327.62, -327.62, 32761, -32761, 3276.1, -3276.1, 327.61, -327.61,32760, -32760, 3276.0, -3276.0, 327.60, -327.60,
        32759, -32759, 3275.9, -3275.9, 327.59, -327.59,32751, -32751, 3275.1, -3275.1, 327.51, -327.51,-3277,-3276.9,-38.5,25.4,255
    ];
    function b($val, $array = ['ethylene', 'sp_ethyleno', 'defrost_prueba','compress_coil_1' , 'co2_reading', 'set_point_co2', 'relative_humidity', 'humidity_set_point', 'ripener_prueba', 'temp_supply_1', 'avl']) {
        $result = [];
        foreach ($array as $key) {
            $datoDepurar = $val->$key;
            if (in_array($datoDepurar, $array)) {
                $result[$key] = 'NA';
            } else {
                $result[$key] = $datoDepurar;
            }
        }
        return $result;
    }
  
    function ContenedorReefer($val , $url){
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
        $valR ='"'.$val->nombre_contenedor.'"';
        $text = "
        <div class='swiper-slide' data-title='card_{$val->nombre_contenedor}'>
            <div class='card'>
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
                                    <div class='col-6 '><h6 class='mt-2' data-toggle='tooltip' data-placement='top' title='+21.5' id='temp1_{$val->telemetria_id}'>{$temp1}<i class='ri-arrow-up-circle-fill text-success' width='20px' height='20px'></i></h6></div>
                                    <div class='col-6 border'><p class='mt-2'><i class='ri-text-wrap'></i>Return :</p> </div>
                                    <div class='col-6 border-top border-bottom'><h6 class='mt-2' id='return_{$val->telemetria_id}'>{$return}</h6></div>
                                </div>
                            </div>
                            <div class='col-4 border'>
                                <div class='row '>
                                        <div class='col-12 '><h6 class='mt-2'>Set Temp :</h6></div>
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
                        
                            <div class='col-8'>
                                <div class='row '>
                                    <div class='col-6 border-start border-end border-bottom'><p class='mt-2'>Compresor:</p></div>
                                    <div class='col-6 '><h6 class='mt-2'> Value Compressor</h6></div>
                                    <div class='col-6 border-start border-bottom'><p class='mt-2'><i class='bi bi-thermometer-half'></i>Ambiente :</p> </div>
                                    <div class='col-6 border-top border-bottom border-start'><h6 class='mt-2'>Value Ambiente</h6></div>
                                </div>
                            </div>
                            <div class='col-4 border'>
                                <div class='row text-center'>
                                    <div class='col-12 '><h6 class='mt-2'>Defrost :</h6></div>
                                    <div class='col-12 p-1'><button type='button' class='mt-1 btn btn-block btn-success'>ACTIVE</button> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
        $result = array(
            'text'=>$text,
            'latitud'=>$val->latitud,
            'longitud'=>$val->longitud,
            'nombre_contenedor'=> $val->nombre_contenedor,
        );
        return $result;
    }

    function ContenedorMadurador($val, $url){   
        //validacion de informacion
        $datosDepurados = b($val);
        $etileno = $datosDepurados['ethylene'];
        $sp_ethyleno = $datosDepurados['sp_ethyleno'];
        $co2 = $datosDepurados['co2_reading'];
        $sp_co2 = $datosDepurados['set_point_co2'];
        $humedad = $datosDepurados['relative_humidity'];
        $sp_humedad = $datosDepurados['humidity_set_point'];
        $h_inyeccion = $datosDepurados['ripener_prueba'];
        $supply = $datosDepurados['temp_supply_1'];
        $n_apertura = $datosDepurados['avl'];
        $compresor = $datosDepurados['compress_coil_1'];
        $i = $datosDepurados['defrost_prueba'];

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
        $valR ='"'.$val->nombre_contenedor.'"';
        if($val->power_state==1){
            $power_state="text-success";
        }else{
            $power_state="text-danger";
        }
        
        $text ="
        <div class='swiper-slide' data-title='card_{$val->nombre_contenedor}'>
            <div class='card'  >
                <div class='card-body'>
                    <div class='container '>
                        <div class='row py-4' style='padding-right:5px ; padding-left:5px;'>
                            <div class='col-2' style='padding-right:5px ; padding-left:5px;' data-toggle='tooltip' data-placement='top' title='Turn ON/OFF'>         
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' strokeWidth={1.5} stroke='currentColor' width='35px' height='35px' class='mt-2 {$power_state}'>
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
                            <div class='col-4 border text-center fw-bold'><p class='mt-1'>Control</p></div>
                            <div class='col-4 border-start border-bottom'>
                            <div class='row'>
                                <p class='mt-3 text-center'>Ethylene</p>
                            </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3'>{$etileno}</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-end border-bottom'>
                                <div class='row'>
                                    <div class='col-12 '><h6 class='mt-2'>SP Ethylene :</h6></div>
                                    <div class='col-7 p-1'><input type='text' class='form-control' placeholder={$sp_ethyleno} readonly></div>
                                    <div class='col-5 align-self-center p-1 '>ppm</div>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3 text-center'>CO2</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3'>{$co2}</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-end'>
                                <div class='row'>
                                    <div class='col-12 '><h6 class='mt-2'>SP CO2 :</h6></div>
                                    <div class='col-7 p-1'>  <input type='text' class='form-control' placeholder={$sp_co2} readonly></div>
                                    <div class='col-5 align-self-center p-1 '>%</div>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3 text-center'>Humedad</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3'>{$humedad}</p>
                                </div>
                            </div>
                            <div class='col-4 border'>
                                <div class='row'>
                                    <div class='col-12 '><h6 class='mt-2'>SP Humedad:</h6></div>
                                    <div class='col-7 p-1'>  <input type='text' class='form-control' placeholder={$sp_humedad} readonly></div>
                                    <div class='col-5 align-self-center p-1 '>%</div>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3 text-center'>H. Inyeccion</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3'>{$h_inyeccion}</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-end border-bottom'>
                                <div class='row'>
                                    <div class='col-12 '><h6 class='mt-2'>H. Inyeccion :</h6></div>
                                    <div class='col-7 p-1'>  <input type='text' class='form-control'></div>
                                    <div class='col-5 align-self-center p-1 '>H</div>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3 text-center'>Supply</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3'>{$supply}</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-end border-bottom'>
                                <div class='row'>
                                    <div class='col-12 '><h6 class='mt-2'>SP Temp :</h6></div>
                                    <div class='col-7 p-1'>  <input type='text' class='form-control' placeholder={$val->set_point}></div>
                                    <div class='col-5 align-self-center p-1 '>%</div>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3 text-center'>N. Apertura</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3'>{$n_apertura}</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-end border-bottom'>
                                 <div class='row'>
                                    <p class='mt-3 text-center'>I: {$i}</p>
                                </div>
                            </div>
                              <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3 text-center'>Compresor</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <p class='mt-3'>{$compresor}</p>
                                </div>
                            </div>
                            <div class='col-4 border-start border-end border-bottom'>
                                <div class='row text-center'>
                                    <div class='col-12 '><h6 class='mt-2'>Defrost :</h6></div>
                                    <div class='col-12 p-1'><button type='button' class='mt-1 btn btn-block btn-success'>ACTIVE</button> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
        $result = array(
            'text'=>$text,
            'latitud'=>$val->latitud,
            'longitud'=>$val->longitud,
            'nombre_contenedor'=> $val->nombre_contenedor,
        );
        return $result;
    }

    function ContenedorTunel($val, $url){
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
        $valR ='"'.$val->nombre_contenedor.'"';
        $text ="
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
                                    <div class='col-6 '><h6 class='mt-2' data-toggle='tooltip' data-placement='top' title='+21.5' id='temp1_{$val->telemetria_id}'>{$temp1}<i class='ri-arrow-up-circle-fill text-success' width='20px' height='20px'></i></h6></div>
                                    <div class='col-6 border'><p class='mt-2'><i class='ri-text-wrap'></i>Return :</p> </div>
                                    <div class='col-6 border-top border-bottom'><h6 class='mt-2' id='return_{$val->telemetria_id}'>{$return}</h6></div>
                                </div>
                            </div>
                            <div class='col-4 border'>
                                <div class='row '>
                                        <div class='col-12 '><h6 class='mt-2'>Set Temp :</h6></div>
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
                        
                            <div class='col-8'>
                                <div class='row '>
                                    <div class='col-6 border-start border-end border-bottom'><p class='mt-2'>Compresor:</p></div>
                                    <div class='col-6 '><h6 class='mt-2'> Value Compressor</h6></div>
                                    <div class='col-6 border-start border-bottom'><p class='mt-2'><i class='bi bi-thermometer-half'></i>Ambiente :</p> </div>
                                    <div class='col-6 border-top border-bottom border-start'><h6 class='mt-2'>Value Ambiente</h6></div>
                                </div>
                            </div>
                            
                            <div class='col-4 border'>
                                <div class='row text-center'>
                                    <div class='col-12 '><h6 class='mt-2'>Defrost :</h6></div>
                                    <div class='col-12 p-1'><button type='button' class='mt-1 btn btn-block btn-success'>ACTIVE</button> </div>
                                </div>
                            </div>
                            
                            <div class='col-4 border-start border-bottom'>
                                <div class='row'>
                                    <div class='col-12'><p class='mt-2 text-center py-5'><i class='ri-sip-fill'></i>USDA :</p></div>
                                </div>
                            </div>
                            <div class='col-4 border-start border-bottom'>
                                <div class='row px-2 py-2'>
                                    <div class='col-12 border mt-1'><h6 class='mt-2' id='cargo1_{$val->telemetria_id}'>{$cargo1}</h6></div> 
                                    <div class='col-12 border mt-1'><h6 class='mt-2' id='cargo2_{$val->telemetria_id}'>{$cargo2}</h6></div>
                                    <div class='col-12 border mt-1'><h6 class='mt-2' id='cargo3_{$val->telemetria_id}'>{$cargo3}</h6></div>
                                    <div class='col-12 border mt-1'><h6 class='mt-2' id='cargo4_{$val->telemetria_id}'>{$cargo4}</h6></div>                         
                                </div>
                            </div>
                            <div class='col-4 border-start border-end border-bottom'>
                                <div class='row'>
                                
                                </div>
                                <div class='row'>
                            
                                </div>
                            </div>

                            <div class='col-4 border-start border-end border-bottom'>
                                <p class='mt-3 text-center'>OBJETIVO</p>
                            </div>
                            <div class='col-4 border-end border-bottom'>
                                <p class='mt-3'>Value Objetivo</p>
                            </div>
                            <div class='col-4 border-end border-bottom'>
                                <div class='row'>
                                    <div class='col-12 '><h6 class='mt-2'>Tmp Objetivo:</h6></div>
                                    <div class='col-7 p-1'>  <input type='text' class='form-control' ></div>
                                    <div class='col-5 align-self-center p-1 '>°C</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
        $result = array(
            'text'=>$text,
            'latitud'=>$val->latitud,
            'longitud'=>$val->longitud,
            'nombre_contenedor'=> $val->nombre_contenedor,
        );
        return $result;
    }


    function CorreoOTConInsumos($data,$remitente,$passRemietente,$destinatario){
        //manipular el array para usar en correo
        //para array es un doble tratamiento de data 
        $datos =json_encode($data);
        $datos1 =json_decode($datos);
        //echo $datos1->c_numot;
        $cadena = '<html>  
        <head>
        <style>@import url("https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap");
        .borde,.container{width:100%;height:15%;padding:0 20px;border:1px solid #000}.borde,.centrado,.container{padding:0 20px}.container,table+table{margin-top:10px}.borde,.container,td,th{border:1px solid #000}.centrado,.titulo h2,td,th{text-align:center}body{font-family:"Roboto Slab",sans-serif;font-size:9px;margin:0;padding:0}.titulo h2{font-size:20px;margin-top:50px}.borde{margin-top:20px}.centrado{margin-top:200px;margin-right:200px}.contenido__fecha,.contenido__superior__derecha{left:0;padding:10px;position:absolute;color:#1d2d4d;font-size:12px}.container .columna{width:30%;height:100%;float:left}.container .columna__respuesta{width:40%;height:100%;float:left;line-height:1.8}table{width:90%;border-collapse:collapse;margin:30px 0 10px}td,th{padding:8px;word-wrap:break-word}th{background-color:#d9e5f4;color:#000}.contenido__fecha{bottom:0}.contenido__superior__derecha{top:0;height:50px;text-align:right;line-height:5px}.contenido__inferior__derecho{position:absolute;bottom:0;right:0;padding:0;line-height:0;color:#1d2d4d;font-size:12px;text-align:right}
        </style>
        </head>
        <body>
        <div class="contenido__superior__derecha">
        <h3>Fecha de solicitud:<em>'.$datos1->fechaS.'</em></h3>
        <h3>Nro Solicitud:<em>'.$datos1->numSolicitud.'</em></h3>
        </div>
        <h2>Sres. Almacen,</h2>
        <div class="titulo">
        <p>Solicito insumos para el trabajo <em>'.$datos1->Trabajo.'</em> según OT <em>'.$datos1->c_numot.'</em>. A continuación el
        detalle de los insumos solicitados. Pido celeridad y apoyo con este requerimiento:</p>
        </did>
        <div class="container">
            <div class="columna">
                <h3>RUC</h3><h3>PROVEEDOR</h3><h3>TRABAJO REALIZADO</h3><h3>TECNICO ENCARGADO</h3><h3>FECHA SOLICITUD</h3>
            </div>
            <div class="columna__respuesta">
            <p><em>:'.$datos1->RUC.'</em></p><p><em>:'.$datos1->Proveedor.'</em></p><p><em>:'.$datos1->Trabajo.'</em></p>
            <p><em>:'.$datos1->TecnicoEncargado.'</em></p><p><em>:'.$datos1->fechaS.'</em></p>
            </div> 
        </div>
        <div class="centrado">
        <h2>Insumos requeridos <a href="https://ztrack.app/zgroup/">(Atender Solicitud)</a></h2>
        </div>
        <div class="borde"></div>
        <table>
            <thead>
                <tr>
                    <th>CODIGO</th><th>DESCRIPCION</th><th>UNIDAD</th><th>SOLICITADO</th><th>STOCK</th>
                </tr>
            </thead>
            <tbody>' ;
        foreach ($datos1->solicitud as $detS) {
            $cadena .='<tr>
                <td>'.$detS->IN_CODI.'</td><td>'.$detS->IN_ARTI.'</td><td>'.$detS->IN_UVTA.'</td><td>'.$detS->cantidadUsar.'</td><td>'.$detS->stock.'</td>
            </tr>';
        }
        $cadena .='</tbody>
            </table>
                <div class="contenido__inferior__derecho">
                    <h3>Atte: USUARIO</h3><h3>ZGROUP S.A.C. RUC:20521180774</h3><h3>SISTEMA INTRANET - SOPORTE</h3>
                    <h3>EMAIL: ZTRACK@ZGROUP.COM.PE</h3><h3>WWW.ZGROUP.COM.PE</h3>
                </div>
                </body>
            </html>';
    //echo $cadena ;
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'zgroupsistemas@gmail.com'; // Reemplaza con tu dirección de correo electrónico de Gmail
    $mail->Password = 'bsfgahtiqboilexe'; // Reemplaza con tu contraseña de Gmail
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    // Configuración del correo electrónico
    $mail->setFrom('devpablito2023@gmail.com', 'ZGROUPSISTEMAS');
    $mail->addAddress('devpablito2023@gmail.com'); // Reemplaza con la dirección de correo electrónico del destinatario
    $mail->Subject = 'Solicitud :'.$datos1->numSolicitud.' / Nro OT : '.$datos1->c_numot.' / '.$datos1->Trabajo.'';
    $mail->isHTML(true); 
    //$mail->addEmbeddedImage('Assets/img/logo_pdf.png', 'logo_img');
    $mail->Body = utf8_decode($cadena); 
    try {
        $mail->send();
        $resC= json_encode(['msg' => 'Correo enviado ', 'icono' => 'success']);
    } catch (Exception $e) {
        $resC= json_encode(['msg' => 'Error al enviar el correo: ' . $mail->ErrorInfo, 'icono' => 'error']);
    }
    return  $resC;
}


?>