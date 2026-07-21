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


    public function usuariosPass(string $pass)
    {
        
        $R = dbExecSP("dbo.spG_usuariosPass", [
            "pass" => $pass
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }
        
        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mozosPass(string $pass)
    {
        if (!file_exists('local.txt') && !file_exists('local19.txt')) {
            if (!isset($_SESSION['mozos']) || $_SESSION['mozos'] !== TRUE) {
                throw new Exception("Acceso denegado"); // si el usuario no tiene permisos de mozo, se lanza una excepción
            }
        }
        
        $R = dbExecSP("dbo.spG_mozosPass", [
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

     public function paramAcept()
    {
        $R = dbExecSP("dbo.spG_ParamAcept", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function getTabla(string $tabla, string $campoCondicion = "NULL", string $valorCondicion = "NULL" )
    {
        if (!$tabla) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        if ($campoCondicion <> "NULL" && $valorCondicion <> "NULL") {
            $R = dbExecSP("dbo.spG_TablaGen", [
                "tableName" => $tabla,
                "CampoCondicion" => $campoCondicion,
                "ValorCondicion" => $valorCondicion
            ],TRUE);
        } else {    
            $R = dbExecSP("dbo.spG_TablaGen", [
                "tableName" => $tabla
            ],TRUE);
        }

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function actualizarCampo(string $tabla, string $campo, string $valor, 
                    string $campoCondicion, string $valorCondicion, 
                    string $campoCondicion2, string $valorCondicion2,
                    string $campoCondicion3, string $valorCondicion3,int $borrar)
    {
        if (!$tabla || !$campo ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        if ($campoCondicion <> "NULL" && $valorCondicion <> "NULL") {
            if ($campoCondicion2 <> "NULL" && $valorCondicion2 <> "NULL") {
                if ($campoCondicion3 <> "NULL" && $valorCondicion3 <> "NULL") {
                    $R = dbExecSP("dbo.spP_ActualizarCampo", [
                    "Tabla" => $tabla,
                    "Campo" => $campo,
                    "Valor" => $valor,
                    "CampoCondicion" => $campoCondicion,
                    "ValorCondicion" => $valorCondicion,
                    "CampoCondicion2" => $campoCondicion2,
                    "ValorCondicion2" => $valorCondicion2,
                    "CampoCondicion3" => $campoCondicion3,
                    "ValorCondicion3" => $valorCondicion3,
                    "Borrar" => $borrar
                ]);
                } else {
                     $R = dbExecSP("dbo.spP_ActualizarCampo", [
                    "Tabla" => $tabla,
                    "Campo" => $campo,
                    "Valor" => $valor,
                    "CampoCondicion" => $campoCondicion,
                    "ValorCondicion" => $valorCondicion,
                    "CampoCondicion2" => $campoCondicion2,
                    "ValorCondicion2" => $valorCondicion2,
                    "Borrar" => $borrar
                ]);
                }
            } else {    
                $R = dbExecSP("dbo.spP_ActualizarCampo", [
                "Tabla" => $tabla,
                "Campo" => $campo,
                "Valor" => $valor,
                "CampoCondicion" => $campoCondicion,
                "ValorCondicion" => $valorCondicion,
                "Borrar" => $borrar
                ]);
            }    
        } else {
            $R = dbExecSP("dbo.spP_ActualizarCampo", [
                    "Tabla" => $tabla,
                    "Campo" => $campo,
                    "Valor" => $valor,
                    "Borrar" => $borrar
            ]);
        }           
                 
     
        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }
        
        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function actualizarCamposMultiples(string $tabla, array $jsonData, 
                    string $campoCondicion, string $valorCondicion)
    {
        if (!$tabla || !$jsonData) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $jsonData = json_encode(array($jsonData),true);
        $jsonData = substr($jsonData, 1, strlen($jsonData) - 2);
        //echo $jsonData;
        if ($campoCondicion <> "NULL" && $valorCondicion <> "NULL") {
             $R = dbExecSP("dbo.spP_ActualizarCamposMultiples", [
                "Tabla" => $tabla,
                "JsonCampos" => $jsonData,
                "CampoCondicion" => $campoCondicion,
                "ValorCondicion" => $valorCondicion
            ]);
         } else {
                 $R = dbExecSP("dbo.spP_ActualizarCamposMultiples", [
                "Tabla" => $tabla,
                "JsonCampos" => $jsonData
          ]);
        }
     
        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }
        
        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function insertarDesdeJson(string $tabla ,array $jsonData)
    {
        if (!$tabla || !$jsonData) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        // convetir [JsonData] => {"Data":[{"idClima":"5","Descripcion":"Lluvioso"},{"idClima":"6","Descripcion":"Nublado"}]} a [{"idClima":"5","Descripcion":"Lluvioso"},{"idClima":"6","Descripcion":"Nublado"}]
        $jsonData = json_encode(array($jsonData),true);
        $jsonData = substr($jsonData, 1, strlen($jsonData) - 2);
        //echo $jsonData;
        $R = dbExecSP("dbo.spP_InsertarDesdeJson", [
            "Tabla" => $tabla,
            "JsonData" => $jsonData
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

     public function verificarCampo(string $tabla, string $campo, string $valor)
    {
        if (!$tabla || !$campo  ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

      
        $R = dbExecSP("dbo.spP_VerificarCampo", [
                "Tabla" => $tabla,
                "Campo" => $campo,
                "Valor" => $valor
          ],TRUE);
        
     
        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }
        
        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
    //------------------------------------------------------------------------------------------------------

}
?>
