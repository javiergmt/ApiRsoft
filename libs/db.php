<?php

function db_connect()
{
    // Conexion a la base de datos
    global $DB;
    if ($DB) {
        //echo "Conexion ok!\n";
        return $DB;
    }
    try {

        if (file_exists('local.txt') || file_exists('local19.txt')) {
            $usr = "sa";
            $pwd = "6736";
            $db = "RestobarW";
            if ( file_exists('local.txt') ) {
                $host = ".\SQLEXPRESS";
            } else {
                $host = ".\SQLEXPRESS01";
            }
            
            $DB = new PDO("sqlsrv:Server=$host;Database=$db", $usr, $pwd, array(
            PDO::SQLSRV_ATTR_DIRECT_QUERY => TRUE,
            PDO::ATTR_EMULATE_PREPARES => TRUE,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //PDO::ATTR_STRINGIFY_FETCHES => FALSE,
            PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => TRUE
            ));
        } else {
            // Si las variables de Session estan vacias
            // Tomo los datos fijos de la base de datos General
            // Sino, tomo los datos de la Session
            if (!isset($_SESSION['logged']) || $_SESSION['logged'] === FALSE) {
                $usuario = getUsuario();
                $password = getPassword();
                $db = getDb();
            } else {
                $usuario = $_SESSION['db_usuario'];
                $password = $_SESSION['db_password'];
                $db = $_SESSION['db_nombre'];
            }
            $host =  getHost();
            //$host = "172.31.9.3";
            $DB = new PDO("sqlsrv:Server=".$host.";MultipleActiveResultSets=0;TrustServerCertificate=1;Database=".$db, $usuario, $password, array(
            PDO::SQLSRV_ATTR_DIRECT_QUERY => TRUE,
            PDO::ATTR_EMULATE_PREPARES => TRUE,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //PDO::ATTR_STRINGIFY_FETCHES => FALSE,
            PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => TRUE
            ));
        }
        
        if (!$DB) {
            throw new Exception("Imposible conectar a la Base de Datos: ");
        }
        //echo "Conexion ok!\n";
        $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $DB->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
        $DB->query("SET DATEFORMAT YMD");

        return $DB;
    } catch (Throwable $ex) {
        throw new Exception("Error fatal al conectar a la Base de Datos: " . $ex->getMessage());
    }
}

function dbExecSP(string $storeName, array $params = array(), $fetchAll = FALSE)
{
    $SQL = "EXEC " . $storeName . " " . implode(", ", array_map(function ($val) {
        return "@" . $val . "=?";
    }, array_keys($params)));

    //echo "SQL: $SQL\n";

    
    if (!$stmt = db_connect()->prepare($SQL)) {
        throw new Exception("Error al conectar a la Base de Datos: ");
    }
    try {
        $stmt->execute(array_values($params));

        if($stmt->columnCount() == 0) {
            if ($stmt->rowCount() == 0) {   
            $R = ["error" => "error al agregar registro"];
            } else {
            $R = ["mensaje" => "registro agregado o actualizado"];
            }
            return $R;
        }
        $columnsInfo = $stmt->getColumnMeta(0);
        if ($fetchAll) {
            $R = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } else {    
            $R = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // si el SP devuelve un resultado con una sola columna y el nombre de la columna comienza con "JSON", se interpreta como un JSON
            if (count(array_keys($R)) == 1 && substr(@array_keys($R)[0], 0, 4) == "JSON") {
                $R = json_decode(array_values($R)[0], TRUE);
            } 
            
            
        }
    } catch (Throwable $ex) {
        $debugMsg = $ex->getMessage();
        // solo para debug, eliminar en producción
        @file_put_contents("./db.error.log.txt", DATE("Y-m-d H:i:s") . "\n" . $storeName . print_r($params, 1) . $debugMsg . "\n\n-------------------------------------------\n", FILE_APPEND);

        // si en la base de datos hay un error, se lanza una excepción generica para no exponer información sensible
        throw new Exception("Error al ejecutar el procedimiento almacenado: " . $storeName . " ".$debugMsg);
    }

    return $R;
}
