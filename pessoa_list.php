<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Listagem de Pessoas</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/list.css'>
    
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <td></td>
                <td></td>
                <td>Id</td>
                <td>Nome</td>
                <td>Endereço</td>
                <td>Bairro</td>
                <td>Telefone</td>
                <td>Email</td>
                <td>Cidade</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $user ="system";
            $password ="243156";
            $host = "192.168.0.169";
            $port = "1521";
            $bd = "XE";

            $dsn ="//{$host}:{$port}/{$bd}";
            $conn = oci_connect($user, $password, $dsn);

            //Garante que o cabeçalho de conteúdo seja UTF-8
            header('Content-Type: text/html; charset=UTF-8');

            if(!$conn)
            {
                $e = oci_error($conn);
                echo!"".$e['message'];
            }
            else
            {
                //echo "Conexão bem-sucedida!<br>";
            }
            $sql = "SELECT * FROM PESSOA";
            $result = oci_parse($conn, $sql);
            
            if (!oci_execute($result)) 
            {
                $e = oci_error($result);
                die("Erro ao executar consulta: " . $e['message']);
            }
            
            // Busca os dados corretamente
            while ($row = oci_fetch_assoc($result)) 
            {
             /***O Oracle pode estar armazenando os dados em um charset diferente (ISO-8859-1, WE8ISO8859P1, WE8MSWIN1252, etc.).mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1') converte para UTF-8 e corrige caracteres estranhos. */
                foreach ($row as $key => $value) {
                    //Dessa forma, se o valor for null, ele não tentará convertê-lo
                    if ($value !== null) {
                    $row[$key] = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
                    }
                }

                $id        = $row['ID'];
                $nome      = $row['NOME'];
                $endereco  = $row['ENDERECO'];
                $bairro    = $row['BAIRRO'];
                $telefone  = $row['TELEFONE'];
                $email     = $row['EMAIL'];
                $id_cidade = $row['ID_CIDADE'];
            
                print '<tr>';
                print "<td><a href='pessoa_form_edit.php?id={$id}'>
                            <img src='images/edit.svg' style='width:17px'>
                           </a></td>";
                print "<td><a href='pessoa_delete.php?id={$id}'>
                            <img src='images/remove.svg' style='width:17px'>
                           </a></td>";
                print "<td> {$id} </td>";
                print "<td> {$nome} </td>";
                print "<td> {$endereco} </td>";
                print "<td> {$bairro} </td>";
                print "<td> {$telefone} </td>";
                print "<td> {$email} </td>";
                print "<td> {$id_cidade} </td>";
                print '</tr>';
            }
            
            oci_close($conn);

            ?>
        </tbody>
    </table> 
    <button onclick="window.location='pessoa_form_insert.php'">
        <img src="images/insert.svg" style="width: 17px;">Inserir
    </button>       
</body>
</html>