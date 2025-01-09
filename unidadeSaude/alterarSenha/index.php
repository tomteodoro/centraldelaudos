<!--Criado por Wellinton C. Teodoro em SET/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(1);
?>

<script>
	
	function emailErro() {
        alert('E-mail não cadastrado para a unidade! \nTente novamente.');
        setTimeout("window.location = './', 0");
    }
	
	function envioEmailErro() {
        alert('Houve um erro ao enviar o e-mail! \nTente novamente.');
        setTimeout("window.location = './', 0");
    }
	
	function geraLinkErro() {
	alert('Não foi possível gerar o link de alteração do código de acesso! \nTente novamente.');
	setTimeout("window.location = './', 0");
    }
	
	function emailEnviado() {
	alert('Foi enviado um e-mail para o endereço que você digitou. \nNele você poderá encontrar um link único para alterar o seu código de acesso!');
	setTimeout("window.location = './', 0");
    }
	
</script>

<?php
  if( !empty($_POST) ){
    // processar o pedido
	
	include_once('../../conexao.php');
	include "../../registraLog.php";
	
	$user = $_POST['email'];
	$userMaiusculo = strtoupper($user);
	
	//EMAIL CADASTRADO
	$queryEmail = "SELECT * FROM view_unidade WHERE email = '$user' OR email = '$userMaiusculo'";
	$resultEmail = pg_query($conexao, $queryEmail) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
	$numrowsEmail = pg_numrows($resultEmail);
	while ($rowEmail = pg_fetch_row($resultEmail)) {
		$email = $rowEmail[4];
		$cnes = $rowEmail[1];
		$nomeUnidade = $rowEmail[0];
	}
	
	$from = utf8_decode("AME São João da Boa Vista");
	$subject = utf8_decode("Central de Laudos e Contrarreferências - Alteração do Código  de Acesso");
	$nomeUnidadeCorreto = utf8_decode($nomeUnidade);
	$a = utf8_decode("à");
	$cr = utf8_decode("Contrarreferências");
	$ola = utf8_decode("Olá");
	$msg1 = utf8_decode("Alguém solicitou a alteração do código de acesso do (a)");
	$msg2 = utf8_decode("Se isso foi um erro, apenas ignore este e-mail e nada acontecerá.");
	$codigoAcesso = utf8_decode("código");
	$msg3 = utf8_decode("Lembrando que, obrigatoriamente, o código de acesso pode ter até 06 (seis) caracteres APENAS e que o link acima ficará inválido após a alteração.
Qualquer dúvida, estamos à disposição.");
	$assinatura = utf8_decode("Setor de Tecnologia da Informação
AME São João da Boa Vista");
	
	/*echo "EMAIL: " . $email;
	echo "<br>CNES: " . $cnes;*/
 
    if( $numrowsEmail == 1 ){
      // o utilizador existe, vamos gerar um link único e enviá-lo para o e-mail 
      // gerar a chave
      // exemplo adaptado de http://snipplr.com/view/20236/
      $chave = sha1(uniqid( mt_rand(), true));
 
      // guardar este par de valores na tabela para confirmar mais tarde
      $conf = "UPDATE ame_portal_unidades SET email = '$user', hash = '$chave' WHERE cnes = '$cnes'";
	  $result = pg_query($conexao, $conf) or die("<br>Não foi possível confirmar o e-mail no banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
	  //echo "INSERT INTO recuperacao VALUES ('$user', '$chave')";
	  
	  $sql_cont = "SELECT COUNT(*) FROM ame_portal_unidades WHERE cnes = '$cnes'";
	  $resultCount = pg_query($conexao, $sql_cont) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
	  $numrowsCount = pg_numrows($resultCount);
 
      if ($numrowsCount == 1) {
 
        $link = "https://www.amesaojoao.unicamp.br/centraldelaudos/unidadeSaude/alterarSenha/alterar.php?user=$user&hash=$chave";
		$headers = "From: Setor de TI - $from <tiamesaojoao@gmail.com>";
 
        if( mail($user,
				"$subject",
				"$ola,

$msg1 $nomeUnidadeCorreto $a nossa Central de Laudos e $cr.

$msg2
Para alterar o seu $codigoAcesso de acesso, clique no link abaixo:

$link

$msg3

Atenciosamente,
--
$assinatura
(19) 3634-1125
www.amesaojoao.unicamp.br",
				$headers) ) {
          echo '<script>emailEnviado()</script>';
		  
		  //REGISTRA O LOG DE EMAIL ENVIADO
		$arquivo = "../../logs/unidades/$cnes.log";
		$fp = fopen($arquivo, "a+");
		$data = date("d/m/Y");
		$hora = date("H:i:s");
		$ip = $_SERVER['REMOTE_ADDR'];
		$msg = 'Recebeu e-mail de troca de senha!';
		$texto = "[$ip][$data][$hora] > $msg \n";
		fwrite($fp, $texto);
		fclose($fp);
		  
 
        } else {
          echo '<script>envioEmailErro()</script>'; 
        }
 
		// Apenas para testar o link, no caso do e-mail falhar
		//echo '<p>Link: '.$link.' (apresentado apenas para testes; nunca expor a público!)</p>';
 
      } else {
        echo '<script>geraLinkErro()</script>'; 
      }
    } else {
	  echo '<script>emailErro()</script>';
	}
  } else {
    // FORM
?>

	<!DOCTYPE html>
	<html lang="pt">
		<head>
			<meta charset="UTF-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
			<link rel="icon" href="../../imagens/icone.png" alt="Logo">
			<title>Central de Laudos e Contrarreferências - AME São João da Boa Vista</title>
			<!--<title>Central de Laudos - AME São João da Boa Vista</title>-->
			<style>
				body{
					font-family: Arial, Helvetica, sans-serif;
					background: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
				}
				input{
					padding: 5%;
					border-radius: 10px;
					outline: none;
					font-size: 15px;
				}
				.inputSubmit{
					background-color: dodgerblue;
					border: none;
					padding: 5%;
					border-radius: 10px;
					color: white;
					font-size: 15px;
				}
				.inputSubmit:hover{
					background-color: deepskyblue;
					cursor: pointer;
				}
				.login{
					background-color: rgba(0, 0, 0, 0.6);
					position: absolute;
					top: 50%;
					left: 50%;
					transform: translate(-50%,-50%);
					padding: 7%;
					border-radius: 15px;
					color: #fff
				}
				@media screen and (max-width: 480px){
					.login{
						width: 85%;
						padding-top: 15%;
					}
				}
				.rodape{
					position: absolute;
					bottom: 0;
					height: 2.5rem;
					color: #fff;
				}
				.logo{
					position: absolute;
					top: 0;
				}
				.posicaoVoltar{
					position: absolute;
					top: 35%;
					left: 80%;
				}
				@media screen and (max-width: 480px){
					.posicaoVoltar{
						left: 86%;
						top: 2%;
						border-spacing: 100px;
					}
				}
				.voltar{
					background-color: red;
					border: none;
					color: white;
					border-radius: 5px;
					text-decoration: none;
					padding: 15%;
					font-size: 15px;
				}
				.voltar:hover{
					background-color: #fe8484;
					cursor: pointer;
					text-decoration: none;
					color: WHITE;
				}
			.esqueci{
					border: none;
					color: white;
					font-size: 15px;
					text-decoration: none;
				}
				.esqueci:hover{
					border: none;
					color: red;
					font-size: 15px;
					text-decoration: underline;
				}
			</style>
		</head>
		<body>
			<div class="login">
				<div class="logo">
					<image src="../../imagens/cabecalho.png">
				</div>
				<h1>Central de Laudos e <br />Contrarreferências</h1>
				<!--<h1>Central de Laudos</h1>-->
				<form method="POST">
					<h5>Digite o e-mail da unidade de saúde:</h5>
					<input type="email" name="email" id="email" placeholder="E-mail" required autofocus>
					<br><br>
					<input class="inputSubmit" type="submit" name="submit" value="Enviar">
				</form>
				<div class="posicaoVoltar">
					<a class="voltar" href="../">Voltar</a>
				</div>
			</div>
			<div class="rodape">
				<h6>v4.1 - ©
					<?php
					//PEGAR ANO ATUAL
					echo date('Y', strtotime('0 years', strtotime(date('Y'))));
					?>
					Copyright. AME São João da Boa Vista - SP.</h6>
			</div>
		</body>
	</html>

<?php
  }
?>
