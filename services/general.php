<?php

error_reporting(0);

class general
{
    public function usuarios()
    {
        $R = dbExecSP("dbo.spG_Usuarios", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }


    public function usuariosPass( string $pass)
    {
        if (!$pass) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
    
        $R = dbExecSP("dbo.spG_usuariosPass", [
            "pass" => $pass
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function empresa()
    {
        $R = dbExecSP("dbo.spG_Empresa", [],FALSE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function condicionIva()
    {
        $R = dbExecSP("dbo.spG_CondicionIva", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function obsDesc()
    {
        $R = dbExecSP("dbo.spG_ObsDesc", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }


    //------------------------------------------------------------------------------------------------------

    public function factElect()
    {
        //$afip = new Afip(['CUIT' => 20409378472]);

        // Certificado (Puede estar guardado en archivos, DB, etc)
        //$cert = file_get_contents('c:/open/pausa_3a39b5068770f68f.crt');

        // Key (Puede estar guardado en archivos, DB, etc)
        //$key = file_get_contents('c:/open/PAUSA.key');

        // CUIT del certificado
        //$tax_id = 30714004391;

        /*
        $afip = new Afip([
            'CUIT' => $tax_id,
            'cert' => $cert,
            'key' => $key,
            'production' => true //true para produccion false para homologacion
        ]);

        // Numero de punto de venta
        $punto_de_venta = 1;

        // Tipo de comprobante
        $tipo_de_comprobante = 6; // 6 = Factura B

        $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_comprobante);

        // echo "Ultimo comprobante: " . $last_voucher . "\n";

        // Devolver respuesta completa del web service
        $return_full_response = TRUE;

        // Info del comprobante
        $data = array(
            'CantReg' 	=> 1,  // Cantidad de comprobantes a registrar
            'PtoVta' 	=> 1,  // Punto de venta
            'CbteTipo' 	=> 6,  // Tipo de comprobante (ver tipos disponibles) 
            'Concepto' 	=> 1,  // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
            'DocTipo' 	=> 99, // Tipo de documento del comprador (99 consumidor final, ver tipos disponibles)
            'DocNro' 	=> 0,  // Número de documento del comprador (0 consumidor final)
            'CbteDesde' 	=> $last_voucher+1,  // Número de comprobante o numero del primer comprobante en caso de ser mas de uno
            'CbteHasta' 	=> $last_voucher+1,  // Número de comprobante o numero del último comprobante en caso de ser mas de uno
            'CbteFch' 	=> intval(date('Ymd')), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
            'ImpTotal' 	=> 121, // Importe total del comprobante
            'ImpTotConc' 	=> 0,   // Importe neto no gravado
            'ImpNeto' 	=> 100, // Importe neto gravado
            'ImpOpEx' 	=> 0,   // Importe exento de IVA
            'ImpIVA' 	=> 21,  //Importe total de IVA
            'ImpTrib' 	=> 0,   //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
            'MonCotiz' 	=> 1,     // Cotización de la moneda usada (1 para pesos argentinos)  
            'CondicionIVAReceptorId' => 5, // Condición frente al IVA del receptor  
            'Iva' 		=> array( // (Opcional) Alícuotas asociadas al comprobante
                array(
                    'Id' 		=> 5, // Id del tipo de IVA (5 para 21%)(ver tipos disponibles) 
                    'BaseImp' 	=> 100, // Base imponible
                    'Importe' 	=> 21 // Importe 
                )
            ), 
        );

        $res = $afip->ElectronicBilling->CreateVoucher($data, $return_full_response);
        //$result = (array) json_decode($res);
        //var_dump($result);
        //echo "\n";
        //var_dump($res);
        //$R = json_decode($res, true);
        //$res['CAE']; //CAE asignado el comprobante
        //$res['CAEFchVto']; //Fecha de vencimiento del CAE (yyyy-mm-dd)

        //echo "CAE: " . $res['CAE'] . "\n";
        //echo "Fecha de vencimiento del CAE: " . $res['CAEFchVto'] . "\n";
        
        //if (!$R) {
        //    throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        //}

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $res;
        */
    }
}
?>
