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
                            <div class='col-3 border-top border-bottom text-center fw-bold'><p class='mt-1'>Value</p></div>
                            <div class='col-5 border-start border-end border-top text-center fw-bold'><p class='mt-1'>Control</p></div>
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
        // sp_ethyleno y etileno
        if(abs($etileno - $sp_ethyleno) <= $sp_ethyleno * 0.10){
            $etileno_color = "text-success";
        }else if(abs($etileno - $sp_ethyleno) <= $sp_ethyleno * 0.25){
            $etileno_color = "text-warning";
        }else{
            $etileno_color = "text-secondary";
        }    
        // sp_co2 y co2
        if(abs($co2 - $sp_co2) <= $sp_co2 * 0.10){
            $co2_color = "text-success";
        }else if(abs($co2 - $sp_co2) <= $sp_co2 * 0.25){
            $co2_color = "text-warning";
        }else{
            $co2_color = "text-secondary";
        }
        
        // sp_humedad y humedad
        if(abs($humedad - $sp_humedad) <= $sp_humedad * 0.10){
            $humedad_color = "text-success";
        }else if(abs($humedad - $sp_humedad) <= $sp_humedad * 0.25){
            $humedad_color = "text-warning";
        }else{
            $humedad_color = "text-secondary";
        }        

         // $val->set_point y supply
        if(abs($supply - $s_temp) <= $s_temp * 0.10){
            $supply_color = "text-success";
        }else if(abs($supply - $s_temp) <= $s_temp * 0.25){
            $supply_color = "text-warning";
        }else{
            $supply_color = "text-secondary";
        }

        $text ="
        <div class='swiper-slide' data-title='card_{$val->nombre_contenedor}'>
            <div class='card'  >
                <div class='card-body'>
                    <div class='container '>
                        <div class='row py-4' style='padding-right:5px ; padding-left:5px;'>
                            <!--POWER ON/OFF -->
                            <div class='col-2' style='padding-right:5px ; padding-left:5px;' data-toggle='tooltip' data-placement='top' title='Turn ON/OFF'>         
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='4' stroke='currentColor' width='30px' height='30px' class='mt-2 {$power_state}'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9' />
                                </svg>
                            </div>
                            <div class='col-8 '><h4 id='nombre_contenedor_{$val->telemetria_id}' class='text-center mt-3 fw-bold'> {$val->nombre_contenedor}</h4></div>
                            <div class='col-2 text-right' id='controlMode' onclick='accessModal({$valR})' style='padding-right:5px ; padding-left:5px' data-toggle='tooltip' data-placement='top' title='Access Control'>
                                <svg version='1.0' xmlns='http://www.w3.org/2000/svg' width='30px' height='30px' class='mt-2' viewBox='0 0 118.000000 118.000000' preserveAspectRatio='xMidYMid meet'>
                                    <g transform='translate(0.000000,118.000000) scale(0.100000,-0.100000)' fill='#000000' stroke='none'>
                                    <path fill='green' d='M499 1165 c-63 -20 -136 -72 -171 -121 -40 -56 -58 -132 -58 -251 l0 -103 -38 0 c-27 0 -45 -7 -58 -21 -18 -20 -19 -40 -19 -326 l0 -305 24 -19
                                            c22 -18 44 -19 411 -19 367 0 389 1 411 19 l24 19 0 305 c0 286 -1 306 -19 326 -13 14 -31 21 -58 21 l-38 0 0 104 c0 185 -50 285 -175 347 -77 38 -165
                                            47 -236 24z m178 -122 c46 -21 101 -85 110 -130 4 -21 8 -78 8 -128 l0 -90 -205 0 -205 0 1 100 c0 121 12 159 63 209 63 62 147 76 228 39z m-52 -563 c15
                                            -6 32 -24 41 -46 14 -33 14 -39 -5 -74 -17 -34 -19 -47 -11 -97 6 -32 10 -59 10 -60 0 -2 -31 -3 -70 -3 -38 0 -70 1 -70 3 0 1 4 28 10 60 8 50 6 63 -11 97
                                        -19 35 -19 41 -5 74 20 49 60 65 111 46z'/>
                                    </g>
                                </svg>
                            </div>
                            <div class='col-12 '><h5 class='text-center mt-2'>{$val->descripcionC}</h5> </div>
                            <div class='col-12 mt-1 p-1'>
                                <div class='row'>
                                    <i class='ri-time-line col-3 text-end align-content-center fs-2 text-primary'></i>
                                    <h6 id='fechita_{$val->telemetria_id}' class='col-9 align-content-center px-0 m-0'> {$fechita}</h6>
                                </div>
                            </div>
                          
                            <div class='col-3 p-2 text-center'><button type='button' onclick='graficaM({$valR})' class='mt-1 btn btn-block btn-outline-primary' data-toggle='tooltip' data-placement='top' title='Gráfico'><i class='ri-line-chart-line fs-5'></i></button></div>
                            <div class='col-3 p-2 text-center'><button type='button' class='mt-1 btn btn-block btn-outline-secondary' data-toggle='tooltip' data-placement='top' title='Datos'><i class='ri-layout-horizontal-line fs-5'></i></button></div>
                            <div class='col-3 p-2 text-center'><button type='button' class='mt-1 btn btn-block btn-outline-success' data-toggle='tooltip' data-placement='top' title='Correo'><i class='ri-mail-line fs-5'></i></button> </div>
                            <div class='col-3 p-2 text-center'><button type='button' class='mt-1 btn btn-block btn-outline-info' data-toggle='tooltip' data-placement='top' title='Reporte'><i class='ri-file-chart-line fs-5'></i></button></div>

                            <div class='col-12' id='btnToSave1' hidden>
                                <button type='button' id='btnSaveData1' class='btn btn-primary col-12' onclick='guardarDatos()'>SAVE</button>
                            </div>
                            <!-- TABLA -->
                            <div class='table-responsive mt-3 px-0 mx-0'>
                                <table class='border table table-sm table-bordered overflow-hidden'>
                                    <thead>
                                        <tr class='text-center'>
                                            <th scope='col'>Params</th>
                                            <th scope='col'>Value</th>
                                            <th scope='col'>Control</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class='col-4 text-center align-content-center'>
                                                <div class='row justify-content-center'>
                                                    <div class='col-10'>
                                                        <svg version='1.0' xmlns='http://www.w3.org/2000/svg' width='70' height='70' class='icon-params icon px-1 btn' ondblclick='ethyModal()' ontouchstart='ethyModal()' viewBox='0 0 306 236' preserveAspectRatio='xMidYMid meet'>
                                                            <g transform='translate(0,236) scale(0.1,-0.1)' stroke='none'>
                                                                <path fill='gray' d='M472 2126 c-62 -29 -102 -68 -133 -132 -28 -57 -31 -71 -27 -127 6 -73 25 -114 76 -165 59 -59 105 -77 194 -77 l77 0 40 -63 c23 -34 41 -67 41 -72 0 -5 -24 -33 -54 -63 -80 -80 -99 -123 -104 -238 -4 -87 -2 -101 21 -150 15 -30 51 -78 81 -108 l55 -55 -31 -55 c-46 -83 -50 -86 -126 -87 -84 -2 -143 -27 -199 -85 -53 -56 -73 -103 -73 -176 0 -170 162 -295 326 -254 190 48 258 277 125 424 l-35 39 39 75 39 74 73 -6 c83 -8 158 6 218 40 46 26 121 105 147 153 l17 32 284 0 285 0 47 -64 c51 -70 91 -102 170 -137 48 -22 102 -26 210 -17 27 3 33 -3 73 -70 l44 -72 -46 -51 c-57 -64 -76 -122 -66 -200 8 -61 25 -94 74 -147 50 -55 101 -76 181 -76 80 1 124 16 179 64 177 156 58 450 -181 450 -61 0 -64 1 -90 38 -16 21 -36 54 -46 73 l-18 34 55 59 c72 76 96 139 96 245 0 70 -4 86 -35 149 -22 44 -53 86 -81 111 l-45 39 43 71 43 71 61 0 c86 0 150 25 204 80 130 129 95 333 -72 421 -50 26 -167 26 -223 0 -48 -22 -96 -71 -126 -129 -17 -34 -21 -58 -20 -114 2 -77 14 -109 68 -176 l35 -43 -41 -68 -40 -68 -98 1 c-85 0 -105 -3 -153 -25 -67 -31 -116 -73 -156 -131 -16 -24 -33 -46 -39 -50 -5 -4 -137 -7 -293 -7 -306 1 -296 -1 -326 58 -21 40 -97 106 -152 132 -40 18 -67 23 -147 23 l-98 1 -44 69 -43 69 44 63 c48 71 59 111 51 189 -8 82 -62 165 -130 201 -45 23 -182 29 -225 10z'/>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                    <div class='col-10'>
                                                        <h6 class='mt-2 text-params'> Ethylene</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <div class='d-flex align-items-center'>
                                                        <p class='value-icon' id='eti_icon_{$val->telemetria_id}'><i class='bi bi-arrows me-2 align-items-center mb-1 text-primary value-icon'></i></p>
                                                        <p class='value-parameter {$etileno_color} align-items-center mb-1' id='etileno_{$val->telemetria_id}'>{$etileno}</p>
                                                    </div>
                                                    <small class='value-si'>ppm</small>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <p class='me-2 text-center mb-1 text-control'>Set Point</p>
                                                    <p class='me-2 text-center fw-bold mb-1 text-control'>Ethy</p>
                                                </div>
                                                <div class='row justify-content-center g-2'>
                                                    <input type='text' class='input col-8 w-50 form-control text-center align-content-center px-0 mx-0' id='sp_etileno_{$val->telemetria_id}' placeholder={$sp_ethyleno} readonly>
                                                    <small class='col-4 align-content-center text-control-si'>ppm</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class='col-4 text-center align-content-center'>
                                                <div class='row justify-content-center'>
                                                    <div class='col-12'>
                                                    
                                                    <svg version='1.0' xmlns='http://www.w3.org/2000/svg' width='70' height='70' class='icon-params icon px-1 btn' ondblclick='co2Modal()' ontouchstart='co2Modal()' viewBox='0 0 1920.000000 1080.000000' preserveAspectRatio='xMidYMid meet'>
                                                        <g transform='translate(0.000000,1080.000000) scale(0.100000,-0.100000)'
                                                        fill='green' stroke='none'>
                                                        <path d='M11467 10043 c-15 -16 -27 -32 -27 -36 0 -8 -71 -111 -145 -212 -24
                                                        -33 -44 -65 -44 -72 -1 -15 -33 -33 -58 -33 -10 0 -31 7 -48 16 -16 9 -48 22
                                                        -70 29 -22 7 -80 27 -130 44 -206 72 -285 93 -490 131 -33 6 -89 18 -125 25
                                                        -36 8 -94 17 -130 20 -36 4 -112 14 -170 23 -149 23 -765 23 -925 -1 -60 -9
                                                        -139 -19 -175 -22 -36 -4 -82 -11 -102 -16 -20 -5 -69 -16 -110 -24 -150 -29
                                                        -184 -37 -230 -51 -26 -8 -66 -19 -90 -24 -101 -22 -239 -65 -305 -95 -17 -8
                                                        -37 -15 -44 -15 -17 0 -204 -73 -294 -115 -240 -112 -483 -241 -590 -315 -22
                                                        -15 -67 -45 -100 -66 -71 -45 -277 -196 -303 -221 -9 -10 -42 -38 -72 -63
                                                        -154 -126 -324 -288 -493 -469 -73 -78 -243 -287 -279 -341 -9 -14 -39 -56
                                                        -67 -95 -80 -110 -113 -159 -145 -216 -10 -19 -38 -63 -61 -99 -24 -36 -50
                                                        -81 -59 -100 -9 -19 -26 -53 -39 -75 -71 -122 -106 -195 -209 -435 -11 -25
                                                        -26 -57 -34 -72 -8 -14 -14 -31 -14 -38 0 -7 -7 -26 -14 -43 -8 -18 -31 -79
                                                        -51 -137 -34 -99 -48 -144 -76 -250 -6 -25 -20 -74 -30 -109 -11 -35 -19 -76
                                                        -19 -91 0 -14 -6 -49 -14 -76 -8 -27 -18 -76 -21 -109 -4 -33 -13 -82 -20
                                                        -110 -12 -46 -21 -122 -45 -400 -12 -132 -12 -382 0 -525 21 -259 39 -440 45
                                                        -449 3 -5 10 -47 16 -93 5 -46 14 -96 19 -113 6 -16 17 -66 25 -110 43 -218
                                                        90 -399 126 -480 5 -11 22 -60 39 -110 45 -134 70 -201 114 -300 8 -16 24 -52
                                                        36 -80 26 -59 59 -125 127 -255 70 -134 84 -157 121 -206 17 -25 32 -47 32
                                                        -50 0 -7 51 -87 112 -177 72 -106 240 -311 272 -333 47 -32 109 -33 130 -2 32
                                                        45 21 87 -48 181 -35 48 -70 93 -78 101 -7 7 -20 24 -28 36 -8 13 -24 35 -35
                                                        49 -35 45 -65 90 -65 96 0 3 -8 18 -19 33 -15 22 -58 88 -139 217 -7 11 -19
                                                        34 -28 50 -8 17 -26 48 -39 70 -46 77 -225 453 -225 472 0 7 -28 82 -55 148
                                                        -7 17 -23 64 -35 105 -13 41 -27 86 -33 100 -13 34 -45 152 -60 225 -7 33 -16
                                                        66 -20 73 -8 11 -25 107 -48 257 -5 39 -17 108 -24 155 -18 105 -23 161 -35
                                                        425 -11 233 7 663 34 817 8 46 20 115 26 153 7 39 16 97 22 130 6 33 16 78 24
                                                        100 7 22 16 60 20 85 4 25 12 56 19 70 7 14 16 43 20 65 4 22 11 45 15 50 5 6
                                                        13 29 19 50 10 43 36 121 57 170 6 17 22 57 34 90 11 33 27 74 35 90 7 17 25
                                                        57 40 90 68 152 197 399 249 475 40 59 52 79 72 114 9 15 38 59 65 97 27 38
                                                        49 71 49 74 0 9 72 109 100 140 16 17 48 56 72 87 142 188 365 427 503 541 33
                                                        27 83 70 110 96 60 57 270 223 304 241 14 7 56 35 95 63 38 27 88 59 111 71
                                                        22 12 66 38 96 57 29 19 63 40 74 46 42 25 401 205 425 214 14 4 57 20 95 34
                                                        39 14 111 39 160 55 50 17 98 35 108 40 10 6 37 15 60 20 40 8 96 23 212 55
                                                        106 29 318 72 425 85 289 36 380 42 655 37 271 -4 576 -26 639 -47 17 -5 63
                                                        -14 101 -19 76 -9 183 -33 256 -56 26 -8 55 -15 65 -15 18 0 120 -28 189 -52
                                                        17 -6 52 -16 79 -24 26 -7 54 -18 61 -24 7 -6 32 -14 56 -18 63 -11 62 -32 -9
                                                        -138 -33 -49 -74 -111 -92 -139 -19 -29 -33 -64 -34 -83 l-1 -32 565 -1 c311
                                                        0 564 2 563 5 -2 3 -3 15 -3 25 0 11 -42 82 -94 159 -51 77 -96 146 -100 154
                                                        -4 7 -17 30 -29 50 -12 20 -46 79 -76 130 -30 51 -69 116 -86 143 -42 64 -81
                                                        132 -100 170 -8 17 -24 43 -37 58 l-23 28 -28 -28z'/>
                                                        <path d='M9985 8640 c-148 -4 -283 -10 -300 -13 -16 -4 -106 -11 -200 -17 -93
                                                        -6 -186 -15 -205 -20 -19 -5 -69 -14 -110 -20 -41 -5 -104 -17 -140 -25 -36
                                                        -8 -87 -19 -115 -25 -153 -34 -292 -74 -375 -109 -41 -17 -79 -31 -84 -31 -5
                                                        0 -27 -9 -50 -19 -23 -11 -63 -29 -91 -41 -68 -30 -201 -97 -250 -126 -22 -13
                                                        -53 -31 -70 -40 -16 -9 -48 -29 -70 -45 -22 -16 -65 -47 -95 -69 -30 -21 -62
                                                        -45 -70 -52 -8 -7 -40 -32 -71 -55 -111 -81 -386 -370 -467 -489 -7 -11 -35
                                                        -50 -62 -89 -78 -111 -189 -309 -241 -430 -17 -38 -37 -84 -45 -102 -8 -17
                                                        -14 -38 -14 -46 0 -9 -4 -18 -8 -21 -14 -9 -102 -300 -102 -337 0 -14 -6 -48
                                                        -14 -75 -14 -47 -29 -176 -46 -389 -9 -130 14 -436 44 -560 8 -33 20 -82 26
                                                        -110 31 -137 38 -163 48 -181 5 -10 13 -38 17 -61 3 -22 13 -49 20 -60 8 -10
                                                        15 -25 15 -33 0 -8 9 -32 20 -53 11 -20 20 -43 20 -49 1 -7 9 -31 20 -53 11
                                                        -22 19 -46 20 -53 0 -6 9 -29 20 -49 11 -21 20 -43 20 -50 0 -14 36 -80 59
                                                        -106 19 -23 17 -134 -4 -183 -7 -18 -17 -55 -20 -81 -4 -26 -13 -68 -21 -93
                                                        -21 -68 -44 -194 -55 -295 -5 -49 -14 -110 -20 -135 -28 -115 -36 -528 -18
                                                        -960 13 -333 10 -325 124 -325 78 0 80 1 89 28 7 19 4 64 -9 155 -26 175 -28
                                                        897 -2 1032 9 47 19 121 23 165 4 44 11 91 16 105 6 14 14 54 19 88 5 35 15
                                                        85 23 110 8 26 18 70 24 97 16 77 40 168 51 190 5 11 14 38 19 60 13 50 29 98
                                                        53 153 11 23 19 50 19 58 0 9 7 28 15 42 8 15 26 56 40 92 14 36 32 79 40 95
                                                        33 74 97 212 105 227 5 9 26 47 45 85 67 126 123 224 200 352 25 41 130 197
                                                        136 201 3 3 13 19 22 35 9 17 46 68 83 115 37 47 111 139 163 205 139 175 479
                                                        512 598 594 21 14 80 59 130 98 51 40 95 73 98 73 3 0 43 25 90 55 81 52 262
                                                        148 356 189 24 11 55 24 69 31 14 6 66 23 115 39 50 16 104 35 120 42 17 7 42
                                                        15 57 18 47 10 68 60 68 165 0 106 -8 112 -78 61 -41 -30 -224 -110 -251 -110
                                                        -21 0 -271 -110 -321 -141 -8 -5 -26 -14 -40 -19 -14 -6 -43 -21 -65 -35 -22
                                                        -13 -53 -31 -70 -39 -26 -14 -83 -52 -190 -126 -48 -33 -139 -99 -150 -107 -5
                                                        -5 -30 -24 -54 -43 -25 -19 -52 -42 -61 -50 -9 -8 -50 -44 -91 -80 -41 -36
                                                        -103 -92 -138 -125 -98 -93 -414 -437 -435 -474 -8 -13 -39 -53 -99 -126 -13
                                                        -15 -144 -205 -162 -234 -77 -124 -129 -210 -168 -281 -17 -30 -43 -77 -59
                                                        -105 -15 -27 -32 -61 -38 -75 -5 -14 -15 -32 -20 -40 -6 -8 -13 -22 -16 -30
                                                        -14 -37 -57 -121 -64 -125 -4 -3 -11 -17 -15 -32 -3 -16 -12 -35 -19 -43 -6
                                                        -8 -22 -42 -35 -75 -65 -173 -109 -267 -120 -261 -5 3 -11 24 -15 47 -4 23
                                                        -13 51 -21 62 -8 12 -15 31 -15 42 0 11 -9 37 -20 58 -11 20 -20 45 -20 54 0
                                                        10 -8 40 -19 68 -33 88 -43 119 -52 163 -5 24 -15 64 -23 90 -61 193 -82 638
                                                        -40 867 8 44 19 107 24 140 12 78 38 182 56 223 8 18 14 40 14 50 1 9 9 35 20
                                                        57 11 22 19 46 20 52 1 46 176 387 269 522 9 13 21 31 26 40 91 145 282 360
                                                        432 484 82 68 275 207 288 207 6 0 17 6 23 12 14 14 119 73 202 113 186 90
                                                        453 190 560 209 19 4 58 13 85 21 71 21 231 53 295 60 30 4 72 11 93 16 21 6
                                                        111 14 200 19 89 5 212 14 272 19 164 14 847 24 864 12 16 -10 18 8 -30 -276
                                                        -11 -66 -24 -138 -30 -160 -5 -22 -16 -76 -24 -120 -8 -44 -19 -96 -25 -115
                                                        -5 -19 -10 -50 -10 -68 0 -18 -9 -72 -19 -120 -18 -84 -26 -128 -56 -309 -7
                                                        -46 -18 -97 -24 -115 -5 -18 -14 -64 -20 -103 -6 -38 -15 -86 -21 -105 -5 -19
                                                        -10 -48 -10 -65 0 -16 -9 -65 -19 -110 -10 -44 -24 -105 -30 -135 -34 -162
                                                        -63 -277 -90 -355 -6 -16 -22 -64 -35 -105 -30 -89 -46 -131 -92 -228 -19 -40
                                                        -34 -76 -34 -80 0 -8 -62 -131 -92 -182 -75 -128 -235 -360 -285 -414 -16 -17
                                                        -58 -65 -94 -106 -105 -121 -262 -265 -378 -347 -25 -18 -77 -55 -116 -83 -38
                                                        -27 -73 -50 -78 -50 -4 0 -29 -15 -55 -33 -44 -31 -161 -90 -262 -135 -25 -11
                                                        -61 -27 -82 -36 -20 -9 -41 -16 -48 -16 -7 0 -26 -6 -43 -14 -60 -26 -233 -72
                                                        -387 -103 -148 -29 -166 -32 -210 -33 -48 0 -115 -26 -143 -54 -10 -11 -17
                                                        -33 -17 -56 0 -21 -5 -51 -11 -66 -12 -34 -4 -74 15 -74 8 0 27 6 43 14 34 17
                                                        102 34 205 51 42 7 81 16 86 19 5 3 42 10 82 16 39 6 93 16 118 24 26 8 70 18
                                                        97 24 113 23 176 40 187 50 7 5 28 13 46 17 18 4 45 13 60 21 15 8 31 14 35
                                                        14 20 0 243 108 277 134 8 6 36 22 62 36 51 26 248 168 369 264 81 66 282 264
                                                        369 365 62 72 130 162 130 171 0 4 19 31 43 62 41 54 88 127 114 178 7 14 24
                                                        43 37 65 59 99 161 316 206 440 13 33 28 76 35 95 8 19 23 67 34 105 11 39 28
                                                        95 37 125 22 74 43 162 55 235 6 33 17 83 24 110 8 28 17 75 20 105 4 30 11
                                                        72 17 94 5 21 16 77 24 125 8 47 19 115 25 151 6 36 19 104 30 152 10 48 19
                                                        102 19 121 0 19 6 52 14 73 7 22 17 69 20 104 4 36 13 88 21 115 7 28 15 78
                                                        19 112 3 34 12 88 20 120 8 32 19 90 24 128 23 159 34 234 48 310 17 98 18
                                                        197 2 213 -13 13 -392 14 -873 2z'/>
                                                        <path d='M12861 8393 c-19 -24 -20 -28 -6 -49 15 -23 82 -99 145 -164 54 -55
                                                        206 -257 256 -340 16 -25 43 -63 61 -86 18 -23 33 -45 33 -49 0 -5 6 -16 14
                                                        -24 7 -9 35 -56 62 -106 26 -49 58 -108 71 -130 12 -22 36 -69 53 -105 104
                                                        -217 119 -252 160 -365 13 -33 28 -71 35 -85 7 -14 16 -41 20 -60 4 -19 11
                                                        -40 15 -45 5 -6 14 -33 21 -60 6 -28 15 -54 20 -60 4 -5 10 -28 14 -50 3 -22
                                                        12 -62 20 -90 65 -222 120 -514 136 -720 38 -504 7 -978 -88 -1345 -7 -30 -20
                                                        -80 -28 -110 -8 -30 -23 -86 -34 -125 -11 -38 -28 -97 -37 -130 -9 -33 -20
                                                        -64 -24 -70 -4 -5 -11 -25 -15 -44 -6 -29 -22 -72 -55 -146 -5 -11 -21 -51
                                                        -35 -90 -29 -75 -44 -110 -108 -245 -58 -124 -117 -240 -144 -283 -13 -21 -28
                                                        -49 -34 -63 -9 -25 -41 -78 -105 -173 -16 -24 -45 -67 -64 -96 -137 -207 -291
                                                        -392 -515 -619 -87 -88 -264 -248 -328 -295 -37 -28 -78 -60 -90 -72 -12 -12
                                                        -33 -28 -47 -37 -14 -9 -52 -37 -85 -62 -33 -25 -78 -57 -100 -72 -141 -90
                                                        -258 -161 -295 -178 -14 -6 -43 -21 -65 -35 -22 -13 -47 -27 -55 -30 -8 -4
                                                        -53 -25 -100 -47 -185 -87 -227 -106 -280 -128 -30 -12 -68 -28 -85 -36 -16
                                                        -7 -46 -17 -65 -23 -19 -5 -66 -21 -105 -36 -38 -14 -92 -32 -120 -40 -27 -7
                                                        -68 -18 -90 -25 -114 -33 -144 -40 -185 -45 -25 -3 -72 -12 -105 -20 -222 -52
                                                        -450 -76 -780 -83 -243 -4 -490 10 -700 39 -38 6 -113 16 -167 24 -119 16
                                                        -250 45 -430 94 -43 12 -86 21 -95 21 -10 0 -31 7 -47 15 -16 9 -46 20 -65 24
                                                        -20 5 -49 14 -66 20 -16 6 -49 16 -72 21 -30 8 -43 16 -43 28 0 17 26 72 87
                                                        182 43 78 84 183 75 192 -12 12 -1042 12 -1055 -1 -7 -7 -7 -14 0 -23 6 -7 32
                                                        -53 58 -103 27 -49 51 -92 54 -95 4 -3 12 -16 19 -30 7 -14 21 -38 32 -55 11
                                                        -16 27 -44 37 -62 10 -18 45 -78 78 -135 78 -134 123 -219 187 -350 44 -90 57
                                                        -108 76 -108 13 0 25 8 29 18 6 15 111 207 133 242 6 8 15 26 21 40 5 13 13
                                                        27 17 30 4 3 15 17 25 33 10 15 24 27 33 27 13 0 143 -39 239 -71 71 -24 259
                                                        -74 340 -91 58 -12 203 -41 290 -58 33 -6 112 -18 175 -25 63 -8 147 -19 185
                                                        -25 98 -17 836 -17 930 -1 39 7 120 18 180 26 129 16 206 30 294 56 35 11 74
                                                        19 87 19 12 0 44 7 70 15 27 8 76 21 109 30 122 32 161 43 215 65 66 26 150
                                                        56 206 75 23 7 50 18 60 24 30 17 155 72 194 86 74 26 471 241 527 285 14 11
                                                        30 20 36 20 5 0 29 16 53 35 23 19 46 35 50 35 19 0 215 150 459 350 63 52
                                                        313 299 368 364 23 27 76 89 117 137 41 48 94 114 118 146 23 32 55 73 70 90
                                                        26 31 38 49 124 181 105 163 272 474 366 682 34 75 102 244 102 255 1 6 9 28
                                                        20 50 11 22 19 47 20 55 0 8 8 37 19 65 31 82 42 118 52 160 5 22 15 63 23 90
                                                        35 120 67 264 87 385 5 36 17 94 24 130 8 36 19 119 25 185 6 66 14 154 20
                                                        195 12 96 12 436 0 560 -5 52 -14 147 -20 210 -6 63 -17 144 -25 180 -7 36
                                                        -16 90 -19 120 -5 50 -21 128 -47 235 -6 22 -17 67 -24 100 -8 33 -23 90 -34
                                                        128 -12 37 -29 95 -40 130 -10 34 -29 89 -40 122 -12 33 -28 78 -36 100 -14
                                                        43 -38 101 -60 150 -16 37 -62 137 -104 227 -17 37 -31 69 -31 72 0 2 -23 44
                                                        -51 93 -28 48 -55 97 -60 108 -5 11 -14 29 -21 40 -7 11 -22 36 -34 55 -114
                                                        186 -301 444 -397 547 -88 94 -153 133 -221 133 -47 0 -57 -4 -75 -27z'/>
                                                        <path d='M9440 3850 c-144 -35 -216 -73 -297 -157 -100 -103 -173 -311 -173
                                                        -494 0 -108 48 -312 93 -392 39 -70 103 -140 168 -182 99 -64 309 -83 503 -46
                                                        124 24 141 38 139 115 -2 88 -30 126 -72 97 -15 -10 -54 -28 -88 -40 -55 -20
                                                        -76 -23 -165 -19 -113 6 -154 21 -216 80 -100 97 -141 353 -93 583 30 147 68
                                                        211 156 265 37 23 49 25 167 24 129 0 141 -3 253 -55 41 -19 65 -12 65 18 0 9
                                                        8 37 17 64 l17 48 -29 31 c-20 20 -38 30 -59 30 -17 0 -59 7 -94 15 -77 18
                                                        -245 27 -292 15z'/>
                                                        <path d='M10415 3835 c-78 -26 -112 -42 -170 -83 -43 -30 -141 -145 -151 -177
                                                        -4 -11 -12 -27 -18 -35 -31 -40 -93 -322 -82 -375 3 -11 10 -56 16 -100 36
                                                        -247 170 -425 367 -484 134 -40 348 -23 480 39 57 27 163 129 189 182 12 23
                                                        31 62 43 86 11 23 21 53 21 66 0 13 7 43 16 67 24 69 22 291 -4 397 -25 98
                                                        -50 171 -69 193 -7 8 -13 20 -13 26 0 16 -134 140 -172 159 -131 68 -321 84
                                                        -453 39z m257 -146 c98 -21 156 -87 205 -233 20 -60 23 -88 23 -231 0 -134 -4
                                                        -173 -21 -231 -11 -38 -24 -76 -30 -84 -5 -8 -13 -25 -19 -37 -5 -13 -24 -39
                                                        -41 -58 -100 -109 -262 -124 -388 -37 -86 60 -136 171 -161 357 -14 101 18
                                                        291 64 388 69 144 197 202 368 166z'/>
                                                        <path d='M11555 3856 c-5 -2 -46 -12 -90 -21 -44 -10 -90 -24 -102 -31 -12 -8
                                                        -29 -14 -36 -14 -8 0 -27 -10 -42 -22 -24 -19 -27 -26 -22 -63 12 -88 43 -115
                                                        92 -82 11 7 47 24 80 37 53 21 75 24 185 24 116 0 129 -2 168 -26 71 -42 92
                                                        -80 92 -163 0 -77 -12 -109 -72 -193 -45 -63 -369 -386 -546 -543 -34 -30 -42
                                                        -44 -47 -85 -10 -77 -2 -82 145 -88 211 -9 722 -8 731 1 12 12 15 125 3 145
                                                        -8 15 -42 17 -277 23 -147 3 -279 10 -292 15 -23 9 -22 10 34 67 31 32 88 85
                                                        126 118 128 111 310 299 358 371 38 56 50 86 59 138 22 135 -23 253 -123 321
                                                        -79 53 -131 66 -279 71 -74 2 -139 2 -145 0z'/>
                                                        </g>
                                                        </svg>
                                                    </div>
                                                    <div class='col-12'>
                                                        <h6 class='mt-2 text-params'>CO2</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <div class='d-flex align-items-center'>
                                                        <p class='value-icon' id='co2_icon_{$val->telemetria_id}'><i class='bi bi-arrows me-2 align-items-center mb-1 text-primary value-icon'></i></p>
                                                        <p class='value-parameter {$co2_color} align-items-center mb-1' id='co2_{$val->telemetria_id}'>{$co2}</p>
                                                    </div>
                                                    <small>ppm</small>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <p class='me-2 align-items-center mb-1 text-control'>Set Point</p>
                                                    <p class='me-2 align-items-center fw-bold mb-1 text-control'>CO2</p>            
                                                </div>
                                                <div class='row justify-content-center g-2'>
                                                    <input type='text' id='sp_co2_{$val->telemetria_id}' class='input col-8 w-50 form-control text-center px-0 mx-0' placeholder={$sp_co2} readonly>
                                                    <small class='col-4 align-content-center text-control-si'>ppm</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class='col-4 text-center align-content-center'>
                                                <div class='row justify-content-center'>
                                                    <div class='col-12'>
                                                        <svg version='1.0' xmlns='http://www.w3.org/2000/svg'
                                                            width='70' height='70' class='icon-params icon px-1' ondblclick='humidityModal()' ontouchstart='humidityModal()' viewBox='0 0 720.000000 720.000000' preserveAspectRatio='xMidYMid meet'>

                                                            <g transform='translate(0.000000,720.000000) scale(0.100000,-0.100000)'
                                                            fill='#0070fc' stroke='none'>
                                                            <path d='M3160 6300 c-14 -4 -57 -8 -97 -9 -39 -1 -74 -5 -77 -10 -3 -4 -33
                                                            -11 -66 -15 -33 -4 -72 -13 -87 -21 -16 -8 -40 -15 -55 -15 -15 0 -30 -4 -33
                                                            -10 -3 -5 -16 -10 -27 -10 -26 0 -83 -17 -120 -36 -14 -8 -37 -14 -51 -14 -13
                                                            0 -30 -7 -37 -15 -7 -8 -21 -15 -31 -15 -10 0 -21 -4 -24 -10 -3 -5 -14 -10
                                                            -23 -10 -16 0 -45 -14 -207 -100 -33 -18 -73 -39 -90 -48 -51 -26 -167 -103
                                                            -240 -161 -98 -77 -440 -423 -484 -489 -20 -30 -45 -67 -56 -82 -34 -46 -97
                                                            -143 -117 -180 -11 -19 -27 -48 -37 -65 -50 -85 -71 -127 -71 -144 0 -10 -7
                                                            -24 -15 -31 -8 -7 -15 -21 -15 -31 0 -19 -2 -26 -33 -87 -9 -19 -17 -44 -17
                                                            -57 0 -13 -4 -27 -10 -30 -5 -3 -10 -20 -10 -37 0 -17 -7 -39 -16 -49 -9 -10
                                                            -17 -35 -18 -56 -1 -21 -4 -43 -8 -48 -3 -6 -9 -32 -13 -60 -4 -27 -11 -54
                                                            -15 -60 -16 -20 -22 -137 -24 -460 -1 -300 5 -444 22 -485 7 -18 30 -131 36
                                                            -178 2 -18 11 -41 20 -51 9 -10 16 -32 16 -49 0 -17 4 -34 10 -37 6 -3 10 -19
                                                            10 -34 0 -16 7 -34 15 -41 8 -7 15 -21 15 -31 0 -10 5 -21 10 -24 6 -3 10 -19
                                                            10 -34 0 -16 7 -34 15 -41 8 -7 15 -18 15 -25 0 -25 153 -311 216 -405 35 -52
                                                            72 -106 81 -120 9 -14 42 -53 72 -86 31 -34 70 -80 86 -103 38 -51 211 -207
                                                            318 -286 100 -73 225 -148 332 -199 44 -21 87 -42 95 -47 8 -5 22 -12 30 -15
                                                            33 -13 66 -27 90 -38 14 -7 45 -18 70 -25 25 -7 57 -19 72 -27 14 -8 37 -14
                                                            50 -14 13 0 28 -4 34 -9 5 -6 26 -12 47 -14 21 -2 43 -9 49 -15 7 -7 33 -12
                                                            58 -12 26 0 50 -3 54 -8 23 -23 175 -35 446 -36 275 0 416 10 459 33 11 6 34
                                                            11 51 11 17 0 41 5 53 12 12 6 35 13 52 15 16 2 34 8 39 13 6 6 18 10 27 10
                                                            16 0 30 6 109 46 l25 13 0 218 0 218 -24 3 c-23 4 -40 -2 -104 -34 -16 -8 -37
                                                            -14 -47 -14 -10 0 -22 -5 -25 -10 -3 -6 -16 -10 -28 -10 -12 0 -31 -7 -41 -15
                                                            -11 -8 -30 -15 -42 -15 -13 0 -26 -4 -29 -10 -3 -5 -26 -10 -51 -10 -25 0 -55
                                                            -7 -68 -16 -20 -13 -64 -15 -268 -15 -133 0 -283 5 -333 11 -49 6 -112 13
                                                            -140 16 -27 4 -55 10 -61 15 -6 5 -25 10 -43 12 -17 2 -37 9 -43 15 -7 7 -27
                                                            12 -44 12 -18 0 -36 4 -39 10 -3 5 -19 10 -34 10 -16 0 -34 7 -41 15 -7 8 -20
                                                            15 -30 15 -10 0 -40 11 -67 24 -26 13 -88 43 -138 67 -101 49 -179 100 -310
                                                            205 -234 186 -414 429 -552 744 -8 19 -19 42 -24 50 -5 8 -12 22 -15 30 -18
                                                            45 -22 58 -33 91 -6 19 -11 45 -11 56 0 11 -7 26 -15 33 -8 7 -17 28 -20 49
                                                            -3 20 -10 44 -16 53 -5 10 -9 31 -9 47 0 17 -4 33 -10 36 -5 3 -10 30 -10 59
                                                            0 30 -7 67 -15 82 -22 42 -22 531 0 560 8 11 14 42 15 75 0 30 5 60 10 65 6 6
                                                            11 29 13 51 1 23 6 45 10 49 4 4 7 17 7 29 0 11 9 38 20 60 11 22 20 47 20 57
                                                            0 9 5 20 10 23 6 3 10 17 10 30 0 13 5 27 10 30 6 3 10 14 10 23 1 9 9 31 18
                                                            49 9 18 34 65 55 105 21 40 54 94 73 121 19 26 34 50 34 54 0 21 218 279 312
                                                            369 112 109 300 251 378 287 8 4 38 21 65 37 28 17 57 33 65 36 19 8 70 32 90
                                                            43 55 30 81 41 98 41 12 0 23 4 26 9 5 8 30 14 118 27 21 3 42 12 49 20 7 8
                                                            30 14 58 14 26 0 52 5 57 10 15 15 146 24 379 24 232 1 363 -7 394 -23 11 -6
                                                            38 -11 60 -11 22 0 43 -4 46 -10 3 -5 26 -10 49 -10 27 0 48 -6 56 -15 7 -8
                                                            25 -15 41 -15 15 0 31 -4 34 -10 3 -5 16 -10 27 -10 12 0 29 -6 37 -13 9 -7
                                                            36 -20 61 -27 25 -7 52 -19 60 -25 8 -7 22 -15 30 -18 71 -31 286 -166 370
                                                            -233 79 -63 263 -251 310 -317 19 -27 46 -63 60 -80 26 -34 31 -41 80 -122 17
                                                            -27 35 -57 40 -65 38 -60 89 -151 103 -182 10 -20 22 -40 27 -43 6 -3 10 -15
                                                            10 -25 0 -10 8 -33 17 -52 31 -61 33 -68 33 -87 0 -11 5 -23 10 -26 6 -3 10
                                                            -21 10 -39 0 -18 7 -41 15 -52 8 -10 15 -38 15 -62 0 -23 4 -50 9 -60 11 -20
                                                            27 -134 35 -245 4 -44 11 -87 16 -97 6 -10 10 -28 10 -40 0 -22 48 -115 82
                                                            -160 28 -37 122 -167 164 -227 20 -29 39 -53 43 -53 15 0 41 37 41 56 0 11 7
                                                            29 15 40 8 10 15 37 15 60 0 22 5 45 12 52 6 6 14 42 17 79 8 120 12 355 6
                                                            372 -4 9 -8 48 -9 88 0 42 -6 77 -14 85 -6 6 -12 35 -12 63 0 30 -6 59 -15 71
                                                            -8 10 -15 38 -15 60 0 24 -6 47 -15 54 -8 7 -15 22 -15 34 0 23 -22 92 -33
                                                            102 -4 4 -7 20 -7 34 0 15 -7 33 -15 40 -8 7 -15 21 -15 30 0 10 -6 31 -14 47
                                                            -8 15 -20 42 -26 58 -7 17 -16 37 -21 45 -9 15 -19 37 -38 80 -6 14 -24 48
                                                            -41 75 -16 28 -33 57 -37 65 -21 45 -175 258 -265 367 -64 78 -248 259 -323
                                                            319 -147 116 -252 191 -335 236 -19 11 -57 33 -85 49 -27 16 -57 32 -65 35 -8
                                                            3 -23 10 -32 15 -10 5 -24 9 -32 9 -7 0 -19 7 -26 15 -7 8 -23 15 -36 15 -12
                                                            0 -26 5 -29 10 -3 6 -17 10 -29 10 -13 0 -29 7 -36 15 -7 8 -25 15 -41 15 -15
                                                            0 -31 5 -34 10 -3 6 -26 10 -50 10 -24 0 -47 5 -50 10 -3 6 -18 10 -33 10 -15
                                                            0 -39 7 -54 14 -15 8 -53 18 -85 21 -32 4 -63 11 -68 15 -22 18 -579 36 -625
                                                            20z'/>
                                                            <path d='M3251 5409 c-52 -5 -99 -13 -105 -19 -5 -5 -31 -10 -56 -10 -25 0
                                                            -54 -6 -65 -14 -11 -7 -33 -16 -50 -19 -39 -7 -126 -36 -145 -48 -8 -5 -22
                                                            -12 -30 -15 -8 -3 -22 -10 -30 -15 -8 -5 -22 -12 -30 -15 -8 -3 -33 -15 -55
                                                            -26 -139 -71 -126 -63 -251 -157 -95 -72 -252 -230 -315 -319 -32 -45 -59 -86
                                                            -59 -92 0 -5 -6 -13 -12 -17 -7 -4 -21 -24 -30 -43 -10 -19 -31 -57 -48 -85
                                                            -16 -27 -32 -57 -36 -65 -3 -8 -10 -23 -15 -32 -5 -10 -9 -25 -9 -33 0 -8 -4
                                                            -23 -9 -33 -24 -43 -41 -86 -41 -102 0 -9 -7 -25 -15 -36 -8 -10 -15 -35 -15
                                                            -54 0 -20 -4 -40 -9 -45 -36 -38 -56 -447 -26 -530 8 -22 15 -55 15 -73 0 -61
                                                            59 -140 134 -177 33 -17 82 -20 91 -5 3 6 17 10 30 10 13 0 27 5 30 10 3 6 13
                                                            10 21 10 25 0 94 62 94 84 1 12 7 32 14 45 14 26 12 73 -5 132 -18 58 5 387
                                                            29 411 6 6 12 27 12 44 0 18 5 36 10 39 6 3 10 15 10 27 0 17 11 43 41 98 5 8
                                                            15 29 23 45 55 118 80 165 92 173 6 4 23 25 39 47 66 89 205 221 287 270 9 6
                                                            49 30 90 55 40 25 114 58 163 74 50 15 104 33 120 40 45 17 427 15 485 -3 25
                                                            -7 68 -20 95 -28 28 -9 58 -19 67 -24 10 -5 24 -9 32 -9 7 0 19 -7 26 -15 7
                                                            -8 19 -15 28 -15 8 0 39 -18 68 -40 29 -22 63 -40 74 -40 12 0 47 24 78 53
                                                            113 106 154 142 170 151 33 19 72 60 72 76 0 33 -211 200 -253 200 -8 0 -20 7
                                                            -27 15 -7 8 -21 15 -31 15 -10 0 -21 5 -24 10 -3 6 -15 10 -25 10 -17 0 -62
                                                            19 -122 51 -10 5 -31 9 -47 9 -17 0 -33 5 -36 10 -3 6 -25 10 -49 10 -23 0
                                                            -47 5 -53 11 -12 12 -176 29 -263 27 -30 0 -97 -5 -149 -9z'/>
                                                            <path d='M4490 5137 c-103 -99 -339 -309 -438 -391 -26 -21 -70 -59 -98 -85
                                                            -28 -25 -95 -82 -149 -126 -54 -44 -128 -107 -165 -140 -74 -67 -159 -135
                                                            -169 -135 -3 0 -53 -2 -111 -3 -89 -2 -112 -6 -150 -27 -57 -31 -180 -153
                                                            -203 -202 -10 -20 -22 -40 -27 -43 -6 -3 -10 -19 -10 -34 0 -15 -5 -32 -12
                                                            -39 -7 -7 -14 -51 -16 -101 -5 -97 11 -164 55 -233 12 -21 23 -42 23 -47 0
                                                            -15 105 -105 146 -125 21 -10 51 -25 66 -32 37 -20 240 -19 276 0 15 8 46 23
                                                            70 32 23 10 42 21 42 25 0 4 13 15 29 26 37 23 125 132 140 175 7 18 17 41 22
                                                            50 17 32 32 168 22 191 -6 12 -8 38 -5 59 4 33 50 81 488 513 266 262 484 481
                                                            484 485 0 16 -226 270 -239 270 -3 0 -35 -28 -71 -63z'/>
                                                            <path d='M4488 4541 c-71 -77 -128 -146 -128 -154 0 -8 9 -22 20 -32 29 -26
                                                            130 -239 130 -274 0 -11 5 -32 12 -48 7 -19 12 -121 12 -310 l1 -282 30 -31
                                                            c31 -33 78 -58 157 -84 41 -14 53 -14 94 -1 52 15 94 56 94 90 0 11 5 26 12
                                                            33 7 7 16 38 20 70 3 31 12 84 18 117 15 78 9 360 -8 377 -7 7 -12 28 -12 47
                                                            0 38 -14 90 -36 134 -8 16 -14 33 -14 39 0 12 -44 112 -60 138 -5 8 -17 31
                                                            -27 50 -42 87 -157 260 -172 260 -9 0 -73 -63 -143 -139z'/>
                                                            <path d='M5351 3531 c-11 -27 -25 -53 -31 -56 -5 -3 -26 -35 -47 -71 -54 -93
                                                            -56 -96 -78 -129 -29 -44 -42 -66 -61 -102 -9 -18 -22 -37 -30 -44 -8 -6 -14
                                                            -16 -14 -21 0 -5 -21 -39 -46 -74 -25 -36 -48 -73 -51 -82 -4 -10 -34 -56 -67
                                                            -104 -34 -47 -69 -99 -78 -115 -26 -43 -41 -65 -65 -100 -13 -17 -23 -35 -23
                                                            -38 0 -4 -10 -21 -22 -38 -30 -41 -40 -59 -79 -137 -18 -36 -42 -81 -54 -100
                                                            -12 -19 -28 -53 -35 -75 -7 -22 -16 -44 -21 -49 -5 -6 -9 -21 -9 -35 0 -13 -7
                                                            -32 -15 -41 -8 -9 -16 -30 -18 -46 -2 -16 -7 -36 -13 -44 -5 -8 -10 -95 -11
                                                            -194 -2 -144 0 -182 12 -199 8 -12 15 -29 15 -38 0 -20 28 -117 41 -141 5 -10
                                                            9 -25 9 -33 0 -8 11 -33 25 -55 14 -22 25 -41 25 -44 0 -12 78 -118 118 -159
                                                            76 -79 213 -177 246 -177 8 0 19 -7 26 -15 7 -8 23 -15 36 -15 12 0 26 -4 29
                                                            -10 3 -5 16 -10 28 -10 13 0 33 -7 46 -16 13 -10 40 -18 60 -20 20 -2 56 -9
                                                            81 -15 77 -19 342 9 372 39 6 6 24 12 38 12 14 0 30 4 35 9 6 5 28 14 50 21
                                                            149 48 350 236 424 395 51 111 59 131 66 155 39 140 37 443 -3 517 -7 12 -12
                                                            35 -12 52 0 16 -4 33 -10 36 -5 3 -10 16 -10 29 0 12 -7 31 -15 42 -8 10 -15
                                                            25 -15 31 0 21 -106 230 -168 333 -12 19 -31 53 -43 75 -12 22 -32 54 -45 72
                                                            -13 17 -24 35 -24 38 0 4 -16 29 -35 55 -19 26 -35 52 -35 57 0 5 -11 20 -25
                                                            33 -14 13 -25 31 -25 41 0 9 -7 22 -15 29 -19 16 -119 165 -161 240 -17 30
                                                            -57 96 -89 145 -69 105 -80 123 -95 150 -25 47 -47 79 -57 82 -5 2 -20 -19
                                                            -32 -46z m97 -918 c12 -17 22 -35 22 -41 0 -5 7 -16 16 -23 16 -13 154 -204
                                                            154 -213 0 -2 17 -31 38 -63 43 -67 92 -165 92 -186 0 -8 7 -20 15 -27 8 -7
                                                            15 -23 15 -36 0 -12 4 -25 9 -28 14 -9 21 -186 11 -274 -5 -46 -16 -97 -25
                                                            -114 -8 -17 -15 -36 -15 -43 0 -21 -124 -131 -175 -154 -27 -13 -58 -27 -67
                                                            -32 -10 -5 -66 -9 -124 -9 -105 0 -174 12 -204 35 -8 6 -31 19 -50 28 -48 21
                                                            -134 105 -152 148 -37 83 -41 95 -44 116 -1 13 -6 33 -10 45 -15 48 1 262 22
                                                            279 8 6 14 20 14 31 0 33 87 192 162 295 21 29 38 56 38 61 0 5 9 17 20 27 10
                                                            9 44 55 75 100 30 46 62 88 70 95 8 7 15 18 15 26 0 7 8 18 18 23 13 8 19 5
                                                            27 -12 6 -13 20 -37 33 -54z'/>
                                                            <path d='M5175 2135 c-22 -21 -25 -33 -25 -95 0 -66 2 -74 29 -101 40 -39 82
                                                            -39 122 0 26 26 29 36 29 91 0 64 -18 113 -45 124 -37 14 -86 6 -110 -19z m83
                                                            -47 c17 -17 15 -80 -3 -104 -15 -19 -15 -19 -30 0 -17 23 -20 69 -5 97 12 22
                                                            21 24 38 7z'/>
                                                            <path d='M5355 1968 c-59 -106 -107 -197 -108 -203 -1 -5 11 -10 27 -10 26 0
                                                            35 12 137 195 60 107 109 198 109 203 0 4 -13 7 -29 7 -28 0 -35 -11 -136
                                                            -192z'/>
                                                            <path d='M5459 1974 c-21 -22 -24 -35 -24 -101 0 -70 2 -76 28 -99 30 -25 75
                                                            -31 108 -14 65 35 65 195 0 230 -36 19 -86 12 -112 -16z m85 -50 c9 -23 7 -87
                                                            -3 -103 -5 -8 -16 -11 -25 -8 -12 5 -16 20 -16 60 0 30 3 57 7 60 11 12 31 7
                                                            37 -9z'/>
                                                            </g>
                                                            </svg>
                                                    </div>
                                                    <div class='col-12'>
                                                        <h6 class='mt-2 text-params'>Humidity</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <div class='d-flex align-items-center'>
                                                        <p class='value-icon' id='humidity_icon_{$val->telemetria_id}'><i class='bi bi-arrows me-2 align-items-center mb-1 text-primary value-icon'></i></p>
                                                        <p class='value-parameter {$humedad_color} align-items-center mb-1' id='humd_{$val->telemetria_id}'>{$humedad}</p>
                                                    </div>
                                                    <small>%</small>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <p class='me-2 align-items-center mb-1 text-control'>Set Point</p>
                                                    <p class='me-2 align-items-center fw-bold mb-1 text-control'>Humidity</p>            
                                                </div>
                                                <div class='row justify-content-center g-2'>
                                                    <input type='text' id='sp_humd_{$val->telemetria_id}' class='input col-10 w-50 form-control text-center px-0 mx-0' placeholder={$sp_humedad} readonly>
                                                    <small class='col-2 align-content-center text-control-si'>%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class='col-4 text-center align-content-center'>
                                                <div class='row justify-content-center'>
                                                    <div class='col-12'>
                                                        <svg version='1.0' xmlns='http://www.w3.org/2000/svg'
                                                        width='70' height='70' class='icon-params px-1 btn' ondblclick='injectionModal()' ontouchstart='injectionModal()' viewBox='0 0 720.000000 720.000000'
                                                        preserveAspectRatio='xMidYMid meet'>

                                                        <g transform='translate(0.000000,720.000000) scale(0.100000,-0.100000)'
                                                        fill='#000000' stroke='none'>
                                                        <path d='M3400 6601 c-8 -5 -44 -12 -80 -15 -257 -27 -565 -177 -833 -403
                                                        -289 -246 -509 -645 -558 -1014 -23 -168 -12 -496 20 -625 5 -21 17 -67 25
                                                        -100 34 -138 170 -408 271 -541 139 -182 319 -339 538 -468 l97 -58 -2 -181
                                                        -3 -181 -357 -3 -357 -2 -20 38 c-24 46 -108 135 -149 156 -98 51 -210 60
                                                        -316 26 -74 -24 -158 -94 -205 -170 l-31 -50 -720 0 -720 0 0 -1090 0 -1090
                                                        719 0 720 0 22 -33 c41 -59 114 -128 164 -155 47 -25 58 -27 180 -27 114 0
                                                        135 3 170 22 42 22 126 105 164 161 l22 32 1439 0 1440 0 19 -38 c11 -21 38
                                                        -57 60 -80 l41 -42 233 0 233 0 45 48 c25 26 56 62 68 79 l22 33 720 0 719 0
                                                        0 1090 0 1090 -724 0 -724 0 -21 43 c-59 118 -193 197 -336 197 -150 0 -285
                                                        -79 -337 -197 l-19 -43 -359 0 -360 0 0 185 c0 181 0 185 23 198 12 8 59 36
                                                        105 63 46 27 91 56 100 64 10 9 24 21 33 26 60 35 209 174 295 275 218 257
                                                        357 567 395 880 24 200 10 454 -36 629 -25 99 -97 286 -144 380 -31 59 -124
                                                        200 -181 271 -249 312 -626 536 -1022 606 -103 18 -463 29 -488 14z m388 -261
                                                        c300 -38 580 -180 820 -416 194 -192 317 -404 382 -659 40 -155 50 -464 20
                                                        -600 -18 -83 -31 -131 -39 -150 -5 -11 -21 -54 -36 -95 -83 -225 -235 -437
                                                        -425 -594 -43 -36 -102 -79 -132 -97 -29 -17 -69 -43 -90 -56 -87 -59 -309
                                                        -136 -468 -165 -68 -12 -466 -5 -500 10 -14 5 -45 15 -70 22 -301 81 -581 256
                                                        -759 474 -136 167 -209 312 -291 581 -31 103 -39 508 -11 605 5 19 17 63 25
                                                        97 9 35 20 71 25 80 6 10 28 61 51 113 72 169 149 279 300 431 189 190 404
                                                        316 670 394 112 32 370 44 528 25z m-458 -3092 c204 -27 614 -7 705 34 14 6
                                                        28 8 33 4 4 -4 8 -68 10 -142 l4 -134 -481 0 -481 0 0 133 c0 74 3 136 6 139
                                                        3 3 29 0 57 -8 29 -7 95 -19 147 -26z m-1449 -287 l29 -29 2 -339 c0 -186 0
                                                        -446 0 -579 -1 -132 -2 -433 -2 -668 l0 -428 -31 -29 c-44 -41 -105 -41 -151
                                                        0 l-33 29 -3 1007 -2 1006 29 30 c25 25 37 29 81 29 44 0 56 -4 81 -29z m3586
                                                        7 c11 -13 24 -30 29 -38 5 -8 11 -172 14 -365 l5 -350 85 -5 85 -5 0 -355 0
                                                        -355 -85 -5 -85 -5 -5 -280 c-5 -299 -7 -312 -57 -334 -31 -14 -76 -14 -107 0
                                                        -51 23 -56 51 -58 344 l-3 270 -105 5 -105 5 0 355 0 355 105 5 105 5 1 358 0
                                                        357 29 30 c25 26 36 30 80 30 41 0 56 -5 72 -22z m-4039 -1050 l-3 -833 -530
                                                        2 c-291 1 -558 3 -592 3 l-63 0 0 830 0 830 595 0 595 0 -2 -832z m3597 100
                                                        c3 -403 5 -778 3 -833 l-3 -100 -1427 -3 -1428 -2 0 835 0 835 1424 0 1424 0
                                                        7 -732z m1923 -92 c1 -605 -1 -822 -9 -833 -10 -11 -104 -13 -588 -7 -317 3
                                                        -578 8 -581 11 -7 7 -13 1620 -6 1638 5 13 75 15 593 13 l588 -3 3 -819z'/>
                                                        <path d='M3490 6133 c-99 -6 -183 -24 -280 -60 -212 -79 -371 -187 -522 -355
                                                        -91 -102 -105 -121 -167 -239 -55 -106 -84 -183 -101 -279 -7 -36 -16 -75 -21
                                                        -88 -5 -13 -9 -74 -9 -136 0 -109 1 -113 28 -138 59 -57 127 -60 178 -7 25 26
                                                        30 40 35 107 24 279 106 468 279 643 194 197 416 289 692 289 263 -1 456 -75
                                                        639 -249 172 -161 282 -359 303 -541 23 -200 27 -217 56 -245 40 -38 114 -46
                                                        152 -16 35 28 62 109 54 164 -3 23 -10 80 -16 127 -13 111 -64 286 -98 340 -9
                                                        14 -27 45 -40 69 -50 95 -100 160 -197 257 -136 136 -302 245 -440 287 -28 9
                                                        -75 24 -105 35 -53 19 -260 44 -332 40 -18 -1 -58 -3 -88 -5z'/>
                                                        <path d='M3583 5718 c-20 -25 -213 -359 -213 -368 0 -5 26 -10 58 -12 l57 -3
                                                        3 -383 c2 -358 1 -383 -15 -389 -10 -4 -40 -23 -68 -44 -127 -94 -175 -179
                                                        -175 -310 0 -135 48 -238 145 -311 70 -53 122 -68 229 -67 108 0 156 16 226
                                                        74 96 80 140 173 140 295 0 147 -65 255 -202 335 l-53 31 0 385 0 384 58 3
                                                        c36 2 57 7 56 15 -1 18 -213 370 -225 374 -6 2 -16 -2 -21 -9z m99 -1417 c30
                                                        -27 33 -35 32 -85 0 -60 -16 -86 -66 -112 -44 -23 -83 -17 -123 17 -33 29 -35
                                                        35 -35 92 0 52 4 65 27 89 23 24 35 28 80 28 44 0 58 -5 85 -29z'/>
                                                        </g>
                                                        </svg>
                                                    </div>
                                                    <div class='col-12'>
                                                        <h6 class='mt-2 text-params'>Injection Hours</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='value-parameter col-4 text-center align-content-center' id='h_inyeccion_{$val->telemetria_id}'>{$h_inyeccion}</td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <p class='me-2 align-items-center mb-1 text-control'>I. Hours:</p>
                                                </div>
                                                <div class='row justify-content-center g-2'>
                                                     <input type='text' class='input col-8 w-50 form-control text-center px-0 mx-0' placeholder='' readonly>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class='col-4 text-center align-content-center'>
                                                <div class='row justify-content-center'>
                                                    <div class='col-12'>
                                                        <i class='ri-windy-line px-3 fs-1 text-secondary' ondblclick='supplyModal()' ontouchstart='supplyModal()'></i>
                                                    </div>
                                                    <div class='col-12'>
                                                        <h6 class='mt-2 text-params'>Supply</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <div class='d-flex align-items-center'>
                                                        <p class='value-icon' id='tmp_icon_{$val->telemetria_id}'><i class='bi bi-arrows me-2 align-items-center mb-1 text-primary value-icon'></i></p>
                                                        <p class='value-parameter {$supply_color} align-items-center mb-1' id='temp1_{$val->telemetria_id}'>{$supply}</p>
                                                    </div>
                                                    <small>°C</small>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <p class='me-2 align-items-center mb-1 text-control'>Set Point</p>
                                                    <p class='me-2 align-items-center fw-bold mb-1 text-control'>Tmp</p>            
                                                </div>
                                                <div class='row justify-content-center g-2'>
                                                    <input type='text' id='sp_temp_{$val->telemetria_id}' class='input col-8 w-50 form-control text-center px-0 mx-0' placeholder={$val->set_point} readonly>
                                                    <small class='col-4 align-content-center text-control-si'>°C</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class='col-4 text-center align-content-center'>
                                                <div class='row justify-content-center'>
                                                    <div class='col-12'>
                                                        <svg version='1.0' xmlns='http://www.w3.org/2000/svg'
                                                        width='70' height='70' class='icon-params px-1 btn' ondblclick='apertureModal()' ontouchstart='apertureModal()' viewBox='0 0 802.000000 643.000000'
                                                        preserveAspectRatio='xMidYMid meet'>

                                                        <g transform='translate(0.000000,643.000000) scale(0.100000,-0.100000)'
                                                        fill='#000000' stroke='none'>
                                                        <path d='M3039 4995 c-60 -31 -93 -71 -113 -137 -15 -47 -16 -111 -14 -538 l3
                                                        -485 144 -3 c97 -2 148 1 157 9 11 9 14 78 14 381 l0 370 32 49 c17 27 37 49
                                                        43 49 7 0 18 7 25 15 8 10 31 15 71 15 53 0 60 -3 81 -30 l22 -30 571 0 c554
                                                        0 572 1 589 19 42 46 112 33 153 -28 l23 -34 0 -1364 0 -1365 -33 -33 c-50
                                                        -50 -79 -54 -132 -16 l-43 31 -669 0 -669 0 -32 33 -32 33 0 171 c0 214 9 203
                                                        -170 203 -85 0 -130 -4 -138 -12 -16 -16 -17 -526 -1 -577 36 -112 68 -160
                                                        130 -191 58 -30 91 -26 109 13 l15 32 279 3 c190 1 283 -1 292 -9 7 -6 14 -25
                                                        16 -42 l3 -32 615 -3 c436 -2 627 0 655 8 52 15 110 71 141 136 l24 51 -3
                                                        1564 c-2 1102 -6 1575 -13 1601 -18 59 -65 120 -113 145 l-44 23 -971 0 -971
                                                        -1 -46 -24z'/>
                                                        <path d='M2205 4333 c-11 -3 -24 -9 -30 -13 -5 -4 -26 -11 -45 -15 -19 -4 -39
                                                        -10 -45 -13 -13 -8 -121 -46 -365 -128 -107 -36 -215 -73 -240 -83 -47 -19
                                                        -73 -28 -178 -63 -35 -12 -66 -27 -69 -35 -3 -7 8 -29 23 -49 16 -20 34 -44
                                                        39 -53 6 -9 34 -46 62 -81 29 -36 66 -83 83 -105 54 -71 144 -184 198 -250 28
                                                        -36 52 -67 52 -71 0 -4 15 -23 33 -43 17 -20 41 -50 52 -65 11 -16 32 -43 46
                                                        -60 14 -17 28 -39 32 -48 12 -34 34 -18 56 41 11 32 21 63 21 69 0 6 14 55 31
                                                        109 79 250 80 251 137 228 19 -8 44 -15 56 -15 12 0 30 -6 41 -13 11 -7 38
                                                        -18 60 -25 22 -6 63 -17 90 -25 28 -8 66 -18 85 -22 19 -4 40 -10 45 -14 10
                                                        -6 73 -21 180 -42 127 -25 215 -30 460 -26 209 4 271 8 315 22 30 10 80 23
                                                        110 29 30 7 62 15 70 18 8 4 33 11 55 16 22 5 81 22 130 37 179 55 248 73 380
                                                        102 140 30 193 35 373 40 l183 4 -3 137 -3 137 -135 -1 c-131 -2 -314 -17
                                                        -355 -30 -11 -3 -42 -10 -70 -14 -27 -4 -59 -10 -70 -13 -51 -13 -115 -29
                                                        -165 -41 -30 -7 -82 -22 -115 -33 -33 -11 -89 -28 -125 -38 -131 -36 -166 -46
                                                        -195 -55 -49 -16 -152 -31 -281 -42 -189 -17 -466 6 -669 55 -83 20 -367 116
                                                        -383 130 -19 15 -9 89 28 197 83 250 84 270 15 253z'/>
                                                        <path d='M5322 3932 c-9 -6 -12 -40 -10 -132 l3 -124 230 -77 c127 -43 234
                                                        -77 239 -76 14 4 66 160 69 207 2 30 -44 59 -155 94 -42 14 -94 33 -115 42
                                                        -35 15 -180 60 -228 70 -11 2 -26 1 -33 -4z'/>
                                                        <path d='M2915 3258 c-3 -13 -4 -111 -3 -218 l3 -195 148 -3 147 -3 -2 218 -3
                                                        218 -142 3 c-141 3 -143 2 -148 -20z'/>
                                                        <path d='M6183 3169 c-6 -8 -15 -34 -21 -59 -6 -25 -22 -88 -37 -140 -14 -52
                                                        -32 -120 -39 -150 -13 -53 -33 -110 -38 -110 -2 0 -41 14 -88 31 -47 17 -98
                                                        35 -115 41 -16 5 -39 14 -50 19 -46 21 -239 88 -325 112 -30 9 -74 21 -96 28
                                                        -23 6 -47 8 -53 5 -16 -10 -15 -250 2 -264 12 -10 96 -41 130 -47 23 -5 204
                                                        -67 292 -101 39 -14 87 -33 108 -40 20 -8 45 -18 55 -24 9 -5 29 -14 45 -20
                                                        25 -9 27 -12 21 -52 -3 -24 -8 -50 -12 -58 -6 -16 -26 -100 -43 -188 -6 -29
                                                        -9 -56 -5 -59 5 -5 33 6 171 67 22 10 60 26 85 35 25 10 68 28 95 41 28 13 88
                                                        38 135 57 47 19 130 54 185 79 55 25 119 53 143 63 23 10 42 24 42 32 0 15
                                                        -51 82 -170 222 -41 49 -91 110 -110 135 -19 26 -61 77 -93 114 -33 37 -69 83
                                                        -82 102 -13 19 -45 59 -72 89 -39 43 -51 51 -60 40z'/>
                                                        <path d='M4290 2949 c-101 -14 -224 -37 -290 -54 -118 -29 -135 -34 -190 -50
                                                        -30 -10 -77 -23 -105 -30 -27 -8 -75 -21 -105 -30 -68 -20 -113 -32 -210 -52
                                                        -90 -19 -466 -29 -520 -14 -19 5 -64 14 -100 20 -172 27 -322 69 -588 167 -43
                                                        16 -77 23 -82 18 -13 -13 -80 -217 -74 -225 5 -8 78 -44 149 -74 34 -14 282
                                                        -96 380 -125 17 -5 50 -12 75 -15 25 -4 54 -11 65 -16 92 -41 661 -41 750 1
                                                        11 5 45 12 75 15 30 4 75 14 100 22 25 8 54 17 65 19 11 2 40 10 65 19 83 27
                                                        183 55 197 55 7 0 21 4 31 9 9 5 53 16 97 26 44 9 94 21 112 26 18 6 80 13
                                                        138 16 58 3 108 10 111 15 3 4 70 8 150 8 l144 0 0 130 0 130 -192 -1 c-106
                                                        -1 -218 -5 -248 -10z'/>
                                                        </g>
                                                        </svg>
                                                    </div>
                                                    <div class='col-12'>
                                                        <h6 class='text-params'>Aperture Level</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='value-parameter col-4 text-center align-content-center' id='n_apertura_{$val->telemetria_id}'>{$n_apertura}</td>
                                            <td class='col-4 text-center align-content-center' id='defrost_prueba_{$val->telemetria_id}'>I: {$i}</td>
                                        </tr>
                                        <tr>
                                            <td class='col-4 text-center align-content-center'>
                                                <div class='row justify-content-center'>
                                                    <div class='col-12'>
                                                    <svg version='1.0' xmlns='http://www.w3.org/2000/svg'
                                                        width='70' height='70' class='icon-params px-1 btn' ondblclick='compressorModal()' ontouchstart='compressorModal()' viewBox='0 0 172.000000 123.000000'
                                                        preserveAspectRatio='xMidYMid meet'>
                                                        <metadata>
                                                        Created by potrace 1.16, written by Peter Selinger 2001-2019
                                                        </metadata>
                                                        <g transform='translate(0.000000,123.000000) scale(0.100000,-0.100000)'
                                                        fill='#000000' stroke='none'>
                                                        <path fill='red' d='M367 1209 c-56 -42 -57 -50 -57 -392 l0 -315 -23 -15 c-36 -23 -85
                                                        -106 -97 -165 -25 -114 32 -234 138 -290 257 -137 499 205 300 424 l-48 52 0
                                                        301 c0 331 -5 362 -60 401 -38 27 -115 27 -153 -1z m127 -71 c14 -19 16 -70
                                                        16 -349 l0 -326 37 -24 c115 -74 116 -260 2 -336 -124 -82 -283 1 -296 155 -6
                                                        71 23 132 85 177 l41 30 0 329 c1 292 3 332 18 348 23 26 78 24 97 -4z'/>
                                                        <path fill='red'd='M400 733 l-1 -288 -38 -24 c-145 -89 -86 -321 81 -321 62 0 101 20
                                                        138 72 25 33 30 51 30 95 0 30 -7 67 -14 82 -18 34 -81 91 -101 91 -13 0 -15
                                                        42 -15 290 l0 290 -40 0 -40 0 0 -287z'/>
                                                        <path fill='blue' d='M1049 1194 l-34 -35 -3 -322 -3 -322 -38 -40 c-61 -63 -84 -118 -84
                                                        -200 0 -86 31 -155 94 -209 81 -69 171 -81 264 -36 168 81 206 306 75 438
                                                        l-40 40 0 301 c0 327 -3 347 -56 396 -22 21 -37 25 -83 25 -52 0 -60 -3 -92
                                                        -36z m136 -49 l25 -24 0 -328 1 -328 39 -27 c53 -37 80 -92 80 -165 0 -72 -28
                                                        -125 -88 -167 -37 -26 -52 -31 -103 -30 -49 1 -67 6 -101 31 -113 82 -117 253
                                                        -6 331 l38 27 0 323 c0 209 4 330 11 343 10 20 41 37 67 38 7 1 24 -10 37 -24z'/>
                                                        <path fill='blue' d='M1100 520 c0 -64 -3 -80 -15 -80 -8 0 -33 -16 -54 -36 -74 -67 -76
                                                        -188 -3 -256 68 -63 154 -63 223 -1 72 65 77 172 12 244 -20 22 -46 42 -59 45
                                                        -23 6 -24 10 -24 85 l0 79 -40 0 -40 0 0 -80z'/>
                                                        </g>
                                                    </svg>
                                                    </div>
                                                    <div class='col-12'>
                                                        <h6 class='mt-2 text-params'>Compressor</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='value-parameter col-4 text-center align-content-center' id='compresor_{$val->telemetria_id}'>{$compresor}</td>
                                            <td class='col-4 text-center'>
                                                <div class='row justify-content-center'>
                                                    <div class='col-auto'>
                                                        <label for='' class='col-form-label text-control'>Defrost:</label>
                                                    </div>
                                                    <div class='col-auto'>
                                                        <button type='button' class='btn btn-block btn-success text-control'>ACTIVE</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- FIN TABLA-->
                            <div class='col-12' id='btnToSave2' hidden>
                                <button type='button' id='btnSaveData2' class='btn btn-primary col-12' onclick='guardarDatos()'>SAVE</button>
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