<?php

error_reporting(0);

class rsoft
{
   public function getClientes( int $idResto)
   {
        if (!isset($idResto) ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.sp_getClientes", [
            "idResto" => $idResto
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }

   public function addCliente( int $idResto, string $nombreRest, string $db_usuario, string $db_password, string $db_nombre, int $modMozos)
   {
        if (!isset($idResto) || !isset($nombreRest) || !isset($db_usuario) || !isset($db_password) || !isset($db_nombre) || !isset($modMozos)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.sp_AddCliente", [
            "idResto" => $idResto,
            "nombreRest" => $nombreRest,
            "db_usuario" => $db_usuario,
            "db_password" => $db_password,
            "db_nombre" => $db_nombre,
            "modMozos" => $modMozos
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
   }    
   public function modifCliente( int $idResto, string $nombreRest, string $db_usuario, string $db_password, string $db_nombre, int $modMozos)
   {
        if (!isset($idResto) || !isset($nombreRest) || !isset($db_usuario) || !isset($db_password) || !isset($db_nombre) || !isset($modMozos)) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.sp_ModifCliente", [
            "idResto" => $idResto,
            "nombreRest" => $nombreRest,
            "db_usuario" => $db_usuario,
            "db_password" => $db_password,
            "db_nombre" => $db_nombre,
            "modMozos" => $modMozos
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
}