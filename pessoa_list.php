<?php
require_once'bd/pessoa_db.php';


if (!empty($_GET['action']) && $_GET['action'] == 'delete') {
    $id = (int) $_GET['id'];
    exclui_pessoa($id);
        echo "<script>alert('Registro excluído com sucesso!');</script>";
        echo "<script>window.location='pessoa_list.php';</script>"; // Após excluir, a página recarrega para refletir a mudança.
   
}
    $pessoas = lista_pessoas();

$items ='';
// Busca os dados corretamente

if($pessoas){
    foreach ($pessoas as $pessoa) {
        //Dessa forma, se o valor for null, ele não tentará convertê-lo
           
    $item = file_get_contents('html/item.html');
    $item = str_replace('{id}',        $pessoa['ID'],        $item);
    $item = str_replace('{nome}',      $pessoa['NOME'],      $item);
    $item = str_replace('{endereco}',  $pessoa['ENDERECO'],  $item);
    $item = str_replace('{bairro}',    $pessoa['BAIRRO'],    $item);
    $item = str_replace('{telefone}',  $pessoa['TELEFONE'],  $item);
    $item = str_replace('{email}',     $pessoa['EMAIL'],     $item);
    $item = str_replace('{id_cidade}', $pessoa['ID_CIDADE'], $item);

    $items .= $item;  
}

}

$list = file_get_contents('html/list.html');
$list = str_replace('{items}', $items, $list);

print $list;
