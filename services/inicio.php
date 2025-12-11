<?php

error_reporting(0);

class inicio
{
    // Metodo para validar cliente
    public function validarCliente(string $pass)   
    {
        if (!$pass) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
    
        if (!isset($_SESSION['logged']) || $_SESSION['logged'] === FALSE) {
        $R = dbExecSP("dbo.sp_validarCliente", [
            "pass" => $pass
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