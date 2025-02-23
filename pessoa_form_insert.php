<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Cadastro de Pessoa</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/form.css'>
    <script src='main.js'></script>
</head>
<body>
    <form enctype="multipart/form-data" method="post" action="pessoa_save_insert.php">
        <label>Código</label>
        <input name="id" readonly="1" type="text" style="width:25%">
        <label>Nome</label>
        <input name="nome"  type="text" style="width:50%">
        <label>Endereço</label>
        <input name="endereco" type="text" style="width:50%">
        <label>Bairro</label>
        <input name="bairro" type="text" style="width:25%">
        <label>Telefone</label>
        <input name="telefone" type="text" style="width:25%">
        <label>Email</label>
        <input name="email" type="text" style="width:25%">
        <label>Cidade</label>
        <select name="id_cidade" style="width: 25%">
            <?php 
            require_once'lista_combo_cidades.php';
            print lista_combo_cidade();
            ?>
        </select>

        <input type="submit">
       
    </form>
    
</body>
</html>