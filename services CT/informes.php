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

    public function infConsRubDetGral(string $FechaD, string $FechaH, string $Horad, string $Horah, int $todo, 
    int $Ptovta, int $sector, int $turno, string $tipofe, int $Combos)
    {
        if (!$FechaD || !$FechaH  ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaD);
        $objeto_fecha_hasta = new DateTime($FechaH);
        $R = dbExecSP("dbo.spG_ConsRubDet_Gral", [
            "FechaD" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaH" => $objeto_fecha_hasta->format('Y/m/d'),
            "HoraD" => $Horad,
            "HoraH" => $Horah,
            "todo" => $todo,
            "PtoVta" => $Ptovta,
            "sector" => $sector,
            "turno" => $turno,
            "tipoFe" => $tipofe,
            "Combos" => $Combos
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infConsRubDetRub(string $FechaD, string $FechaH, string $Horad, string $Horah, int $todo, 
    int $Ptovta, int $sector, int $turno, string $tipofe, int $Combos, int $CodRub, int $CodSubRub)
    {
        if (!$FechaD || !$FechaH ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaD);
        $objeto_fecha_hasta = new DateTime($FechaH);
        $R = dbExecSP("dbo.spG_ConsRubDet_Rub", [
            "FechaD" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaH" => $objeto_fecha_hasta->format('Y/m/d'),
            "HoraD" => $Horad,
            "HoraH" => $Horah,
            "todo" => $todo,
            "PtoVta" => $Ptovta,
            "sector" => $sector,
            "turno" => $turno,
            "tipoFe" => $tipofe,
            "Combos" => $Combos,
            "CodRub" => $CodRub,
            "CodSubRub" => $CodSubRub
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infConsRubDetRubSub(string $FechaD, string $FechaH, string $Horad, string $Horah, int $todo, 
    int $Ptovta, int $sector, int $turno, string $tipofe, int $Combos, int $CodRub, int $CodSubRub)
    {
        if (!$FechaD || !$FechaH ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaD);
        $objeto_fecha_hasta = new DateTime($FechaH);
        $R = dbExecSP("dbo.spG_ConsRubDet_RubSub", [
            "FechaD" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaH" => $objeto_fecha_hasta->format('Y/m/d'),
            "HoraD" => $Horad,
            "HoraH" => $Horah,
            "todo" => $todo,
            "PtoVta" => $Ptovta,
            "sector" => $sector,
            "turno" => $turno,
            "tipoFe" => $tipofe,
            "Combos" => $Combos,
            "CodRub" => $CodRub,
            "CodSubRub" => $CodSubRub
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infCajaESCtaGral(string $FechaDesde, string $FechaHasta, int $idMoneda)
    {
        if (!$FechaDesde || !$FechaHasta ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaDesde);
        $objeto_fecha_hasta = new DateTime($FechaHasta);
        $R = dbExecSP("dbo.spG_InfCajaESCtaGral", [
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "idMoneda" => $idMoneda
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infCajaESCtaDetalle(string $FechaDesde, string $FechaHasta,int $idCuenta, int $idMoneda)
    {
        if (!$FechaDesde || !$FechaHasta ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaDesde);
        $objeto_fecha_hasta = new DateTime($FechaHasta);
        $R = dbExecSP("dbo.spG_InfCajaESCtaDetalle", [
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "idCuenta" => $idCuenta,
            "idMoneda" => $idMoneda
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infCajaESClasGral(string $FechaDesde, string $FechaHasta, int $idMoneda)
    {
        if (!$FechaDesde || !$FechaHasta ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaDesde);
        $objeto_fecha_hasta = new DateTime($FechaHasta);
        $R = dbExecSP("dbo.spG_InfCajaESClasGral", [
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "idMoneda" => $idMoneda
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infCajaESClasDetalle(string $FechaDesde, string $FechaHasta,int $idRubro, int $idMoneda)
    {
        if (!$FechaDesde || !$FechaHasta ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaDesde);
        $objeto_fecha_hasta = new DateTime($FechaHasta);
        $R = dbExecSP("dbo.spG_InfCajaESClasDetalle", [
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "idRubro" => $idRubro,
            "idMoneda" => $idMoneda
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infVtasFestad(string $FechaD, string $FechaH,int $Todo, int $PtoVta, int $sector)
    {
        if (!$FechaD || !$FechaH ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaD);
        $objeto_fecha_hasta = new DateTime($FechaH);
        $R = dbExecSP("dbo.spG_InfVtasFestad", [
            "FechaD" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaH" => $objeto_fecha_hasta->format('Y/m/d'),
            "Todo" => $Todo,
            "PtoVta" => $PtoVta,
            "sector" => $sector
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infFpagoGral(string $FechaDesde, string $FechaHasta,int $Todo,
     int $PtoVta, int $sector, int $turno)
    {
        if (!$FechaDesde || !$FechaHasta ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaDesde);
        $objeto_fecha_hasta = new DateTime($FechaHasta);
        $R = dbExecSP("dbo.spG_InfFpagoGral", [
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "Todo" => $Todo,
            "PtoVta" => $PtoVta,
            "sector" => $sector,
            "turno" => $turno
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infFpagoDetalle(string $FechaDesde, string $FechaHasta,int $Todo,
     int $PtoVta, int $sector, int $turno, int $idFormadePago)
    {
        if (!$FechaDesde || !$FechaHasta ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaDesde);
        $objeto_fecha_hasta = new DateTime($FechaHasta);
        $R = dbExecSP("dbo.spG_InfFpagoDetalle", [
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "Todo" => $Todo,
            "PtoVta" => $PtoVta,
            "sector" => $sector,
            "turno" => $turno,
            "idFormadePago" => $idFormadePago
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infFpagoTarjetas(string $FechaDesde, string $FechaHasta,int $Todo,
     int $PtoVta, int $sector, int $turno)
    {
        if (!$FechaDesde || !$FechaHasta ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaDesde);
        $objeto_fecha_hasta = new DateTime($FechaHasta);
        $R = dbExecSP("dbo.spG_InfFpagoTarjetas", [
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "Todo" => $Todo,
            "PtoVta" => $PtoVta,
            "sector" => $sector,
            "turno" => $turno
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

     public function infDescuentos(string $FechaDesde, string $FechaHasta,int $Todo,
     int $SinSoloPagos)
    {
        if (!$FechaDesde || !$FechaHasta ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaDesde);
        $objeto_fecha_hasta = new DateTime($FechaHasta);
        $R = dbExecSP("dbo.spG_InfDescuentos", [
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "Todo" => $Todo,
            "SinSoloPagos" => $SinSoloPagos
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
}