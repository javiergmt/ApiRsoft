<?php

error_reporting(0);

class clientes
{

  public function clientes()
  {

    $R = dbExecSP("dbo.spG_Clientes", [], TRUE);

    if (!$R) {
      throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
    }

    // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
    return $R;
  }

      /*-- Falta el SP */
    public function clienteCambiar( int $idCliente, string $Nombre, string $Direccion, string $Localidad, string $Tel1,
    string $Tel2, string $Tel3, string $Email, int $idZona, string $FechaNac, string $idIva, string $Cuit, string $Tarj)
    {
       if (!isset($idcliente) ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha = new DateTime($FechaNac);

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
            "fechaNac" => $FechaNac ? $objeto_fecha->format('Y/m/d') : null,
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
