 <?php

error_reporting(0);

class facturas
{
    public function facturaCrear( int $idPedido, int $NroMesa, bool $PagoEnMesa, int $fiscal, int $idRepartidor,
    int $idObsDesc, int $idUsuario, int $idCliente, float $Total, int $TipoDesc, float $ImpDesc,
    string $nombreClie, string $cuitClie, string $idIva)
    {
        if (!$NroMesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_FacturaCrear", [
            "idPedido" => $idPedido,
            "nromesa" => $NroMesa,
            "pagoenmesa" => $PagoEnMesa,
            "fiscal" => $fiscal,
            "idRepartidor" => $idRepartidor,
            "idObsDesc" => $idObsDesc,
            "idUsuario" => $idUsuario,
            "idCliente" => $idCliente,
            "total" => $Total,
            "tipodesc" => $TipoDesc,
            "impdesc" => $ImpDesc,
            "nombreClie" => $nombreClie,
            "cuitClie" => $cuitClie,
            "ivaClie" => $idIva
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function facturaPagar( string $Nro, float $Importe,int $idCliente, int $idFormaPago, 
    int $idCupon, int $idMoneda, int $ImporteMoneda, float $Cotizacion, int $Billetes)
    {
        if (!$Nro) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_FacturaPagar", [
            "nro" => $Nro,
            "importe" => $Importe,
            "idCliente" => $idCliente,
            "idFormaPago" => $idFormaPago,
            "idCupon" => $idCupon,
            "idMoneda" => $idMoneda,
            "importeMoneda" => $ImporteMoneda,
            "cotizacion" => $Cotizacion,
            "billetes" => $Billetes
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }


    public function formasDePago()
    {
        $R = dbExecSP("dbo.spG_FormasDePago", [], TRUE);

        if (!$R) {
        throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;

    }

    public function facturaPagarMulti(string $nro, float $importe, int $idCliente, array $detallePago)
    {
        //if (!$det) {
        //    throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        //}

        $det = json_encode(array('detallePago' => $detallePago), true);
        //echo var_dump($det);

        $R = dbExecSP("dbo.spP_FacturaPagarMulti", [
            // convertir Json a string para pasarlo al SP
            "nro" => $nro,
            "importe" => $importe,
            "idCliente" => $idCliente,
            "Det" => $det
            
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function comprobantes( string $FechaD, string $FechaH, string $TiposComp, 
                                  string $VerAnul, int $PtoVta, int $Todo)
    {
        if (!$FechaD || !$FechaH) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha_desde = new DateTime($FechaD);
        $objeto_fecha_hasta = new DateTime($FechaH);

        $R = dbExecSP("dbo.spG_Comprobantes", [
            "FechaD" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaH" => $objeto_fecha_hasta->format('Y/m/d'),
            "TiposComp" => $TiposComp,
            "VerAnul" => $VerAnul,
            "PtoVta" => $PtoVta,
            "Todo" => $Todo            
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function detalleComprobante( string $Tipo, string $Nro)
    {
        if (!$Tipo || !$Nro) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_detalleComprobante", [
            "Tipo" => $Tipo,
            "Nro" => $Nro         
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function borrarComprobante( string $Tipo, string $Nro, string $Motivo, int $idUsuario)
    {
        if (!$Tipo || !$Nro || !$Motivo) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_borrarComprobante", [
            "Tipo" => $Tipo,
            "Nro" => $Nro,
            "Motivo" => $Motivo,
            "idUsuarioElim" => $idUsuario         
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

     public function facturaManual(int $NroMesa, float $importe, int $idMozo, int $idUsuario, string $tipo, string $detalle="",
                             int $idCliente,  string $nombreClie ="", string $cuitClie = "00-00000000-0",
                             int $idObsDesc=0, int $tipoDesc , float $impDesc=0.0, float $propina=0.0,
                             int $fiscal=0, string $caeNro="", string $caeVto="", string $Nro="", string $NroCompAsoc="")
    { 
        if (!isset($NroMesa) ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_caeVto = new DateTime($caeVto);

        $R = dbExecSP("dbo.spP_FacturaManual", [
            "nmesa" => $NroMesa,
            "importe" => $importe,
            "idMozo" => $idMozo,
            "idUsuario" => $idUsuario,
            "tipo" => $tipo,
            "detalle" => $detalle,
            "idCliente" => $idCliente,
            "nombreClie" => $nombreClie,
            "cuitClie" => $cuitClie,
            "idObsDesc" => $idObsDesc,
            "tipoDesc" => $tipoDesc,
            "impDesc" => $impDesc,
            "propina" => $propina,
            "fiscal" => $fiscal,
            "caeNro" => $caeNro,
            "caeVto" => $objeto_caeVto->format('Y/m/d'),
            "Nro" => $Nro,
            "NroCompAsoc" => $NroCompAsoc
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
   
}