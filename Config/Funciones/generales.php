<?php 
//envios de correo
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    //genrar pdf
    use Dompdf\Dompdf;

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