<!--Criado por Wellinton C. Teodoro em SET/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);

//include "registraLog.php";
?>

<script>

    function loginErro() {
        alert('Dados incorretos! Tente novamente.');
        setTimeout("window.location = './', 0");
    }

</script>

<?php
session_name(md5('seg'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']));
session_start();

if (isset($_POST['submit']) && !empty($_POST['cnes']) && !empty($_POST['senha'])) {
    //ACESSA
    include_once('../conexao.php');
    $cnes = $_POST['cnes'];
    $senha = $_POST['senha'];
    //CONVERTE CARACTERES DA SENHA PARA MAIUSCULOS
    //$novaSenha = strtoupper($senha);

    $query = "SELECT cnes, senha FROM ame_portal_unidades WHERE cnes = '$cnes' AND senha = '$senha'";
    $result = pg_query($conexao, $query) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
    $numrows = pg_numrows($result);

    if ($numrows > 0) {
        $_SESSION['cnes'] = $cnes;
        $_SESSION['senha'] = $senha;
        header('Location: pesquisaPaciente.php');
        //REGISTRA O LOG
        $arquivo = "../logs/unidades/$cnes.log";
        $fp = fopen($arquivo, "a+");
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'];
        $msg = 'Entrou';
        $texto = "[$ip][$data][$hora] > $msg \n";
        fwrite($fp, $texto);
        fclose($fp);
        //Logger("$protocolo.Entrou");
    } else {
        unset($_SESSION['cnes']);
        unset($_SESSION['senha']);
        //REGISTRA O LOG
        $arquivo = "../logs/unidades/$cnes.log";
        $fp = fopen($arquivo, "a+");
        $data = date("d/m/Y");
        $hora = date("H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'];
        $msg = 'Dados incorretos. Acesso Negado!';
        $texto = "[$ip][$data][$hora] > $msg \n";
        fwrite($fp, $texto);
        fclose($fp);
        //Logger("$protocolo.Dados incorretos. Acesso Negado!");
        echo '<script>loginErro()</script>';
    }
} else {
    //NÃO ACESSA
    header('Location: ./');
}
?>
