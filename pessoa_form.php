<?php

require_once 'classes/Pessoa.php';
require_once 'classes/Cidades.php';

$pessoa = [];
$pessoa['ID'] = '';
$pessoa['NOME'] = '';
$pessoa['ENDERECO'] = '';
$pessoa['BAIRRO'] = '';
$pessoa['TELEFONE'] = '';
$pessoa['EMAIL'] = '';
$pessoa['ID_CIDADE'] = '';


if (!empty($_REQUEST['action'])) {

    try {
        if ($_REQUEST['action'] == 'edit') {

            if (isset($_GET['id']) && !empty($_GET['id'])) {



                $id = (int) $_GET['id']; //Obtendo o ID:
                $pessoa = Pessoa::find($id);
                

            }
        } elseif ($_REQUEST['action'] == 'save') {
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $pessoa = $_POST;

            Pessoa::save($pessoa);


            echo "<script>alert('Salvo com sucesso!')</script>";
            echo "<script>window.location ='pessoa_list.php';</script>";
        }

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

$cidades = '';
foreach(Cidades::all() as $cidade)
{
    $id = $cidade['ID'];
    $nome = $cidade['NOME'];
    $check = ($cidade['ID'] == $pessoa['ID_CIDADE']) ? 'selected = 1' : '';
    $cidades .= "<option {$check} value='{$id}'>{$nome}</option>";

}

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