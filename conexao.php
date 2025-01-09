<!--Criado por Wellinton C. Teodoro em MAR/2023 - wteodoro@amesaojoao.unicamp.br-->

<?php
error_reporting(0);
?>

<?php
$conexao = pg_connect("host=143.106.234.11 port=7002 dbname=salutem user=ame password=ame123") or die("Não foi possível realizar conexão com o banco de dados!<br>Tente novamente.\n <br><br><a href='./'>Voltar</a>");
?>
