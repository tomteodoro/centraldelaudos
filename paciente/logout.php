<!--Criado por Wellinton C. Teodoro em MAR/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);

//include "registraLog.php";
?>

<?php
session_name(md5('seg'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']));
session_start();
$logado = $_SESSION['protocolo'];
$caminho = "laudos/$logado/";
$caminhoAnexos = "anexos/$logado/";

if (is_dir($caminho)) {
    //echo "Existe";
    deletaPasta("$caminho");
} else {
    //echo "Não Existe";
}

if (is_dir($caminhoAnexos)) {
    //echo "Existe";
    deletaPasta("$caminhoAnexos");
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

unset($_SESSION['protocolo']);
unset($_SESSION['senha']);
//REGISTRA O LOG
$arquivo = "../logs/$logado.log";
$fp = fopen($arquivo, "a+");
$data = date("d/m/Y");
$hora = date("H:i:s");
$ip = $_SERVER['REMOTE_ADDR'];
$msg = 'Saiu';
$texto = "[$ip][$data][$hora] > $msg \n";
fwrite($fp, $texto);
fclose($fp);
//Logger("$protocolo.Saiu");
header('Location: acessar.php');
?>
