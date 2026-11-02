<?php

error_reporting(0);

class monitor
{
    public function getMonPrincipal(int $Todo)
    {
        $R = dbExecSP("dbo.spG_MonPrincipal", [
            "Todo" => $Todo
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function getMonDetalle(int $Todo, int $idcub)
    {
        $R = dbExecSP("dbo.spG_MonDetalle", [
            "Todo" => $Todo,
            "idcub" => $idcub
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function getMonMozos()
    {
        $R = dbExecSP("dbo.spG_MonMozos", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function getMonProdxCant()
    {
        $R = dbExecSP("dbo.spG_MonProdxCant", [],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function getMonProdxOper(int $toper, int $idcub)
    {
        $R = dbExecSP("dbo.spG_MonProdxOper", [
            "toper" => $toper,
            "idcub" => $idcub
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }

    public function getMonProdxOrig(int $idcub)
    {
        $R = dbExecSP("dbo.spG_MonProdxOrig", [
            "idcub" => $idcub
        ],TRUE);

        if (!$R) {
            throw new Exception("Sin datos"); // si el SP no devuelve nada, se lanza una excepción generica
        }

        // DEVUELVO el resultado del SP, esto se convierte a JSON automáticamente
        return $R;
    }
    
}