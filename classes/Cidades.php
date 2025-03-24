<?php

class Cidades
{
    public static function all()
    {
        $host = "192.168.0.169";
        $porta = "1521";
        $bd = "XE";
        $usuario = "system";
        $senha = "243156";

        try {
            $dsn = "oci:dbname=//$host:$porta/$bd;charset=UTF8";
            $conn = new PDO($dsn, $usuario, $senha, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $result = $conn->query("SELECT * FROM cidade ORDER BY id");
            return $result->fetchAll();

        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }

    }


}