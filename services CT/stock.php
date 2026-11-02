<?php

error_reporting(0);

class stock
{
   public function addRemito( string $nroComp , int $idTipoMov, int $idProveedor)
   {
    if (!isset($nroComp) || !isset($idTipoMov) || !isset($idProveedor)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_AddRemito", [
            "nroComp" => $nroComp,
            "idTipoMov" => $idTipoMov,
            "idProveedor" => $idProveedor
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function infRemitosDet( int $idCompra)
   {
    if (!isset($idCompra)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_InfRemitosDet", [
            "idcompra" => $idCompra
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function infRemitosEnc(string $FechaDesde, string $FechaHasta)
    {
        if (!$FechaDesde || !$FechaHasta) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaDesde);
        $objeto_fecha_hasta = new DateTime($FechaHasta);
        $R = dbExecSP("dbo.spG_InfRemitosEnc", [
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d')
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infStock(string $FechaHasta, int $idLugarStk, int $idRubro, int $IncluyeMesas)
    {
        if (!$FechaHasta || !$idLugarStk || !$idRubro ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_hasta = new DateTime($FechaHasta);
        $R = dbExecSP("dbo.spG_InfStock", [
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "idLugarStk" => $idLugarStk,
            "idRubro" => $idRubro,
            "IncluyeMesas" => $IncluyeMesas
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function cierreStock(string $FechaCierre, int $idLugarStk, int $idRubro)
    {
        if (!$FechaCierre || !$idLugarStk || !$idRubro ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_cierre = new DateTime($FechaCierre);
        $R = dbExecSP("dbo.spP_CierreStock", [
            "FechaCierre" => $objeto_fecha_cierre->format('Y/m/d'),
            "idLugarStk" => $idLugarStk,
            "idRubro" => $idRubro
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function cierreStockEnc()
    {
        
     
        $R = dbExecSP("dbo.spP_CierreStockEnc", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infStockDias(int $CantidadDias, int $idRubro, string $FechaDesde, int $IncluyeMesas)
    {
        if (!$CantidadDias || !$idRubro || !$FechaDesde ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaDesde);
        $R = dbExecSP("dbo.spG_InfStockDias", [
            "CantidadDias" => $CantidadDias,
            "idRubro" => $idRubro,
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "IncluyeMesas" => $IncluyeMesas
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infStockDif(int $idRubro, int $idLugarStk,string $FDesde, string $FHasta)
    {
        if (!$idRubro || !$idLugarStk || !$FDesde || !$FHasta ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FDesde);
        $objeto_fecha_hasta = new DateTime($FHasta);
        $R = dbExecSP("dbo.spG_InfStockDif", [
            "idRubro" => $idRubro,
            "idLugarStk" => $idLugarStk,
            "FDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FHasta" => $objeto_fecha_hasta->format('Y/m/d')        
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
   
    public function infStockComp(int $idRubro)
    {
        if (!$idRubro ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $R = dbExecSP("dbo.spG_InfStockComp", [
            "idRubro" => $idRubro
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infStockMpDesc(int $idRubro)
    {
        if (!$idRubro ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $R = dbExecSP("dbo.spG_InfStockMpDesc", [
            "idRubro" => $idRubro
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infStockMpCons(int $idRubroMP,string $FechaDesde, string $FechaFHasta)
    {
        if (!$idRubroMP || !$FechaDesde || !$FechaFHasta ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FechaDesde);
        $objeto_fecha_hasta = new DateTime($FechaFHasta);
        $R = dbExecSP("dbo.spG_InfStockMpCons", [
            "idRubroMP" => $idRubroMP,
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d')        
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infStockInventario(int $idRubro, int $idLugarStk,string $FDesde, string $FHasta, int $IncluyeMesas)
    {
        if (!$idRubro || !$idLugarStk || !$FDesde || !$FHasta ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $objeto_fecha_desde = new DateTime($FDesde);
        $objeto_fecha_hasta = new DateTime($FHasta);
        $R = dbExecSP("dbo.spG_InfStockInventario", [
            "idRubro" => $idRubro,
            "idLugarStk" => $idLugarStk,
            "FechaDesde" => $objeto_fecha_desde->format('Y/m/d'),
            "FechaHasta" => $objeto_fecha_hasta->format('Y/m/d'),
            "IncluyeMesas" => $IncluyeMesas
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function infStockFaltantes(int $idRubro)
    {
        if (!$idRubro ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
        $R = dbExecSP("dbo.spG_InfStockFaltantes", [
            "idRubro" => $idRubro
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
   
}