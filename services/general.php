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


    public function usuariosPass( string $pass)
    {
        if (!$pass) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
    
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

    //------------------------------------------------------------------------------------------------------

}
?>
