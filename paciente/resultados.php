<!--Criado por Wellinton C. Teodoro em MAR/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);
?>

<?php
session_name(md5('seg' . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));
session_start();
//print_r($_SESSION);
if ((!isset($_SESSION['protocolo']) == true) and (!isset($_SESSION['senha']) == true)) {
    unset($_SESSION['protocolo']);
    unset($_SESSION['senha']);
    header('Location: ./');
}

$logado = $_SESSION['protocolo'];
include_once('../conexao.php');
include "../registraLog.php";

//NOME DO PACIENTE
$queryNome = "SELECT nome, prontuario FROM view_paciente WHERE prontuario = '$logado'";
$resultNome = pg_query($conexao, $queryNome) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='resultados.php'>Voltar</a>");
$numrowsNome = pg_numrows($resultNome);
while ($rowNome = pg_fetch_row($resultNome)) {
    $nome = $rowNome[0];
}
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
            .content{
                display: flex;
                margin: auto;
            }
            .tabela{
                width: 100%;
                text-align: center;
                background-color: rgba(255, 255, 255, 1);
                color: black;
                border-radius: 10px;
                border-spacing: 10px;

            }
            @media screen and (max-width: 480px){
                .content{
                    width: 94%;
                    border-spacing: 100px;
                }
                .tabela{
                    border-spacing: 25px;
                }
                .tabela thead{
                    display:none;
                }
                .tabela tbody td{
                    display: flex;
                    flex-direction: column;
                }
            }
            @media only screen and (min-width: 1200px){
                .content{
                    width:100%;
                }
                .tabela tbody tr td:nth-child(1){
                    width:10%;
                }
                .tabela tbody tr td:nth-child(2){
                    width:30%;
                }
                .tabela tbody tr td:nth-child(3){
                    width:20%;
                }
                .tabela tbody tr td:nth-child(4){
                    width:10%;
                }
                .tabela tbody tr td:nth-child(5){
                    width:30%;
                }
            }

            tr:nth-child(even) {
                background: #FFFFFF
            }
            tr:nth-child(odd) {
                background: #CDEBFF
            }
	    th:nth-child(even) {
		background: #FFFFFF
	    }
	    th:nth-child(odd) {
		background: #FFFFFF
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
                left: 1%;
                right: 1%;
                top: 1%;
                padding: 7%;
                border-radius: 15px;
                color: #fff;
            }
            .rodape{
                position: absolute;
                bottom: 0;
                height: 2.5rem;
                color: #fff;
            }
            @media screen and (max-width: 480px){
                h6{
                    visibility: hidden;
                }
            }
            .logo{
                position: absolute;
                top: 0%;
                align: middle;
            }
            .sair{
                background-color: red;
                border: none;
                color: white;
                border-radius: 5px;
                text-decoration: none;
                padding: 25%;
                font-size: 15px;
            }
            .sair:hover{
                background-color: #FE8484;
                color: white;
                text-decoration: none;
                cursor: pointer;
            }
            .posicaoSair{
                position: absolute;
                top: 20%;
                left: 70%;
            }
            @media screen and (max-width: 480px){
                .posicaoSair{
                    left: 89%;
                    top: 2%;
                    border-spacing: 100px;
                }
            }
            a{
                border: none;
                color: red;
                text-decoration: none;
            }
            a:hover{
                border: none;
                color: red;
                text-decoration: underline;
            }
            .design{
                animation: animate 3.9s linear infinite;
                color: red;
		background-color: white;
                font-weight: bold;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="login">
            <div class="logo">
                <image src="../imagens/cabecalho.png">
            </div>
            <div class="posicaoSair">
                <a href="logout.php" class="sair">Sair</a>
            </div>
            <br>
            <h3>Bem-vindo (a),</h3>
            <h2><?php echo $nome; ?></h2>
            <h5>• Essa é a lista dos seus exames dos últimos 12 meses; <p>• Para períodos anteriores, faça o pedido através do e-mail <a href=mailto:laudos-l@amesaojoao.unicamp.br>laudos-l@amesaojoao.unicamp.br</a>.</h5>
            <div class="content">
                <table class="tabela">
                    <thead>
                        <tr>
                            <th align='center'>Pedido</th>
                            <th align='center'>Exame</th>
                            <th align='center'>Data do Atendimento</th>
                            <!--<th align='center'>Data do Laudo</th>-->
                            <th align='center'>Visualizar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //LAUDOS A SEREM EXIBIDOS
                        $queryLaudos = "SELECT prontuario, cod_item, exame, to_char(data_laudo, 'DD/MM/YYYY'), to_char(data_agendamento, 'DD/MM/YYYY'), anexo_id from view_exames_laudados WHERE
						                exame NOT LIKE '%ELETROENCEFALO%' AND exame NOT LIKE '%HOLTER%' AND exame NOT LIKE '%MAPA%' AND
										exame NOT LIKE '%MAMOGRAFIA%' AND exame NOT LIKE '%TESTE ERGOMETRICO%' AND
										exame NOT LIKE '%AUDIOMETRIA%' AND exame NOT LIKE '%OTONEUROLOGICO%' AND exame NOT LIKE '%TESTES ALERGICOS DE CONTATO%' AND prontuario = '$logado' ORDER BY exame";
                        $resultLaudos = pg_query($conexao, $queryLaudos) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='acessar.php'>Voltar</a>");
                        $numrowsLaudos = pg_numrows($resultLaudos);

                        if ($numrowsLaudos > 0) {

                            //LAUDOS
                            $diretorio = '/mnt/laudos/' . $logado . '/';
                            //$diretorio = '//143.106.234.11/laudos/' . $logado . '/';
                            //Verifica se o diretório existe (se tem exame para exibir)
                            if (is_dir($diretorio)) {
                                //echo "Existe! Diretorio: " . $diretorio . "<br />";
                                $listaDiretorio = array_diff(scandir($diretorio), ['.', '..']);
                                //ORDEM DECRESCENTE DOS ARQUIVOS NA PASTA
                                krsort($listaDiretorio, SORT_STRING);

                                while ($rowLaudos = pg_fetch_row($resultLaudos)) {
                                    $pedidos = $rowLaudos[1];
                                    $exames = $rowLaudos[2];
                                    $datas = $rowLaudos[3];
                                    $dataAgendamento = $rowLaudos[4];
                                    $temAnexo = $rowLaudos[5];

                                    //echo "LAUDO: " . $pedidos . "<br>";

                                    if ($temAnexo == "" || (str_contains($exames, 'ELETROCARDIO')) || (str_contains($exames, 'DENSITO')) || (str_contains($exames, 'ESPIROMETRIA'))) {
                                        //echo "Exibe: " . $temAnexo;

                                        echo "<tr>";
                                        echo "<td align='center' autofocus>$pedidos</td>";
                                        if ((str_contains($exames, 'ELETROCARDIO')) || (str_contains($exames, 'DENSITO')) || (str_contains($exames, 'ESPIROMETRIA'))) {
                                            echo "<td align='center'>$exames - <strong>LAUDO</strong></td>";
                                        } else {
                                            echo "<td align='center'>$exames</td>";
                                        }
                                        echo "<td align='center'>$dataAgendamento</td>";
                                        //echo "<td align='center'>$datas</td>";
                                        //copia laudos para a central de laudos
                                        if (!file_exists("laudos")) {
                                            //echo "NÃO EXISTIA...";
                                            mkdir("laudos", 0777, true);
                                            //echo "AGORA EXISTE!";
                                        }
                                        mkdir("laudos/$logado");
                                        $laudo = "$diretorio$arquivos$pedidos.pdf";

                                        //echo "BANCO: " . "$pedidos.pdf" . "<br>";
                                        //echo "ARQUIVO: " . $arquivos . "<br>";
                                        //COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
                                        $copiaLaudo = "laudos/$logado/$arquivos$pedidos.pdf";
                                        if (!copy($laudo, $copiaLaudo)) {
                                            //echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
                                        } else {
                                            //echo "Copiou para a central de laudos!";
                                            echo "<td align='center'><a href='" . $copiaLaudo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
                                        }
                                        echo "</tr>";
                                    }
                                }
                            }
                        }

                        //ANEXOS A SEREM EXIBIDOS
                        $queryAnexos = "SELECT prontuario, exame, to_char(data_laudo, 'DD/MM/YYYY'), to_char(data_agendamento, 'DD/MM/YYYY'), anexo_id, cod_item, usuario_anexo from view_exames_laudados WHERE
						                prontuario = '$logado' ORDER BY exame";
                        $resultAnexos = pg_query($conexao, $queryAnexos) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='acessar.php'>Voltar</a>");
                        $numrowsAnexos = pg_numrows($resultAnexos);

                        if ($numrowsAnexos > 0) {

                            $contadorEspiro = 0;
                            /*$contadorEletrocardio = 0;
                            $contadorDensito = 0;*/

                            //ANEXOS
                            $diretorioAnexos = '/mnt/anexos/' . $logado . '/';
                            //$diretorioAnexos = '//143.106.234.11/anexos/' . $logado . '/';
                            //Verifica se o diretório existe (se tem exame para exibir)
                            if (is_dir($diretorioAnexos)) {
                                //echo "Existe! Diretorio: " . $diretorio . "<br />";
                                $listaDiretorioAnexos = array_diff(scandir($diretorioAnexos), ['.', '..']);

                                //ORDEM DECRESCENTE DOS ARQUIVOS NA PASTA
                                krsort($listaDiretorioAnexos, SORT_STRING);

                                while ($rowAnexos = pg_fetch_row($resultAnexos)) {
                                    $examesAnexos = $rowAnexos[1];
                                    $datasAnexos = $rowAnexos[2];
                                    $dataAgendamentoAnexos = $rowAnexos[3];
                                    $anexos = $rowAnexos[4];
                                    $pedidosAnexos = $rowAnexos[5];
                                    $usuarioAnexou = $rowAnexos[6];

                                    //echo $usuarioAnexou . "<br>";
                                    //echo $examesAnexos . "<br>";

                                    if ($anexos != 0) {

                                        $primeiroAnexo = $examesAnexos;

                                        if (str_contains($primeiroAnexo, 'ESPIROMETRIA')) {
                                            if ($contadorEspiro == 0) {
                                                echo "<tr>";
                                                echo "<td align='center' autofocus>$pedidosAnexos</td>";
                                                echo "<td align='center'>$examesAnexos - <strong>EXAME</strong></td>";
                                                echo "<td align='center'>$dataAgendamentoAnexos</td>";
                                                //echo "<td align='center'>$datasAnexos</td>";
                                                //copia laudos para a central de laudos
                                                if (!file_exists("anexos")) {
                                                    //echo "NÃO EXISTIA...";
                                                    mkdir("anexos", 0777, true);
                                                    //echo "AGORA EXISTE!";
                                                }
                                                mkdir("anexos/$logado");
                                                $anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

                                                //echo "BANCO: " . "$anexos.pdf" . "<br>";
                                                //echo "ARQUIVO: " . $arquivosAnexos . "<br>";
                                                //COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
                                                $copiaAnexo = "anexos/$logado/$arquivosAnexos$anexos.pdf";
                                                if (!copy($anexo, $copiaAnexo)) {
                                                    echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
                                                } else {
                                                    //echo "Copiou para a central de laudos!";
                                                    echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
                                                }
                                            } else {
                                                echo "<tr>";
                                                echo "<td align='center' autofocus>$pedidosAnexos</td>";
                                                echo "<td align='center'>$examesAnexos - <strong>LAUDO</strong></td>";
                                                echo "<td align='center'>$dataAgendamentoAnexos</td>";
                                                //echo "<td align='center'>$datasAnexos</td>";
                                                //copia laudos para a central de laudos
                                                if (!file_exists("anexos")) {
                                                    //echo "NÃO EXISTIA...";
                                                    mkdir("anexos", 0777, true);
                                                    //echo "AGORA EXISTE!";
                                                }
                                                mkdir("anexos/$logado");
                                                $anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

                                                //echo "BANCO: " . "$anexos.pdf" . "<br>";
                                                //echo "ARQUIVO: " . $arquivosAnexos . "<br>";
                                                //COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
                                                $copiaAnexo = "anexos/$logado/$arquivosAnexos$anexos.pdf";
                                                if (!copy($anexo, $copiaAnexo)) {
                                                    echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
                                                } else {
                                                    //echo "Copiou para a central de laudos!";
                                                    echo "<td><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
                                                }
                                            }

                                            $contadorEspiro = $contadorEspiro + 1;
                                            if ($contadorEspiro >= 2) {
                                                $contadorEspiro = 0;
                                            }
                                            $primeiroAnexo = "";
                                        } else if (str_contains($primeiroAnexo, 'ELETROCARDIO')) {
                                            //if ($contadorEletrocardio == 0) {
                                                echo "<tr>";
                                                echo "<td align='center' autofocus>$pedidosAnexos</td>";
                                                echo "<td align='center'>$examesAnexos - <strong>EXAME</strong></td>";
                                                echo "<td align='center'>$dataAgendamentoAnexos</td>";
                                                //echo "<td align='center'>$datasAnexos</td>";
                                                //copia laudos para a central de laudos
                                                if (!file_exists("anexos")) {
                                                    //echo "NÃO EXISTIA...";
                                                    mkdir("anexos", 0777, true);
                                                    //echo "AGORA EXISTE!";
                                                }
                                                mkdir("anexos/$logado");
                                                $anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

                                                //echo "BANCO: " . "$anexos.pdf" . "<br>";
                                                //echo "ARQUIVO: " . $arquivosAnexos . "<br>";
                                                //COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
                                                $copiaAnexo = "anexos/$logado/$arquivosAnexos$anexos.pdf";
                                                if (!copy($anexo, $copiaAnexo)) {
                                                    echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
                                                } else {
                                                    //echo "Copiou para a central de laudos!";
                                                    echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
                                                }
                                            /*} else {
                                                echo "<tr>";
                                                echo "<td align='center' autofocus>$pedidosAnexos</td>";
                                                echo "<td align='center'>$examesAnexos - <strong>LAUDO</strong></td>";
                                                echo "<td align='center'>$dataAgendamentoAnexos</td>";
                                                //echo "<td align='center'>$datasAnexos</td>";
                                                //copia laudos para a central de laudos
                                                if (!file_exists("anexos")) {
                                                    //echo "NÃO EXISTIA...";
                                                    mkdir("anexos", 0777, true);
                                                    //echo "AGORA EXISTE!";
                                                }
                                                mkdir("anexos/$logado");
                                                $anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

                                                //echo "BANCO: " . "$anexos.pdf" . "<br>";
                                                //echo "ARQUIVO: " . $arquivosAnexos . "<br>";
                                                //COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
                                                $copiaAnexo = "anexos/$logado/$arquivosAnexos$anexos.pdf";
                                                if (!copy($anexo, $copiaAnexo)) {
                                                    echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
                                                } else {
                                                    //echo "Copiou para a central de laudos!";
                                                    echo "<td><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
                                                }
                                            }

                                            $contadorEletrocardio = $contadorEletrocardio + 1;
                                            if ($contadorEletrocardio >= 2) {
                                                $contadorEletrocardio = 0;
                                            }*/
                                            $primeiroAnexo = "";
                                        } else if (str_contains($primeiroAnexo, 'DENSITO')) {
                                            //if ($contadorDensito == 0) {
                                                echo "<tr>";
                                                echo "<td align='center' autofocus>$pedidosAnexos</td>";
                                                echo "<td align='center'>$examesAnexos - <strong>EXAME</strong></td>";
                                                echo "<td align='center'>$dataAgendamentoAnexos</td>";
                                                //echo "<td align='center'>$datasAnexos</td>";
                                                //copia laudos para a central de laudos
                                                if (!file_exists("anexos")) {
                                                    //echo "NÃO EXISTIA...";
                                                    mkdir("anexos", 0777, true);
                                                    //echo "AGORA EXISTE!";
                                                }
                                                mkdir("anexos/$logado");
                                                $anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

                                                //echo "BANCO: " . "$anexos.pdf" . "<br>";
                                                //echo "ARQUIVO: " . $arquivosAnexos . "<br>";
                                                //COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
                                                $copiaAnexo = "anexos/$logado/$arquivosAnexos$anexos.pdf";
                                                if (!copy($anexo, $copiaAnexo)) {
                                                    echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
                                                } else {
                                                    //echo "Copiou para a central de laudos!";
                                                    echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
                                                }
                                            /*} else {
                                                echo "<tr>";
                                                echo "<td align='center' autofocus>$pedidosAnexos</td>";
                                                echo "<td align='center'>$examesAnexos - <strong>LAUDO</strong></td>";
                                                echo "<td align='center'>$dataAgendamentoAnexos</td>";
                                                //echo "<td align='center'>$datasAnexos</td>";
                                                //copia laudos para a central de laudos
                                                if (!file_exists("anexos")) {
                                                    //echo "NÃO EXISTIA...";
                                                    mkdir("anexos", 0777, true);
                                                    //echo "AGORA EXISTE!";
                                                }
                                                mkdir("anexos/$logado");
                                                $anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

                                                //echo "BANCO: " . "$anexos.pdf" . "<br>";
                                                //echo "ARQUIVO: " . $arquivosAnexos . "<br>";
                                                //COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
                                                $copiaAnexo = "anexos/$logado/$arquivosAnexos$anexos.pdf";
                                                if (!copy($anexo, $copiaAnexo)) {
                                                    echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
                                                } else {
                                                    //echo "Copiou para a central de laudos!";
                                                    echo "<td><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
                                                }
                                            }

                                            $contadorDensito = $contadorDensito + 1;
                                            if ($contadorDensito >= 2) {
                                                $contadorDensito = 0;
                                            }*/
                                            $primeiroAnexo = "";
                                        } else {
                                            echo "<tr>";
                                            echo "<td align='center' autofocus>$pedidosAnexos</td>";
                                            echo "<td align='center'>$examesAnexos</td>";
                                            echo "<td align='center'>$dataAgendamentoAnexos</td>";
                                            //echo "<td align='center'>$datasAnexos</td>";
                                            //copia laudos para a central de laudos
                                            if (!file_exists("anexos")) {
                                                //echo "NÃO EXISTIA...";
                                                mkdir("anexos", 0777, true);
                                                //echo "AGORA EXISTE!";
                                            }
                                            mkdir("anexos/$logado");
                                            $anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

                                            //echo "BANCO: " . "$anexos.pdf" . "<br>";
                                            //echo "ARQUIVO: " . $arquivosAnexos . "<br>";
                                            //COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
                                            $copiaAnexo = "anexos/$logado/$arquivosAnexos$anexos.pdf";
                                            if (!copy($anexo, $copiaAnexo)) {
                                                echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
                                            } else {
                                                //echo "Copiou para a central de laudos!";
                                                echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
                                            }
                                        }
                                        echo "</tr>";
                                        $primeiroAnexo = "";
                                    }
                                }
                            }
                        } else {
                            echo "<tr>";
                            echo "<td bgcolor='white'></td>";
                            echo "<td bgcolor='white'></td>";
                            echo "<td class='design'><br>NÃO EXISTEM EXAMES LAUDADOS NOS ÚLTIMOS 12 MESES! </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--<div class="rodape">
        <h6>©
        <?php
        //PEGAR ANO ATUAL
        echo date('Y', strtotime('0 years', strtotime(date('Y'))));
        ?>
        Copyright. AME São João da Boa Vista - SP.</h6>
        </div>-->
    </body>
</html>
