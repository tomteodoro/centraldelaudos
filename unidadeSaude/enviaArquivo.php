<!-- Criado por Wellinton C. Teodoro em AGO/2024 - wteodoro@amesaojoao.unicamp.br -->

<?php
error_reporting(0);

require './vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

?>

<script>

	function erroTamanhoArquivo() {
        var erroTamanhoSearch = document.getElementById('dados');
        var erroTamanhoAnd = document.getElementById('data');
        alert('O arquivo PDF selecionado excede o tamanho máximo permitido! \nTente novamente.');
        window.location = 'pesquisaPaciente.php?search='+erroTamanhoSearch.value+'&and='+erroTamanhoAnd.value;
		return false;
    }

	function erroArquivoNaoPermitido() {
        var erroArquivoSearch = document.getElementById('dados');
        var erroArquivoAnd = document.getElementById('data');
        alert('O tipo de arquivo selecionado não é permitido! \nEnvie apenas arquivos PDF.');
        window.location = 'pesquisaPaciente.php?search='+erroArquivoSearch.value+'&and='+erroArquivoAnd.value;
		return false;
    }

	function erroAoEnviarArquivo() {
        var erroEnvioSearch = document.getElementById('dados');
        var erroEnvioAnd = document.getElementById('data');
        alert('O encaminhamento não foi enviado! \nPor favor, tente novamente.');
        window.location = 'pesquisaPaciente.php?search='+erroEnvioSearch.value+'&and='+erroEnvioAnd.value;
		return false;
    }

	function arquivoEnviado() {
        var search = document.getElementById('dados');
        var and = document.getElementById('data');
        alert('Encaminhamento enviado com sucesso!');
        window.location = 'pesquisaPaciente.php?search='+search.value+'&and='+and.value;
    }
	
</script>

<?php

    if(isset($_POST['enviar'])) {

        $pront = $_POST['pront'];

        $dados = $_POST['dados'];
        $data = $_POST['data'];

        ?>
            <!DOCTYPE html>
            <html lang="pt">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                    <link rel="icon" href="../imagens/icone.png" alt="Logo">
                    <title>Central de Laudos e Contrarreferências - AME São João da Boa Vista</title>
                    <style>
                        body{
                            font-family: Arial, Helvetica, sans-serif;
                            background: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
                        }
                    </style>
                </head>
                <body>
                    <input type="hidden" id="dados" name="dados" value='<?php echo $dados?>'/>
                    <input type="hidden" id="data" name="data" value='<?php echo $data?>'/>
                </body>
            </html>
        <?php

        if (!empty($_FILES['encaminhamento']['name'])) {

            $nomeArquivo = $_FILES['encaminhamento']['name'];													
            $tipoArquivo = $_FILES['encaminhamento']['type'];
            $nomeTemp = $_FILES['encaminhamento']['tmp_name'];
            $tamanho = $_FILES['encaminhamento']['size'];
            $erros = array();
            
            //VALIDA TAMANHO
            $tamanhoMaximo = 1024 * 1024 * 25; //25MB
            if($tamanho > $tamanhoMaximo) {
                echo '<script>erroTamanhoArquivo()</script>';
            } else {

                //VALIDA TIPO
                $arquivosPermitidos = ["pdf"];
                $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
                if(!in_array($extensao, $arquivosPermitidos)) {
                    echo '<script>erroArquivoNaoPermitido()</script>';
                } else {
                    $tiposPermitidos = ["application/pdf"];
                    if(!in_array($tipoArquivo, $tiposPermitidos)) {
                        echo '<script>erroArquivoNaoPermitido()</script>';
                    } else {
                        $caminho = '/mnt/ged-salutem/';
                        $hoje = date('dmY');

                        $encaminhamento = $_POST['select'];

                        switch ($encaminhamento) {
                            case 'Cardiologia':
                                $novoNomeArquivo = "6-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Cirurgia Geral':
                                $novoNomeArquivo = "7-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Cirurgia Vascular':
                                $novoNomeArquivo = "8-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Dermatologia':
                                $novoNomeArquivo = "9-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Dermatologia - Fototerapia':
                                $novoNomeArquivo = "10-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Dermatologia/Plástica - Tumor de Pele':
                                $novoNomeArquivo = "11-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Endocrinologia':
                                $novoNomeArquivo = "12-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Endocrinologia - Pré-Natal de Alto Risco':
                                $novoNomeArquivo = "13-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Gastroenterologia':
                                $novoNomeArquivo = "14-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Mastologia':
                                $novoNomeArquivo = "15-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Neurologia':
                                $novoNomeArquivo = "16-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Oftalmologia':
                                $novoNomeArquivo = "17-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Ortopedia':
                                $novoNomeArquivo = "18-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Otorrinolaringologia':
                                $novoNomeArquivo = "19-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Pneumologia':
                                $novoNomeArquivo = "20-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Proctologia':
                                $novoNomeArquivo = "21-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Reumatologia':
                                $novoNomeArquivo = "22-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Urologia':
                                $novoNomeArquivo = "23-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Audiometria/Imitanciometria':
                                $novoNomeArquivo = "24-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Biópsia de Próstata':
                                $novoNomeArquivo = "25-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Cistoscopia':
                                $novoNomeArquivo = "26-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Colonoscopia':
                                $novoNomeArquivo = "27-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Colposcopia':
                                $novoNomeArquivo = "28-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Densitometria':
                                $novoNomeArquivo = "29-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Ecocardiografia':
                                $novoNomeArquivo = "30-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Eletroencefalo (EEG)':
                                $novoNomeArquivo = "31-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Endoscopia':
                                $novoNomeArquivo = "32-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Espirometria':
                                $novoNomeArquivo = "33-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Histeroscopia':
                                $novoNomeArquivo = "34-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Holter':
                                $novoNomeArquivo = "35-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Laringoscopia':
                                $novoNomeArquivo = "36-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Mamografia':
                                $novoNomeArquivo = "37-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'MAPA':
                                $novoNomeArquivo = "38-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Otoneurológico':
                                $novoNomeArquivo = "39-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'PAAF de Tireóide':
                                $novoNomeArquivo = "40-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Ressonância Magnética':
                                $novoNomeArquivo = "41-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Retossigmoidoscopia':
                                $novoNomeArquivo = "42-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Teste Ergométrico':
                                $novoNomeArquivo = "43-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Tomografia Computadorizada':
                                $novoNomeArquivo = "44-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            case 'Ultrassonografia':
                                $novoNomeArquivo = "45-" . $pront . "-6-" . $hoje . ".pdf";
                                break;
                            default:
                                echo '<script>erroAoEnviarArquivo()</script>';
                                break;
                            }

                        if(move_uploaded_file($nomeTemp, $caminho . $novoNomeArquivo)) {                          
                            echo '<script>arquivoEnviado()</script>';
                        } else {
                            echo '<script>erroAoEnviarArquivo()</script>';
                        }
                    }
                }
            }

        }
    } else {
        header('Location: ./');
    }
?>
