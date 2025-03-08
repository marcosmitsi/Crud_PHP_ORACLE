<?php

$pessoa = [];
$pessoa['id'] = '';
$pessoa['nome'] = '';
$pessoa['endereco'] = '';
$pessoa['bairro'] = '';
$pessoa['telefone'] = '';
$pessoa['email'] = '';
$pessoa['id_cidade'] = '';


if (!empty($_REQUEST['action'])) {
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
    if (!$conn) //Verificação de erro na conexão:
    {
        $e = oci_error();
        die("Erro ao Conectar: " . $e['message']);
    }

    if ($_REQUEST['action'] == 'edit') {

        if (isset($_GET['id']) && !empty($_GET['id'])) {



            $id = (int) $_GET['id']; //Obtendo o ID:
            $sql_id = "SELECT * FROM PESSOA WHERE id ='{$id}'";  //Criação da consulta SQL
            $result = oci_parse($conn, $sql_id); //Preparação da consulta
            oci_execute($result); //precisa chamar a função oci_execute() depois de oci_parse() // EXECUTA A CONSULTA:
            $pessoa = oci_fetch_assoc($result); //Extração dos dados oci_fetch_assoc() tenta buscar um array associativo.
            // print_r($pessoa);

        }
    } elseif ($_REQUEST['action'] == 'save') {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $pessoa = $_POST;

        if (empty($_POST['id'])) {
            $result = oci_parse($conn, "SELECT MAX(ID) AS NEXT FROM PESSOA");
            oci_execute($result);
            $row = oci_fetch_assoc($result);
            $next = (int) $row['NEXT'] + 1;

            $sql = "INSERT INTO PESSOA (id, nome, endereco, bairro, telefone, email, id_cidade)
                        VALUES('{$next}','{$pessoa['nome']}','{$pessoa['endereco']}','{$pessoa['bairro']}','{$pessoa['telefone']}','{$pessoa['email']}','{$pessoa['id_cidade']}')";



            $result = oci_parse($conn, $sql);
            //oci_execute($result);  estamos chamando no IF abaixo




            if (!oci_execute($result)) {
                $erro = oci_error($result);
                echo "<script>alert('Erro ao inserir: ');</script> " . $erro['message'];
            } else {
                oci_commit($conn);
                echo "<script>alert('Registro inserido com sucesso!');</script>";
                echo "<script>window.location='pessoa_list.php';</script>"; // Após excluir, a página recarrega para 

            }
            oci_free_statement($result);
            oci_close($conn);

        } else {
            $sql = "UPDATE PESSOA SET nome      = '{$pessoa['nome']}',
                                      endereco  = '{$pessoa['endereco']}',
                                      bairro    = '{$pessoa['bairro']}',
                                      telefone  = '{$pessoa['telefone']}',
                                      email     = '{$pessoa['email']}',
                                      id_cidade = '{$pessoa['id_cidade']}'
                                   WHERE id     = '{$id}'";
            $result = oci_parse($conn, $sql);
            if (!oci_execute($result)) {
                $erro = oci_error($result);
                echo "<script>alert('Erro ao alterar: ')</script>" . $erro['message'];
            } else {
                oci_commit($conn);
                echo "<script>alert('Registro alterado com sucesso!')</script>";
                echo "<script>window.location ='pessoa_list.php';</script>";
            }
            oci_free_statement($result);
            oci_close($conn);

        }
    }
}

require_once 'lista_combo_cidades.php';
$cidades = lista_combo_cidade($pessoa['ID_CIDADE'] ?? '');

$form = file_get_contents('html/form.html');
$form = str_replace('{id}',        $pessoa['ID']        ?? '', $form);
$form = str_replace('{nome}',      $pessoa['NOME']      ?? '', $form);
$form = str_replace('{endereco}',  $pessoa['ENDERECO']  ?? '', $form);
$form = str_replace('{bairro}',    $pessoa['BAIRRO']    ?? '', $form);
$form = str_replace('{telefone}',  $pessoa['TELEFONE']  ?? '', $form);
$form = str_replace('{email}',     $pessoa['EMAIL']     ?? '', $form);
$form = str_replace('{id_cidade}', $pessoa['ID_CIDADE'] ?? '', $form);
$form = str_replace('{cidades}',   $cidades, $form);


print $form;


?>