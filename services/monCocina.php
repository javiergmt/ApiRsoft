 <?php

error_reporting(0);

class monCocina
{
    public function getMonCocina()
    {
        $R = dbExecSP("dbo.spG_MonCocina", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function getMonCocinaMesaDet(int $Delivery, int $NroMesa, int $idPedido)
    {
        $R = dbExecSP("dbo.spG_MonCocinaMesaDet", [
            "Delivery" => $Delivery,
            "NroMesa" => $NroMesa,
            "idPedido" => $idPedido
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

}