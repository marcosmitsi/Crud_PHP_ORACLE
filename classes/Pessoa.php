<?php

class Pessoa
{
    public static function find($id)
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
            $result = $conn->query("SELECT * FROM pessoa WHERE id = '{$id}'");
            return $result->fetch();

        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }

    }

    public static function delete($id)
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
            $result = $conn->query("DELETE FROM pessoa WHERE id = '{$id}'");
            return $result;

        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }

    }

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
            $result = $conn->query("SELECT * FROM pessoa ORDER BY id");
            return $result->fetchAll();

        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }

    }

    public static function save($pessoa)
    {
        $host = "192.168.0.169";
        $porta = "1521";
        $bd = "XE";
        $usuario = "system";
        $senha = "243156";

      
            $dsn = "oci:dbname=//$host:$porta/$bd;charset=UTF8";
            $conn = new PDO($dsn, $usuario, $senha, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            if (empty($pessoa['id'])) {
                $result = $conn->query("SELECT MAX(id) AS NEXT FROM pessoa");
                $row = $result->fetch();
                $pessoa['id'] = (int) $row['NEXT'] + 1 ;
                $sql = "INSERT INTO PESSOA (id, nome, endereco, bairro, telefone, email, id_cidade)
                        VALUES('{$pessoa['id']}',
                               '{$pessoa['nome']}',
                               '{$pessoa['endereco']}',
                               '{$pessoa['bairro']}',
                               '{$pessoa['telefone']}',
                               '{$pessoa['email']}',
                               '{$pessoa['id_cidade']}')";

            } else {
                $sql = "UPDATE PESSOA SET nome      = '{$pessoa['nome']}',
                                          endereco  = '{$pessoa['endereco']}',
                                          bairro    = '{$pessoa['bairro']}',
                                          telefone  = '{$pessoa['telefone']}',
                                          email     = '{$pessoa['email']}',
                                          id_cidade = '{$pessoa['id_cidade']}'
                                    WHERE id        = '{$pessoa['id']}'";

            }

            return $conn->query($sql);


        
    }



}