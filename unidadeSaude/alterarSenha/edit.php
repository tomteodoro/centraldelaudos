<!--Criado por Wellinton C. Teodoro em SET/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);
?>

<script>

    function senhaAlterada() {
        alert('Código de Acesso alterado com sucesso!');
        setTimeout("window.location = '.././', 0");
    }
	
	function updateErro(){
		alert('Não foi possível alterar o código de acesso no banco de dados!\nInicie o processo novamente!');
        setTimeout("window.location = './', 0");
	}

</script>

<?php

if ( (!empty($_POST['senha']) ) && (!empty($_POST['confirmaSenha'])) ) {
	$senha = $_POST['senha'];
	$user = $_POST['user'];
	$userMaiusculo = strtoupper($user);
	$hash = $_POST['hash'];
	$cnes = $_POST['cnes'];
	
	include_once('../../conexao.php');
	include "../../registraLog.php";
	
	$queryUpdateSenha = "UPDATE ame_portal_unidades SET senha = '$senha' WHERE (email = '$user' OR email = '$userMaiusculo') AND hash = '$hash'";
	$resultUpdateSenha = pg_query($conexao, $queryUpdateSenha) or die("<br>Não foi possível alterar o código de acesso no banco de dados!<br>Inicie o processo novamente.\n <br><br><a href='./'>Voltar</a>");
	
	if ($resultUpdateSenha) {
		echo '<script>senhaAlterada()</script>';
		
		//eliminar user and hash
        $sql = "UPDATE ame_portal_unidades SET email = NULL, hash = NULL WHERE (email = '$user' OR email = '$userMaiusculo') AND hash = '$hash'";
        $result = pg_query($conexao, $sql) or die("<br>Não foi possível alterar o código de acesso no banco de dados!<br>Inicie o processo novamente.\n <br><br><a href='./'>Voltar</a>");
		
		//REGISTRA O LOG
		$arquivo = "../../logs/unidades/$cnes.log";
		$fp = fopen($arquivo, "a+");
		$data = date("d/m/Y");
		$hora = date("H:i:s");
		$ip = $_SERVER['REMOTE_ADDR'];
		$msg = 'Atualizou a senha!';
		$texto = "[$ip][$data][$hora] > $msg \n";
		fwrite($fp, $texto);
		fclose($fp);
	} else {
		echo '<script>updateErro()</script>';
	}
	
} else {
	//echo "FALTANDO...";
	header('Location: ./');
}

?>