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
    function formularioPlantilla($val,$id){
        $text = " <div class='mt-2' id='form_{$id}'>
        <h6 class='fw-bold'>Are you sure to activate control mode {$val->nombre_contenedor}?</h6>
        </div>
        <div class='mt-2'>
            <label for='access_{$val->telemetria_id}'>Enter your password</label>
            <input type='password' class='form-control' id='access_{$val->telemetria_id}' name='access' required>
            <button type='button' class='btn btn-primary mt-2' onClick='btnAccess({$val->telemetria_id})'>SAVE</button>
        </div>
        ";
        $result = array(
            'text'=>$text,
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

        if($compresor<90){
            $compresor_color = "text-success";
        }else if($compresor>=90 && $compresor<=99){
            $compresor_color = "text-warning";
        }else if ($compresor>100){
            $compresor_color = "text-danger";
        }
        
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
            <div class='card '>
                <div class='card-body'>
                    <div class='container '>
                        <div class='row py-2' style='padding-right:5px ; padding-left:5px;'>
                            <!--POWER ON/OFF -->
                            <div class='col-2 justify-content-center align-content-center' style='padding-right:5px ; padding-left:5px;' data-toggle='tooltip' data-placement='top' title='Turn ON/OFF'>         
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='4' stroke='currentColor' width='27px' height='27px' class='{$power_state}'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9' />
                                </svg>
                            </div>
                            <div class='col-8 '><h4 id='nombre_contenedor_{$val->telemetria_id}' class='text-center mt-3 fw-bold'> {$val->nombre_contenedor}</h4></div>
                            
                            <div class='col-2 text-center' style='padding-right:5px ; padding-left:5px;'>
                                <button type='button' id='btnIconAccess_{$val->telemetria_id}' onclick='accessModal({$val->telemetria_id})' class='btn btn-block btn-outline-success border-0' data-toggle='tooltip' data-placement='top' title='Access Control'>
                                    <i class='ri-lock-2-fill fs-3' id='candado_{$val->telemetria_id}'></i>
                                </button>
                            </div>
                            <div class='col-12 '><h5 class='text-center mt-2'>{$val->descripcionC}</h5> </div>
                            <div class='col-12'>
                                <div class='row'>
                                    <i class='ri-time-line col-3 text-end align-content-center fs-1 text-primary'></i>
                                    <h6 id='fechita_{$val->telemetria_id}' class='col-9 align-content-center px-0 m-0'> {$fechita}</h6>
                                </div>
                            </div>
                          
                            <div class='col-3 p-2 text-center'><button type='button' onclick='graficaM({$valR})' class='mt-1 btn btn-block btn-outline-primary' data-toggle='tooltip' data-placement='top' title='Gráfico'><i class='ri-line-chart-line fs-5'></i></button></div>
                            <div class='col-3 p-2 text-center'><button type='button' class='mt-1 btn btn-block btn-outline-secondary' data-toggle='tooltip' data-placement='top' title='Datos'><i class='ri-layout-horizontal-line fs-5'></i></button></div>
                            <div class='col-3 p-2 text-center'><button type='button' class='mt-1 btn btn-block btn-outline-success' data-toggle='tooltip' data-placement='top' title='Correo'><i class='ri-mail-line fs-5'></i></button> </div>
                            <div class='col-3 p-2 text-center'><button type='button' class='mt-1 btn btn-block btn-outline-info' data-toggle='tooltip' data-placement='top' title='Reporte'><i class='ri-file-chart-line fs-5'></i></button></div>

                            <div class='col-12' id='btnToSave1_{$val->telemetria_id}' hidden>
                                <button type='button' id='btnSaveData1' class='btn btn-primary col-12' onclick='guardarDatos({$val->telemetria_id})'>SAVE</button>
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
                                                    <p class='me-2 text-center mb-1 text-control'>SP <span class='fw-bold'>Ethy</span></p>
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
                                                    <svg width='100' height='100' class='icon-params-co2 icon px-1 btn' ondblclick='co2Modal()' ontouchstart='co2Modal()'>
                                                        <use xlink:href='sprite.svg#co2'></use>
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
                                                    <small>%</small>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <p class='me-2 align-items-center mb-1 text-control'>SP <span class='fw-bold'>CO2</span></p>            
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
                                                            width='70' height='70' class='icon-params icon px-1' ondblclick='humidityModal()' ontouchstart='humidityModal()' viewBox='0 0 74.000000 74.000000' preserveAspectRatio='xMidYMid meet'>

                                                            <g transform='translate(0.000000,74.000000) scale(0.100000,-0.100000)'
                                                            fill='#0070fc' stroke='none'>
                                                            <path d='M270 635 c-61 -20 -116 -67 -146 -128 -23 -44 -26 -60 -22 -127 4
                                                            -96 38 -159 112 -204 54 -34 156 -48 195 -27 26 15 30 57 4 46 -68 -26 -146
                                                            -11 -203 41 -134 121 -61 347 116 362 116 10 203 -58 229 -178 9 -40 29 -73
                                                            38 -63 14 13 6 90 -14 133 -55 123 -187 185 -309 145z'/>
                                                            <path d='M315 551 c-17 -4 -43 -16 -58 -25 -55 -37 -92 -133 -64 -170 19 -26
                                                            37 -12 37 30 0 84 57 130 146 119 l53 -6 -33 -30 c-19 -16 -41 -29 -50 -29
                                                            -26 0 -50 -45 -36 -69 19 -36 80 -22 80 18 0 9 23 39 52 68 40 40 49 56 41 64
                                                            -9 9 -16 9 -28 -1 -10 -8 -15 -9 -15 -2 0 25 -76 45 -125 33z'/>
                                                            <path d='M460 465 c-6 -8 -8 -20 -4 -27 5 -7 11 -31 14 -53 3 -27 10 -40 20
                                                            -40 21 0 25 67 5 104 -18 36 -18 36 -35 16z'/>
                                                            <path d='M516 308 c-65 -103 -69 -122 -36 -175 54 -89 194 -21 161 79 -11 33
                                                            -78 148 -87 148 -2 0 -19 -24 -38 -52z m81 -106 c7 -31 -7 -56 -36 -60 -51 -8
                                                            -68 53 -29 103 l22 28 18 -23 c10 -12 21 -34 25 -48z'/>
                                                            <path d='M530 210 c0 -12 35 -31 44 -23 3 3 -2 12 -12 19 -20 16 -32 17 -32 4z'/>
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
                                                    <p class='me-2 align-items-center mb-1 text-control'>SP <span class='fw-bold'>Hum</span></p>       
                                                </div>
                                                <div class='row justify-content-center g-2'>
                                                    <input type='text' id='sp_humd_{$val->telemetria_id}' class='input col-10 w-50 form-control text-center px-0 mx-0' placeholder={$sp_humedad} readonly>
                                                    <small class='col-2 align-content-center text-control-si'>%</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id='row_injection_{$val->telemetria_id}' hidden>
                                            <td class='col-4 text-center align-content-center'>
                                                <div class='row justify-content-center'>
                                                    <div class='col-12'>
                                                        <svg version='1.0' xmlns='http://www.w3.org/2000/svg'
                                                        width='70' height='70' class='icon-params px-1 btn' ondblclick='injectionModal()' ontouchstart='injectionModal()' viewBox='0 0 64.000000 64.000000'
                                                        preserveAspectRatio='xMidYMid meet'>

                                                        <g transform='translate(0.000000,64.000000) scale(0.100000,-0.100000)'
                                                        fill='#000000' stroke='none'>
                                                        <path d='M275 579 c-40 -12 -58 -28 -84 -69 -38 -62 -21 -148 38 -191 46 -34
                                                        41 -53 -11 -45 -49 7 -48 7 -145 0 l-73 -6 0 -99 0 -99 68 -1 c112 0 422 -1
                                                        500 0 l72 1 0 99 0 99 -72 6 c-98 7 -97 7 -145 0 -52 -7 -58 11 -15 43 34 25
                                                        62 79 62 119 0 41 -35 107 -67 126 -37 22 -91 29 -128 17z m130 -52 c45 -45
                                                        53 -92 26 -145 -52 -102 -193 -93 -230 14 -18 52 -9 88 34 131 29 29 39 33 85
                                                        33 46 0 56 -4 85 -33z m-45 -247 c0 -5 -18 -10 -40 -10 -22 0 -40 5 -40 10 0
                                                        6 18 10 40 10 22 0 40 -4 40 -10z m-190 -110 c0 -53 -4 -90 -10 -90 -6 0 -10
                                                        37 -10 90 0 53 4 90 10 90 6 0 10 -37 10 -90z m324 41 c7 -55 -2 -131 -15
                                                        -131 -5 0 -9 11 -9 24 0 14 -4 28 -10 31 -5 3 -10 -3 -10 -14 0 -20 -5 -21
                                                        -130 -21 l-130 0 0 70 0 70 130 0 c123 0 130 -1 130 -20 0 -11 5 -20 10 -20 6
                                                        0 10 14 10 30 0 48 17 35 24 -19z m-364 -41 l0 -70 -55 0 -55 0 0 70 0 70 55
                                                        0 55 0 0 -70z m490 0 l0 -70 -55 0 -55 0 0 70 0 70 55 0 55 0 0 -70z'/>
                                                        <path d='M274 531 c-18 -11 -39 -30 -48 -42 -18 -26 -21 -59 -6 -59 6 0 10 7
                                                        10 16 0 27 56 74 88 74 36 0 78 -31 86 -65 4 -14 11 -25 17 -25 15 0 2 50 -19
                                                        74 -19 21 -65 46 -84 46 -7 0 -27 -9 -44 -19z'/>
                                                        <path d='M309 493 c0 -4 0 -27 1 -50 1 -27 -3 -43 -10 -43 -6 0 -10 -12 -8
                                                        -27 2 -22 8 -28 28 -28 20 0 26 6 28 28 2 15 -2 27 -8 27 -7 0 -10 17 -9 47 2
                                                        30 -1 48 -9 51 -7 2 -12 0 -13 -5z m21 -123 c0 -5 -4 -10 -10 -10 -5 0 -10 5
                                                        -10 10 0 6 5 10 10 10 6 0 10 -4 10 -10z'/>
                                                        </g>
                                                        </svg>
                                                    </div>
                                                    <div class='col-12'>
                                                        <h6 class='mt-2 text-params'>Injection Hours</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <div class='d-flex align-items-center'>
                                                        <p class='value-icon' id='inj_icon_{$val->telemetria_id}'><i class='bi bi-arrows me-2 align-items-center mb-1 text-primary value-icon'></i></p>
                                                        <p class='value-parameter align-items-center mb-1' id='h_inyeccion_{$val->telemetria_id}'>{$h_inyeccion}</p>
                                                    </div>
                                                    <small class='value-small'>Hours</small>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <p class='me-2 align-items-center mb-1 text-control'>I. <span class='fw-bold'>Hours:</span></p>
                                                </div>
                                                <div class='row justify-content-center g-2'>
                                                     <input type='text' class='input col-8 w-50 form-control text-center px-0 mx-0' placeholder={$h_inyeccion} readonly>
                                                     <small class='col-4 align-content-center text-control-si'>H</small>
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
                                                    <p class='me-2 align-items-center mb-1 text-control'>SP <span class='fw-bold'>Tmp</span></p>
                                                </div>
                                                <div class='row justify-content-center g-2'>
                                                    <input type='text' id='sp_temp_{$val->telemetria_id}' class='input col-8 w-50 form-control text-center px-0 mx-0' placeholder={$val->set_point} readonly>
                                                    <small class='col-4 align-content-center text-control-si'>°C</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id='row_aperture_{$val->telemetria_id}' hidden>
                                            <td class='col-4 text-center align-content-center'>
                                                <div class='row justify-content-center'>
                                                    <div class='col-12'>
                                                        <svg version='1.0' xmlns='http://www.w3.org/2000/svg'
                                                        width='70' height='70' class='icon-params px-1 btn' ondblclick='apertureModal()' ontouchstart='apertureModal()' viewBox='0 0 93.000000 76.000000'
                                                        preserveAspectRatio='xMidYMid meet'>

                                                        <g transform='translate(0.000000,76.000000) scale(0.100000,-0.100000)'
                                                        fill='#000000' stroke='none'>
                                                        <path d='M287 654 c-4 -4 -7 -45 -7 -91 0 -76 2 -83 20 -83 17 0 20 7 20 53 0
                                                        78 7 83 128 79 l102 -3 0 -222 0 -222 -102 3 c-57 2 -109 5 -115 7 -7 2 -13
                                                        18 -13 35 0 23 -4 30 -20 30 -18 0 -20 -7 -20 -54 0 -30 5 -58 11 -61 5 -4 75
                                                        -9 154 -12 l144 -5 6 23 c3 13 4 137 3 274 l-3 250 -151 3 c-82 1 -153 -1
                                                        -157 -4z'/>
                                                        <path d='M103 527 c-29 -12 -53 -26 -53 -30 0 -5 19 -36 42 -69 l42 -60 12 34
                                                        c6 18 17 32 25 31 8 -1 44 -7 80 -14 60 -11 73 -11 140 9 41 12 90 23 109 24
                                                        27 2 35 7 35 23 0 17 -6 20 -43 18 -24 -1 -67 -10 -95 -19 -62 -21 -140 -21
                                                        -190 -1 -34 15 -35 17 -27 46 11 38 -1 39 -77 8z'/>
                                                        <path d='M620 471 c0 -12 11 -23 31 -30 41 -14 39 -14 39 9 0 13 -10 24 -26
                                                        30 -36 14 -44 13 -44 -9z'/>
                                                        <path d='M280 355 c0 -28 4 -35 20 -35 16 0 20 7 20 35 0 28 -4 35 -20 35 -16
                                                        0 -20 -7 -20 -35z'/>
                                                        <path d='M734 344 c-10 -43 -10 -43 -64 -23 l-50 19 0 -23 c0 -18 9 -26 43
                                                        -39 35 -13 42 -19 42 -42 -1 -14 3 -26 7 -26 13 0 108 50 108 56 0 5 -47 69
                                                        -71 98 -4 5 -11 -4 -15 -20z'/>
                                                        <path d='M388 317 c-78 -22 -105 -21 -205 9 -19 5 -23 2 -23 -14 0 -15 12 -24
                                                        47 -36 59 -20 125 -20 192 0 29 8 72 19 95 22 26 5 41 13 41 22 0 23 -59 21
                                                        -147 -3z'/>
                                                        </g>
                                                        </svg>
                                                    </div>
                                                    <div class='col-12'>
                                                        <h6 class='text-params'>Aperture Level</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <div class='d-flex align-items-center'>
                                                        <p class='value-icon' id='aperture_icon_{$val->telemetria_id}'><i class='bi bi-arrows me-2 align-items-center mb-1 text-primary value-icon'></i></p>
                                                        <p class='value-parameter align-items-center mb-1' id='n_apertura_{$val->telemetria_id}'>{$n_apertura}</p>
                                                    </div>
                                                    <small>%</small>
                                                </div>
                                            </td>
                                            <td class='col-4 text-center align-content-center' id='defrost_prueba_{$val->telemetria_id}'>I: {$i}</td>
                                        </tr>
                                        <tr id='row_compressor_{$val->telemetria_id}' hidden>
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
                                            <td class='col-4 align-content-center'>
                                                <div class='d-flex flex-column align-items-center'>
                                                    <div class='d-flex align-items-center'>
                                                        <p class='value-icon' id='comp_icon_{$val->telemetria_id}'><i class='bi bi-arrows me-2 align-items-center mb-1 text-primary value-icon'></i></p>
                                                        <p class='value-parameter align-items-center mb-1 {$compresor_color}' id='compresor_{$val->telemetria_id}'>{$compresor}</p>
                                                    </div>
                                                    <small>°C</small>
                                                </div>
                                            </td>
                                            <td class='col-4 text-center'>
                                                <div class='row justify-content-center'>
                                                    <div class='col-12'>
                                                        <label for='' class='col-form-label text-control'>Defrost:</label>
                                                    </div>
                                                    <div class='col-12'>
                                                        <button type='button' class='btn btn-block btn-success text-control'>ACTIVE</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan='4' class='text-center'>
                                                <button class='btn btn-primary rounded-circle' onclick='mostrarContenido({$val->telemetria_id})'>
                                                    <i class='ri-arrow-down-circle-line fs-4' id='icon_mostrar_contenido_{$val->telemetria_id}'></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- FIN TABLA-->
                            <div class='col-12' id='btnToSave2_{$val->telemetria_id}' hidden>
                                <button type='button' id='btnSaveData2' class='btn btn-primary col-12' onclick='guardarDatos({$val->telemetria_id})'>SAVE</button>
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