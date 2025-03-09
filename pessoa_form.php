<?php

require_once 'bd/pessoa_db.php';

$pessoa = [];
$pessoa['id'] = '';
$pessoa['nome'] = '';
$pessoa['endereco'] = '';
$pessoa['bairro'] = '';
$pessoa['telefone'] = '';
$pessoa['email'] = '';
$pessoa['id_cidade'] = '';


if (!empty($_REQUEST['action'])) {


    if ($_REQUEST['action'] == 'edit') {

        if (isset($_GET['id']) && !empty($_GET['id'])) {



            $id = (int) $_GET['id']; //Obtendo o ID:
            $pessoa = get_pessoa($id);

        }
    } elseif ($_REQUEST['action'] == 'save') {
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $pessoa = $_POST;

        if (empty($_POST['id'])) {
            $pessoa['id'] = get_next_pessoa();
            $result = insert_pessoa($pessoa);
            echo "<script>alert('Registro alterado com sucesso!')</script>";
            echo "<script>window.location ='pessoa_list.php';</script>";

        } else {
            $result = update_pessoa($pessoa);
            echo "<script>alert('Registro alterado com sucesso!')</script>";
            echo "<script>window.location ='pessoa_list.php';</script>";
        }
    }
}

require_once 'lista_combo_cidades.php';
$cidades = lista_combo_cidade($pessoa['ID_CIDADE'] ?? '');

$form = file_get_contents('html/form.html');
$form = str_replace('{id}', $pessoa['ID'] ?? '', $form);
$form = str_replace('{nome}', $pessoa['NOME'] ?? '', $form);
$form = str_replace('{endereco}', $pessoa['ENDERECO'] ?? '', $form);
$form = str_replace('{bairro}', $pessoa['BAIRRO'] ?? '', $form);
$form = str_replace('{telefone}', $pessoa['TELEFONE'] ?? '', $form);
$form = str_replace('{email}', $pessoa['EMAIL'] ?? '', $form);
$form = str_replace('{id_cidade}', $pessoa['ID_CIDADE'] ?? '', $form);
$form = str_replace('{cidades}', $cidades, $form);


print $form;


?>