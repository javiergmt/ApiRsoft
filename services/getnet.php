<?php

error_reporting(0);

class getnet
{
    public function gnTerminales()
    {
       
        $url = 'https://api-uat.ione-tech.com/api/posdata';
        $curl = curl_init();
    
        $access_token = $this->getToken();
        //echo $access_token;
        //$json_string = json_encode($fields);
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'env: UAT',
        'country: AR',
        'app: posintegrado'
        ]);
          
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

    public function gnCrearOrden(string $idTerminal,string $idSucursal, string $serialNumber,
    float $monto)
    {
        // Implement the logic to create an order with MercadoPago
        // This is a placeholder implementation
        $fields = array(
        "idTerminal"=> $idTerminal,
        "idSucursal" => $idSucursal,
        "serialNumber" => $serialNumber,
        "command" => 100,
        "amount" => $monto,
        "tip" => 0,
        "printOnPos" => false,
        "saleType" => 0,
        "employeeId" => 1,
        "installments" => 1,
        "planId" => "contado",
        "interest" => 0,
        "operationMode" => 0,
        "skipConfirmation" => false,
        "skipReceipt" => false,
        "customId" =>  $_SESSION['idResto'],
        "webhook" => "https://webhook.site/0456e5f5-a66b-4c78-b5a6-e414cf1c5fd8"
        );

        $url = 'https://api-uat.ione-tech.com/api/postxs/sale';
        //$idempotencyKey = $this->uuidv4();
        $curl = curl_init();

        $access_token = $this->getToken();
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'env: UAT',
        'country: AR',
        'app: posintegrado']);
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
        if (isset($DATA['data']['idPosTxs'])) {
            //echo "Orden creada con ID: " . $DATA['data']['idPosTxs'];
            //$this->generarOrden($DATA['data']['idPosTxs']);
        }

        return $DATA;
    }

    public function gnConsultarOrdenGn(string $idOrden)
    {
       
        $url = 'https://api-uat.ione-tech.com/api/PosTxs/' . $idOrden;
        $curl = curl_init();
    
        $access_token = $this->getToken();
        //$json_string = json_encode($fields);
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'env: UAT',
        'country: AR',
        'app: posintegrado']);
          
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
         return dbExecSP(getDb() . ".dbo.sp_gnConsultarOrden", [
        "idOrden" => $idOrden
        ]);
    }

    public function gnCancelarOrden(string $idTerminal,string $idSucursal, string $serialNumber)
    {
       
        $url = 'https://api-uat.ione-tech.com/api/postxs/cancelsale';
        $curl = curl_init();
    
        $access_token = $this->getToken();
        $fields = array(
        "idTerminal"=> $idTerminal,
        "idSucursal" => $idSucursal,
        "serialNumber" => $serialNumber,
        "command" => 116,
        "customId" => $_SESSION['idResto']
        );
        //$json_string = json_encode($fields);
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'env: UAT',
        'country: AR',
        'app: posintegrado'
        ]);
        curl_setopt($curl, CURLOPT_POST, TRUE);  
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
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

    public function gnReembolsarOrden(string $idTerminal,string $idSucursal, string $serialNumber,
    string $authorizationCode, string $originalTransDate, float $monto)
    {
        $url = 'https://api-uat.ione-tech.com/api/postxs/return';
        $curl = curl_init();
        $access_token = $this->getToken();
        $fields = array(
        "idTerminal"=> $idTerminal,
        "idSucursal" => $idSucursal,
        "serialNumber" => $serialNumber,
        "command" => 108,
        "authorizationCode" => $authorizationCode,
        "originalTransDate" => $originalTransDate,
        "skipConfirmation" => false,
        "skipReceipt" => false,
        "printOnPos" => false,
        "amount" => $monto,
        "customId" => $_SESSION['idResto']
        );
        //$json_string = json_encode($fields);
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'env: UAT',
        'country: AR',
        'app: posintegrado'
        ]);
        curl_setopt($curl, CURLOPT_POST, TRUE);  
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
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

    public function gnModoStandAlone(string $idTerminal,string $idSucursal, string $serialNumber)
    {
        
        $url = 'https://api-uat.ione-tech.com/api/postxs/normalmode';
        $curl = curl_init();
        $access_token = $this->getToken();
        $fields = array(
        "idTerminal"=> $idTerminal,
        "idSucursal" => $idSucursal,
        "serialNumber" => $serialNumber,
        "command" => 107,
        "customId" => $_SESSION['idResto']
        );
        //$json_string = json_encode($fields);
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
        'env: UAT',
        'country: AR',
        'app: posintegrado'
        ]);
        curl_setopt($curl, CURLOPT_POST, TRUE);  
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
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
        // Obtengo el clientId y clientSecret desde el archivo de configuración
        /*
        $archivo = DIRNAME(__FILE__) . '/../getnet.env';
        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lineas === false) {
            throw new Exception("No se pudo leer el archivo de configuración de Getnet");
        }
        $clientId = trim($lineas[0] ?? '');
        $clientSecret = trim($lineas[1] ?? '');
        if ($clientId === '' || $clientSecret === '') {
            throw new Exception("Faltan clientId o clientSecret en la configuración de Getnet");
        }

        */
        $clientId = 'ove53p6MQJlWo+vHK8holoutWf05zljGp5f+OmwH';
        $clientSecret = 'U3WDU+zrailf0jXRIm9wWwVA1HD3tOyps5jCRUfr';
        // TODO: reemplazar por la ruta real del endpoint de token de Getnet
        $url = 'https://api-uat.ione-tech.com/api/postxs/auth';

        $fields = array(
            "clientId" => $clientId,
            "clientSecret" => $clientSecret
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'env: UAT',
        'country: AR',
        'app: posintegrado'
        ]);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $R = curl_exec($curl);
        if(curl_errno($curl))
        {
            throw new Exception("Error al enviar el mensaje con CURL". " - " . curl_error($curl));
        }
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if (!$DATA = @json_decode($R, TRUE)) {
            throw new Exception("Imposible obtener datos de respuesta");
        }
 
        $token = $DATA['data']['token'] ?? null;
        if (!$token) {
            throw new Exception("Getnet no devolvió el token (HTTP " . $httpCode . ")");
        }

        return $token;
    }

    private function generarOrden(string $idOrden)
    {
        // Marcar la sesión como logPrincipal para indicar que el proximo dbConnet() es a la base de datos principal
        return dbExecSP(getDb() . ".dbo.sp_gnGrabarOrden", [
        "idResto" => $_SESSION['idResto'],
        "idOrden" => $idOrden
        ]);

    }
} 