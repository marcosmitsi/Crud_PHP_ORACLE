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
            $user = "system";
            $password = "243156";
            $host = "192.168.0.169";
            $port = "1521";
            $bd = "XE";

            $dsn = "//{$host}:{$port}/{$bd}";
            $conn = oci_connect($user, $password, $dsn);

            //Garante que o cabeçalho de conteúdo seja UTF-8
            header('Content-Type: text/html; charset=UTF-8');

            if (!$conn) {
                $e = oci_error($conn);
                echo !"" . $e['message'];
            } else {
                //echo "Conexão bem-sucedida!<br>";
            }

            if (!empty($_GET['action']) && $_GET['action'] == 'delete') {
                $id = (int) $_GET['id'];
                $sql = "DELETE FROM PESSOA WHERE id = :id";


                $stmt = oci_parse($conn, $sql); //Prepara a query para execução.
            
                oci_bind_by_name($stmt, ':id', $id); //Associa o valor id na query (segurança contra SQL Injection).
            
                // Executa a query no banco.
                if (oci_execute($stmt)) {
                    oci_commit($conn); // Confirma a exclusão
                    echo "<script>alert('Registro excluído com sucesso!');</script>";
                    echo "<script>window.location='pessoa_list.php';</script>"; // Após excluir, a página recarrega para refletir a mudança.
                } else {
                    $e = oci_error($stmt);
                    echo "Erro ao excluir registro: " . $e['message'];
                }
            }



            $sql = "SELECT * FROM PESSOA ORDER BY id";
            $result = oci_parse($conn, $sql);

            if (!oci_execute($result)) {
                $e = oci_error($result);
                die("Erro ao executar consulta: " . $e['message']);
            }

            // Busca os dados corretamente
            while ($row = oci_fetch_assoc($result)) {
                /***O Oracle pode estar armazenando os dados em um charset diferente (ISO-8859-1, WE8ISO8859P1, WE8MSWIN1252, etc.).mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1') converte para UTF-8 e corrige caracteres estranhos. */
                foreach ($row as $key => $value) {
                    //Dessa forma, se o valor for null, ele não tentará convertê-lo
                    if ($value !== null) {
                        $row[$key] = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
                    }
                }

                $id = $row['ID'];
                $nome = $row['NOME'];
                $endereco = $row['ENDERECO'];
                $bairro = $row['BAIRRO'];
                $telefone = $row['TELEFONE'];
                $email = $row['EMAIL'];
                $id_cidade = $row['ID_CIDADE'];

                print '<tr>';
                print "<td><a href='pessoa_form.php?action=edit&id={$id}'>
                            <img src='images/edit.svg' style='width:17px'>
                           </a></td>";
                print "<td><a href='pessoa_list.php?action=delete&id={$id}'>
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
    <button onclick="window.location='pessoa_form.php'">
        <img src="images/insert.svg" style="width: 17px;">Inserir
    </button>
</body>

</html>