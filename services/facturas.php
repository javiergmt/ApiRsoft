 <?php

error_reporting(0);

class facturas
{
    /*----*/
    public function facturaCrear( int $idPedido, int $NroMesa, bool $PagoEnMesa, int $fiscal, int $idRepartidor,
    int $idObsDesc, int $idUsuario, int $idCliente, float $Total, int $TipoDesc, float $ImpDesc)
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
            "impdesc" => $ImpDesc
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    /*----*/
    public function facturaPagar( string $Nro, float $Importe,int $idCliente, int $idFormaDePago, 
    int $idCupon, int $idMoneda, int $ImporteMoneda, float $Cotizacion, int $Billetes)
    {
        if (!$Nro) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_FacturaPagar", [
            "nro" => $Nro,
            "importe" => $Importe,
            "idCliente" => $idCliente,
            "idFormaDePago" => $idFormaDePago,
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

    /*----*/
    public function clienteCambiar( int $idCliente, string $Nombre, string $Direccion, string $Localidad, string $Tel1,
    string $Tel2, string $Tel3, string $Email, int $idZona, DateTime $FechaNac, int $idIva, string $Cuit, string $Tarj)
    {
        if (!$idCliente) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_ClienteCambiar", [
            "idCliente" => $idCliente,
            "nombre" => $Nombre,
            "direccion" => $Direccion,
            "localidad" => $Localidad,
            "tel1" => $Tel1,
            "tel2" => $Tel2,
            "tel3" => $Tel3,
            "email" => $Email,
            "idZona" => $idZona,
            "fechaNac" => $FechaNac,
            "idIva" => $idIva,
            "cuit" => $Cuit,
            "tarj" => $Tarj
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
}