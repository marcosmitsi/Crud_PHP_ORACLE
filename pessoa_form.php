<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Edição de Pessoa</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/form.css'>
    <script src='main.js'></script>
</head>

<body>
    <?php

    $id = '';
    $nome = '';
    $endereco = '';
    $bairro = '';
    $telefone = '';
    $email = '';
    $id_cidade = '';


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

            if (!empty($_GET['id'])) {


                $id = (int) $_GET['id']; //Obtendo o ID:
                $sql_id = "SELECT * FROM PESSOA WHERE id ='{$id}'";  //Criação da consulta SQL
                $result = oci_parse($conn, $sql_id); //Preparação da consulta
                oci_execute($result); //precisa chamar a função oci_execute() depois de oci_parse() // EXECUTA A CONSULTA:
                $row = oci_fetch_assoc($result); //Extração dos dados oci_fetch_assoc() tenta buscar um array associativo.
    
                $id = $row['ID'];
                $nome = $row['NOME'];
                $endereco = $row['ENDERECO'];
                $bairro = $row['BAIRRO'];
                $telefone = $row['TELEFONE'];
                $email = $row['EMAIL'];
                $id_cidade = $row['ID_CIDADE'];


                //print_r($row);
    
            }
        } elseif ($_REQUEST['action'] == 'save') {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $endereco = $_POST['endereco'];
            $bairro = $_POST['bairro'];
            $telefone = $_POST['telefone'];
            $email = $_POST['email'];
            $id_cidade = $_POST['id_cidade'];

            if (empty($_POST['id'])) {
                $result = oci_parse($conn, "SELECT MAX(ID) AS NEXT FROM PESSOA");
                oci_execute($result);
                $row = oci_fetch_assoc($result);
                $next = (int) $row['NEXT'] + 1;

                $sql = "INSERT INTO PESSOA (id, nome, endereco, bairro, telefone, email, id_cidade)
                        VALUES('{$next}','{$nome}','{$endereco}','{$bairro}','{$telefone}','{$email}','{$id_cidade}')";

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
                $sql = "UPDATE PESSOA SET nome      = '{$nome}',
                                          endereco  = '{$endereco}',
                                          bairro    = '{$bairro}',
                                          telefone  = '{$telefone}',
                                          email     = '{$email}',
                                          id_cidade = '{$id_cidade}'
                                          WHERE id  = '{$id}'";
                $result = oci_parse($conn, $sql);
                if(!oci_execute($result)){
                    $erro = oci_error($result);
                    echo"<script>alert('Erro ao alterar: ')</script>" . $erro['message'];
                }else{
                    oci_commit($conn);
                    echo"<script>alert('Registro alterado com sucesso!')</script>";
                    echo"<script>window.location ='pessoa_list.php';</script>";
                }
                oci_free_statement($result);
                oci_close($conn);

            }
        }
    }
    ?>
    <form enctype="multipart/form-data" method="post" action="pessoa_form.php?action=save">
        <label>Código</label>
        <input name="id" readonly="1" type="text" style="width:25%" value="<?= $id ?>">
        <label>Nome</label>
        <input name="nome" type="text" style="width:50%" value="<?= $nome ?>">
        <label>Endereço</label>
        <input name="endereco" type="text" style="width:50%" value="<?= $endereco ?>">
        <label>Bairro</label>
        <input name="bairro" type="text" style="width:25%" value="<?= $bairro ?>">
        <label>Telefone</label>
        <input name="telefone" type="text" style="width:25%" value="<?= $telefone ?>">
        <label>Email</label>
        <input name="email" type="text" style="width:25%" value="<?= $email ?>">
        <label>Cidade</label>
        <select name="id_cidade" style="width: 25%">
            <?php
            require_once 'lista_combo_cidades.php';
            print lista_combo_cidade($id_cidade);
            ?>
        </select>

        <input type="submit">

    </form>

</body>

</html>