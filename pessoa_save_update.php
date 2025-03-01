<?php

$dados = $_POST;

//var_dump($dados);

if($dados['id'])
{
     //Connection
     $user ="system";
     $password = "243156";
     $host ="192.168.0.169";
     $port ="1521";
     $bd ="XE";

     $dsn = "//{$host}:{$port}/{$bd}"; //Construção da DSN (Data Source Name):
     $conn = oci_connect($user, $password, $dsn, 'AL32UTF8'); //Conexão com o banco: DSN e especificando AL32UTF8.

     // Garate que o conteúdo seja Utf8
     header('content-type: text/html; charset=UTF-8'); //Essa linha garante que o navegador interprete a página como HTML em UTF-8.
     if(!$conn) //Verificação de erro na conexão:
     {
         $e = oci_error();
         die("Erro ao Conectar: ". $e['message']);
     }
     
     
     $sql = "UPDATE PESSOA SET NOME      = '{$dados['nome']}',
                               ENDERECO  = '{$dados['endereco']}', 
                               BAIRRO    = '{$dados['bairro']}',
                               TELEFONE  = '{$dados['telefone']}',
                               EMAIL     = '{$dados['email']}',
                               ID_CIDADE = '{$dados['id_cidade']}'
                               WHERE ID  = '{$dados['id']}'";  //Criação da consulta SQL

     $result = oci_parse($conn, $sql); //Preparação da consulta
     //oci_execute($result); //precisa chamar a função oci_execute() depois de oci_parse() // EXECUTA A CONSULTA:
     
     if(oci_execute($result)) //EXECUTA A CONSULTA:
     {
        echo"Registro Atualizado com sucesso";
     }
     else
     {
        $e = oci_error($result);
        die("Erro ao inserir: " . $e['message']);
     }

     //print_r($result);
     
     oci_close($conn);
}