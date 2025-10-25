<?php

error_reporting(0);

class platos
{
  
   public function platos( string $coper, string $ccadena, int $nrubro, int $nsubrubro, int $sucursal)
   {
        if (!isset($sucursal) || !isset($nrubro) || !isset($nsubrubro) || !isset($coper) || !isset($ccadena)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_Platos", [
            "coper" => $coper,
            "ccadena" => $ccadena,
            "nrubro" => $nrubro,
            "nsubrubro" => $nsubrubro,
            "sucursal" => $sucursal
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

   public function platosGustos( int $idplato)
   {
        if (!isset($idplato)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatosGustos", [
            "idplato" => $idplato
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function platosTamanios( int $idplato, int $idSector)
   {
        if (!isset($idplato) || !isset($idSector)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatosTamanios", [
            "idplato" => $idplato,
            "idSector" => $idSector
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

   public function platosPrecio( int $idplato, int $idtam, int $idsector, string $hora)
   {
        if (!isset($idplato) || !isset($idtam) || !isset($idsector) || !isset($hora)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoPrecio", [
            "nplato" => $idplato,
            "idtam" => $idtam,
            "idsector" => $idsector,
            "hora" => $hora
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

   public function platoEnMesa( int $idplato)
   {
        if (!isset($idplato)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoEnMesa", [
            "idplato" => $idplato
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }
 
   public function comboSec( int $idplato)
   {
        if (!isset($idplato)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_ComboSec", [
            "idplato" => $idplato
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function comboDet( int $idseccion)
   {
        if (!isset($idseccion)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_ComboDet", [
            "idseccion" => $idseccion
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function platosObs()
   {
        
        $R = dbExecSP("dbo.spG_PlatosObs", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function platoInfo( int $nroMesa, int $idDetalle, int $idPedido)
   {
        if (!isset($nroMesa) || !isset($idDetalle) || !isset($idPedido)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoInfo", [
            "nroMesa" => $nroMesa,
            "idDetalle" => $idDetalle,
            "idPedido" => $idPedido
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function platoInfoGustos(  int $nroMesa, int $idDetalle, int $idPedido)
   {
        if (!isset($nroMesa) || !isset($idDetalle) || !isset($idPedido)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoInfoGustos", [
            "nroMesa" => $nroMesa,
            "idDetalle" => $idDetalle,
            "idPedido" => $idPedido
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function platoInfoCombo(  int $nroMesa, int $idDetalle, int $idPedido)
   {
        if (!isset($nroMesa) || !isset($idDetalle) || !isset($idPedido)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoInfoCombo", [
            "nroMesa" => $nroMesa,
            "idDetalle" => $idDetalle,
            "idPedido" => $idPedido
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }
  
   public function platoInfoComboGustos(  int $nroMesa, int $idDetalle, int $idPedido)
   {
        if (!isset($nroMesa) || !isset($idDetalle) || !isset($idPedido)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoInfoComboGustos", [
            "nroMesa" => $nroMesa,
            "idDetalle" => $idDetalle,
            "idPedido" => $idPedido
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function obsRenglones()
   {
        
        $R = dbExecSP("dbo.spG_ObsRenglones", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }
}