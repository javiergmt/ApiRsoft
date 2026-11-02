<?php

error_reporting(0);

class reservas
{
    public function reservasTurnos()
    {

        $R = dbExecSP("dbo.spG_ReservasTurnos", [
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function reservas( string $Fecha, string $FechaHasta ,int $idTurno)
    {
        if (!$Fecha || !$FechaHasta || !$idTurno) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha = new DateTime($Fecha);
        $objeto_fecha_hasta = new DateTime($FechaHasta);
        
        $R = dbExecSP("dbo.spG_Reservas", [
            "fecha" => $objeto_fecha->format('Y/m/d'),
            "fechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "idturno" => $idTurno
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
}
?>