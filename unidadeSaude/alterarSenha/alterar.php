<!--Criado por Wellinton C. Teodoro em SET/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);
?>

<script>

    function faltaDadosErro() {
        alert('Não foi possível alterar o código de acesso: Dados em falta!\nTente novamente.');
        setTimeout("window.location = './', 0");
    }

    function dadosIncorretosErro() {
        alert('Não foi possível alterar o código de acesso: Dados incorretos ou inválidos!\nRealize o processo novamente.');
        setTimeout("window.location = './', 0");
    }

</script>

<?php
if (empty($_GET['user']) || empty($_GET['hash'])) {
    die('<script>faltaDadosErro()</script>');
} else {

    include_once('../../conexao.php');
	include "../../registraLog.php";

    //$user = mysqli_real_escape_string($conn, $_GET['user']);
	$user = $_GET['user'];
	$userMaiusculo = strtoupper($user);
    $hash = $_GET['hash'];
	
	//CNES
	$queryCNES = "SELECT cnes FROM ame_portal_unidades WHERE (email = '$user' OR email = '$userMaiusculo') AND hash = '$hash'";
	$resultCNES = pg_query($conexao, $queryCNES) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
	$linhasCNES = pg_numrows($resultCNES);
	
	while ($rowCNES = pg_fetch_row($resultCNES)) {
		$cnes = $rowCNES[0];
	}
	
    $query = "SELECT COUNT(*) FROM ame_portal_unidades WHERE (email = '$user' OR email = '$userMaiusculo') AND hash = '$hash'";
	$result = pg_query($conexao, $query) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
	$linhas = pg_numrows($result);
	
	while ($row = pg_fetch_row($result)) {
		$quantidade = $row[0];
	}

    if ($quantidade == 1) {
        //echo 'Sucesso! (Mostrar formulário de alteração de password aqui)';
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
			<script>
			function validaSenha(){
				var form = document.getElementById('form');
				if( (form.senha.value != '' && form.confirmaSenha.value != '' ) && (form.senha.value != form.confirmaSenha.value) ) {
					alert("Os códigos de acesso não conferem! Tente novamente.");
					document.getElementById('senha').value='';
					document.getElementById('confirmaSenha').value='';
					form.senha.focus();
					return false;
				}
				return true;
			}
			</script>
            <body>
                <div class="login">
                    <div class="logo">
                        <image src="../../imagens/cabecalho.png">
                    </div>
					<h1>Central de Laudos e <br />Contrarreferências</h1>
                    <!--<h1>Central de Laudos</h1>-->
                    <form action="edit.php" method="POST" id="form">
                        <h5>Digite um novo código de acesso com <font color="red">no máximo 6 caracteres</font>:</h5>
                        <input type="password" name="senha" id="senha" placeholder="Novo Código de Acesso" required autofocus oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6">
                        <br><br>
						<input type="password" name="confirmaSenha" id="confirmaSenha" placeholder="Confirmar Código de Acesso" required autofocus oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="6">
                        <br><br>
						<input type="hidden" name="user" id="user" value="<?=$user?>">
						<input type="hidden" name="hash" id="hash" value="<?=$hash?>">
						<input type="hidden" name="cnes" id="cnes" value="<?=$cnes?>">
                        <input class="inputSubmit" type="submit" value="Alterar" onClick="return validaSenha();">
                    </form>
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
        echo '<script>dadosIncorretosErro()</script>';
    }
}
?>