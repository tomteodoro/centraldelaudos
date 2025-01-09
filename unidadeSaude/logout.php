<!--Criado por Wellinton C. Teodoro em SET/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);

//include "registraLog.php";
?>

<?php
session_name(md5('seg'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']));
session_start();
$logado = $_SESSION['cnes'];
$prontuario = $_SESSION['prontuario'];
$caminho = "laudos/$prontuario/";
$caminhoAnexos = "anexos/$prontuario/";
$caminhoCR = "cr/$prontuario/";

if (is_dir($caminho) && $prontuario != '') {
    //echo "Existe";
    deletaPasta("$caminho");
} else {
    //echo "Não Existe";
}

if (is_dir($caminhoAnexos) && $prontuario != '') {
    //echo "Existe";
    deletaPasta("$caminhoAnexos");
} else {
    //echo "Não Existe";
}

if (is_dir($caminhoCR) && $prontuario != '') {
    //echo "Existe";
    deletaPasta("$caminhoCR");
} else {
    //echo "Não Existe";
}

//funcao que apaga laudos copiados apenas para exibição ao paciente
function deletaPasta($diretorio) {
    $arquivos = array_diff(scandir($diretorio), array('.', '..'));
    foreach ($arquivos as $arquivo) {
        (is_dir("$diretorio/$arquivo")) ? deletaPasta("$diretorio/$arquivo") : unlink("$diretorio/$arquivo");
    }
    return rmdir($diretorio);
}

unset($_SESSION['cnes']);
unset($_SESSION['senha']);
unset($_SESSION['prontuario']);
//REGISTRA O LOG
$arquivo = "../logs/unidades/$logado.log";
$fp = fopen($arquivo, "a+");
$data = date("d/m/Y");
$hora = date("H:i:s");
$ip = $_SERVER['REMOTE_ADDR'];
$msg = 'Saiu';
$texto = "[$ip][$data][$hora] > $msg \n";
fwrite($fp, $texto);
fclose($fp);
//Logger("$cnes.Saiu");
header('Location: ./');
?>
