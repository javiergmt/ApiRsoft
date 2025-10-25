<?php

error_reporting(0);

class comandas
{
    public function lugSectImpre()
    {
      
        $R = dbExecSP("dbo.spG_LugSectImpre", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function grabarMensaje(string $descripcion, int $idMozo, int $idUsuario, int $nromesa)
    {
        if (!$nromesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_GrabaMensaje", [
            "descripcion" => $descripcion,
            "idMozo" => $idMozo,
            "idUsuario" => $idUsuario,
            "nromesa" => $nromesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
}