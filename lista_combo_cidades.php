<?php

function lista_combo_cidade($id_cidade = null)
{
    $user = "system";
    $password = "243156";
    $host = "192.168.0.169";
    $port = "1521";
    $bd = "XE";

    $dsn = "//{$host}:{$port}/{$bd}";
    $conn = oci_connect($user, $password, $dsn);


    // Garantir que o cabeçalho de conteúdo seja UTF-8
    header('Content-Type: text/html; charset=UTF-8');

    if (!$conn) {
        $e = oci_error($conn);
        echo "Erro ao consultar " . $e['message'];

    } else {
        // echo "Conexão bem-sucedida!<br>";
    }
    // oci_set_charset($conn, 'UTF8');
    $output = '';

    $sql = "SELECT id, nome FROM cidade";

    $pconn = oci_parse($conn, $sql);


    if (!oci_execute($pconn)) {
        $e = oci_error($pconn);
        echo "Erro ao executar consulta" . $e['message'];
    } else {
        //$row = oci_fetch_assoc($pconn);

        while ($row = oci_fetch_assoc($pconn)) {

            $id = $row["ID"];
            $nome = $row["NOME"];
            $check = ($id == $id_cidade) ? 'selected=1' : '';
            $output .= "<option {$check} value={$id}> $nome </option>";
        }


    }
    /* echo"<pre>"."<br>";
      var_dump($row);
      echo"</pre>";*/



    oci_close($conn);
    return $output;
}