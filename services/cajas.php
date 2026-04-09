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

     public function cajaTira( int $idCierre )
    {
        if (!$idCierre) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_CajaTira", [
            "idCierre" => $idCierre
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function paramCaja()
    {
        $R = dbExecSP("dbo.spG_ParamCaja", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function entradasSalidas( string $FechaD, string $FechaH, int $PtoVta,  int $idCierre)
    {
        if (!$FechaD || !$FechaH) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha_desde = new DateTime($FechaD);
        $objeto_fecha_hasta = new DateTime($FechaH);

        $R = dbExecSP("dbo.spG_EntradasSalidas", [
            "FechaD" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaH" => $objeto_fecha_hasta->format('Y/m/d'),
            "PtoVta" => $PtoVta,
            "idCierre" => $idCierre            
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function addEntadaSalida( string $Fecha, string $Hora, string $Movimiento, string $Concepto, float $Monto, 
                                      int $idCierre, int $idCuentaGastos, int $PuntoDeVenta, int $idUsuario )
    {
        if (!$Fecha || !$Hora || !$Movimiento || !$Concepto || !$Monto || !$idCuentaGastos || !$PuntoDeVenta || !$idUsuario) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha = new DateTime($Fecha);

        $R = dbExecSP("dbo.spP_EntradaSalida", [
            "Fecha" => $objeto_fecha->format('Y/m/d'),
            "Hora" => $Hora,
            "Movimiento" => $Movimiento,
            "Concepto" => $Concepto,
            "Monto" => $Monto,
            "idCierre" => $idCierre,
            "idCuentaGastos" => $idCuentaGastos,
            "PuntoDeVenta" => $PuntoDeVenta,
            "idUsuario" => $idUsuario
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

}