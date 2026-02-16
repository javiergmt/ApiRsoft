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

    public function getTabla(string $tabla)
    {
        if (!$tabla) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_TablaGen", [
             "tableName" => $tabla
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function actualizarCampo(string $tabla, string $campo, string $valor, 
                    string $campoCondicion, string $valorCondicion, int $borrar)
    {
        if (!$tabla || !$campo || !$valor) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

       if ($campoCondicion <> "NULL" && $valorCondicion <> "NULL") {
             $R = dbExecSP("dbo.spP_ActualizarCampo", [
            "Tabla" => $tabla,
            "Campo" => $campo,
            "Valor" => $valor,
            "CampoCondicion" => $campoCondicion,
            "ValorCondicion" => $valorCondicion,
            "Borrar" => $borrar
            ]);
         } else {
                 $R = dbExecSP("dbo.spP_ActualizarCampo", [
                "Tabla" => $tabla,
                "Campo" => $campo,
                "Valor" => $valor
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
    //------------------------------------------------------------------------------------------------------

}
?>
