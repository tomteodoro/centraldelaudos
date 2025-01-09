<!--Criado por Wellinton C. Teodoro em MAR/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);
?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <link rel="icon" href="../imagens/icone.png" alt="Logo">
        <title>Central de Laudos - AME São João da Boa Vista</title>
        <style>
            body{
                font-family: Arial, Helvetica, sans-serif;
                background: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
            }
            .inputSubmit{
                background-color: dodgerblue;
                border: none;
                padding: 5%;
                border-radius: 10px;
                outline: none;
                color: white;
                font-size: 15px;
            }
            .inputSubmit:hover{
                background-color: deepskyblue;
                cursor: pointer;
            }
            .inputSubmitAcesso{
                background-color: #006055;
                border: none;
                padding: 5%;
                border-radius: 10px;
                color: white;
                font-size: 15px;
            }
            .inputSubmitAcesso:hover{
                background-color: #01C0BB;
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
                color: #fff;
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
        </style>
    </head>
    <body>
        <div class="login">
            <div class="logo">
                <image src="../imagens/cabecalho.png">
            </div>
            <h1>Central de Laudos</h1>
            <h4>Acesse e veja o resultado do seu exame.</h4>
            <h5>Como deseja continuar?</h5>
            <form action="acessar.php" method="POST">
                <input class="inputSubmit" type="submit" name="submit" value="Já tenho os dados de acesso" autofocus>
            </form>
            <br>
            <form action="geraAcesso.php" method="POST">
                <input class="inputSubmitAcesso" type="submit" name="submit" value="Ainda não tenho os dados de acesso">
            </form>
            <br>
            <a href="geraAcesso.php" class="esqueci"><h5>Esqueci meus dados</h5></a>
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
