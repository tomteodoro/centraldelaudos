<!--Criado por Wellinton C. Teodoro em MAR/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);

//include "registraLog.php";
?>

<script>

    /*function cpfDuplicado() {
        alert('O CPF digitado está duplicado no cadastro do AME! \nEntre em contato para correção.');
        setTimeout("window.location = 'geraAcesso.php', 0");
    }*/

    function cpfErro() {
        alert('O CPF digitado não confere com o cadastrado no AME! \nTente novamente.');
        setTimeout("window.location = 'geraAcesso.php', 0");
    }

    function anoErro() {
        alert('O ano de nascimento digitado não confere com o cadastrado no AME! \nTente novamente.');
        setTimeout("window.location = 'geraAcesso.php', 0");
    }

    function celularErro() {
        alert('O celular digitado não confere com o(s) cadastrado(s) no AME! \nTente novamente.');
        setTimeout("window.location = 'geraAcesso.php', 0");
    }

    function validacaoOk() {
        alert('Suas informações foram validadas com sucesso!');
        //setTimeout("window.location = 'dadosAcesso.php', 0");
    }

    /*function smsEnviado() {
     alert('SMS enviado! Verifique os dados e faça login.');
     setTimeout("window.location = 'acessar.php', 0");
     }*/

    function mostraDados(div) {
        var display = document.getElementById(div).style.display;
        if (display == "none")
            document.getElementById(div).style.display = 'block';
        else
            document.getElementById(div).style.display = 'none';
    }

    function mostraInfos(elemento) {
        var display = document.getElementById(elemento).style.display;
        if (display == "none")
            document.getElementById(elemento).style.display = 'block';
        else
            document.getElementById(elemento).style.display = 'none';
    }

    function mostraBotao(entrar) {
        var display = document.getElementById(entrar).style.display;
        if (display == "none")
            document.getElementById(entrar).style.display = 'block';
        else
            document.getElementById(entrar).style.display = 'none';
    }

</script>

