<?php
// webhook_order.php

// Ruta donde guardaremos los order IDs procesados para idempotencia (simple)
define('PROCESSED_FILE', __DIR__ . '/ordenes_procesadas.txt');

// Función para enviar respuesta JSON y terminar
function respond(int $code, $body = []) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($body);
    exit;
}

// Leer raw POST body
$raw = file_get_contents('php://input');
if ($raw === false || strlen(trim($raw)) === 0) {
    respond(400, ['error' => 'No body received']);
}

// Intentar decodificar JSON
$data = json_decode($raw, true);
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    respond(400, ['error' => 'Invalid JSON', 'json_error' => json_last_error_msg()]);
}

// Extraer campos del payload del webhook
$orderId = $data['data']['id'] ?? null;
$externalRef = $data['data']['external_reference'] ?? null;
$status = $data['data']['status'] ?? null;
$totalPaid = $data['data']['total_paid_amount'] ?? null;
$paymentId = $data['data']['transactions']['payments'][0]['id'] ?? null;

// Validaciones mínimas
if (!$orderId) {
    respond(400, ['error' => 'Missing order id']);
}

// Idempotencia simple: chequear archivo
$processed = [];
if (file_exists(PROCESSED_FILE)) {
    $lines = file(PROCESSED_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines !== false) $processed = $lines;
}

// Si ya procesado, devolvemos 200 sin reprocesar
if (in_array($orderId, $processed, true)) {
    // Opcional: log
    error_log("Webhook recibido pero ya procesado: $orderId");
    respond(200, ['status' => 'already_processed', 'order_id' => $orderId]);
}


// Ejemplo simple de log:
// Solo Grabar el ultimo webhook recibido   
$logLine = sprintf("[%s] Processed order %s (ext_ref=%s) status=%s total=%s payment=%s\n",
    date('c'), $orderId, $externalRef ?? '-', $status ?? '-', $totalPaid ?? '-', $paymentId ?? '-');
file_put_contents('webhook.log', $logLine); 

// Marcar como procesado (append al archivo)
//file_put_contents(PROCESSED_FILE, $orderId . PHP_EOL, FILE_APPEND | LOCK_EX);

// Responder OK al proveedor del webhook
ActualizarOrden($orderId, $status);
respond(200, ['status' => 'processed', 'order_id' => $orderId]);

function ActualizarOrden(string $id,string $state)
{
    $host =  getHost();
    $db = "RsoftClientes";
    $usuario = "RsUsr0001";
    $password = "rest0.s0ft";
    //echo "Conectando a la base de datos en el host: " . $host . "\n";
    
    $DB = new PDO("sqlsrv:Server=".$host.";MultipleActiveResultSets=0;TrustServerCertificate=1;Database=".$db, $usuario, $password, array(
        PDO::SQLSRV_ATTR_DIRECT_QUERY => TRUE,
        PDO::ATTR_EMULATE_PREPARES => TRUE,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        //PDO::ATTR_STRINGIFY_FETCHES => FALSE,
        PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => TRUE,
        PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8
    ));

    $sql = "Update Ordenes Set estado = :state Where idOrden = :id";
    $stmt = $DB->prepare($sql);
    $stmt->execute(["state" => $state, "id" => $id]);

    //echo "Orden actualizada: $id con estado: $state\n";
    return true;
}

function getHost() {
    // Leo el host desde un archivo de texto
    $host = trim(file_get_contents(DIRNAME(__FILE__) . '/libs/host.txt'));
    return $host ?: "localhost";
}


