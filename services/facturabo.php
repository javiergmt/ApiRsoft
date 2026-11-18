<?php

error_reporting(0);

class facturabo
{

function factura(int $sucursal,int $punto_venta, string $actividad_economica,
int $tipo_documento, string $numero_documento, string $razon_social,
int $metodo_pago, float $descuento_Global, array $items)
{
    $fields = array(
        "emisor" => array(
            "sucursal" => $sucursal,
            "punto_venta" => $punto_venta,
            "actividad_economica" => $actividad_economica
        ),
        "cliente" => array(
            "tipo_documento" => $tipo_documento,
            "numero_documento" => $numero_documento,
            "razon_social" => $razon_social
        ),
        "metodo_pago" => $metodo_pago,
        "descuento_Global" => $descuento_Global,
        "items" => $items,
     
    );

        $url = 'https://apisandbox.factura.bo/v1/facturas';
        //$idempotencyKey = $this->uuidv4();
        $curl = curl_init();

        $api_key = $this->getApiKey();
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        //'X-Idempotency-Key: ' . $idempotencyKey,
        'X-API-KEY: ' . $api_key
        ]);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($fields));  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $R = curl_exec($curl);
        if(curl_errno($curl))
        {
            throw new Exception("Error al enviar el mensaje con CURL". " - " . curl_error($curl));
        }
        $http_code = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $DATA = $this->analizarRespuesta($http_code, $R);

        return $DATA;
}

/**
 * Analiza la respuesta de factura.bo según el código HTTP y arma $DATA.
 * Éxito:  ['ok' => true,  'http' => 200, 'mensaje' => ..., 'data' => <respuesta API>]
 * Error:  ['ok' => false, 'http' => N,   'mensaje' => ..., 'detail' => ..., 'errores' => [...], 'data' => <respuesta API>]
 */
private function analizarRespuesta(int $http_code, $R)
{
    $respuesta = @json_decode($R, TRUE);
    if (!is_array($respuesta)) {
        $respuesta = array();
    }

    if ($http_code == 200) {
        if (empty($respuesta)) {
            throw new Exception("Imposible obtener datos de respuesta");
        }
        return array(
            "ok"      => true,
            "http"    => 200,
            "mensaje" => "Operación exitosa",
            "data"    => $respuesta
        );
    }

    $mensajes = array(
        400 => "Datos inválidos, falta configuración, o el SIAT rechazó el documento",
        402 => "Créditos de facturación agotados",
        403 => "API Key inválida, cliente inactivo, o email en sandbox",
        404 => "Recurso no encontrado / punto de venta no sincronizado",
        409 => "Ya hay una operación en curso con la misma X-Idempotency-Key, o se intentó revertir una anulación sobre una factura que no está ANULADA. Reintente o espere unos segundos",
        502 => "El SIAT no respondió, se cayó a mitad de la operación, o rechazó con un error no HTTP"
    );

    if ($http_code == 0) {
        $mensaje = "Sin respuesta del servidor de facturación";
    } elseif (isset($mensajes[$http_code])) {
        $mensaje = $mensajes[$http_code];
    } else {
        $mensaje = "Error HTTP " . $http_code;
    }

    // "detail" puede venir como string o como array (errores de validación)
    $detail = "";
    if (isset($respuesta["detail"])) {
        $detail = is_string($respuesta["detail"])
            ? $respuesta["detail"]
            : json_encode($respuesta["detail"], JSON_UNESCAPED_UNICODE);
    } elseif (!empty($R) && empty($respuesta)) {
        $detail = trim(strip_tags($R));
    }

    $errores = array();
    if ($http_code == 400 || $http_code == 502) {
        $errores = $this->parsearErroresSiat($detail);
    }

    return array(
        "ok"      => false,
        "http"    => $http_code,
        "mensaje" => $mensaje,
        "detail"  => $detail,
        "errores" => $errores,
        "data"    => $respuesta
    );
}

/**
 * Extrae los pares codigo/descripcion del "detail" del SIAT, ej:
 * "El SIAT rechazó la factura: [{'codigo': 1040, 'descripcion': '...'}]"
 */
private function parsearErroresSiat(string $detail)
{
    $errores = array();
    if ($detail === "") {
        return $errores;
    }

    $patron = '/[\'"]codigo[\'"]\s*:\s*(\d+)\s*,\s*[\'"]descripcion[\'"]\s*:\s*([\'"])(.*?)\2\s*\}/su';
    if (preg_match_all($patron, $detail, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $codigo = (int) $m[1];
            $errores[] = array(
                "codigo"      => $codigo,
                "descripcion" => $m[3],
                "significado" => $this->significadoCodigoSiat($codigo)
            );
        }
    }

    return $errores;
}

private function significadoCodigoSiat(int $codigo)
{
    $codigos = array(
        123  => "CUFD fuera de tolerancia (vencido)",
        981  => "El evento significativo tiene fecha_fin en el futuro, o se solapa con otro ya registrado",
        984  => "El CUFD no corresponde a la ventana del evento declarado",
        902  => "CAFC no encontrado",
        1040 => "La fecha de emisión de la factura offline no se encuentra en el rango de contingencia",
        1047 => "El número de factura excede el rango autorizado por el CAFC",
        954  => "El paquete de contingencia supera las 500 facturas permitidas",
        1002 => "CUF o CUFD del archivo no coincide con lo esperado por el SIAT en el paquete",
        1006 => "CUF o CUFD del archivo no coincide con lo esperado por el SIAT en el paquete",
        1049 => "En una nota de crédito/débito, la línea codigo_detalle_transaccion=1 no replica exacto la cantidad/precio de la factura original"
    );

    return isset($codigos[$codigo]) ? $codigos[$codigo] : "Código SIAT no catalogado";
}

private function getApiKey()
{
    $api_key = 'JFAUIHwbqCLtrzP6bZmPk0S13fvwsbxTm8zPY2XX_DQ';
    return $api_key;
}

}