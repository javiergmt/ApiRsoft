 <?php

error_reporting(0);

class cajas
{
    public function cajaResumen( string $Fecha, int $PtoVta)
    {
        if (!$Fecha || !$PtoVta) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha = new DateTime($Fecha);

        $R = dbExecSP("dbo.spG_CajaResumen", [
            "Fecha" => $objeto_fecha->format('Y/m/d'),
            "PtoVta" => $PtoVta           
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function cajaDetalle( string $Fecha, int $PtoVta, int $idForma)
    {
        if (!$Fecha || !$PtoVta || !$idForma) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha = new DateTime($Fecha);

        $R = dbExecSP("dbo.spG_CajaDetalle", [
            "Fecha" => $objeto_fecha->format('Y/m/d'),
            "PtoVta" => $PtoVta,
            "idForma" => $idForma
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function cajaCerrar( string $Fecha, int $PtoVta, int $idTurno, int $idClima,
                                int $idUsuario, int $idUsuarioResp, string $Obs)
    {
        if (!$Fecha || !$PtoVta || !$idTurno || !$idClima || !$idUsuario || !$idUsuarioResp) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha = new DateTime($Fecha);

        $R = dbExecSP("dbo.spP_CajaCerrar", [
            "Fecha" => $objeto_fecha->format('Y/m/d'),
            "PtoVta" => $PtoVta,
            "idTurno" => $idTurno,
            "idClima" => $idClima,
            "idUsuario" => $idUsuario,
            "idUsuarioResp" => $idUsuarioResp,
            "Obs" => $Obs
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function cajaAnularCierre( int $idCierre )
    {
        if (!$idCierre) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_CajaAnularCierre", [
            "idCierre" => $idCierre
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
}