<?php

class Pessoa
{
    private static $conn;
    public static function getConnection()
    {

        $ini = parse_ini_file('./config/xe.ini');

        $host = $ini['host'];  // IP do servidor Oracle
        $porta = $ini['porta'];          // Porta padrão do Oracle
        $servicename = $ini['servicename'];      // Nome do serviço ou SID
        $usuario = $ini['usuario']; // Usuário do banco
        $senha = $ini['senha'];     // Senha do banco

        // DSN (Data Source Name) para Oracle com Service Name
        $dsn = "oci:dbname=//$host:$porta/$servicename;charset=UTF8";



        // Criando a conexão PDO
        if (empty(self::$conn)) {
            self::$conn = new PDO($dsn, $usuario, $senha, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Ativar erros como exceções
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retornar arrays associativos

            ]);
        }

        return self::$conn;


    }
    public static function find($id)
    {
        $conn = self::getConnection();
        $result = $conn->prepare("SELECT * FROM pessoa WHERE id = :id");
        $result->execute([':id' => $id]);
        return $result->fetch();


    }

    public static function delete($id)
    {
        $conn = self::getConnection();
        $result = $conn->prepare("DELETE FROM pessoa WHERE id = :id");
        $result->execute([':id' => $id]);
        return $result;


    }

    public static function all()
    {

        $conn = self::getConnection();
        $result = $conn->query("SELECT * FROM pessoa ORDER BY id");
        return $result->fetchAll();


    }

    public static function save($pessoa)
    {
        $conn = self::getConnection();

        if (empty($pessoa['id'])) {
            $result = $conn->query("SELECT MAX(id) AS NEXT FROM pessoa");
            $row = $result->fetch();
            $pessoa['id'] = (int) $row['NEXT'] + 1;
            $sql = "INSERT INTO PESSOA (id, nome, endereco, bairro, telefone, email, id_cidade)
                        VALUES(:id, :nome, :endereco, :bairro, :telefone, :email, :id_cidade)";

        } else {
            $sql = "UPDATE PESSOA SET     nome      = :nome,
                                          endereco  = :endereco,
                                          bairro    = :bairro,
                                          telefone  = :telefone,
                                          email     = :email,
                                          id_cidade = :id_cidade
                                    WHERE id        = :id";

        }

        $result = $conn->prepare($sql);
        $result->execute([
            ':id' => $pessoa['id'],
            ':nome' => $pessoa['nome'],
            ':endereco' => $pessoa['endereco'],
            ':bairro' => $pessoa['bairro'],
            ':telefone' => $pessoa['telefone'],
            ':email' => $pessoa['email'],
            ':id_cidade' => $pessoa['id_cidade']

        ]);



    }



}