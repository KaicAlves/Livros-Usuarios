<?php
header('Content-Type: text/html; charset=utf-8');
$arq0="LabBD-Gr5-ProdEletron-Exerc4-senhas.php";
$arq = "LabBD-Gr5-ProdEletron-Exerc4-RegistroDeEmprestimos";
$arq1 = "LabBD-Gr5-ProdEletron-Exerc4-pegaUsr.php";
$arq2 = "LabBD-Gr5-ProdEletron-Exerc4-emprestarLivro";
$arq3="LabBD-Gr5-ProdEletron-Exerc4-sair.php";

require_once("$arq0");

echo <<<_TEXTO1
<form name = "sair" action="$arq3" method="post">
<input type="submit" value="Sair"></form>
_TEXTO1;

if ($_SESSION['usuarioDigitado'] = '' or $_SESSION['senhaDigitada'] = '')
	header("Location: $arq1");
else
{
$tab="livros";

$conexão = new mysqli($servidor, $usuario, $senha, $bd);
if ($conexão->connect_error) die($conexão->connect_error);

$tombo = mostraLivros($tab, $arq2, $conexão);

//$tombo = $_POST['tombo'];

$handle = fopen("$arq","a+");
// modo a+: escrita; cursor no fim; o texto existente não é sobrescrito
echo "<br> Gravando em arquivo:<br>";
echo "<br>------------------------------------------------------------------------------";

fwrite($handle,"Livro: $tombo\n>");
fclose($handle);

echo "<br>";
if (isset($_POST['Tombo'])){
echo "O livro de tombo $tombo foi emprestado";
echo "<br>------------------------------------------------------------------------------";
echo "<br>";
}
}

function mostraLivros($tab, $arq, $conexão){
		//  ************* Mostrar os livros existentes *************
		$query= "SELECT * FROM $tab";
		$resultado = $conexão->query($query);
		if (!$resultado) die ("Erro de acesso à base de dados: " . $conexão->error);
		
		$linhas = $resultado->num_rows;
		echo "<br>";
		echo "Lista de livros:";
		echo "<br>";
		$_novoId=0;
		for ($j = 0 ; $j < $linhas ; ++$j)
		{
		$resultado->data_seek($j);
		$linha = $resultado->fetch_array(MYSQLI_NUM);
		echo <<<_TEXTO
		<pre>

		Autor	$linha[0]
		Título	$linha[1]
		Área	$linha[2]
		Ano	$linha[3]
		Tombo	$linha[4]
		</pre>

		<form name = "emprestar" action="$arq" method="post">
		<input type ="hidden" name="Tombo" value="$linha[4]">
		<input type ="submit" value="Emprestar"></form>
_TEXTO;
		}
		if (isset($_POST['Tombo'])){
		$tombo = $_POST["Tombo"];
		return ($tombo);
	}
}
?>