<?php
if (isset($_POST['submit']) && !empty($_POST['cpf']) && !empty($_POST['ano']) && !empty($_POST['celular'])) {
    //ENVIA
    include_once('../conexao.php');
    $cpf = $_POST['cpf'];
    
   if (str_contains($cpf, '.'))  {
        //NÃO APLICA MASCARA
        $cpfMascara = $cpf;
    } else {
        $cpf1 = substr_replace($cpf, '.', 3, 0);
        $cpf2 = substr_replace($cpf1, '.', 7, 0);
        $cpfMascara = substr_replace($cpf2, '-', 11, 0);
    }
    if (str_contains($cpf, '.')){
        $cpfSemMascara = trim($cpf);
        $cpfSemMascara = str_replace(array('.','-'), "", $cpf);
    } else {
        $cpfSemMascara = $cpf;
    }

    $ano = $_POST['ano'];
    $celular = $_POST['celular'];
    $exibeDadosAcesso = 0;

    //echo "CPF DIGITADO: " . $cpf . "<br>" . "Celular DIGITADO: " . $celular;
    //VERIFICA CPF
    $query = "SELECT prontuario, cpf, celular, nome, nacimento, telefone FROM view_paciente WHERE cpf = '$cpfMascara' OR cpf = '$cpfSemMascara'";
    $result = pg_query($conexao, $query) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='geraAcesso.php'>Voltar</a>");
    $numrows = pg_numrows($result);

    if ($numrows > 0 and $numrows <= 1) {
        //echo "<br><br>" . 'CPF ENCONTRADO!' . "<br><br>";
        while ($row = pg_fetch_row($result)) {
            //echo "PROTOCOLO NO BANCO: " . $row[0] . "<br>";
            $protocolo = $row[0];
            /* echo "CPF NO BANCO: " . $row[1] . "<br>";
              echo "CELULAR NO BANCO: " . $row[2] . "<br>";
              echo "CELULAR SECUNDARIO NO BANCO: " . $row[5] . "<br>";
              echo "NOME NO BANCO: " . $row[3] . "<br>";
              echo "NASCIMENTO NO BANCO: " . $row[4] . "<br>"; */
            //VERIFICA ANO
            $dataNascimento = $row[4];
            $anoNascimento = substr($dataNascimento, 0, 4);
            if ($ano === $anoNascimento) {
                //echo 'ANO ENCONTRADO!';
                //VERIFICA CELULAR
                $celularCadastrado = $row[2];
                $finalCelular = substr($celularCadastrado, -4);
                //VERIFICA TELEFONE
                $telefoneCadastrado = $row[5];
                $finalTelefone = substr($telefoneCadastrado, -4);
                if (($celular === $finalCelular) || ($celular === $finalTelefone)) {
                    //echo "<br>" . 'CELULAR OU TELEFONE ENCONTRADO!' . "<br><br>";
                    //GERA SENHA
                    $caracteres = '0123456789AME';
                    $senha = substr(str_shuffle($caracteres), 0, 6);
                    //echo "Senha gerada: " . $senha;
                    //VERIFICACAO DE PRONTUARIO
                    $queryVerificacao = "SELECT prontuario FROM ame_portal WHERE prontuario = $protocolo";
                    $resultVerificacao = pg_query($conexao, $queryVerificacao) or die("<br>Não foi possível verificar o prontuario para criação da senha!<br>Tente novamente.\n <br><br><a href='geraAcesso.php'>Voltar</a>");
                    $numrowsVerificacao = pg_numrows($resultVerificacao);
                    if ($numrowsVerificacao == 1) {
                        //ATUALIZA SENHA, POIS JA EXISTE PRONTUARIO NA TABELA (JÁ FOI FEITO O PRIMEIRO ACESSO A CENTRAL)
                        $queryUpdateSenha = "UPDATE ame_portal SET senha = '$senha' WHERE prontuario = $protocolo";
                        $resultUpdateSenha = pg_query($conexao, $queryUpdateSenha) or die("<br>Não foi possível atualizar a senha no banco de dados!<br>Tente novamente.\n <br><br><a href='geraAcesso.php'>Voltar</a>");
                        //echo "ENCONTROU O PRONTUARIO E ATUALIZOU A SENHA!";
                        //echo "<br><br>Dados de acesso gerados com sucesso!";
                        $exibeDadosAcesso = 1;
                        //REGISTRA O LOG
                        $arquivo = "../logs/$protocolo.log";
                        $fp = fopen($arquivo, "a+");
                        $data = date("d/m/Y");
                        $hora = date("H:i:s");
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $msg = 'Obteve os dados de acesso!';
                        $texto = "[$ip][$data][$hora] > $msg \n";
                        fwrite($fp, $texto);
                        fclose($fp);
                        //Logger("$protocolo.Obteve os dados de acesso!");
                        echo '<script>validacaoOk()</script>';
                    } elseif ($numrowsVerificacao == 0) {
                        //NAO ENCONTROU O PRONTUARIO, POIS E O PRIMEIRO ACESSO ENTAO...
                        //INSERE O PRONTUARIO E A SENHA GERADA
                        $queryInsert = "INSERT INTO ame_portal (prontuario, senha) VALUES ($protocolo, '$senha')";
                        $resultInsert = pg_query($conexao, $queryInsert) or die("<br>Não foi possível inserir o prontuario e o código de acesso no banco de dados!<br>Tente novamente.\n <br><br><a href='geraAcesso.php'>Voltar</a>");
                        //echo "<br><br>Dados de acesso gerados com sucesso!";
                        $exibeDadosAcesso = 1;
                        //REGISTRA O LOG
                        $arquivo = "../logs/$protocolo.log";
                        $fp = fopen($arquivo, "a+");
                        $data = date("d/m/Y");
                        $hora = date("H:i:s");
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $msg = 'Obteve os dados de acesso!';
                        $texto = "[$ip][$data][$hora] > $msg \n";
                        fwrite($fp, $texto);
                        fclose($fp);
                        //Logger("$protocolo.Obteve os dados de acesso!");
                        echo '<script>validacaoOk()</script>';
                    } elseif ($numrowsVerificacao > 1) {
                        //PACIENTE CONSEGUIU GERAR MAIS DE UMA VEZ (???), PARA O MESMO PRONTUARIO
                        //DELETA E DEPOIS INSERE NOVAMENTE
                        $queryDelete = "DELETE FROM ame_portal WHERE prontuario = $protocolo";
                        $resultDelete = pg_query($conexao, $queryDelete) or die("<br>Não foi possível inserir o prontuario e o código de acesso no banco de dados!<br>Tente novamente.\n <br><br><a href='geraAcesso.php'>Voltar</a>");
                        //INSERE O PRONTUARIO E A SENHA GERADA
                        $queryInsert = "INSERT INTO ame_portal (prontuario, senha) VALUES ($protocolo, '$senha')";
                        $resultInsert = pg_query($conexao, $queryInsert) or die("<br>Não foi possível inserir o prontuario e o código de acesso no banco de dados!<br>Tente novamente.\n <br><br><a href='geraAcesso.php'>Voltar</a>");
                        //echo "<br><br>Dados de acesso gerados com sucesso!";
                        $exibeDadosAcesso = 1;
                        //REGISTRA O LOG
                        $arquivo = "../logs/$protocolo.log";
                        $fp = fopen($arquivo, "a+");
                        $data = date("d/m/Y");
                        $hora = date("H:i:s");
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $msg = 'Obteve os dados de acesso!';
                        $texto = "[$ip][$data][$hora] > $msg \n";
                        fwrite($fp, $texto);
                        fclose($fp);
                        //Logger("$protocolo.Obteve os dados de acesso!");
                        echo '<script>validacaoOk()</script>';
                        //echo "<br><br>Erro ao gerar os dados de acesso!<br>Tente novamente.\n <br><br><a href='geraAcesso.php'>Voltar</a>";
                    }
                    if ($exibeDadosAcesso == 1) {
                        //EXIBE DADOS DE ACESSO
                        ?>
                        <!DOCTYPE html>
                        <html lang="pt">
                            <head>
                                <meta charset="UTF-8">
                                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                                        color: white;
                                        font-size: 15px;
                                        outline: none;
                                    }
                                    .inputSubmit:hover{
                                        background-color: deepskyblue;
                                        cursor: pointer;
                                    }
                                    .login{
                                        background-color: rgba(0, 0, 0, 0.6);
                                        position: relative;
                                        top: 50%;
                                        left: 50%;
                                        transform: translate(-50%,-50%);
                                        padding: 7%;
                                        border-radius: 15px;
                                        color: #fff;
                                        max-width: 400px;
                                    }
				    @-moz-document url-prefix() {
                                        .login{
                                            background-color: rgba(0, 0, 0, 0.6);
                                            position: relative;
                                            top: 50%;
                                            left: 50%;
                                            transform: translate(-50%,3%);
                                            padding: 7%;
                                            border-radius: 15px;
                                            color: #fff;
                                            max-width: 400px;
                                        }
                                    }
                                    @media screen and (max-width: 480px){
                                        .login{
                                            width: 85%;
                                            padding-top: 15%;
                                        }
                                    }
                                    .rodape{
                                        position: fixed;
                                        bottom: 0;
                                        height: 2.5rem;
                                        color: #fff;
                                        float: left;
                                    }
                                    .logo{
                                        position: absolute;
                                        top: 0%;
                                        align: middle;
                                    }
                                    .dados{
                                        background-color: red;
                                        border-radius: 15px;
                                        padding: 1%;
                                        text-align: left;
                                        position: absolute;
                                        top: 33%;
                                    }
                                    @media screen and (max-width: 480px){
                                        .dados{
                                            padding: 1%;
                                            top: 37%;
                                        }
                                    }
                                    .animacao{
                                        animation: animate 3.9s linear infinite;
                                        background-color: red;
                                        text-align: center;
                                    }
                                    @keyframes animate{
                                        0%{
                                            opacity: 0.3;
                                        }
                                        50%{
                                            opacity: 1;
                                        }
                                        100%{
                                            opacity: 0.3;
                                        }
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="login">
                                    <div class="logo">
                                        <image src="../imagens/cabecalho.png">
                                    </div>
                                    <h1>Central de Laudos</h1>
                                    <h5>Clique no botão abaixo para ver os seus dados de acesso.</h5>
                                    <br>
                                    <div>
                                        <button class="inputSubmit" type="button" onclick="mostraDados('dados');mostraInfos('infos');mostraBotao('botao');">Ver</button>
                                    </div>
                                    <div id="dados" class="dados" style="display:none">
                                        <h5>Protocolo: <?php echo $protocolo; ?></h5>
                                        <h5>Código de Acesso: <?php echo $senha; ?></h5>
                                    </div>
                                    <br><br>
                                    <form action="acessar.php">
                                        <h5 id="infos" align="justify" style="display:none"><p class="animacao">ATENÇÃO!</p>
                                            Anote esses dados para visualizar os resultados dos seus exames
                                            e <font color="red">NÃO</font> compartilhe com ninguém,
                                            pois essas informações são confidenciais e de sua inteira responsabilidade.</h5>
                                        <input id="botao" class="inputSubmit" type="submit" style="display:none" value="Entrar" autofocus>
                                    </form>
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
                    } else {
                        echo "<br><br>Erro ao gerar os dados de acesso!<br>Tente novamente.\n <br><br><a href='geraAcesso.php'>Voltar</a>";
                    }
                } else {
                    echo '<script>celularErro()</script>';
                }
            } else {
                echo '<script>anoErro()</script>';
            }
        }
    }
    //if ($numrows >= 2) {
//	echo '<script>cpfDuplicado()</script>';
  //  } else {
    else {
        echo '<script>cpfErro()</script>';
    }
} else {
    //NÃO ENVIA
    header('Location: geraAcesso.php');
}
?>
