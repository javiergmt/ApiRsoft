<?php

$code  = $_GET['code']  ?? null;
$state = $_GET['state'] ?? null;

if (!$code) {
  http_response_code(400);
  exit("Falta el parámetro 'code'");
}
echo "CODE recibido: " . $code. " STATE recibido: " . $state. "<br>";

// Obtengo el clientId y ClientSecret desde el archivo de configuración
$archivo = DIRNAME(__FILE__) . '/mpInt.env';

if (file_exists($archivo)) {
    // Lee el archivo y elimina los saltos de línea invisibles (\n o \r)
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Asigna cada línea a una variable (asumiendo que hay al menos 2 líneas)
    $clientId = $lineas[0] ?? null;
    $clientSecret = $lineas[1] ?? null;

    //echo "Cliente ID: " . $clientId . "<br>";
    //echo "Cliente Secret: " . $clientSecret. "<br>";
} else {
    echo "El archivo no existe.";
    exit("El archivo de configuración no existe.");
}

    
$DATA = obtenerAccessToken($code, $clientId, $clientSecret);
//echo "DATA recibido: " . json_encode($DATA);
if (isset($DATA['access_token'])) {

    $accessToken = $DATA['access_token'] ?? null;
    $refreshToken = $DATA['refresh_token'] ?? null;
            
    $DB = ConectarDb($state);
    
    if (!$DB) {
        throw new Exception("Error al conectar a la base de datos");
    } else {
        
       /*
            $sql = "SELECT * FROM PARAMETROS_MP";
            $stmt = $DB->prepare($sql);

            // 2. Ejecutar pasando los valores reales
            $stmt->execute();

            // 3. Obtener un solo registro
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        */
        try {
            $sql = "EXEC spP_MpGrabar :token, :refresh";
            $stmt = $DB->prepare($sql);
            $stmt->execute(array(
                ':token' => $accessToken,
                ':refresh' => $refreshToken
            ));
        // Como ultimo paso se deberia eliminar el stateMp del la base Principal


        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    
    }    

}
exit;

function obtenerAccessToken(string $code,string $clientId,string $clientSecret) {
    $url =  'https://api.mercadopago.com/oauth/token';
    $curl = curl_init();

    $fields = array(
                "client_id" => $clientId,
                "client_secret" => $clientSecret,
                "code" => $code,
                "grant_type" => "authorization_code",
                "redirect_uri" => "https://desarapi.on.restosoft.com.ar/ApiRsoft/mPagoCode.php",
                "test_token" => "false"
    );

    try {
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
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(["error" => $e->getMessage()]);
        exit;
    }
    

}

function ConectarDb(string $state)
{
    $host =  getHost();
    $db = "RsoftClientes";
    $usuario = "RsUsr0001";
    $password = "rest0.s0ft";
    echo "Conectando a la base de datos en el host: " . $host . "\n";
    
    $DB = new PDO("sqlsrv:Server=".$host.";MultipleActiveResultSets=0;TrustServerCertificate=1;Database=".$db, $usuario, $password, array(
        PDO::SQLSRV_ATTR_DIRECT_QUERY => TRUE,
        PDO::ATTR_EMULATE_PREPARES => TRUE,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        //PDO::ATTR_STRINGIFY_FETCHES => FALSE,
        PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => TRUE,
        PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8
    ));

    $sql = "SELECT db_nombre FROM Clientes Where stateMp = :stateMp";
    $stmt = $DB->prepare($sql);
    $stmt->execute(["stateMp" => $state]);

    // 3. Obtener un solo registro
    $Datos = $stmt->fetch(PDO::FETCH_ASSOC);
    $db = $Datos['db_nombre'];
    //echo json_encode($Datos);
    $DB = new PDO("sqlsrv:Server=".$host.";MultipleActiveResultSets=0;TrustServerCertificate=1;Database=".$db, $usuario, $password, array(
        PDO::SQLSRV_ATTR_DIRECT_QUERY => TRUE,
        PDO::ATTR_EMULATE_PREPARES => TRUE,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        //PDO::ATTR_STRINGIFY_FETCHES => FALSE,
        PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => TRUE,
        PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8
    ));
    return $DB;
}

function getHost() {
    // Leo el host desde un archivo de texto
    $host = trim(file_get_contents(DIRNAME(__FILE__) . '/libs/host.txt'));
    return $host ?: "localhost";
}


