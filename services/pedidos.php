 <?php

error_reporting(0);

class pedidos
{
    public function pedidoNuevo( int $idPedido, string $Fecha, string $Hora, int $idCliente, string $FechaEntrega, string $HoraEntrega,
    float $Subtotal, float $Descuento, float $Total, float $Envio, float $Pago, float $Pagacon, string $Obs,
    int $idRepartidor, string $NombreClie, string $DireccionClie, bool $EnUso, bool $Cobrado, bool $xMostrador,
    int $idUsuario, int $PuntoDeVenta, bool $Delivery, int $tipoDesc, int $descRec)
    {
        if ($idPedido === null || $idPedido < 0) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        if ($idCliente == 0 ) {
            $idCliente = null; // permitir nulos en idCliente
        }
        if ($idRepartidor == 0 ) {
            $idRepartidor = null; // permitir nulos en idRepartidor
        }   

        $objeto_fecha = new DateTime($Fecha);
        $objeto_fecha_entrega = new DateTime($FechaEntrega);

        $R = dbExecSP("dbo.spP_PedidoNuevo", [
            "idPedido" => $idPedido,
            "Fecha" => $objeto_fecha->format('Y/m/d'),
            "Hora" => $Hora,
            "idCliente" => $idCliente,
            "FechaEntrega" => $objeto_fecha_entrega->format('Y/m/d'),
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

    public function pedidoRenglonCambiar( int $idPedido, int $idDetalle, int $cant)
    {
        if (!$idPedido || !$idDetalle || !$cant) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_PedidoRenglonCambiar", [
            "idPedido" => $idPedido,
            "idDetalle" => $idDetalle,
            "cant" => $cant
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

        // echo "Debug: Detalle enviado al SP: " . $det . "\n";
        
        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
       
    }

    public function pedidos( int $idRepartidor, string $FechaDesde, string $FechaHasta, int $Cobrado,
    int $noPedidosCerrados, int $PtoVta)
    {
        if (!$idRepartidor || !$FechaDesde || !$FechaHasta) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha_desde = new DateTime($FechaDesde);
        $objeto_fecha_hasta = new DateTime($FechaHasta);
     

        $R = dbExecSP("dbo.spG_Pedidos", [
            "idRepartidor" => $idRepartidor,
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
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

    public function pedidoCocinado( int $idPedido, int $idDetalle      )
    {
        if (!$idPedido || !$idDetalle) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_PedidoCocinado", [
            "idPedido" => $idPedido,
            "idDetalle" => $idDetalle
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function pedidoEntregado( int $idPedido, int $idRepartidor  )
    {
        if (!$idPedido || !$idRepartidor) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_PedidoEntregado", [
            "idPedido" => $idPedido,
            "idRepartidor" => $idRepartidor
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }


}
?>