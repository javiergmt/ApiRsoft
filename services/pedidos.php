 <?php

error_reporting(0);

class pedidos
{
    /*----*/
    public function pedidoNuevo( int $idPedido, DateTime $Fecha, string $Hora, int $idCliente, DateTime $FechaEntrega, string $HoraEntrega,
    float $Subtotal, float $Descuento, float $Total, float $Envio, float $Pago, float $Pagacon, string $Obs,
    int $idRepartidor, string $NombreClie, string $DireccionClie, bool $EnUso, bool $Cobrado, bool $xMostrador,
    int $idUsuario, int $PuntoDeVenta, bool $Delivery, int $tipoDesc, int $descRec)
    {
        if (!$idPedido ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_PedidoNuevo", [
            "idPedido" => $idPedido,
            "Fecha" => $Fecha->format('Y-m-d'),
            "Hora" => $Hora,
            "idCliente" => $idCliente,
            "FechaEntrega" => $FechaEntrega->format('Y-m-d'),
            "HoraEntrega" => $HoraEntrega,
            "Subtotal" => $Subtotal,
            "Descuento" => $Descuento,
            "Total" => $Total,
            "Envio" => $Envio,
            "Pago" => $Pago,
            "Pagacon" => $Pagacon,
            "Obs" => $Obs,
            "idRepartidor" => $idRepartidor,
            "NombreClie" => $NombreClie,
            "DireccionClie" => $DireccionClie,
            "EnUso" => $EnUso,
            "Cobrado" => $Cobrado,
            "xMostrador" => $xMostrador,
            "idUsuario" => $idUsuario,
            "PuntoDeVenta" => $PuntoDeVenta,
            "Delivery" => $Delivery,
            "tipoDesc" => $tipoDesc,
            "descRec" => $descRec
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    /*----*/
    public function pedidoRenglonCambiar( int $idPedido, int $idDetalle, int $Cant)
    {
        if (!$idPedido || !$idDetalle || !$Cant) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_PedidoRenglonCambiar", [
            "idPedido" => $idPedido,
            "idDetalle" => $idDetalle,
            "cant" => $Cant
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
  

    public function pedidoMultiDet( array $mesaDetM )
    {
        // convertir el array a Json
        $det = json_encode (array('mesaDetM' => $mesaDetM), true);
        
        $R = dbExecSP("dbo.spP_MesaDetMulti", [
            "det" => $det,
            "tipo" => 'P'
        ]);
        
        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
       
    }

    /*----*/
    public function pedidos( int $idRepartidor, DateTime $FechaDesde, DateTime $FechaHasta, int $Cobrado,
    int $noPedidosCerrados, int $PtoVta)
    {
        if (!$idRepartidor || !$FechaDesde || !$FechaHasta) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_Pedidos", [
            "idRepartidor" => $idRepartidor,
            "FechaDesde" => $FechaDesde->format('Y-m-d'),
            "FechaHasta" => $FechaHasta->format('Y-m-d'),
            "Cobrado" => $Cobrado,
            "noPedidosCerrados" => $noPedidosCerrados,
            "PtoVta" => $PtoVta
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    /*----*/
    public function pedidoEnc( int $idPedido)
    {
        if (!$idPedido) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PedidoEnc", [
            "idPedido" => $idPedido
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }


}
?>