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
}