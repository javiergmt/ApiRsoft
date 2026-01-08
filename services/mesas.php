<?php

error_reporting(0);

class mesas
{
    public function sectores(int $count, int $sucursal, int $iddelivery)
    {
        if (!$count || !$sucursal || !$iddelivery) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_Sectores", [
            "count" => $count,
            "sucursal" => $sucursal,
            "iddelivery" => $iddelivery
        ], TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesas(int $count, int $idSector)
    {
        if (!$count || !$idSector) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_Mesas", [
            "count" => $count,
            "idsector" => $idSector
        ], TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesa(int $NroMesa, int $Sucursal)
    {
        if (!$NroMesa || !$Sucursal) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_Mesa", [
            "nromesa" => $NroMesa,
            "sucursal" => $Sucursal
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesasMozo(int $idMozo)
    {
        if (!$idMozo) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_MesasMozos", [
            "idmozo" => $idMozo
        ], TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaEnc(int $NroMesa)
    {
        if (!$NroMesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_MesaEnc", [
            "nromesa" => $NroMesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaDet(int $NroMesa,  int $agrupar, int $idPedido)
    {
        if (!$NroMesa && !$idPedido) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_MesaDet", [
            "nromesa" => $NroMesa,
            "agrupar" => $agrupar,
            "idpedido" => $idPedido
        ], TRUE);

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
                    "nromesa" => $NroMesa,
                    "iddetalle" => $R[$key]['idDetalle']
                ], TRUE);

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
                    "nromesa" => $NroMesa,
                    "iddetalle" => $R[$key]['idDetalle']
                ], TRUE);
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

    public function mesaPagos(int $NroMesa)
    {
        if (!$NroMesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spG_MesaPagos", [
            "nromesa" => $NroMesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaFormas()
    {

        $R = dbExecSP("dbo.spG_MesaFormas", [], TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaBloquear(int $NroMesa, int $idMozo)
    {
        if (!$NroMesa || !$idMozo) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaBloquear", [
            "nromesa" => $NroMesa,
            "idmozo" => $idMozo
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaDesbloquear(int $NroMesa)
    {
        if ( !isset($NroMesa) ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaDesbloquear", [
            "nromesa" => $NroMesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaAbrir(int $NroMesa, int $idMozo)
    {
        if (!$NroMesa || !$idMozo) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaAbrir", [
            "NroMesa" => $NroMesa,
            "Mozo" => $idMozo
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaAddDet(
        int $NroMesa,
        int $idDetalle,
        int $idPlato,
        float $Cant,
        float $PcioUnit,
        float $Importe,
        string $Obs,
        int $idTamanio,
        string $Tamanio,
        bool $Procesado,
        string $Hora,
        int $idMozo,
        int $idUsuario,
        bool $Cocinado,
        bool $EsEntrada,
        string $Descripcion,
        DateTime $FechaHora,
        bool $Comanda
    ) {
        if (!$NroMesa || !$idDetalle || !$idPlato || !$Cant || !$PcioUnit || !$idTamanio || !$idMozo || !$idUsuario) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaDet", [
            "nromesa" => $NroMesa,
            "iddetalle" => $idDetalle,
            "idplato" => $idPlato,
            "cant" => $Cant,
            "pcioUnit" => $PcioUnit,
            "importe" => $Importe,
            "obs" => $Obs,
            "idTamanio" => $idTamanio,
            "Tamanio" => $Tamanio,
            "procesado" => $Procesado,
            "hora" => $Hora,
            "idMozo" => $idMozo,
            "idUsuario" => $idUsuario,
            "cocinado" => $Cocinado,
            "esEntrada" => $EsEntrada,
            "descripcion" => $Descripcion,
            "fechaHora" => $FechaHora,
            "comanda" => $Comanda
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaAddDetGustos(int $NroMesa, int $idDetalle, int $idGusto, string $Descripcion)
    {
        if (!$NroMesa || !$idDetalle || !$idGusto) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaDetGustos", [
            "nromesa" => $NroMesa,
            "iddetalle" => $idDetalle,
            "idgusto" => $idGusto,
            "descripcion" => $Descripcion
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaAddDetCombos(
        int $NroMesa,
        int $idDetalle,
        int $idSeccion,
        int $idPlato,
        float $Cant,
        bool $Procesado,
        int $idTamanio,
        string $Obs,
        bool $Cocinado,
        string $Descripcion,
        DateTime $FechaHora,
        bool $Comanda
    ) {
        if (!$NroMesa || !$idDetalle || !$idSeccion || !$idPlato || !$Cant) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaDetCombos", [
            "nromesa" => $NroMesa,
            "iddetalle" => $idDetalle,
            "idseccion" => $idSeccion,
            "idplato" => $idPlato,
            "cant" => $Cant,
            "procesado" => $Procesado,
            "idtamanio" => $idTamanio,
            "obs" => $Obs,
            "cocinado" => $Cocinado,
            "descripcion" => $Descripcion,
            "fechahora" => $FechaHora,
            "comanda" => $Comanda
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaAddDetCombosGustos(int $NroMesa, int $idDetalle, int $idSeccion, int $idPlato, int $idGusto)
    {
        if (!$NroMesa || !$idDetalle || !$idGusto) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaDetCombosGustos", [
            "nromesa" => $NroMesa,
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

    public function mesaAddComensales(int $NroMesa, int $Cant)
    {
        if (!$NroMesa || !$Cant) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaComensales", [
            "nromesa" => $NroMesa,
            "cant" => $Cant
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaBorrar(int $NroMesa)
    {
        if (!$NroMesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spD_Mesa", [
            "nromesa" => $NroMesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaRenglonBorrar(
        int $NroMesa,
        int $idDetalle,
        int $idPlato,
        string $idTipoConsumo,
        float $cant,
        string $Descripcion,
        float $PcioUnit,
        string $Obs,
        int $idTamanio,
        string $Tamanio,
        string $Fecha,
        string $Hora,
        int $idMozo,
        int $idUsuario,
        string $FechaHoraElim,
        int $idUsuarioElin,
        int $idMozoElim,
        int $idObs,
        string $Observacion,
        string $Comentario,
        int $PuntoDeVenta,
        int $idSeccion
    ) {
        if (!$NroMesa || !$idDetalle || !$idPlato || !$cant || !$PcioUnit || !$idTamanio || !$idMozo || !$idUsuario) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $objeto_fecha = new DateTime($Fecha);
        $objeto_fechaElim = new DateTime($FechaHoraElim);

        $R = dbExecSP("dbo.spD_MesaRenglon", [
            "nromesa" => $NroMesa,
            "iddetalle" => $idDetalle,
            "idplato" => $idPlato,
            "idTipoConsumo" => $idTipoConsumo,
            "cant" => $cant,
            "descripcion" => $Descripcion,
            "pcioUnit" => $PcioUnit,
            "obs" => $Obs,
            "idTamanio" => $idTamanio,
            "tamanio" => $Tamanio,
            "fecha" => $Fecha ? $objeto_fecha->format('Y/m/d') : null,
            "hora" => $Hora,
            "idMozo" => $idMozo,
            "idUsuario" => $idUsuario,
            "fechaHoraElin" => $FechaHoraElim ? $objeto_fechaElim->format('Y/m/d') : null,
            "idUsuarioElin" => $idUsuarioElin,
            "idMozoElim" => $idMozoElim,
            "idObs" => $idObs,
            "observacion" => $Observacion,
            "comentario" => $Comentario,
            "puntoDeVenta" => $PuntoDeVenta,
            "idSeccion" => $idSeccion,
            "borrar" => 1 // Indica que se quiere borrar el renglón
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    // {
        //     "CUIT": "00-17620426-0",
        //     "DescImporte": 0,
        //     "DescTipo": 0,
        //     "Direccion": "467 n 4302",
        //     "Email": "javiergmt@gmail.com",
        //     "FechaNac": "2000-01-01 00:00:00.000",
        //     "idCliente": 2,
        //     "idIva": "C",
        //     "idTarjeta": 0,
        //     "idZona": 0,
        //     "Localidad": "city bell",
        //     "Nombre": "Javier Martinez",
        //     "Pagos": 0,
        //     "Telefono": "4255555",
        //     "telefono2": "",
        //     "telefono3": "",
        //     "NroMesa": 7
        // }
    public function mesaCerrar(int $NroMesa,float $PorcDesc,float $DescPesos,
                               int $idCliente, string $idIva)
     {
        if (!$NroMesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaCerrar", [
            "NroMesa" => $NroMesa,
            "PorcDesc" => $PorcDesc,
            "DescPesos" => $DescPesos,
            "idCliente" => $idCliente,
            "idIva" => $idIva
        
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }


    public function mesaCerrarMozo(int $NroMesa)
    {
        if (!$NroMesa ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaCerrarMozo", [
            "nromesa" => $NroMesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaRenglonCambiar(int $NroMesa, int $idDetalle, int $Cant)
    {
        if (!$NroMesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaRenglonCambiar", [
            "nromesa" => $NroMesa,
            "idDetalle" => $idDetalle,
            "cant" => $Cant
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesasObjetosPlano()
    {

        $R = dbExecSP("dbo.spG_MesasObjetosPlano", [], TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaObjetoPlanoCambiar(
        int $idObjeto,
        string $Descripcion,
        int $Forma,
        int $idSector,
        string $Color,
        string $PenColor,
        int $BrushStyle,
        int $PenStyle,
        int $PosTop,
        int $PosLeft,
        int $Width,
        int $Height,
        bool $PuntasRedondeadas
    ) {
        if (!$idObjeto) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_ObjetoCambiar", [
            "idObjeto" => $idObjeto,
            "descripcion" => $Descripcion,
            "forma" => $Forma,
            "idSector" => $idSector,
            "color" => $Color,
            "penColor" => $PenColor,
            "brushStyle" => $BrushStyle,
            "penStyle" => $PenStyle,
            "posTop" => $PosTop,
            "posLeft" => $PosLeft,
            "width" => $Width,
            "height" => $Height,
            "puntasRedondeadas" => $PuntasRedondeadas
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaObjetoPlanoBorrar(int $idObjeto)
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

    public function mesaMultiDet(array $mesaDetM)
    {
        //if (!$det) {
        //    throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        //}

        $det = json_encode(array('mesaDetM' => $mesaDetM), true);
        //echo var_dump($det);

        $R = dbExecSP("dbo.spP_MesaDetMulti", [
            // convertir Json a string para pasarlo al SP
            "det" => $det,
            "tipo" => 'M'
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaMozoCambiar(int $NroMesa, int $idMozo)
    {
        if (!$NroMesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_MesaMozoCambiar", [
            "nromesa" => $NroMesa,
            "idmozo" => $idMozo
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function mesaPago(int $NroMesa, float $importe, int $idMozo, int $idUsuario, string $tipo, string $detalle="",
                             int $idCliente,  string $nombreClie ="", string $cuitclie = "00-00000000-0")
    { 
        if (!$NroMesa ) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_PagoEnMesa", [
            "nmesa" => $NroMesa,
            "importe" => $importe,
            "idMozo" => $idMozo,
            "idUsuario" => $idUsuario,
            "tipo" => $tipo,
            "detalle" => $detalle,
            "idCliente" => $idCliente,
            "nombreClie" => $nombreClie,
            "cuitclie" => $cuitclie
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
}
