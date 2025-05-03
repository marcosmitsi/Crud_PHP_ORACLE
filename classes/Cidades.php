<?php

class Cidades
{
    public static function all(): array
    {
        $host     = '192.168.0.169';
        $porta    = '1521';
        $bd       = 'XE';
        $usuario  = 'system';
        $senha    = '243156';

        try {
            $dsn  = "oci:dbname=//$host:$porta/$bd;charset=UTF8";
            $conn = new PDO($dsn, $usuario, $senha, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_CASE               => PDO::CASE_LOWER,   // <<< CHAVES em minúsculas
            ]);

            // selecione apenas as colunas necessárias
            $sql = 'SELECT id, nome FROM cidade ORDER BY id';
            return $conn->query($sql)->fetchAll();

        } catch (PDOException $e) {
            echo 'Erro na conexão: ' . $e->getMessage();
            return [];                                   // evita warnings no foreach
        }
    }
}
