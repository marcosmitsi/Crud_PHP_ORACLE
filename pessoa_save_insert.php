<?php

$dados = $_POST;

// CONEXÃO
$user = "system";
$password = "243156";
$host = "192.168.0.169";
$port = "1521";
$bd = "XE"; 

$dsn = "//{$host}:{$port}/{$bd}";
$conn = oci_connect($user, $password, $dsn, 'AL32UTF8');

// Garante que o cabeçalho do conteúdo seja UTF-8
header('Content-type: text/html; charset=UTF-8');

if (!$conn) {
    $e = oci_error();
    die("Erro ao conectar: " . $e['message']);
}

// Obtém o próximo ID corretamente
$sql_id = "SELECT MAX(ID) AS next FROM PESSOA"; 
$result = oci_parse($conn, $sql_id);

if (!oci_execute($result)) {
    $e = oci_error($result);
    die("Erro ao buscar próximo ID: " . $e['message']);
}

$row = oci_fetch_assoc($result);
$next = $row && $row['NEXT'] ? (int) $row['NEXT'] + 1 : 1; // Se não houver registros, começa do 1

// Prepara e executa a inserção
$sql = "INSERT INTO PESSOA (ID, NOME, ENDERECO, BAIRRO, TELEFONE, EMAIL, ID_CIDADE)
        VALUES (:id, :nome, :endereco, :bairro, :telefone, :email, :id_cidade)";

$stmt = oci_parse($conn, $sql);

oci_bind_by_name($stmt, ":id", $next);
oci_bind_by_name($stmt, ":nome", $dados["nome"]);
oci_bind_by_name($stmt, ":endereco", $dados["endereco"]);
oci_bind_by_name($stmt, ":bairro", $dados["bairro"]);
oci_bind_by_name($stmt, ":telefone", $dados["telefone"]);
oci_bind_by_name($stmt, ":email", $dados["email"]);
oci_bind_by_name($stmt, ":id_cidade", $dados["id_cidade"]);

$executed = oci_execute($stmt);

if ($executed) {
    echo "Registro inserido com sucesso!";
} else {
    $e = oci_error($stmt);
    die("Erro ao inserir: " . $e['message']);
}

oci_close($conn);




/*
foreach($dados as $informacoes)
{
    echo"{$informacoes} <br/>";

}
    */
