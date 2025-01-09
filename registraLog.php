<!--Criado por Wellinton C. Teodoro em MAR/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);
?>

<?php
include "logger.php";

date_default_timezone_set('America/Sao_Paulo');

function Logger($msg) {

    //pega o path completo de onde esta executanto
    $caminho_atual = getcwd();

    //muda o contexto de execução para a pasta logs
    chdir("\var\www\html\laudosweb\logs");

    $data = date("d-m-y");
    $hora = date("H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'];

    //Nome do arquivo:
    $arquivo = "logs/log_$data.txt";

    //Texto a ser impresso no log:
    $texto = "[$ip][$hora] > $msg \n";

    $manipular = fopen("$arquivo", "a+b");
    fwrite($manipular, $texto);
    fclose($manipular);

    //Volta o contexto de execução para o caminho em que estava antes
    chdir($caminho_atual);
}
?>
