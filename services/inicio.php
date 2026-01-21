<?php

error_reporting(0);

class inicio
{
    // Metodo para validar cliente
    public function validarCliente(string $cliente, string $usuario, string $pass )   
    {
        if (!$cliente and strlen($cliente) < 50) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
    
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] === FALSE) {
            $R = dbExecSP("dbo.sp_validarCliente", [
                "cliente" => $cliente
            ]);

            if (!$R) {
                throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
            } else {
                if (isset($R['error'])) {
                    throw new Exception($R['error']); // si el SP devuelve un error, se lanza una excepción con el mensaje del error
                } else {
                    // si el SP devuelve un resultado correcto, se devuelve ese resultado
                    // Tomo los datos del cliente y los guardo en la sesión
                $_SESSION['db_usuario'] = Trim($R['db_usuario']);
                $_SESSION['db_password'] = Trim($R['db_password']);
                $_SESSION['db_nombre'] = Trim($R['db_nombre']);
                $_SESSION['logged'] = TRUE; 
                // Completar con 0 a izquierda el idCliente a 4 dígitos
                $_SESSION['idCliente'] = 'Rs'.str_pad(Trim($R['idCliente']), 4, "0", STR_PAD_LEFT);
                $_SESSION['usuario'] = $usuario;
                $_SESSION['pass'] = $pass;

                
                $R = [
                    "Ok" => TRUE
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