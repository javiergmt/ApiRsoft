<?php

error_reporting(0);

class mpago
{
    public function mpTerminales()
    {
       
        $url = 'https://api.mercadopago.com/terminals/v1/list?limit=50&offset=0';
        $curl = curl_init();
    
        $access_token = $this->getToken();
        //$json_string = json_encode($fields);
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token]);
          
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $R = curl_exec($curl);
        if(curl_errno($curl))
        {
            //echo 'Error Curl : ' . curl_error($curl);
            throw new Exception("Error al enviar el mensaje con CURL". " - " . curl_error($curl));
        }
        curl_close($curl);
        if (!$DATA = @json_decode($R, TRUE)) {
            throw new Exception("Imposible obtener datos de respuesta");
        }
        

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $DATA;
    }

    public function mpCrearOrden(string $idTerminal,string $descripcion,string $monto, string $idempotencyKey)
    {
        // Implement the logic to create an order with MercadoPago
        // This is a placeholder implementation
        $fields = array(
        "type" => "point",
        //"external_reference" => "resto1-1-M12-" .time(),
        "external_reference" => $this->uuidv4(),
        "expiration_time" => "PT5M",
        "description" => $descripcion,
        "transactions" => array(
            "payments" => array(
                array(
                    "amount" => $monto
                )
            )
        ),
        "config" => array(
            "point" => array(
                "terminal_id" => $idTerminal,
                "print_on_terminal" => "no_ticket"
            )
        )/*,
        "integration_data" => array(
            "integrator_id" => "{{mpIntegratorId}}"
        )*/
        );

        $url = 'https://api.mercadopago.com/v1/orders';
        //$idempotencyKey = $this->uuidv4();
        $curl = curl_init();

        $access_token = $this->getToken();
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-Idempotency-Key: ' . $idempotencyKey,
        'Authorization: Bearer ' . $access_token]);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($fields));  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $R = curl_exec($curl);
        if(curl_errno($curl))
        {
            throw new Exception("Error al enviar el mensaje con CURL". " - " . curl_error($curl));
        }
        curl_close($curl);
        if (!$DATA = @json_decode($R, TRUE)) {
            throw new Exception("Imposible obtener datos de respuesta");
        }
        // Grabo la orden para recibir el WebHook
        if (isset($DATA['id'])) {
            $this->generarOrden($DATA['id']);
        }

        return $DATA;
    }

    public function mpConsultarOrdenMp(string $idOrden)
    {
       
        $url = 'https://api.mercadopago.com/v1/orders/' . $idOrden;
        $curl = curl_init();
    
        $access_token = $this->getToken();
        //$json_string = json_encode($fields);
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token]);
          
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $R = curl_exec($curl);
        if(curl_errno($curl))
        {
            throw new Exception("Error al enviar el mensaje con CURL". " - " . curl_error($curl));
        }
        curl_close($curl);
        if (!$DATA = @json_decode($R, TRUE)) {
            throw new Exception("Imposible obtener datos de respuesta");
        }
        
        return $DATA;
    }

    public function mpConsultarOrden(string $idOrden)
    {
         return dbExecSP(getDb() . ".dbo.sp_mpConsultarOrden", [
        "idOrden" => $idOrden
        ]);
    }

    public function mpCancelarOrden(string $idOrden,string $idempotencyKey)
    {
       
        $url = 'https://api.mercadopago.com/v1/orders/' . $idOrden . '/cancel';
        $curl = curl_init();
    
        $access_token = $this->getToken();
        //$json_string = json_encode($fields);
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'X-Idempotency-Key: ' . $idempotencyKey]);
        curl_setopt($curl, CURLOPT_POST, TRUE);  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $R = curl_exec($curl);
        if(curl_errno($curl))
        {
            throw new Exception("Error al enviar el mensaje con CURL". " - " . curl_error($curl));
        }
        curl_close($curl);
        if (!$DATA = @json_decode($R, TRUE)) {
            throw new Exception("Imposible obtener datos de respuesta");
        }
        
        return $DATA;
    }

    public function mpReembolsarOrden(string $idOrden,string $idempotencyKey)
    {
       
        $url = 'https://api.mercadopago.com/v1/orders/' . $idOrden . '/refund';
        $curl = curl_init();
    
        $access_token = $this->getToken();
        //$json_string = json_encode($fields);
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'X-Idempotency-Key: ' . $idempotencyKey]);
        curl_setopt($curl, CURLOPT_POST, TRUE);  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $R = curl_exec($curl);
        if(curl_errno($curl))
        {
            throw new Exception("Error al enviar el mensaje con CURL". " - " . curl_error($curl));
        }
        curl_close($curl);
        if (!$DATA = @json_decode($R, TRUE)) {
            throw new Exception("Imposible obtener datos de respuesta");
        }
        
        return $DATA;
    }

    public function mpCambiarModoTerminal(string $id,string $modo)
    {
        
        $fields = array(
            "terminals" => array(
                array(
                    "id" => $id,
                    "operating_mode" => $modo
                )
            )
        );

        $url = 'https://api.mercadopago.com/terminals/v1/setup';
   
        $curl = curl_init();

        $access_token = $this->getToken();
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token]);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($fields));  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $R = curl_exec($curl);
        if(curl_errno($curl))
        {
            throw new Exception("Error al enviar el mensaje con CURL". " - " . curl_error($curl));
        }
        curl_close($curl);
        if (!$DATA = @json_decode($R, TRUE)) {
            throw new Exception("Imposible obtener datos de respuesta");
        }

        return $DATA;
    }

    private function getToken()
    {
        // Obtengo el Token de acceso de MercadoPago
        // y si los dias a vencer son menor a 5 lo renuevo
        $R = dbExecSP("dbo.spG_MpGetToken", [
        ]); 
        if (!$R) {
             return 'APP_USR-1290352043861328-091710-745be1c17ba1829066cbd78dce5c5a90-3694787869';
        }
        $token = $R['sToken'] ?? null;
        $refresh = $R['sRefresh'] ?? null;
        $vtoToken = $R['vtoToken'] ?? null;
        $fecha_registro = new DateTime($vtoToken);
        $fecha_actual   = new DateTime(); // Toma la fecha de hoy
        $diferencia = $fecha_actual->diff($fecha_registro);
        $dias_pasados = $diferencia->days;
        if ($dias_pasados >= 179) {
            $DATA = $this->refreshToken($refresh);
            $token = $DATA['access_token'] ?? null;
            $R = dbExecSP("dbo.spP_MpGrabar", [
                "token" => $token,
                "refresh" => $DATA['refresh_token'] ?? null
            ]);
        }
        return $token;
    }

    private function refreshToken(string $refresh)
    {
        $archivo = DIRNAME(__FILE__) . '/../mpInt.env';
        $lineas = $this->leerEnv($archivo);
        $clientId = $lineas[0] ?? null;
        $clientSecret = $lineas[1] ?? null;

        // de aca llamaria al metodo para obtener el token de acceso usando el 'code' recibido.


        $url =  'https://api.mercadopago.com/oauth/token';
        $curl = curl_init();

        $fields = array(
                    "client_id" => $clientId,
                    "client_secret" => $clientSecret,
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh
        );
            
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POST, TRUE);  
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($fields));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $R = curl_exec($curl);
        if(curl_errno($curl))
        {
            throw new Exception("Error al enviar el mensaje con CURL". " - " . curl_error($curl));
        }
        curl_close($curl);
        if (!$DATA = @json_decode($R, TRUE)) {
            throw new Exception("Imposible obtener datos de respuesta");
        } else {
           return $DATA;
        }
        
    }   

    function generarLinkMp()
    {
        // Marcar la sesión como logPrincipal para indicar que el proximo dbConnet() es a la base de datos principal
        
        $_SESSION['logPrincipal'] = 1; 
        $clave = $this->uuidv4();    
  
        $archivo = DIRNAME(__FILE__) . '/../mpInt.env';
        $lineas = $this->leerEnv($archivo);
        $clientId = $lineas[0] ?? null;

        $authUrl = "https://auth.mercadopago.com/authorization?client_id=" . $clientId . "&response_type=code&platform_id=mp&state=" . $clave . "&redirect_uri=https://desarapi.on.restosoft.com.ar/ApiRsoft/mPagoCode.php";
        
        $R = dbExecSP("dbo.sp_mpGrabarState", [
            "db" => $_SESSION['db_nombre'],
            "state" => $clave
        ]);

        $_SESSION['logPrincipal'] = 0;    
        return $authUrl;
    }

    function generarOrden(string $idOrden)
    {
        // Marcar la sesión como logPrincipal para indicar que el proximo dbConnet() es a la base de datos principal
        return dbExecSP(getDb() . ".dbo.sp_mpGrabarOrden", [
        "idResto" => $_SESSION['idResto'],
        "idOrden" => $idOrden
        ]);

    }

    private function uuidv4()
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
            
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private function leerEnv($archivo)
    {
        if (file_exists($archivo)) {
            // Lee el archivo y elimina los saltos de línea invisibles (\n o \r)
            $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
             return $lineas;
        } else {
            throw new Exception("El archivo de configuración". $archivo . " no existe.");
        }
    }
}   