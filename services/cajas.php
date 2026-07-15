 <?php

error_reporting(0);

class cajas
{
    public function cajaResumen( string $Fecha, int $PtoVta, int $todo, int $idCierre)
    {
        if (!$Fecha || !$PtoVta) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha = new DateTime($Fecha);

        $R = dbExecSP("dbo.spG_CajaResumen", [
            "Fecha" => $objeto_fecha->format('Y/m/d'),
            "PtoVta" => $PtoVta,
            "todo" => $todo,
            "idCierre" => $idCierre           
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function cajaDetalle( string $Fecha, int $PtoVta, int $idForma, int $idCierre, int $todo)
    {
        if (!$Fecha || !$PtoVta || !$idForma) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha = new DateTime($Fecha);

        $R = dbExecSP("dbo.spG_CajaDetalle", [
            "Fecha" => $objeto_fecha->format('Y/m/d'),
            "PtoVta" => $PtoVta,
            "idForma" => $idForma,
            "idCierre" => $idCierre,
            "todo" => $todo
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function cajaCerrar( string $Fecha, int $PtoVta, int $idTurno, int $idClima,
                                int $idUsuario, int $idUsuarioResp, string $Obs)
    {
        if (!$Fecha || !$PtoVta || !$idTurno || !$idClima || !$idUsuario || !$idUsuarioResp) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha = new DateTime($Fecha);

        $R = dbExecSP("dbo.spP_CajaCerrar", [
            "Fecha" => $objeto_fecha->format('Y/m/d'),
            "PtoVta" => $PtoVta,
            "idTurno" => $idTurno,
            "idClima" => $idClima,
            "idUsuario" => $idUsuario,
            "idUsuarioResp" => $idUsuarioResp,
            "Obs" => $Obs
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function cajaAnularCierre( int $idCierre )
    {
        if (!$idCierre) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_CajaAnularCierre", [
            "idCierre" => $idCierre
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

     public function cajaTira( int $idCierre, string $email = '' )
    {
        if (!$idCierre) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_CajaTira", [
            "idCierre" => $idCierre,
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        if ($email == '') {
            return $R;
        } else {
            // Armar PDF y Enviar por mail 
            // Procesar $R para generar PDF y enviarlo por correo electrónic
           

            if (!is_array($R)  ) {
                throw new Exception("JSON inválido o sin 'data'");
            } else{
                //echo print_r($R, true);
                $this->getPdf($R, $idCierre, $email);
            } 
        }
        
    }

    private function getPdf(array $data,string $idCierre,string $email)
    {
        $pdf = new FPDF( 'P', 'mm', 'A4' );
        $pdf->SetAutoPagebreak(False);
        $pdf->SetMargins(0,0,0);
        $pdf->AddPage();
        $x = 10;
        $y = 10;
        $largo = 70;
    
        $pdf->SetXY($x, $y); $pdf->SetFont("Arial", "B", 12); $pdf->Cell(66, 8, 'Caja Nro ' . $idCierre, 0, 0, 'L');
        $y= $y + 8; 
        $pdf->SetFont('Arial','',12);         
        $pdf->Line($x, $y, $x+$largo, $y);

        foreach ($data as $item) {
            
            $detalle = isset($item['Detalle']) ? $item['Detalle'] : '';
            $importeRaw = $item['Importe'] ?? '';
            // tomar todo el string menos las ultimas 2 posiciones
            $importeRaw = substr($importeRaw, 0, -2);
            // Rellenar con espacios a la izquierda hasta que tenga 10 caracteres
            $importeRaw = str_pad($importeRaw, 13, " ", STR_PAD_LEFT);


            // Normalizar Importe: mantener null o convertir a float
            if (Trim($detalle) !== '') {
                if ($detalle === '--') {
                    $y= $y + 6;
                    $pdf->Line($x, $y, $x+$largo, $y);
                } else {    
                    $y= $y + 5;
                    $pdf->SetXY($x, $y); $pdf->SetFont("Arial", "B", 12); $pdf->Cell(66, 8, $detalle , 0, 0, 'L');
               
                    if($importeRaw !== null && Trim($importeRaw) !== '' ) {
                        $pdf->SetXY($x+80, $y); $pdf->SetFont("Arial", "B", 12); $pdf->Cell(66, 8, ' $ ', 0, 0, 'L');
                        $pdf->SetXY($x+80, $y); $pdf->SetFont("Arial", "B", 12); $pdf->Cell(30, 8,  $importeRaw, 0, 0, 'R');
                    }
                }
            }
        }

        if (file_exists('local.txt') or file_exists('local19.txt')) {
            $idCliente = 'Rs0001';
        } else {
            $idCliente = trim($_SESSION['idCliente']);
        }
        $salida = 'cierres/'.$idCliente.'/Cierre_'.$idCierre.'.pdf';
        //echo $salida;
        $pdf->Output('F',$salida);
        $pdfFilePath = $salida;


        // Enviar correo electrónico con PHPMailer
      
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        // admin@restosoft.com.ar
        // Rest0.s0ft

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'mail.restosoft.com.ar'; // servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'admin@restosoft.com.ar'; // usuario SMTP
            $mail->Password = 'Rest0.s0ft'; // contraseña SMTP
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465; // O el puerto de servidor SMTP

            // Destinatarios
            $mail->setFrom('admin@restosoft.com.ar', 'RestoSoft Administracion');
            $mail->addAddress($email);
            $mail->AddAttachment($pdfFilePath);
            //$mail->addCC('otro_remitente@example.com');

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Cierre de Caja Nro ' . $idCierre;
            $mail->Body    = 'Se adjunta el cierre de caja Nro ' . $idCierre . ' en formato PDF.';

            $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
            );

            $mail->send();
            //echo 'El mensaje se ha enviado';
            return true;
        } catch (Exception $e) {
            //echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
            return false;
        }
        
    }   

    public function paramCaja()
    {
        $R = dbExecSP("dbo.spG_ParamCaja", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function entradasSalidas( string $FechaD, string $FechaH, int $PtoVta,  int $idCierre)
    {
        if (!$FechaD || !$FechaH) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha_desde = new DateTime($FechaD);
        $objeto_fecha_hasta = new DateTime($FechaH);

        $R = dbExecSP("dbo.spG_EntradasSalidas", [
            "FechaD" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaH" => $objeto_fecha_hasta->format('Y/m/d'),
            "PtoVta" => $PtoVta,
            "idCierre" => $idCierre            
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function addEntadaSalida( string $Fecha, string $Hora, string $Movimiento, string $Concepto, float $Monto, 
                                      int $idCierre, int $idCuentaGastos, int $PuntoDeVenta, int $idUsuario )
    {
        if (!$Fecha || !$Hora || !$Movimiento || !$Concepto || !$Monto || !$idCuentaGastos || !$PuntoDeVenta || !$idUsuario) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha = new DateTime($Fecha);

        $R = dbExecSP("dbo.spP_EntradaSalida", [
            "Fecha" => $objeto_fecha->format('Y/m/d'),
            "Hora" => $Hora,
            "Movimiento" => $Movimiento,
            "Concepto" => $Concepto,
            "Monto" => $Monto,
            "idCierre" => $idCierre,
            "idCuentaGastos" => $idCuentaGastos,
            "PuntoDeVenta" => $PuntoDeVenta,
            "idUsuario" => $idUsuario
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

}