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

    public function grabarMensaje(string $Descripcion, int $idMozo, int $idUsuario, int $NroMesa)
    {
        if (!$NroMesa) {
            throw new Exception("Parametros invalidos"); // esto llega en la respuesta de la api como {"error": "Invalid Data"}
        }

        $R = dbExecSP("dbo.spP_GrabaMensaje", [
            "descripcion" => $Descripcion,
            "idMozo" => $idMozo,
            "idUsuario" => $idUsuario,
            "nromesa" => $NroMesa
        ]);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function setearInis( string $urlapi)
    {

        $R = dbExecSP("dbo.spG_ParamComandas", [],TRUE);

        $fields = array(
            'idImpresoraComandaCentral' => is_null($R[0]['idImpresoraComandaCentral']) ? 1 : $R[0]['idImpresoraComandaCentral'],
            'concatenarGustos' => is_null($R[0]['ConcatenarGustos']) ? 0 : $R[0]['ConcatenarGustos'],
            'detallarPlatosEnCombina' => is_null($R[0]['DetallarPlatosEnCombina']) ? 0 : $R[0]['DetallarPlatosEnCombina'],
            'delimitadorEntrada1' => is_null($R[0]['DelimitadorEntrada1']) ? "" : $R[0]['DelimitadorEntrada1'],
            'delimitadorEntrada2' => is_null($R[0]['DelimitadorEntrada2']) ? "" : $R[0]['DelimitadorEntrada2'],
            'delimitadorEntrada3' => is_null($R[0]['DelimitadorEntrada3']) ? "" : $R[0]['DelimitadorEntrada3']              
        );
        $api = $urlapi;
        $metodo = 'guardarParamComandas';
        $this->ejecutarCurl($api, $metodo, $fields);
        // -------------------------------------------------------------------------

        $R = dbExecSP("dbo.spG_ParamAcept", [],TRUE);
        
        
        $fields = array(
            'Leyenda1' => is_null($R[0]['Leyenda1']) ? "" : $R[0]['Leyenda1'],
            'Leyenda2' => is_null($R[0]['Leyenda2']) ? "" : $R[0]['Leyenda2'],
            'Leyenda3' => is_null($R[0]['Leyenda3']) ? "" : $R[0]['Leyenda3'],
            'Leyenda4' => is_null($R[0]['Leyenda4']) ? "" : $R[0]['Leyenda4'],
            'LeyendaPie1' => is_null($R[0]['LeyendaPie1']) ? "" : $R[0]['LeyendaPie1'],
            'LeyendaPie2' => is_null($R[0]['LeyendaPie2']) ? "" : $R[0]['LeyendaPie2'],
            'LeyendaPie3' => is_null($R[0]['LeyendaPie3']) ? "" : $R[0]['LeyendaPie3'],
            'LeyendaPie4' => is_null($R[0]['LeyendaPie4']) ? "" : $R[0]['LeyendaPie4'],           
            'Propina' => is_null($R[0]['Propina']) ? "" : $R[0]['Propina'],
            'PropinaTxt' => is_null($R[0]['PropinaLeyenda']) ? "" : $R[0]['PropinaLeyenda'],
            'PropinaTxt2' => is_null($R[0]['PropinaLeyenda2']) ? "" : $R[0]['PropinaLeyenda2'],
            'PropinaAliasMozo' => is_null($R[0]['PropinaAliasMozo']) ? "" : $R[0]['PropinaAliasMozo'],            
            'PorcDesc' => is_null($R[0]['PorcDescPagoEfvo']) ? 0 : $R[0]['PorcDescPagoEfvo'],
            'idImpresoraTk' => is_null($R[0]['idImpresoraTicket']) ? 1 : $R[0]['idImpresoraTicket'],
            'aliasMozo' => ""
        );
        
        $metodo = 'guardarParamAcept';
        $this->ejecutarCurl($api, $metodo, $fields);
        // -------------------------------------------------------------------------

        $R = dbExecSP("dbo.spG_ParamSectores", [],TRUE);
        $fields = [];
        foreach ($R as $row) {
            $fields[] = array(
                'idSector' => $row['idSectorExped'],
                'descSector' => $row['Descripcion'],
                'combinaComandas' => $row['CombinaComandas'],
                'idImpresora' => $row['idImpresora'],
                'ip' => $row['IP'],
                'cantSaltos' => $row['CantSaltos'],
                'cantSaltosCut' => $row['CantSaltosCut'],
                'margen' => $row['Margen'],
                'anchoHoja' => $row['AnchoHoja']
            );
        }
        //echo "ParamSectores:".json_encode($fields);
       
        $metodo = 'guardarParamSectExp';
        $this->ejecutarCurl($api, $metodo, $fields);

    }

     private function ejecutarCurl(string $api, string $metodo, array $fields = []) {
        $url = $api . $metodo;
        $curl = curl_init();
        $json_string = json_encode($fields);
        curl_setopt($curl, CURLOPT_URL, $url);     
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_string);  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
        $R = curl_exec($curl);
        if(curl_errno($curl))
        {
            //echo 'Error Curl : ' . curl_error($curl);
            throw new Exception("Error al enviar el mensaje con CURL". " - " . curl_error($curl));
        }
        curl_close($curl);
               
        return $R;
    }

}