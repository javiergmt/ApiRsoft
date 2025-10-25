<?php

error_reporting(0);

class mesas
{
    public function sectores( int $count, int $sucursal, int $iddelivery)
    {
        if (!$count || !$sucursal || !$iddelivery) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }
    
        $R = dbExecSP("dbo.spG_Sectores", [
            "count" => $count,
            "sucursal" => $sucursal,
            "iddelivery" => $iddelivery
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesas( int $count, int $idsector)
    {
        if (!$count || !$idsector) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_Mesas", [
            "count" => $count,
            "idsector" => $idsector
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesa( int $nromesa, int $sucursal)
    {
        if (!$nromesa || !$sucursal) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_Mesa", [
            "nromesa" => $nromesa,
            "sucursal" => $sucursal
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesasMozo( int $idmozo)
    {
        if (!$idmozo) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_MesasMozos", [
            "idmozo" => $idmozo
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaEnc( int $nromesa)
    {
        if (!$nromesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_MesaEnc", [
            "nromesa" => $nromesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaDet( int $nromesa,  int $agrupar, int $idpedido)
    {
        if (!$nromesa ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_MesaDet", [
            "nromesa" => $nromesa,
            "agrupar" => $agrupar,
            "idpedido" => $idpedido
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        // Analizar si el idtipoconsumo es CV or CB y agregar los gustos y los platos del combo
        foreach ($R as $key => $value) {

            if ($value['idTipoConsumo'] === 'CV') {
                // Si es un consumo con gustos, traer los gustos
                $gustos = '';
                $G = dbExecSP("dbo.spG_MesaDetGustos", [
                    "nromesa" => $nromesa,
                    "iddetalle" => $R[$key]['idDetalle']
                ],TRUE);
 
                if ($G) {
                    foreach ($G as $gusto) {
                        $gustos .= ($gustos ? ', ' : '') . $gusto['Descripcion'];
                    }
                }
                $R[$key]['detalles'] = $gustos ? $gustos : '';
            } elseif ($value['idTipoConsumo'] === 'CB') {
                // Si es un combo, traer los platos del combo
                $platosCombo = '';
                $C = dbExecSP("dbo.spG_MesaDetCombos", [
                    "nromesa" => $nromesa,
                    "iddetalle" => $R[$key]['idDetalle']
                ],TRUE);
                if ($C) {
                    foreach ($C as $plato) {
                        $platosCombo .= ($platosCombo ? ', ' : '') . $plato['DescCorta'];
                    }
                }
                $R[$key]['detalles'] = $platosCombo ? $platosCombo : '';
            } else {
                $R[$key]['detalles'] = '';
            }
        }
        
        return $R;
    }
  
    public function mesaPagos( int $nromesa)
    {
        if (!$nromesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_MesaPagos", [
            "nromesa" => $nromesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaFormas()
    {
    
        $R = dbExecSP("dbo.spG_MesaFormas", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaBloquear( int $nromesa, int $idmozo)
    {
        if (!$nromesa || !$idmozo) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaBloquear", [
            "nromesa" => $nromesa,
            "idmozo" => $idmozo
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaDesbloquear( int $nromesa)
    {
        if (!$nromesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaDesbloquear", [
            "nromesa" => $nromesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaAbrir( int $nromesa, int $idmozo)
    {
        if (!$nromesa || !$idmozo) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaAbrir", [
            "nromesa" => $nromesa,
            "idmozo" => $idmozo
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaAddDet( int $nroMesa, int $idDetalle, int $idPlato, float $cant, float $pcioUnit,
	float $importe, string $obs, int $idTamanio, string $Tamanio, bool $procesado, string $hora,
	int $idMozo, int $idUsuario, bool $cocinado, bool $esEntrada, string $descripcion,
	DateTime $fechaHora, bool $comanda)
    {
        if (!$nroMesa || !$idDetalle || !$idPlato || !$cant || !$pcioUnit || !$idTamanio || !$idMozo || !$idUsuario) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaDet", [
            "nromesa" => $nroMesa,
            "iddetalle" => $idDetalle,
            "idplato" => $idPlato,
            "cant" => $cant,
            "pcioUnit" => $pcioUnit,
            "importe" => $importe,
            "obs" => $obs,
            "idTamanio" => $idTamanio,
            "Tamanio" => $Tamanio,
            "procesado" => $procesado,
            "hora" => $hora,
            "idMozo" => $idMozo,
            "idUsuario" => $idUsuario,
            "cocinado" => $cocinado,
            "esEntrada" => $esEntrada,
            "descripcion" => $descripcion,
            "fechaHora" => $fechaHora,
            "comanda" => $comanda
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaAddDetGustos( int $nroMesa, int $idDetalle, int $idGusto, string $descripcion)  {
        if (!$nroMesa || !$idDetalle || !$idGusto) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaDetGustos", [
            "nromesa" => $nroMesa,
            "iddetalle" => $idDetalle,
            "idgusto" => $idGusto,
            "descripcion" => $descripcion
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaAddDetCombos( int $nroMesa, int $idDetalle, int $idSeccion, int $idPlato, float $cant,
     bool $procesado, int $idTamanio, string $obs, bool $cocinado, string $descripcion, DateTime $fechaHora,
     bool $comanda)  {
        if (!$nroMesa || !$idDetalle || !$idSeccion || !$idPlato || !$cant ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaDetCombos", [
            "nromesa" => $nroMesa,
            "iddetalle" => $idDetalle,
            "idseccion" => $idSeccion,
            "idplato" => $idPlato,
            "cant" => $cant,
            "procesado" => $procesado,
            "idtamanio" => $idTamanio,
            "obs" => $obs,
            "cocinado" => $cocinado,
            "descripcion" => $descripcion,
            "fechahora" => $fechaHora,
            "comanda" => $comanda
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaAddDetCombosGustos( int $nroMesa, int $idDetalle,int $idSeccion, int $idPlato, int $idGusto)  {
        if (!$nroMesa || !$idDetalle || !$idGusto) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaDetCombosGustos", [
            "nromesa" => $nroMesa,
            "iddetalle" => $idDetalle,
            "idseccion" => $idSeccion,
            "idplato" => $idPlato,
            "idgusto" => $idGusto
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaAddComensales( int $nroMesa, int $cant)  {
        if (!$nroMesa || !$cant) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaComensales", [
            "nromesa" => $nroMesa,
            "cant" => $cant
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaBorrar( int $nromesa)
    {
        if (!$nromesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaBorrar", [
            "nromesa" => $nromesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
    
    public function mesaRenglonBorrar( int $nroMesa, int $idDetalle, int $idPlato, string $idTipoConsumo, float $cant,
	string $descripcion,float $pcioUnit, string $obs, int $idTamanio, string $tamanio, DateTime $fecha, string $hora,
	int $idMozo, int $idUsuario, DateTime $fechaHoraElin, int $idUsuarioElin, int $idMozoElim, int $idObs,
    string $observacion, string $comentario, int $puntoDeVenta, int $idSeccion)
    {
        if (!$nroMesa || !$idDetalle || !$idPlato || !$cant || !$pcioUnit || !$idTamanio || !$idMozo || !$idUsuario) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spD_MesaRenglon", [
            "nromesa" => $nroMesa,
            "iddetalle" => $idDetalle,
            "idplato" => $idPlato,
            "idTipoConsumo" => $idTipoConsumo,
            "cant" => $cant,
            "descripcion" => $descripcion,
            "pcioUnit" => $pcioUnit,
            "obs" => $obs,
            "idTamanio" => $idTamanio,
            "tamanio" => $tamanio,
            "fecha" => $fecha,
            "hora" => $hora,
            "idMozo" => $idMozo,
            "idUsuario" => $idUsuario,
            "fechaHoraElin" => $fechaHoraElin,
            "idUsuarioElin" => $idUsuarioElin,
            "idMozoElim" => $idMozoElim,
            "idObs" => $idObs,
            "observacion" => $observacion,
            "comentario" => $comentario,
            "puntoDeVenta" => $puntoDeVenta,
            "idSeccion" => $idSeccion,
            "borrar" => 1 // Indica que se quiere borrar el renglón
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    
    public function mesaCerrar( int $nroMesa, float $pagos, int $descTipo, float $descImporte, int $idCliente,
    string $nombre, string $direccion,string $localidad, string $telefono, string $telefono2, string $telefono3, 
    string $email, int $idZona, DateTime $fechaNac, string $idIva, string $cuit, string $tarjeta)  {
        if (!$nroMesa || !$pagos) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaCerrar", [
            "nromesa" => $nroMesa,
            "pagos" => $pagos,
            "descTipo" => $descTipo,
            "descImporte" => $descImporte,
            "idCliente" => $idCliente,
            "nombre" => $nombre,
            "direccion" => $direccion,
            "localidad" => $localidad,
            "telefono" => $telefono,
            "telefono2" => $telefono2,
            "telefono3" => $telefono3,
            "email" => $email,
            "idZona" => $idZona,
            "fechaNac" => $fechaNac,
            "idIva" => $idIva,
            "cuit" => $cuit,
            "tarjeta" => $tarjeta
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    
    public function mesaCerrarMozo( int $nromesa)
    {
        if (!$nromesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaCerrarMozo", [
            "nromesa" => $nromesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaRenglonCambiar( int $nromesa, int $idDetalle, int $cant)
    {
        if (!$nromesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaRenglonCambiar", [
            "nromesa" => $nromesa,
            "idDetalle" => $idDetalle,
            "cant" => $cant
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesasObjetosPlano()
    {
      
        $R = dbExecSP("dbo.spG_MesasObjetosPlano", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaObjetoPlanoCambiar( int $idObjeto, string $descripcion, int $forma, int $idSector,
    string $color, string $penColor, int $brushStyle, int $penStyle, int $posTop, int $posLeft, int $width, 
    int $height, bool $puntasRedondeadas)
    {
        if (!$idObjeto) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_ObjetoCambiar", [
            "idObjeto" => $idObjeto,
            "descripcion" => $descripcion,
            "forma" => $forma,
            "idSector" => $idSector,
            "color" => $color,
            "penColor" => $penColor,
            "brushStyle" => $brushStyle,
            "penStyle" => $penStyle,
            "posTop" => $posTop,
            "posLeft" => $posLeft,
            "width" => $width,
            "height" => $height,
            "puntasRedondeadas" => $puntasRedondeadas
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaObjetoPlanoBorrar( int $idObjeto)
    {
        if (!$idObjeto) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spD_Objeto", [
            "idObjeto" => $idObjeto
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

}
?>