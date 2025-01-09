<!--Criado por Wellinton C. Teodoro em SET/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);

//include "registraLog.php";
?>

<script>

    function cnesErro() {
        alert('O CNES digitado não confere com o cadastrado no Salutem! Tente novamente.');
        setTimeout("window.location = './', 0");
    }

    function validacaoOk() {
        alert('Unidade de Saúde cadastrada com sucesso!');
        setTimeout("window.location = './', 0");
    }
	
	function cnesJaCadastrado() {
        alert('Já existe esse CNES cadastrado! \nAltere o código de acesso.');
        setTimeout("window.location = './', 0");
    }

</script>

<?php
if (isset($_POST['submit']) && !empty($_POST['cnes'])) {
    //ENVIA
    include_once('../../conexao.php');
    $cnes = $_POST['cnes'];
	
	//echo $cnes;

    //VERIFICA CNES
    $query = "SELECT cnes FROM view_unidade WHERE cnes = '$cnes'";
    $result = pg_query($conexao, $query) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
    $numrows = pg_numrows($result);

    if ($numrows > 0 and $numrows <= 1) {
        //echo "<br><br>" . 'CNES ENCONTRADO!' . "<br><br>";
        while ($row = pg_fetch_row($result)) {
            //echo "CNES NO BANCO: " . $row[0] . "<br>";
            $cnes = $row[0];
            //GERA SENHA
            $caracteres = '0123456789AME';
            $senha = substr(str_shuffle($caracteres), 0, 6);
            //echo "Senha gerada: " . $senha;
            //VERIFICACAO DE CNES
            $queryVerificacao = "SELECT cnes FROM ame_portal_unidades WHERE cnes = '$cnes'";
            $resultVerificacao = pg_query($conexao, $queryVerificacao) or die("<br>Não foi possível verificar o cnes para criação da senha!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
            $numrowsVerificacao = pg_numrows($resultVerificacao);

            if ($numrowsVerificacao == 0) {
                //NAO ENCONTROU O CNES, POIS É O PRIMEIRO ACESSO ENTAO...
                //INSERE O CNES E A SENHA GERADA*/
                $queryInsert = "INSERT INTO ame_portal_unidades (cnes, senha) VALUES ('$cnes', '$senha')";
                $resultInsert = pg_query($conexao, $queryInsert) or die("<br>Não foi possível inserir o cnes e o código de acesso no banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
                //echo "<br><br>Dados de acesso gerados com sucesso!";
                //echo '<script>validacaoOk()</script>';
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
								top: 15%;
								left: 70%;
							}
							@media screen and (max-width: 480px){
								.posicaoVoltar{
									left: 86%;
									top: 15%;
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
							.acesso{
								background-color: RED;
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
							<h3>Unidade de Saúde cadastrada com sucesso!</h3>
							<h5><div class="acesso" id="senha">Código de Acesso:
							<?php echo $senha; ?></div></h5>
							<div class="posicaoVoltar">
								<a class="voltar" href="./">Voltar</a>
							</div>
						</div>
						<div class="rodape">
							<h6>©
								<?php
								//PEGAR ANO ATUAL
								echo date('Y', strtotime('0 years', strtotime(date('Y'))));
								?>
								Copyright. AME São João da Boa Vista - SP.</h6>
						</div>
					</body>
				</html>
				<?php
            } else {
                echo '<script>cnesJaCadastrado()</script>';
            }
        }
    } else {
        echo '<script>cnesErro()</script>';
    }
} else {
    //NÃO ENVIA
    header('Location: ./');
}
?>
