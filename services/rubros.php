<?php

error_reporting(0);

class rubros
{
   public function rubros( int $Sucursal, int $Favoritos, int $Delivery)
   {
        if (!isset($Sucursal) || !isset($Delivery) || !isset($Favoritos)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_Rubros", [
            "sucursal" => $Sucursal,
            "favoritos" => $Favoritos,
            "delivery" => $Delivery
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
    public function subRubros( int $idRubro)
    {
        if (!isset($idRubro)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_SubRubros", [
            "idRubro" => $idRubro
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }




}

 
