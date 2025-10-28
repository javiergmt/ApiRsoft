<?php

error_reporting(0);

class platos
{
  
   public function platos( string $cOper, string $cCadena, int $nRubro, int $nSubRubro, int $Sucursal)
   {
        if (!isset($Sucursal) || !isset($nRubro) || !isset($nSubRubro) || !isset($cOper) || !isset($cCadena)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_Platos", [
            "coper" => $cOper,
            "ccadena" => $cCadena,
            "nrubro" => $nRubro,
            "nsubrubro" => $nSubRubro,
            "sucursal" => $Sucursal
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

   public function platosGustos( int $idPlato)
   {
        if (!isset($idPlato)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatosGustos", [
            "idplato" => $idPlato
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function platosTamanios( int $idPlato, int $idSector)
   {
        if (!isset($idPlato) || !isset($idSector)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatosTamanios", [
            "idplato" => $idPlato,
            "idSector" => $idSector
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

   public function platosPrecio( int $idPlato, int $idTam, int $idSector, string $Hora)
   {
        if (!isset($idPlato) || !isset($idTam) || !isset($idSector) || !isset($Hora)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoPrecio", [
            "nplato" => $idPlato,
            "idtam" => $idTam,
            "idsector" => $idSector,
            "hora" => $Hora
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

   public function platoEnMesa( int $idPlato)
   {
        if (!isset($idPlato)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoEnMesa", [
            "idplato" => $idPlato
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }
 
   public function comboSec( int $idPlato)
   {
        if (!isset($idPlato)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_ComboSec", [
            "idplato" => $idPlato
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function comboDet( int $idSeccion)
   {
        if (!isset($idSeccion)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_ComboDet", [
            "idseccion" => $idSeccion
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

   public function platoInfo( int $NroMesa, int $idDetalle, int $idPedido)
   {
        if (!isset($NroMesa) || !isset($idDetalle) || !isset($idPedido)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoInfo", [
            "nroMesa" => $NroMesa,
            "idDetalle" => $idDetalle,
            "idPedido" => $idPedido
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function platoInfoGustos(  int $NroMesa, int $idDetalle, int $idPedido)
   {
        if (!isset($NroMesa) || !isset($idDetalle) || !isset($idPedido)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoInfoGustos", [
            "nroMesa" => $NroMesa,
            "idDetalle" => $idDetalle,
            "idPedido" => $idPedido
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function platoInfoCombo(  int $NroMesa, int $idDetalle, int $idPedido)
   {
        if (!isset($NroMesa) || !isset($idDetalle) || !isset($idPedido)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoInfoCombo", [
            "nroMesa" => $NroMesa,
            "idDetalle" => $idDetalle,
            "idPedido" => $idPedido
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }
  
   public function platoInfoComboGustos(  int $NroMesa, int $idDetalle, int $idPedido)
   {
        if (!isset($NroMesa) || !isset($idDetalle) || !isset($idPedido)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_PlatoInfoComboGustos", [
            "nroMesa" => $NroMesa,
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