<?php

error_reporting(0);

class inicio
{
    // Metodo para validar cliente
    public function validarCliente(string $cliente, string $usuario, string $pass )   
    {
        if (!$cliente ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        
        if (file_exists('local.txt') || file_exists('local19.txt')) {
            $R = [
                    "Ok" => TRUE
                    ];
          
            return $R;
        }
        
    
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] === FALSE) {

           // Aca va el CURL llamando al servicio , metodo del 252
           // $R = dbExecSP("dbo.sp_validarCliente", [
           //     "cliente" => $cliente
           // ]);

            $url = 'https://crm.dinnersoft.ar/ApiCrm/index.php';
            $curl = curl_init();
            $fields = array(
                'service' => 'crm',
                'method' => 'get_resto',
                'params' => [
                    'nombRest' => $cliente
                ]              
            );
        
            //$access_token = getToken();
            $json_string = json_encode($fields);
            curl_setopt($curl, CURLOPT_URL, $url);     
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
            ]);
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_string);  
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
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
          
            // Deberia Analizar la respuesta y analizar si esta caido para ir a otro Server Auxiliar
            
            if (!$DATA) {
                throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
            } else {
                if (isset($DATA['error'])) {
                    throw new Exception($DATA['error']); // si el SP devuelve un error, se lanza una excepción con el mensaje del error
                } else {
                    // si el SP devuelve un resultado correcto, se devuelve ese resultado
                    // Aca deberia cambiar de base de Datos y validar el usuario con la nueva base de datos
                    
                    // Tomo los datos del cliente y los guardo en la sesión

                    $_SESSION['db_usuario'] = Trim($DATA['db_usuario']);
                    $_SESSION['db_password'] = Trim($DATA['db_password']);
                    $_SESSION['db_nombre'] = Trim($DATA['db_nombre']);
                    $_SESSION['logged'] = TRUE; 
                    $_SESSION['logPrincipal'] = 0; 
                    // Completar con 0 a izquierda el idCliente a 4 dígitos
                    $_SESSION['idCliente'] = 'Rs'.str_pad(Trim($DATA['id']), 4, "0", STR_PAD_LEFT);
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['pass'] = $pass;
                    $_SESSION['mozos'] = $DATA['cantModMozos'];
                    $_SESSION['idResto'] = $DATA['id'];
                    $_SESSION['conMesas'] = $DATA['cantMesas'] ?? 0; 
                    $_SESSION['validado'] = 0;
                    $_SESSION['modMonitor'] = $DATA['cantModCocina'] ?? 0;
                    //echo json_encode($R);
               
                    $R = [
                        "modMozos" => $DATA['cantModMozos'] ?? 0,
                        "conMesas" => $DATA['cantMesas'] ?? 0,
                        "modMonitor" => $DATA['cantModCocina'] ?? 0,
                        "idAbono" => $DATA['idabono'] ?? 0,
                        "username" => $DATA['username'] ?? "",
                        "userclave" => $DATA['userclave'] ?? ""
                        ];
                
                }
                   
            }
               
        } else {
            $R = [
                "error" => "Usuario ya logueado"
            ];
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function logout()
    {
        // Limpiar la sesión actual

        $_SESSION = [];
        // Destruir la sesión para cerrar sesión
        session_destroy();
        
        // Aquí se podría agregar la lógica de cierre de sesión, como invalidar un token o limpiar la sesión
        return [
            "mensaje" => "Logout ok"
        ];
    }

  
}