<?php

error_reporting(0);

class informes
{
    public function infRengEliminados(string $fDesde, string $fHasta, string $tipo,
     int $idCierre, int $ptoVta)
    {
        if (!$fDesde || !$fHasta || !$tipo || !$idCierre || !$ptoVta) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($fDesde);
        $objeto_fecha_hasta = new DateTime($fHasta);
        $R = dbExecSP("dbo.spG_InfRengEliminados", [
            "fDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "fHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "tipo" => $tipo,
            "idCierre" => $idCierre,
            "ptoVta" => $ptoVta
        ],TRUE);   

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infFactEliminados(string $fDesde, string $fHasta, int $idCierre,int $ptoVta)
    {
        if (!$fDesde || !$fHasta || !$idCierre || !$ptoVta) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($fDesde);
        $objeto_fecha_hasta = new DateTime($fHasta);
        $R = dbExecSP("dbo.spG_InfFactEliminados", [
            "fDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "fHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "idCierre" => $idCierre,
            "ptoVta" => $ptoVta
        ],TRUE);   

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

     public function infPedEliminados(string $fDesde, string $fHasta, int $idCierre,
     int $tipo, int $ptoVta)
    {
        if (!$fDesde || !$fHasta || !$idCierre || !$ptoVta || !$tipo) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($fDesde);
        $objeto_fecha_hasta = new DateTime($fHasta);
        $R = dbExecSP("dbo.spG_InfPedEliminados", [
            "fDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "fHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "tipo" => $tipo,
            "idCierre" => $idCierre,
            "ptoVta" => $ptoVta
        ],TRUE);   

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infMesasVaciadas(string $fDesde, string $fHasta, int $idCierre)
    {
        if (!$fDesde || !$fHasta || !$idCierre ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($fDesde);
        $objeto_fecha_hasta = new DateTime($fHasta);
        $R = dbExecSP("dbo.spG_InfMesasVaciadas", [
            "fDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "fHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "idCierre" => $idCierre
        ],TRUE);   

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

     public function infPreciosCambiados(string $fDesde, string $fHasta, int $idCierre,
     int $tipo, int $ptoVta)
    {
        if (!$fDesde || !$fHasta || !$idCierre || !$ptoVta || !$tipo) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($fDesde);
        $objeto_fecha_hasta = new DateTime($fHasta);
        $R = dbExecSP("dbo.spG_InfPreciosCambiados", [
            "fDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "fHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "tipo" => $tipo,
            "idCierre" => $idCierre,
            "ptoVta" => $ptoVta
        ],TRUE);   

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infCajasCerradas(string $fDesde, string $fHasta, int $Todo)
    {
        if (!$fDesde || !$fHasta || !$Todo ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($fDesde);
        $objeto_fecha_hasta = new DateTime($fHasta);
        $R = dbExecSP("dbo.spG_InfCajasCerradas", [
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "Todo" => $Todo
        ],TRUE);   

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

}