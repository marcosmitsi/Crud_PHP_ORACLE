<?php

class Pessoa
{
    private static $conn;
    private static function getConnection(): PDO
    {
        $ini          = parse_ini_file('./config/xe.ini');
        $host         = $ini['host'];
        $porta        = $ini['porta'];
        $service      = $ini['servicename'];
        $usuario      = $ini['usuario'];
        $senha        = $ini['senha'];
        $dsn          = "oci:dbname=//$host:$porta/$service;charset=UTF8";

        if (empty(self::$conn)) {
            self::$conn = new PDO(
                $dsn,
                $usuario,
                $senha,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_CASE               => PDO::CASE_LOWER,   // <<< chave em minúsculas
                ]
            );
        }
        return self::$conn;
    }

    /* ------------------------------------------------------------------
     * 2. BUSCAR UMA PESSOA
     * ------------------------------------------------------------------*/
    public static function find(int $id): ?array
    {
        $sql  = 'SELECT id, nome, endereco, bairro,
                        telefone, email, id_cidade
                 FROM   pessoa
                 WHERE  id = :id';

        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch() ?: null;   // null caso não exista
    }

    /* ------------------------------------------------------------------
     * 3. LISTAR TODAS AS PESSOAS
     * ------------------------------------------------------------------*/
    public static function all(): array
    {
        $sql = 'SELECT id, nome, endereco, bairro,
                       telefone, email, id_cidade
                FROM   pessoa
                ORDER  BY id';

        return self::getConnection()->query($sql)->fetchAll();
    }

    /* ------------------------------------------------------------------
     * 4. EXCLUIR
     * ------------------------------------------------------------------*/
    public static function delete(int $id): bool
    {
        $sql  = 'DELETE FROM pessoa WHERE id = :id';
        $stmt = self::getConnection()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /* ------------------------------------------------------------------
     * 5. INSERIR / ATUALIZAR
     * ------------------------------------------------------------------*/
    public static function save(array &$pessoa): void
    {
        $conn = self::getConnection();

        if (empty($pessoa['id'])) {
            // gera próximo ID (pode trocar por SEQUENCE se preferir)
            $maxId  = $conn->query('SELECT NVL(MAX(id),0)+1 AS prox FROM pessoa')
                           ->fetch()['prox'];
            $pessoa['id'] = $maxId;

            $sql = 'INSERT INTO pessoa
                       (id, nome, endereco, bairro, telefone, email, id_cidade)
                    VALUES
                       (:id, :nome, :endereco, :bairro, :telefone, :email, :id_cidade)';
        } else {
            $sql = 'UPDATE pessoa SET
                        nome      = :nome,
                        endereco  = :endereco,
                        bairro    = :bairro,
                        telefone  = :telefone,
                        email     = :email,
                        id_cidade = :id_cidade
                    WHERE id = :id';
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':id'        => $pessoa['id'],
            ':nome'      => $pessoa['nome'],
            ':endereco'  => $pessoa['endereco'],
            ':bairro'    => $pessoa['bairro'],
            ':telefone'  => $pessoa['telefone'],
            ':email'     => $pessoa['email'],
            ':id_cidade' => $pessoa['id_cidade'],
        ]);
    }
}