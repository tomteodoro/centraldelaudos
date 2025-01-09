<!--Criado por Wellinton C. Teodoro em SET/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);

require './vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

?>

<script>
	
	function pesquisaErro() {
        alert('*** Nenhum paciente encontrado! *** \n\nPor favor, verifique: \n• Se o paciente é do seu município; \n• O CPF e a Data de Nascimento digitados; \n• Se o cadastro do paciente está atualizado no AME São João.');
        setTimeout("window.location = 'pesquisaPaciente.php', 0");
    }
	
	function pesquisaErroAMEs() {
        alert('*** Nenhum paciente encontrado! *** \n\nPor favor, verifique: \n• O CPF e a Data de Nascimento digitados; \n• Se o cadastro do paciente está atualizado no AME São João.');
        setTimeout("window.location = 'pesquisaPaciente.php', 0");
    }
	
</script>

<?php
session_name(md5('seg' . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']));
session_start();
//print_r($_SESSION);
if ((!isset($_SESSION['cnes']) == true) and (!isset($_SESSION['senha']) == true)) {
    unset($_SESSION['cnes']);
    unset($_SESSION['senha']);
    header('Location: ./');
}

$logado = $_SESSION['cnes'];
include_once('../conexao.php');
include "../registraLog.php";

//DADOS DA UNIDADE
$queryNome = "SELECT nome, cnes, cidade_nome, cidade_id, email FROM view_unidade WHERE cnes = '$logado'";
$resultNome = pg_query($conexao, $queryNome) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
$numrowsNome = pg_numrows($resultNome);
while ($row = pg_fetch_row($resultNome)) {
    $nomeUnidade = $row[0];
	$cnesUnidade = $row[1];
	$nomeCidadeUnidade = $row[2];
	$idCidadeUnidade = $row[3];
	$emailUnidade = $row[4];
}

?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <link rel="icon" href="../imagens/icone.png" alt="Logo">
        <title>Central de Laudos e Contrarreferências - AME São João da Boa Vista</title>
        <!--<title>Central de Laudos - AME São João da Boa Vista</title>-->
        <style>
            body{
                font-family: Arial, Helvetica, sans-serif;
                background: linear-gradient(to right, rgb(20, 147, 220), rgb(17, 54, 71));
            }
            .content{
                display: block;
				//flex-direction:column;
                margin: auto;
				justify-content: center;
				//background-color: red;
            }
            .tabela{
                width: 100%;
                text-align: center;
                background-color: rgba(255, 255, 255, 1);
                color: black;
                border-radius: 10px;
                border-spacing: 10px;
				//margin-left: 100%;

            }
            @media screen and (max-width: 480px){
                .content{
                    width: 94%;
                    border-spacing: 100px;
					justify-content: center;
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
					justify-content: center;
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
				height: 7px;
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
                color: white;
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
			.pesquisa{
				float: left;
				//background-color: red;
				margin-bottom: 1%;
				margin-top: 0%;
				//padding-left: 32%;
			}
			@media screen and (max-width: 480px) {
				.pesquisa{
					//margin-top: 3%;
				}
			}
			.pesquisar{
				background-color: red;
				border: none;
                color: white;
                border-radius: 5px;
                text-decoration: none;
				padding: 2%;
				margin: 0.7%;
				font-size: 15px;
			}
			.pesquisar:hover{
                background-color: #FE8484;
                color: white;
                text-decoration: none;
                cursor: pointer;
            }
			@media screen and (max-width: 480px) {
				.pesquisar{
					margin-top: 3%;
					margin-left: 33%;
				}
			}
			.cpf{
			}
			@media screen and (max-width: 480px){
                .cpf{
                    font-size: 11px;
					margin-left: 10%;
                }
            }	
			.data{
			}
			@media screen and (max-width: 480px){
                .data{
                    font-size: 12px;
                }
            }
			span{
				color: red;
				font-size: 14px;
			}
			.top{
				margin-top: -4%;
				margin-bottom: 0%;
			}
			.textCpf{
				color: white;
			}
			@media screen and (max-width: 480px){
                .textCpf{
					margin-left: 10%;
                }
            }
			.textData{
				color: white;
			}
			@media screen and (max-width: 480px){
                .textData{
					position: absolute;
					margin-left: -9%;
                }
            }
			.dadosPaciente{
				//margin-bottom: 1%;
				//background-color: red;
			}
		.encaminhamento{
			color: black;
    			justify-content: space-between;
    			flex-wrap: wrap;
			background-color: white;
			border-radius: 10px;
			display: inline-block;
			//margin-bottom: 1%;
			//margin-left: 7%;
			padding-top: 1%;
			padding-left: 0.5%;
			font-family: Arial, Helvetica, sans-serif;
		}
		@media screen and (max-width: 480px) {
			.encaminhamento{
				margin-top: 7%;
				padding-top: 5%;
				padding-left: 3%;
			}
			.arquivo{
				padding-bottom: 7%;
			}
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
            <h2><?php echo $nomeUnidade;//<br>' . $cnesUnidade . '<br>' . $nomeCidadeUnidade . '<br>'; ?></h2>
            <h5>
			• Pesquise por exames e contrarreferências realizados <span>a partir de 01/09/2022</span>;
			<!-- • Pesquise por exames realizados <span>a partir de 01/09/2022</span>;-->
			<?php
			//VALIDAÇÃO AME e STA CASA MOGI GUAÇU, AME CASA BRANCA E CONDERG - AME e STA CASA SJ tbm
			if ($cnesUnidade == "6603432" || $cnesUnidade == "2096463" || $cnesUnidade == "6568459" || $cnesUnidade == "6895263" || $cnesUnidade == "2084228" || $cnesUnidade == "2082810") {
				echo "<p>• Preencha os campos abaixo para encontrar o paciente desejado, aperte Enter ou clique no botão Pesquisar e aguarde.";
			} else {
				echo "<p>• Preencha os campos abaixo para encontrar o paciente desejado, aperte Enter ou clique no botão Pesquisar e aguarde;";
				echo "<p>• Lembrando que é possível consultar <span>apenas os pacientes do seu município</span>.</p>";
			}
			?>
			</h5>
            <div class="content">
				<div class="pesquisa"><h5><p><strong>&nbsp <span class="textCpf">CPF:</span>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <span class="textData">Data de Nascimento:</span></strong></h5>
						<h5 class="top">
							<input type="text" id="pesquisaCPF" name="cpf" placeholder="000.000.000-00" class="cpf" style="width: 40%" required autofocus oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="14">
							<input type="date" id="pesquisaData" name="nascimento" placeholder="Data de Nascimento" class="data" style="width: 40%" required>
							<button onclick="searchPac();"class="pesquisar">Pesquisar</button>
						</h5>
				</div>
						<?php
						//PESQUISA
						if(!empty($_GET['search']) && !empty($_GET['and'])){
							$dados = $_GET['search'];
							$data = $_GET['and'];
							
							if (str_contains($dados, '.'))  {
								//NÃO APLICA MASCARA
								$dadosMascara = $dados;
							} else {
								$dados1 = substr_replace($dados, '.', 3, 0);
								$dados2 = substr_replace($dados1, '.', 7, 0);
								$dadosMascara = substr_replace($dados2, '-', 11, 0);
							}
							if (str_contains($dados, '.')){
								$dadosSemMascara = trim($dados);
 								$dadosSemMascara = str_replace(array('.','-'), "", $dados);
							} else {
								$dadosSemMascara = $dados;
							}
							
							/*echo "ID: " . $idCidadeUnidade;
							echo "<br>CIDADE: " . $nomeCidadeUnidade;
							echo "<br>DATA: " . $data;
							echo "<br>CPF: " . $dadosMascara;*/
							
							//VALIDAÇÃO AME e STA CASA MOGI GUAÇU, AME CASA BRANCA E CONDERG - AME e STA CASA SJ tbm
							if ($cnesUnidade == "6603432" || $cnesUnidade == "2096463" || $cnesUnidade == "6568459" || $cnesUnidade == "6895263" || $cnesUnidade == "2084228" || $cnesUnidade == "2082810") {
								//echo "ACESSAM QUALQUER PACIENTE!";

								//DADOS DO PACIENTE
								$queryPaciente = "SELECT nome, to_char(nacimento, 'DD/MM/YYYY'), cpf, cidade_nome, cidade_id, prontuario FROM view_paciente WHERE
												  nacimento = '$data' AND (cpf = '$dadosMascara' OR cpf = '$dadosSemMascara')";
								$resultPaciente = pg_query($conexao, $queryPaciente) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
								$numrowsPaciente = pg_numrows($resultPaciente);
								
								if ($numrowsPaciente > 0 and $numrowsPaciente <= 1) {
									while ($rowPaciente = pg_fetch_row($resultPaciente)) {
										$cpfPaciente = $rowPaciente[2];
										$nascimentoPaciente = $rowPaciente[1];
										$idCidadePaciente = $rowPaciente[4];
										$nomePaciente = $rowPaciente[0];
										$pront = $rowPaciente[5];
										?>
										<!--<div class="encaminhamento">
											<form action="enviaArquivo.php" method="post" enctype="multipart/form-data">
												<strong>Encaminhamento *</strong><br><br>
												<select id="select" name="select" required>
													<option value="">---</option>
													<option value="ESPECIALIDADES" style="font-weight:bold;" disabled>ESPECIALIDADES</option>
													<option value="Cardiologia">Cardiologia</option>
													<option value="Cirurgia Geral">Cirurgia Geral</option>
													<option value="Cirurgia Vascular">Cirurgia Vascular</option>
													<option value="Dermatologia">Dermatologia</option>
													<option value="Dermatologia - Fototerapia">Dermatologia - Fototerapia</option>
													<option value="Dermatologia/Plástica - Tumor de Pele">Dermatologia/Plástica - Tumor de Pele</option>
													<option value="Endocrinologia">Endocrinologia</option>
													<option value="Endocrinologia - Pré-Natal de Alto Risco">Endocrinologia - Pré-Natal de Alto Risco</option>
													<option value="Gastroenterologia">Gastroenterologia</option>
													<option value="Mastologia">Mastologia</option>
													<option value="Neurologia">Neurologia</option>
													<option value="Oftalmologia">Oftalmologia</option>
													<option value="Ortopedia">Ortopedia</option>
													<option value="Otorrinolaringologia">Otorrinolaringologia</option>
													<option value="Pneumologia">Pneumologia</option>
													<option value="Proctologia">Proctologia</option>
													<option value="Reumatologia">Reumatologia</option>
													<option value="Urologia">Urologia</option>
													<option value="" disabled></option>
													<option value="EXAMES" style="font-weight:bold;" disabled>EXAMES</option>
													<option value="Audiometria/Imitanciometria">Audiometria/Imitanciometria</option>
													<option value="Biópsia de Próstata">Biópsia de Próstata</option>
													<option value="Cistoscopia">Cistoscopia</option>
													<option value="Colonoscopia">Colonoscopia</option>
													<option value="Colposcopia">Colposcopia</option>
													<option value="Densitometria">Densitometria</option>
													<option value="Ecocardiografia">Ecocardiografia</option>
													<option value="Eletroencefalo (EEG)">Eletroencefalo (EEG)</option>
													<option value="Endoscopia">Endoscopia</option>
													<option value="Espirometria">Espirometria</option>
													<option value="Histeroscopia">Histeroscopia</option>
													<option value="Holter">Holter</option>
													<option value="Laringoscopia">Laringoscopia</option>
													<option value="Mamografia">Mamografia</option>
													<option value="MAPA">MAPA</option>
													<option value="Otoneurológico">Otoneurológico</option>
													<option value="PAAF de Tireóide">PAAF de Tireóide</option>
													<option value="Ressonância Magnética">Ressonância Magnética</option>
													<option value="Retossigmoidoscopia">Retossigmoidoscopia</option>
													<option value="Teste Ergométrico">Teste Ergométrico</option>
													<option value="Tomografia Computadorizada">Tomografia Computadorizada</option>
													<option value="Ultrassonografia">Ultrassonografia</option>
												</select>
												<strong><p style = 'font-size:12px'>* Envie apenas arquivos PDF.</strong>
												<input type="file" class="arquivo" id="encaminhamento" name="encaminhamento" required/>
												<input type="hidden" name="pront" value='<?php echo $pront?>'/>
												<input type="hidden" name="dados" value='<?php echo $dados?>'/>
												<input type="hidden" name="data" value='<?php echo $data?>'/>
												<button type="submit" id="enviar" name="enviar" class="pesquisar">Enviar</button>
											</form>
										</div>
										<br><br><br>-->
										<br><br><br><br><br><br><br><br><br>
										<div class="dadosPaciente">
											<strong>Paciente:</strong>
											<!--<?php //echo " &nbsp " . $nomePaciente . " &nbsp  | &nbsp " . $dadosMascara . " &nbsp  | &nbsp " . $nascimentoPaciente; ?>-->
											<?php echo " &nbsp " . $nomePaciente . " &nbsp  | &nbsp ";
											if (str_contains($dados, '.'))  {
												echo "$dados &nbsp  | &nbsp $nascimentoPaciente";
											} else {
												$dados1 = substr_replace($dados, '.', 3, 0);
												$dados2 = substr_replace($dados1, '.', 7, 0);
												$dadosMascara = substr_replace($dados2, '-', 11, 0);
												echo "$dadosMascara &nbsp  | &nbsp $nascimentoPaciente";
											}
											?>
											<br>
										</div>
										<br>
										<!--<hr>-->
										<h4 align='center'>EXAMES</h4>
										<table class="tabela">
										<thead>
											<tr>
												<th align='center'>Pedido</th>
												<th align='center'>Exame</th>
												<th align='center'>Data do Atendimento</th>
												<!--<th align='center'>Data do Laudo</th>-->
												<th align='center'>Laudo/Exame</th>
												<th align='center'>Imagem</th>
											</tr>
										</thead>
										<tbody>
											<?php
											//LAUDOS A SEREM EXIBIDOS
											$queryLaudos = "SELECT prontuario, cod_item, exame, to_char(data_laudo, 'DD/MM/YYYY'), to_char(data_agendamento, 'DD/MM/YYYY'), anexo_id, cpf, modalidade_synapse from view_exames_laudados_unidades WHERE
															exame NOT LIKE '%ELETROENCEFALO%' AND exame NOT LIKE '%HOLTER%' AND exame NOT LIKE '%MAPA%' AND
															exame NOT LIKE '%MAMOGRAFIA%' AND exame NOT LIKE '%TESTE ERGOMETRICO%' AND
															exame NOT LIKE '%AUDIOMETRIA%' AND exame NOT LIKE '%OTONEUROLOGICO%' AND exame NOT LIKE '%TESTES ALERGICOS DE CONTATO%' AND
															(cpf = '$dadosMascara' OR cpf = '$dadosSemMascara') ORDER BY exame";
											$resultLaudos = pg_query($conexao, $queryLaudos) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.");
											$numrowsLaudos = pg_numrows($resultLaudos);

											if ($numrowsLaudos > 0) {
												while ($rowLaudos = pg_fetch_row($resultLaudos)) {
													//PRONTUARIO
													$prontuario = $rowLaudos[0];
													$_SESSION['prontuario'] = $prontuario;
													//LAUDOS
													$diretorio = '/mnt/laudos/' . $prontuario . '/';
													//$diretorio = '//143.106.234.11/laudos/' . $prontuario . '/';
													//Verifica se o diretório existe (se tem exame para exibir)
													if (is_dir($diretorio)) {
														//echo "Existe! Diretorio: " . $diretorio . "<br />";
														$listaDiretorio = array_diff(scandir($diretorio), ['.', '..']);
														//ORDEM DECRESCENTE DOS ARQUIVOS NA PASTA
														krsort($listaDiretorio, SORT_STRING);
														
														$pedidos = $rowLaudos[1];
														$exames = $rowLaudos[2];
														$datas = $rowLaudos[3];
														$dataAgendamento = $rowLaudos[4];
														$temAnexo = $rowLaudos[5];
														$modalidade = $rowLaudos[7];

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
															mkdir("laudos/$prontuario");
															$laudo = "$diretorio$arquivos$pedidos.pdf";

															//echo "BANCO: " . "$pedidos.pdf" . "<br>";
															//echo "ARQUIVO: " . $arquivos . "<br>";
															//COPIA LAUDOS
															$copiaLaudo = "laudos/$prontuario/$arquivos$pedidos.pdf";
															if (!copy($laudo, $copiaLaudo)) {
																//echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
															} else {
																//echo "Copiou para a central de laudos!";
																echo "<td align='center'><a href='" . $copiaLaudo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																//IMAGENS
																if ( ($modalidade != "CT") && ($modalidade != "MR") && ($modalidade != "US") && ($modalidade != "ECG") && ($modalidade != "CR") && ($modalidade != "ES") )  {
																	echo "<td align='center'>---</td>";
																} else {
																	$pedidosSalutem = $pedidos . "SLT";																	
																	if ( ($modalidade == "CT") || ($modalidade == "MR") ) {
																		$key = '723b5e76e0fa33bca49d82443c50b941f0951aca';
																		$payload = [
																			"iss" => "AURORA",
																			"aud" => "XVIEWER",
																			"user" => ["id" => "", "name" => ""],
																			"iat" => strtotime("now"),
																			"exp" => strtotime("now") + (24*60*60),
																			"jti" => "1892ASD8ASDA9SDA98SD7",
																			"accessionnumber" => "$pedidos"
																		];
																	} else if ( ($modalidade == "US") || ($modalidade == "ECG") || ($modalidade == "CR") || ($modalidade == "ES") ) {
																		$key = '723b5e76e0fa33bca49d82443c50b941f0951aca';
																		$payload = [
																			"iss" => "AURORA",
																			"aud" => "XVIEWER",
																			"user" => ["id" => "", "name" => ""],
																			"iat" => strtotime("now"),
																			"exp" => strtotime("now") + (24*60*60),
																			"jti" => "1892ASD8ASDA9SDA98SD7",
																			"accessionnumber" => "$pedidosSalutem"
																		];
																	}
																	
																	//echo $pedidosSalutem . "<br>";
																	 
																	$token= JWT::encode($payload, $key, 'HS256'); 

																	$decoded = JWT::decode($token, new Key($key, 'HS256'));

																	//print_r($decoded);
																	
																	$ip = $_SERVER['REMOTE_ADDR'];
																	//echo $ip;
																	if ( (str_contains($ip, '143.106.234.')) ) {
																		echo "<td align='center'><a href='http://143.106.234.3:8090/open?token=".$token."' target='_blank'><image src='../imagens/imagem.png' height='' width=''></a></td>";
																	} else {
																		echo "<td align='center'><a href='http://143.106.199.148:8090/open?token=".$token."' target='_blank'><image src='../imagens/imagem.png' height='' width=''></a></td>";
																	}
																}
															}
															echo "</tr>";
														}
													}
												}
											}

											//ANEXOS A SEREM EXIBIDOS
											$queryAnexos = "SELECT prontuario, exame, to_char(data_laudo, 'DD/MM/YYYY'), to_char(data_agendamento, 'DD/MM/YYYY'), anexo_id, cod_item, usuario_anexo, cpf, modalidade_synapse from view_exames_laudados_unidades WHERE
															(cpf = '$dadosMascara' OR cpf = '$dadosSemMascara') ORDER BY exame";
											$resultAnexos = pg_query($conexao, $queryAnexos) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.");
											$numrowsAnexos = pg_numrows($resultAnexos);

											if ($numrowsAnexos > 0) {

												$contadorEspiro = 0;
												/*$contadorEletrocardio = 0;
												$contadorDensito = 0;*/

												while ($rowAnexos = pg_fetch_row($resultAnexos)) {
													//PRONTUARIO
													$prontuarioAnexos = $rowAnexos[0];
													//ANEXOS
													$diretorioAnexos = '/mnt/anexos/' . $prontuarioAnexos . '/';
													//$diretorioAnexos = '//143.106.234.11/anexos/' . $prontuarioAnexos . '/';
													//Verifica se o diretório existe (se tem exame para exibir)
													if (is_dir($diretorioAnexos)) {
														//echo "Existe! Diretorio: " . $diretorio . "<br />";
														$listaDiretorioAnexos = array_diff(scandir($diretorioAnexos), ['.', '..']);

														//ORDEM DECRESCENTE DOS ARQUIVOS NA PASTA
														krsort($listaDiretorioAnexos, SORT_STRING);
														
														$examesAnexos = $rowAnexos[1];
														$datasAnexos = $rowAnexos[2];
														$dataAgendamentoAnexos = $rowAnexos[3];
														$anexos = $rowAnexos[4];
														$pedidosAnexos = $rowAnexos[5];
														$usuarioAnexou = $rowAnexos[6];
														$modalidadeAnexos = $rowAnexos[8];

														//echo $usuarioAnexou . "<br>";
														//echo $examesAnexos . "<br>";

														if ($anexos != 0) {

															$primeiroAnexo = $examesAnexos;

															if (str_contains($primeiroAnexo, 'ESPIROMETRIA')) {
																//echo $contadorEspiro . '<br>';
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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
																	}
																}

																$contadorEletrocardio = $contadorEletrocardio + 1;
																if ($contadorEletrocardio >= 2) {
																	$contadorEletrocardio = 0;
																}*/
																$primeiroAnexo = "";
															} else if (str_contains($primeiroAnexo, 'DENSITO')) {
																//if ($contadorDensito >= 0) {
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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
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
																mkdir("anexos/$prontuarioAnexos");
																$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																//echo "BANCO: " . "$anexos.pdf" . "<br>";
																//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																if (!copy($anexo, $copiaAnexo)) {
																	echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																} else {
																	//echo "Copiou para a central de laudos!";
																	echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																	//IMAGENS
																	if ( ($modalidadeAnexos != "CT") && ($modalidadeAnexos != "MR") && ($modalidadeAnexos != "US") && ($modalidadeAnexos != "ECG") && ($modalidadeAnexos != "CR") && ($modalidadeAnexos != "ES") )  {
																		echo "<td align='center'>---</td>";
																	} else {
																		$pedidosAnexosSalutem = $pedidosAnexos . "SLT";																	
																		if ( ($modalidadeAnexos == "CT") || ($modalidadeAnexos == "MR") ) {
																			$key = '723b5e76e0fa33bca49d82443c50b941f0951aca';
																			$payload = [
																				"iss" => "AURORA",
																				"aud" => "XVIEWER",
																				"user" => ["id" => "", "name" => ""],
																				"iat" => strtotime("now"),
																				"exp" => strtotime("now") + (24*60*60),
																				"jti" => "1892ASD8ASDA9SDA98SD7",
																				"accessionnumber" => "$pedidosAnexos"
																			];
																		} else if ( ($modalidadeAnexos == "US") || ($modalidadeAnexos == "ECG") || ($modalidadeAnexos == "CR") || ($modalidadeAnexos == "ES") ) {
																			$key = '723b5e76e0fa33bca49d82443c50b941f0951aca';
																			$payload = [
																				"iss" => "AURORA",
																				"aud" => "XVIEWER",
																				"user" => ["id" => "", "name" => ""],
																				"iat" => strtotime("now"),
																				"exp" => strtotime("now") + (24*60*60),
																				"jti" => "1892ASD8ASDA9SDA98SD7",
																				"accessionnumber" => "$pedidosAnexosSalutem"
																			];
																		}
																		
																		//echo $pedidosAnexosSalutem . "<br>";
																		 
																		$token= JWT::encode($payload, $key, 'HS256'); 

																		$decoded = JWT::decode($token, new Key($key, 'HS256'));

																		//print_r($decoded);

																		$ip = $_SERVER['REMOTE_ADDR'];
																		//echo $ip;
																		if ( (str_contains($ip, '143.106.234.')) ) {
																			echo "<td align='center'><a href='http://143.106.234.3:8090/open?token=".$token."' target='_blank'><image src='../imagens/imagem.png' height='' width=''></a></td>";
																		} else {
																			echo "<td align='center'><a href='http://143.106.199.148:8090/open?token=".$token."' target='_blank'><image src='../imagens/imagem.png' height='' width=''></a></td>";
																		}
																	}
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
												echo "<td class='design'><br>NÃO EXISTEM EXAMES LAUDADOS PARA ESSE PACIENTE! </td>";
												echo "</tr>";
											}
											?>
										</tbody>
									</table>
									<br><br>
									<!--<hr>-->
									<h4 align='center'>CONTRARREFERÊNCIAS</h4>
									<!-- TABELA PARA AS CR -->
									<table class="tabela">
										<thead>
											<tr>
												<th align='center'>Atendimento</th>
												<th align='center'>Ficha</th>
												<th align='center'>Data do Atendimento</th>
												<th align='center'>Visualizar</th>
											</tr>
										</thead>
										<tbody>
											<?php
											//CRs A SEREM EXIBIDAS
											$queryCR = "SELECT prontuario, cpf, data_nascimento, id_da_ficha, nome_da_ficha, registro_de_atendimento, to_char(data_do_atendimento, 'DD/MM/YYYY'), especialidade
														FROM public.view_rme WHERE data_nascimento = '$data' AND (cpf = '$dadosMascara' OR cpf = '$dadosSemMascara') ORDER BY data_do_atendimento DESC";
											$resultCR = pg_query($conexao, $queryCR) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.");
											$numrowsCR = pg_numrows($resultCR);

											if ($numrowsCR > 0) {
												while ($rowCR = pg_fetch_row($resultCR)) {
													//PRONTUARIO
													$prontuarioCR = $rowCR[0];													
													$_SESSION['prontuario'] = $prontuarioCR;
													//CRs
													$diretorioCR = '/mnt/cr/' . $prontuarioCR . '/';
													//$diretorioCR = '//143.106.234.11/laudos/' . $prontuarioCR . '/';
													//Verifica se o diretório existe (se tem exame para exibir)
													if (is_dir($diretorioCR)) {
														//echo "Existe! Diretorio CR: " . $diretorioCR . "<br />";
														$listaDiretorioCR = array_diff(scandir($diretorioCR), ['.', '..']);
														//ORDEM DECRESCENTE DOS ARQUIVOS NA PASTA
														krsort($listaDiretorioCR, SORT_STRING);
														
														$atendimentos = $rowCR[5];
														$fichas = $rowCR[4];
														$especialidades = $rowCR[7];
														$dataAtendimentos = $rowCR[6];
														$id_ficha = $rowCR[3];

														//echo "CR: " . $dataAtendimento . "<br>";

														
															echo "<tr>";
															echo "<td align='center' autofocus>$atendimentos</td>";															
															echo "<td align='center'>$fichas - <strong>$especialidades</strong></td>";
															echo "<td align='center'>$dataAtendimentos</td>";
															//echo "<td align='center'>$datas</td>";
															//copia laudos para a central de laudos
															if (!file_exists("cr")) {
																//echo "NÃO EXISTIA...";
																mkdir("cr", 0777, true);
																//echo "AGORA EXISTE!";
															}
															mkdir("cr/$prontuarioCR");
															$cr = "$diretorioCR$arquivosCR$id_ficha.pdf";

															//echo "BANCO: " . "$id_ficha.pdf" . "<br>";
															//echo "ARQUIVO: " . $arquivosCR . "<br>";
															//COPIA CRs
															$copiaCR = "cr/$prontuarioCR/$arquivosCR$id_ficha.pdf";
															if (!copy($cr, $copiaCR)) {
																//echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
															} else {
																//echo "Copiou para a central de laudos!";
																echo "<td align='center'><a href='" . $copiaCR . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
															}
															echo "</tr>";
													}
												}
											} else {
												echo "<tr>";
												echo "<td bgcolor='white'></td>";
												echo "<td bgcolor='white'></td>";
												echo "<td class='design'><br>NÃO EXISTEM CONTRARREFERÊNCIAS EMITIDAS PARA ESSE PACIENTE! </td>";
												echo "</tr>";
											}
											?>
										</tbody>
									</table>
									<?php
									}
								} else {
									echo '<script>pesquisaErroAMEs()</script>';
								}
							} else {
								//OUTRAS CIDADES (ACESSAM APENAS DO MUNICIPIO)
								//echo "ACESSAM PACIENTES DO MUNICIPIO!";
								//DADOS DO PACIENTE
								$queryPaciente = "SELECT nome, to_char(nacimento, 'DD/MM/YYYY'), cpf, cidade_nome, cidade_id, prontuario FROM view_paciente WHERE cidade_id = '$idCidadeUnidade' AND nacimento = '$data' AND (cpf = '$dadosMascara' OR cpf = '$dadosSemMascara')";
								$resultPaciente = pg_query($conexao, $queryPaciente) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
								$numrowsPaciente = pg_numrows($resultPaciente);
								
								if ($numrowsPaciente > 0 and $numrowsPaciente <= 1) {
									while ($rowPaciente = pg_fetch_row($resultPaciente)) {
										$cpfPaciente = $rowPaciente[2];
										$nascimentoPaciente = $rowPaciente[1];
										$idCidadePaciente = $rowPaciente[4];
										$nomePaciente = $rowPaciente[0];
										$pront = $rowPaciente[5];
										?>
										<!--<div class="encaminhamento">
											<form action="enviaArquivo.php" method="post" enctype="multipart/form-data">
												<strong>Encaminhamento *</strong><br><br>
												<select id="select" name="select" required>
													<option value="">---</option>
													<option value="ESPECIALIDADES" style="font-weight:bold;" disabled>ESPECIALIDADES</option>
													<option value="Cardiologia">Cardiologia</option>
													<option value="Cirurgia Geral">Cirurgia Geral</option>
													<option value="Cirurgia Vascular">Cirurgia Vascular</option>
													<option value="Dermatologia">Dermatologia</option>
													<option value="Dermatologia - Fototerapia">Dermatologia - Fototerapia</option>
													<option value="Dermatologia/Plástica - Tumor de Pele">Dermatologia/Plástica - Tumor de Pele</option>
													<option value="Endocrinologia">Endocrinologia</option>
													<option value="Endocrinologia - Pré-Natal de Alto Risco">Endocrinologia - Pré-Natal de Alto Risco</option>
													<option value="Gastroenterologia">Gastroenterologia</option>
													<option value="Mastologia">Mastologia</option>
													<option value="Neurologia">Neurologia</option>
													<option value="Oftalmologia">Oftalmologia</option>
													<option value="Ortopedia">Ortopedia</option>
													<option value="Otorrinolaringologia">Otorrinolaringologia</option>
													<option value="Pneumologia">Pneumologia</option>
													<option value="Proctologia">Proctologia</option>
													<option value="Reumatologia">Reumatologia</option>
													<option value="Urologia">Urologia</option>
													<option value="" disabled></option>
													<option value="EXAMES" style="font-weight:bold;" disabled>EXAMES</option>
													<option value="Audiometria/Imitanciometria">Audiometria/Imitanciometria</option>
													<option value="Biópsia de Próstata">Biópsia de Próstata</option>
													<option value="Cistoscopia">Cistoscopia</option>
													<option value="Colonoscopia">Colonoscopia</option>
													<option value="Colposcopia">Colposcopia</option>
													<option value="Densitometria">Densitometria</option>
													<option value="Ecocardiografia">Ecocardiografia</option>
													<option value="Eletroencefalo (EEG)">Eletroencefalo (EEG)</option>
													<option value="Endoscopia">Endoscopia</option>
													<option value="Espirometria">Espirometria</option>
													<option value="Histeroscopia">Histeroscopia</option>
													<option value="Holter">Holter</option>
													<option value="Laringoscopia">Laringoscopia</option>
													<option value="Mamografia">Mamografia</option>
													<option value="MAPA">MAPA</option>
													<option value="Otoneurológico">Otoneurológico</option>
													<option value="PAAF de Tireóide">PAAF de Tireóide</option>
													<option value="Ressonância Magnética">Ressonância Magnética</option>
													<option value="Retossigmoidoscopia">Retossigmoidoscopia</option>
													<option value="Teste Ergométrico">Teste Ergométrico</option>
													<option value="Tomografia Computadorizada">Tomografia Computadorizada</option>
													<option value="Ultrassonografia">Ultrassonografia</option>
												</select>
												<strong><p style = 'font-size:12px'>* Envie apenas arquivos PDF.</strong>
												<input type="file" class="arquivo" id="encaminhamento" name="encaminhamento" required/>
												<input type="hidden" name="pront" value='<?php echo $pront?>'/>
												<input type="hidden" name="dados" value='<?php echo $dados?>'/>
												<input type="hidden" name="data" value='<?php echo $data?>'/>
												<button type="submit" id="enviar" name="enviar" class="pesquisar">Enviar</button>
											</form>
										</div>
										<br><br><br>--->
										<br><br><br><br><br><br><br><br><br>
										<div class="dadosPaciente">
											<strong>Paciente:</strong>
											<?php echo " &nbsp " . $nomePaciente . " &nbsp  | &nbsp ";
											if (str_contains($dados, '.'))  {
												echo "$dados &nbsp  | &nbsp $nascimentoPaciente";
											} else {
												$dados1 = substr_replace($dados, '.', 3, 0);
												$dados2 = substr_replace($dados1, '.', 7, 0);
												$dadosMascara = substr_replace($dados2, '-', 11, 0);
												echo "$dadosMascara &nbsp  | &nbsp $nascimentoPaciente";
											}
											?>
										</div>
										<br>
										<!--<hr>-->
										<h4 align='center'>EXAMES</h4>
										<table class="tabela">
										<thead>
											<tr>
												<th align='center'>Pedido</th>
												<th align='center'>Exame</th>
												<th align='center'>Data do Atendimento</th>
												<!--<th align='center'>Data do Laudo</th>-->
												<th align='center'>Laudo/Exame</th>
												<th align='center'>Imagem</th>
											</tr>
										</thead>
										<tbody>
											<?php
											//LAUDOS A SEREM EXIBIDOS
											$queryLaudos = "SELECT prontuario, cod_item, exame, to_char(data_laudo, 'DD/MM/YYYY'), to_char(data_agendamento, 'DD/MM/YYYY'), anexo_id, cpf, modalidade_synapse from view_exames_laudados_unidades WHERE
															exame NOT LIKE '%ELETROENCEFALO%' AND exame NOT LIKE '%HOLTER%' AND exame NOT LIKE '%MAPA%' AND
															exame NOT LIKE '%MAMOGRAFIA%' AND exame NOT LIKE '%TESTE ERGOMETRICO%' AND
															exame NOT LIKE '%AUDIOMETRIA%' AND exame NOT LIKE '%OTONEUROLOGICO%' AND exame NOT LIKE '%TESTES ALERGICOS DE CONTATO%' AND
															(cpf = '$dadosMascara' OR cpf = '$dadosSemMascara') ORDER BY exame";
											$resultLaudos = pg_query($conexao, $queryLaudos) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.");
											$numrowsLaudos = pg_numrows($resultLaudos);

											if ($numrowsLaudos > 0) {
												while ($rowLaudos = pg_fetch_row($resultLaudos)) {
													//PRONTUARIO
													$prontuario = $rowLaudos[0];
													$_SESSION['prontuario'] = $prontuario;
													//LAUDOS
													$diretorio = '/mnt/laudos/' . $prontuario . '/';
													//$diretorio = '//143.106.234.11/laudos/' . $prontuario . '/';
													//Verifica se o diretório existe (se tem exame para exibir)
													if (is_dir($diretorio)) {
														//echo "Existe! Diretorio: " . $diretorio . "<br />";
														$listaDiretorio = array_diff(scandir($diretorio), ['.', '..']);
														//ORDEM DECRESCENTE DOS ARQUIVOS NA PASTA
														krsort($listaDiretorio, SORT_STRING);
														
														$pedidos = $rowLaudos[1];
														$exames = $rowLaudos[2];
														$datas = $rowLaudos[3];
														$dataAgendamento = $rowLaudos[4];
														$temAnexo = $rowLaudos[5];
														$modalidade = $rowLaudos[7];

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
															mkdir("laudos/$prontuario");
															$laudo = "$diretorio$arquivos$pedidos.pdf";

															//echo "BANCO: " . "$pedidos.pdf" . "<br>";
															//echo "ARQUIVO: " . $arquivos . "<br>";
															//COPIA LAUDOS
															$copiaLaudo = "laudos/$prontuario/$arquivos$pedidos.pdf";
															if (!copy($laudo, $copiaLaudo)) {
																//echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
															} else {
																//echo "Copiou para a central de laudos!";
																echo "<td align='center'><a href='" . $copiaLaudo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																//IMAGENS
																if ( ($modalidade != "CT") && ($modalidade != "MR") && ($modalidade != "US") && ($modalidade != "ECG") && ($modalidade != "CR") && ($modalidade != "ES") )  {
																	echo "<td align='center'>---</td>";
																} else {
																	$pedidosSalutem = $pedidos . "SLT";																	
																	if ( ($modalidade == "CT") || ($modalidade == "MR") ) {
																		$key = '723b5e76e0fa33bca49d82443c50b941f0951aca';
																		$payload = [
																			"iss" => "AURORA",
																			"aud" => "XVIEWER",
																			"user" => ["id" => "", "name" => ""],
																			"iat" => strtotime("now"),
																			"exp" => strtotime("now") + (24*60*60),
																			"jti" => "1892ASD8ASDA9SDA98SD7",
																			"accessionnumber" => "$pedidos"
																		];
																	} else if ( ($modalidade == "US") || ($modalidade == "ECG") || ($modalidade == "CR") || ($modalidade == "ES") ) {
																		$key = '723b5e76e0fa33bca49d82443c50b941f0951aca';
																		$payload = [
																			"iss" => "AURORA",
																			"aud" => "XVIEWER",
																			"user" => ["id" => "", "name" => ""],
																			"iat" => strtotime("now"),
																			"exp" => strtotime("now") + (24*60*60),
																			"jti" => "1892ASD8ASDA9SDA98SD7",
																			"accessionnumber" => "$pedidosSalutem"
																		];
																	}
																	
																	//echo $pedidosSalutem . "<br>";
																	 
																	$token= JWT::encode($payload, $key, 'HS256'); 

																	$decoded = JWT::decode($token, new Key($key, 'HS256'));

																	//print_r($decoded);

																	$ip = $_SERVER['REMOTE_ADDR'];
																	//echo $ip;
																	if ( (str_contains($ip, '143.106.234.')) ) {
																		echo "<td align='center'><a href='http://143.106.234.3:8090/open?token=".$token."' target='_blank'><image src='../imagens/imagem.png' height='' width=''></a></td>";
																	} else {
																		echo "<td align='center'><a href='http://143.106.199.148:8090/open?token=".$token."' target='_blank'><image src='../imagens/imagem.png' height='' width=''></a></td>";
																	}
																}
															}
															echo "</tr>";
														}
													}
												}
											}

											//ANEXOS A SEREM EXIBIDOS
											$queryAnexos = "SELECT prontuario, exame, to_char(data_laudo, 'DD/MM/YYYY'), to_char(data_agendamento, 'DD/MM/YYYY'), anexo_id, cod_item, usuario_anexo, cpf, modalidade_synapse from view_exames_laudados_unidades WHERE
															(cpf = '$dadosMascara' OR cpf = '$dadosSemMascara') ORDER BY exame";
											$resultAnexos = pg_query($conexao, $queryAnexos) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.");
											$numrowsAnexos = pg_numrows($resultAnexos);

											if ($numrowsAnexos > 0) {

												$contadorEspiro = 0;
												/*$contadorEletrocardio = 0;
												$contadorDensito = 0;*/

												while ($rowAnexos = pg_fetch_row($resultAnexos)) {
													//PRONTUARIO
													$prontuarioAnexos = $rowAnexos[0];
													//ANEXOS
													$diretorioAnexos = '/mnt/anexos/' . $prontuarioAnexos . '/';
													//$diretorioAnexos = '//143.106.234.11/anexos/' . $prontuarioAnexos . '/';
													//Verifica se o diretório existe (se tem exame para exibir)
													if (is_dir($diretorioAnexos)) {
														//echo "Existe! Diretorio: " . $diretorio . "<br />";
														$listaDiretorioAnexos = array_diff(scandir($diretorioAnexos), ['.', '..']);

														//ORDEM DECRESCENTE DOS ARQUIVOS NA PASTA
														krsort($listaDiretorioAnexos, SORT_STRING);
														
														$examesAnexos = $rowAnexos[1];
														$datasAnexos = $rowAnexos[2];
														$dataAgendamentoAnexos = $rowAnexos[3];
														$anexos = $rowAnexos[4];
														$pedidosAnexos = $rowAnexos[5];
														$usuarioAnexou = $rowAnexos[6];
														$modalidadeAnexos = $rowAnexos[8];

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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
																	}
																} else {
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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
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
																	mkdir("anexos/$prontuarioAnexos");
																	$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																	//echo "BANCO: " . "$anexos.pdf" . "<br>";
																	//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																	//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																	$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																	if (!copy($anexo, $copiaAnexo)) {
																		echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																	} else {
																		//echo "Copiou para a central de laudos!";
																		echo "<td><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																		echo "<td align='center'>---</td>";
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
																mkdir("anexos/$prontuarioAnexos");
																$anexo = "$diretorioAnexos$arquivosAnexos$anexos.pdf";

																//echo "BANCO: " . "$anexos.pdf" . "<br>";
																//echo "ARQUIVO: " . $arquivosAnexos . "<br>";
																//COPIA APENAS OS LAUDOS DENTRO DE 12 MESES
																$copiaAnexo = "anexos/$prontuarioAnexos/$arquivosAnexos$anexos.pdf";
																if (!copy($anexo, $copiaAnexo)) {
																	echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
																} else {
																	//echo "Copiou para a central de laudos!";
																	echo "<td align='center'><a href='" . $copiaAnexo . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
																	//IMAGENS
																	if ( ($modalidadeAnexos != "CT") && ($modalidadeAnexos != "MR") && ($modalidadeAnexos != "US") && ($modalidadeAnexos != "ECG") && ($modalidadeAnexos != "CR") && ($modalidadeAnexos != "ES") )  {
																		echo "<td align='center'>---</td>";
																	} else {
																		$pedidosAnexosSalutem = $pedidosAnexos . "SLT";																	
																		if ( ($modalidadeAnexos == "CT") || ($modalidadeAnexos == "MR") ) {
																			$key = '723b5e76e0fa33bca49d82443c50b941f0951aca';
																			$payload = [
																				"iss" => "AURORA",
																				"aud" => "XVIEWER",
																				"user" => ["id" => "", "name" => ""],
																				"iat" => strtotime("now"),
																				"exp" => strtotime("now") + (24*60*60),
																				"jti" => "1892ASD8ASDA9SDA98SD7",
																				"accessionnumber" => "$pedidosAnexos"
																			];
																		} else if ( ($modalidadeAnexos == "US") || ($modalidadeAnexos == "ECG") || ($modalidadeAnexos == "CR") || ($modalidadeAnexos == "ES") ) {
																			$key = '723b5e76e0fa33bca49d82443c50b941f0951aca';
																			$payload = [
																				"iss" => "AURORA",
																				"aud" => "XVIEWER",
																				"user" => ["id" => "", "name" => ""],
																				"iat" => strtotime("now"),
																				"exp" => strtotime("now") + (24*60*60),
																				"jti" => "1892ASD8ASDA9SDA98SD7",
																				"accessionnumber" => "$pedidosAnexosSalutem"
																			];
																		}
																		
																		//echo $pedidosAnexosSalutem . "<br>";
																		 
																		$token= JWT::encode($payload, $key, 'HS256'); 

																		$decoded = JWT::decode($token, new Key($key, 'HS256'));

																		//print_r($decoded);

																		$ip = $_SERVER['REMOTE_ADDR'];
																		//echo $ip;
																		if ( (str_contains($ip, '143.106.234.')) ) {
																			echo "<td align='center'><a href='http://143.106.234.3:8090/open?token=".$token."' target='_blank'><image src='../imagens/imagem.png' height='' width=''></a></td>";
																		} else {
																			echo "<td align='center'><a href='http://143.106.199.148:8090/open?token=".$token."' target='_blank'><image src='../imagens/imagem.png' height='' width=''></a></td>";
																		}
																	}
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
												echo "<td class='design'><br>NÃO EXISTEM EXAMES LAUDADOS PARA ESSE PACIENTE! </td>";
												echo "</tr>";
											}
											?>
										</tbody>
									</table>
									<br><br>
									<!--<hr>-->
									<h4 align='center'>CONTRARREFERÊNCIAS</h4>
									<!-- TABELA PARA AS CR -->
									<table class="tabela">
										<thead>
											<tr>
												<th align='center'>Atendimento</th>
												<th align='center'>Ficha</th>
												<th align='center'>Data do Atendimento</th>
												<th align='center'>Visualizar</th>
											</tr>
										</thead>
										<tbody>
											<?php
											//CRs A SEREM EXIBIDAS
											$queryCR = "SELECT prontuario, cpf, data_nascimento, id_da_ficha, nome_da_ficha, registro_de_atendimento, to_char(data_do_atendimento, 'DD/MM/YYYY'), especialidade
														FROM public.view_rme WHERE data_nascimento = '$data' AND (cpf = '$dadosMascara' OR cpf = '$dadosSemMascara') ORDER BY data_do_atendimento DESC";
											$resultCR = pg_query($conexao, $queryCR) or die("Não foi possível consultar o banco de dados!<br>Tente novamente.");
											$numrowsCR = pg_numrows($resultCR);

											if ($numrowsCR > 0) {
												while ($rowCR = pg_fetch_row($resultCR)) {
													//PRONTUARIO
													$prontuarioCR = $rowCR[0];													
													$_SESSION['prontuario'] = $prontuarioCR;
													//CRs
													$diretorioCR = '/mnt/cr/' . $prontuarioCR . '/';
													//$diretorioCR = '//143.106.234.11/laudos/' . $prontuarioCR . '/';
													//Verifica se o diretório existe (se tem exame para exibir)
													if (is_dir($diretorioCR)) {
														//echo "Existe! Diretorio CR: " . $diretorioCR . "<br />";
														$listaDiretorioCR = array_diff(scandir($diretorioCR), ['.', '..']);
														//ORDEM DECRESCENTE DOS ARQUIVOS NA PASTA
														krsort($listaDiretorioCR, SORT_STRING);
														
														$atendimentos = $rowCR[5];
														$fichas = $rowCR[4];
														$especialidades = $rowCR[7];
														$dataAtendimentos = $rowCR[6];
														$id_ficha = $rowCR[3];

														//echo "CR: " . $dataAtendimento . "<br>";

														
															echo "<tr>";
															echo "<td align='center' autofocus>$atendimentos</td>";															
															echo "<td align='center'>$fichas - <strong>$especialidades</strong></td>";
															echo "<td align='center'>$dataAtendimentos</td>";
															//echo "<td align='center'>$datas</td>";
															//copia laudos para a central de laudos
															if (!file_exists("cr")) {
																//echo "NÃO EXISTIA...";
																mkdir("cr", 0777, true);
																//echo "AGORA EXISTE!";
															}
															mkdir("cr/$prontuarioCR");
															$cr = "$diretorioCR$arquivosCR$id_ficha.pdf";

															//echo "BANCO: " . "$id_ficha.pdf" . "<br>";
															//echo "ARQUIVO: " . $arquivosCR . "<br>";
															//COPIA CRs
															$copiaCR = "cr/$prontuarioCR/$arquivosCR$id_ficha.pdf";
															if (!copy($cr, $copiaCR)) {
																//echo "Erro! Não foi possível carregar os exames. Tente novamente!\n";
															} else {
																//echo "Copiou para a central de laudos!";
																echo "<td align='center'><a href='" . $copiaCR . "' target='_blank'><image src='../imagens/lupa.png'></a></td>";
															}
															echo "</tr>";
													}
												}
											} else {
												echo "<tr>";
												echo "<td bgcolor='white'></td>";
												echo "<td bgcolor='white'></td>";
												echo "<td class='design'><br>NÃO EXISTEM CONTRARREFERÊNCIAS EMITIDAS PARA ESSE PACIENTE! </td>";
												echo "</tr>";
											}
											?>
										</tbody>
									</table>
									<?php
									}
								} else {
									echo '<script>pesquisaErro()</script>';
								}
							}
						} else {
							//echo "NÃO TEM NADA!";
						}
						?>
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
	<script>
	//AJUSTA URL
	var search = document.getElementById('pesquisaCPF');
	var and = document.getElementById('pesquisaData');

	var select = document.getElementById('select');
	var encaminhamento = document.getElementById('encaminhamento');
	
	search.addEventListener("keydown", function(event) {
		if (event.key === "Enter") {
			searchPac();
		}
	});
	
	and.addEventListener("keydown", function(event) {
		if (event.key === "Enter") {
			searchPac();
		}
	});

	select.addEventListener("keydown", function(event) {
		if (event.key === "Enter") {
			validaSelect();
		}
	});

	encaminhamento.addEventListener("keydown", function(event) {
		if (event.key === "Enter") {
			document.getElementById("enviar").focus();
		}
	});
	
	function searchPac() {
		if(document.getElementById("pesquisaCPF").value.length < 11){
			alert('Por favor, preencha o CPF corretamente (11 números)!');
			document.getElementById("pesquisaCPF").focus();
			return false;
		} else 
		if(document.getElementById("pesquisaData").value.length < 10){
			alert('Por favor, preencha a Data de Nascimento corretamente (dia/mês/ano)!');
			document.getElementById("pesquisaData").focus();
			return false;
		}
		else {
			window.location = 'pesquisaPaciente.php?search='+search.value+'&and='+and.value;
		}
	}

	function validaSelect() {
		var comboSelect = document.getElementById("select");

		if (comboSelect.options[comboSelect.selectedIndex].value == "" ){
            alert("Selecione uma Especialidade ou um Exame para prosseguir!");
			document.getElementById("select").focus();
			return false;
        } else {
			document.getElementById("encaminhamento").focus();
		}
	}
</script>
</html>
