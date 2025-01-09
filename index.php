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
        <link rel="icon" href="imagens/icone.png" alt="Logo">
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
            .botao{
                background-color: white;
                padding: 7%;
                border-radius: 15px;
                height: 100%;
                width: 100%;
                cursor: pointer;
                color: black;
                border: none;
                font-size: 15px;
            }
            .btn-zoom {
                display:block;
                text-align:center;
                text-decoration:none;
                text-transform: uppercase;
                transition: transform 500ms cubic-bezier(0.68, -0.55, 0.265, 1.55), background-position 800ms cubic-bezier(0.68, -0.55, 0.265, 1.55), box-shadow 500ms linear;
                background-size:contain;
                background-position: -250px center;
                background-repeat: no-repeat;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            }
            .btn-zoom:hover {
                transform: scale(1.1);
                background-position: -60px;
                box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
            }
            .btn-zoom:active {
                transform: scale(1);
                background-position: 500px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            }
        </style>
    </head>
    <body>
        <div class="login">
            <div class="logo">
                <image src="imagens/cabecalho.png">
            </div>
            <h1>Central de Laudos</h1>
            <h4>Olá! Escolha uma opção:</h4>
            <br>
            <center>
                <a href="paciente" class="btn-zoom">
                    <button type="button" class="botao">
                        <image src="imagens/paciente.png" height="50px" width="50px">
                        <h3>PACIENTE</h3>
                    </button>
                </a>
                <br>
                <a href="unidadeSaude" class="btn-zoom">
                    <button type="button" class="botao">
                        <image src="imagens/unidade.png" height="50px" width="50px">
                        <h3>UNIDADE DE SAÚDE</h3>
                    </button>
                </a>
            </center>
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
