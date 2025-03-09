<?php

function get_pessoa($id)
{
    //Connection
    $user = "system";
    $password = "243156";
    $host = "192.168.0.169";
    $port = "1521";
    $bd = "XE";

    $dsn = "//{$host}:{$port}/{$bd}"; //Construção da DSN (Data Source Name):
    $conn = oci_connect($user, $password, $dsn, 'AL32UTF8'); //Conexão com o banco: DSN e especificando AL32UTF8.
    // Garate que o conteúdo seja Utf8
    header('content-type: text/html; charset=UTF-8'); //Essa linha garante que o navegador interprete a página como HTML em UTF-8.
    $sql = "SELECT * FROM PESSOA WHERE ID ='{$id}'";  //Criação da consulta SQL
    $result = oci_parse($conn, $sql); //Preparação da consulta
    oci_execute($result); //precisa chamar a função oci_execute() depois de oci_parse() // EXECUTA A CONSULTA:
    $pessoa = oci_fetch_assoc($result); //Extração dos dados oci_fetch_assoc() tenta buscar um array associativo.

    oci_free_statement($result); //liberar os recursos que $result estava utilizando.
    oci_close($conn);

    return $pessoa;

    //print_r($pessoa);

}

function exclui_pessoa($id)
{
    $user = "system";
    $password = "243156";
    $host = "192.168.0.169";
    $port = "1521";
    $bd = "XE";

    $dsn = "//{$host}:{$port}/{$bd}";
    $conn = oci_connect($user, $password, $dsn, 'AL32UTF8');
    header('content-type: text/html; charset=UTF-8');
    $sql = "DELETE FROM PESSOA WHERE ID ='{$id}'";
    $result = oci_parse($conn, $sql);
    oci_execute($result); // Adicionado para executar a query
    oci_free_statement($result);
    oci_close($conn);
    return $result;
}

function insert_pessoa($pessoa)
{
    $user = "system";
    $password = "243156";
    $host = "192.168.0.169";
    $port = "1521";
    $bd = "XE";

    $dsn = "//{$host}:{$port}/{$bd}";
    $conn = oci_connect($user, $password, $dsn, 'AL32UTF8');
    header('content-type: text/html; charset=UTF-8');

    $sql = "INSERT INTO PESSOA (id, nome, endereco, bairro, telefone, email, id_cidade)
            VALUES('{$pessoa['id']}',
                   '{$pessoa['nome']}',
                   '{$pessoa['endereco']}',
                   '{$pessoa['bairro']}',
                   '{$pessoa['telefone']}',
                   '{$pessoa['email']}',
                   '{$pessoa['id_cidade']}')";
    $result = oci_parse($conn, $sql);
    oci_execute($result); // Adicionado para executar a query
    oci_free_statement($result);
    oci_close($conn);
    return $result;
}

function update_pessoa($pessoa)
{
    $user = "system";
    $password = "243156";
    $host = "192.168.0.169";
    $port = "1521";
    $bd = "XE";

    $dsn = "//{$host}:{$port}/{$bd}";
    $conn = oci_connect($user, $password, $dsn, 'AL32UTF8');
    header('content-type: text/html; charset=UTF-8');
    $sql = "UPDATE PESSOA SET nome      = '{$pessoa['nome']}',
                              endereco  = '{$pessoa['endereco']}',
                              bairro    = '{$pessoa['bairro']}',
                              telefone  = '{$pessoa['telefone']}',
                              email     = '{$pessoa['email']}',
                              id_cidade = '{$pessoa['id_cidade']}'
                        WHERE id        = '{$pessoa['id']}'";
    $result = oci_parse($conn, $sql);
    oci_execute($result); // Adicionado para executar a query
    oci_free_statement($result);
    oci_close($conn);
    return $result;

}

function lista_pessoas()
{
     //Connection
     $user = "system";
     $password = "243156";
     $host = "192.168.0.169";
     $port = "1521";
     $bd = "XE";
 
     $dsn = "//{$host}:{$port}/{$bd}"; //Construção da DSN (Data Source Name):
     $conn = oci_connect($user, $password, $dsn, 'AL32UTF8'); //Conexão com o banco: DSN e especificando AL32UTF8.
     // Garate que o conteúdo seja Utf8
     header('content-type: text/html; charset=UTF-8'); //Essa linha garante que o navegador interprete a página como HTML em UTF-8.
     $sql = "SELECT * FROM PESSOA ORDER BY ID";  //Criação da consulta SQL
     $result = oci_parse($conn, $sql); //Preparação da consulta
     oci_execute($result); //precisa chamar a função oci_execute() depois de oci_parse() // 

     $list = [];
     oci_fetch_all($result, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW); // Busca todos os registros de uma vez.

     oci_free_statement($result); //liberar os recursos que $result estava utilizando.
     oci_close($conn);
 
     return $list;
 
     //print_r($list);
}

function get_next_pessoa()
{
    //Connection
    $user = "system";
    $password = "243156";
    $host = "192.168.0.169";
    $port = "1521";
    $bd = "XE";

    $dsn = "//{$host}:{$port}/{$bd}"; //Construção da DSN (Data Source Name):
    $conn = oci_connect($user, $password, $dsn, 'AL32UTF8'); //Conexão com o banco: DSN e especificando AL32UTF8.
    // Garate que o conteúdo seja Utf8
    header('content-type: text/html; charset=UTF-8'); //Essa linha garante que o navegador interprete a página como HTML em UTF-8.
    $sql = "SELECT max(ID) AS NEXT FROM PESSOA";  //Criação da consulta SQL
    $result = oci_parse($conn, $sql); //Preparação da consulta
    oci_execute($result); //precisa chamar a função oci_execute() depois de oci_parse() // EXECUTA A CONSULTA:
    $pessoa = oci_fetch_assoc($result); //Extração dos dados oci_fetch_assoc() tenta buscar um array associativo.
    $next = (int) $pessoa['NEXT'] + 1;

    oci_free_statement($result); //liberar os recursos que $result estava utilizando.
    oci_close($conn);

    return $next;
}


