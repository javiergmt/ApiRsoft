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
            $idCliente = trim($_SESSION['db_nombre']);
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

    public function exportarCaja(int $idCierre)
    {
        $R = dbExecSP("dbo.spG_Empresa", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos de la empresa"); // si el SP no devuelve nada, se lanza una excepción generica
            return;
        }

        $idPagWeb = $R[0]['idPagWeb'];

         $R = dbExecSP("dbo.spG_TablaGen", [
                "tableName" => 'PARAMETROS_PEDWEB'
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos de parametros de la web"); // si el SP no devuelve nada, se lanza una excepción generica
            return;
        }

        // Datos de Parametros de la web
        $nombreWeb = $R[0]['nubeNombreClave'];
        $urlWeb =Trim($R[0]['nubeUrl']).'/act_datos.php';
        
        $R = dbExecSP("dbo.spG_CierreDatos", [
            "idCierre" => $idCierre
        ],TRUE);

        $fecha = $R[0]['Fecha'];
        $objeto_fecha = new DateTime($fecha);
        //$fecha = '31/10/2026';
        $hora = $R[0]['Hora'];
        $ptoVta = $R[0]['PtoVta'];
        $nombCierre = $R[0]['Turno'];
        
        if (!$R) {
            throw new Exception("Sin datos del cierre"); // si el SP no devuelve nada, se lanza una excepción generica
            return;
        }
        $R = dbExecSP("dbo.spG_CierreTotales", [
            "idCierre" => $idCierre
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos del cierre totales"); // si el SP no devuelve nada, se lanza una excepción generica
            return;
        }

        $C = dbExecSP("dbo.spG_CierreCubiertos", [
            "idCierre" => $idCierre
        ],TRUE);

        $D = dbExecSP("dbo.spG_CierreDescuentos", [
            "idCierre" => $idCierre
        ],TRUE);

        $E = dbExecSP("dbo.spG_CierreRengElim", [
            "idCierre" => $idCierre,
            "ptovta" => $ptoVta
        ],TRUE);

        $T = dbExecSP("dbo.spG_CajaResumen", [
            'fecha' => $fecha,
            "idCierre" => $idCierre,
            "ptovta" => $ptoVta,
            'todo' => 1
        ],TRUE);
        if (!$T) {
           $efectivoimp = 0;
           $tarjetaimp = 0;
           $mercadopagoimp = 0;
           $otrosimp = 0;
        } else {
            //Recorro $T para obtener los importes de cada forma de pago
            for ($i = 0; $i < count($T); $i++) {
                $pago = $T[$i];
                switch ($pago['idFormaDePago']) {
                    case 1:
                        $efectivoimp = $pago['Importe'];
                        break;
                    case 3:
                        $tarjetaimp = $pago['Importe'];
                        break;
                    case 5:
                        $mercadopagoimp = $pago['Importe'];
                        break;
                    case 9:
                        $otrosimp = $pago['Importe'];
                        break;
                }
            }
        }

        // Armar un array con los totales 
        $totales = [
            "nombreclave" => $nombreWeb,
            "idresto" => $idPagWeb,
            "idsuc" => $ptoVta,
            "idcierre" => $idCierre,
            "fecha" => $objeto_fecha->format('d/m/Y'),
            "hora" => $hora,            
            "nombcierre" => $nombCierre,
            "totxmesas" => $R['0']['totxmesas'],
            "totfmesas" => $R['0']['totfmesas'],
            'cantxmesas' => $R['0']['cantxmesas'],
            'cantfmesas' => $R['0']['cantfmesas'],
            'totxmost' => $R['0']['totxmos'],
            'totfmost' => $R['0']['totfmos'],
            'cantxmost' => $R['0']['cantxmos'],
            'cantfmost' => $R['0']['cantfmos'],
            'totxdel' => $R['0']['totxdel'],
            'totfdel' => $R['0']['totfdel'],
            'cantxdel' => $R['0']['cantxdel'],
            'cantfdel' => $R['0']['cantfdel'],
            'cantxpers' => $R['0']['cantxpers'],
            'cantfpers' => $R['0']['cantfpers'],
            'cubxcant' => $C ? $C['0']['cubxcant'] : 0,
            'cubfcant' => $C ? $C['0']['cubfcant'] : 0,
            'cubximp' => $C ? $C['0']['cubximp'] : 0,
            'cubfimp' => $C ? $C['0']['cubfimp'] : 0,
            'descx' => $D ? $D['0']['descx'] : 0,
            'descf' => $D ? $D['0']['descf'] : 0,
            'cantreng' => $E ? $E['0']['cantreng'] : 0,
            'importereng' => $E ? $E['0']['Importereng'] : 0,
            
            'efectivoimp' => $efectivoimp,
            'efectivocant' => 0,
            'tarjetaimp' => $tarjetaimp,
            'tarjetacant' => 0,
            'mercadopagoimp' => $mercadopagoimp,
            'mercadopagocant' => 0,
            'otrosvaloresimp' => $otrosimp,
            'otrosvalorescant' => 0        

        ];  

        $url = $urlWeb;
       
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($totales));  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $R = curl_exec($curl);
        if(curl_errno($curl))
        {
            throw new Exception("Error al enviar el mensaje con CURL". " - " . curl_error($curl));
        }
        echo $R;
        curl_close($curl);
        if (!$DATA = @json_decode($R, TRUE)) {
            throw new Exception("Imposible obtener datos de respuesta");
        }
        

        return $DATA;
    }

}