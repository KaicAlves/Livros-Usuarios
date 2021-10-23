<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'LabBD-Gr5-ProdEletron-Exerc4-senhas.php';
$conexão = new mysqli($servidor, $usuario, $senha, $bd);
$tab="livros";
$arq = "LabBD-Gr5-ProdEletron-Exerc4-fazTudo";
$arq1 = "LabBD-Gr5-ProdEletron-Exerc4-sair";
$arq2 = "LabBD-Gr5-ProdEletron-Exerc4-emprestarLivro";

if ($conexão->connect_error) die($conexão->connect_error);

echo <<<_TEXTO1
<form name = "sair" action="$arq1" method="post">
<input type="submit" value="Sair"></form>
_TEXTO1;

// ************* Apagar dados da tabela *************

if (isset($_POST['apagar']) && isset($_POST['tombo']))
{
	$tombo = get_post($conexão, 'tombo');
	$query= "DELETE FROM $tab WHERE tombo='$tombo'";
	$resultado = $conexão->query($query);
		
	if (!$resultado) echo "Erro ao remover dados: $query<br>" .
		$conexão->error . "<br><br>";
	}

	// ************* Inserir dados na tabela ************* 

if (isset($_POST['autor'])
	&&
	isset($_POST['titulo'])
	&&
	isset($_POST['area'])
	&&
	isset($_POST['ano'])
	&&
	isset($_POST['tombo'])
	)

{
	$autor 	= get_post($conexão, 'autor');
	$titulo	= get_post($conexão, 'titulo');
	$area 	= get_post($conexão, 'area');
	$ano 	= get_post($conexão, 'ano');
	$tombo 	= get_post($conexão, 'tombo');

	$query	= "INSERT INTO $tab VALUES"."('$autor', '$titulo', '$area', '$ano', '$tombo')";	
		
	$resultado 	= $conexão->query($query);

	if (!$resultado) echo "Erro ao inserir dados: $query<br>" .
	$conexão->error . "<br><br>";
	
}

		// ************* Editar dados da tabela *************
		if (isset($_POST['editar']) && isset($_POST['tombo']))
		{

		echo <<<_TEXTO
		<form action="$arq" method="post">
		<input type="text" name="autor" value=$autor>
		<input type="text" name="titulo" value=$titulo>
		<input type="text" name="area" value=$area>
		<input type="text" name="ano" value=$ano>
		<input type="text" name="tombo" value=$tombo>
		<form name = "editar" action="$arq" method="post">
		<input type="submit" value="Editar Registro">
		</form>
		_TEXTO;
	
		$query = "UPDATE $tab SET autor='$autor', titulo='$titulo', area='$area', ano='$ano', tombo='$tombo' WHERE tombo='$tombo'";
}

// ************* Montar os formulários para entrada de dados na tabela *************

echo <<<_END
<form action="$arq" method="post">
<pre>
<br>
Indique os livros a incluir:
	Autor  <input type="text" maxlength="20" name="autor">   Título <input type="text" maxlength="40" name="titulo">
	<br>
	Área   <input type="text" maxlength="16" name="area">    Ano    <input type="text" maxlength="6" name="ano">
	<br>
	Tombo  <input type="text" maxlength="10" name="tombo">
	<form name = "adicionar" action="$arq" method="post">
	<input type="submit" value="Adicionar Registro">
</pre></form>
_END;

//  ************* Mostrar os livros existentes na tabela *************

$query= "SELECT * FROM $tab";

$resultado = $conexão->query($query);

if (!$resultado) die ("Erro de acesso à base de dados: " . $conexão->error);

$linhas = $resultado->num_rows;
echo "        ------------------------";
echo "<br>";
echo "Conteúdo atual da tabela livros:";
echo "<br>";

for ($j = 0 ; $j < $linhas ; ++$j)
	{
	$resultado->data_seek($j);
	$linha = $resultado->fetch_array(MYSQLI_NUM);
	echo <<<_END
<pre>


	Autor  $linha[0]
	Título $linha[1]
	Área   $linha[2]
	Ano    $linha[3]
	Tombo  $linha[4]
</pre>
	<form action="$arq" method="post">
	<input type="hidden" name="apagar" value="yes">
	<input type="hidden" name="tombo" value="$linha[4]">
	<input type="submit" value="Apagar Registro"></form>

	<form action="$arq" method="post">
	<input type="hidden" name="editar" value="yes">
	<input type="hidden" name="autor" value="$linha[0]">
	<input type="hidden" name="titulo" value="$linha[1]">
	<input type="hidden" name="area" value="$linha[2]">
	<input type="hidden" name="ano" value="$linha[3]">
	<input type="hidden" name="tombo" value="$linha[4]">
	<input type="submit" value="Editar Registro"></form>

	<form name = "emprestar" action="$arq2" method="post">
	<input type ="hidden" name="Tombo" value="$linha[4]">
	<input type ="submit" value="Emprestar"></form>

_END;
	echo "        ------------------------";
}

$resultado->close();
$conexão->close();

function get_post($conexão, $variável)
	{
	return $conexão->real_escape_string($_POST[$variável]);
	}
?>