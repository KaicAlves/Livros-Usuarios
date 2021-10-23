<?php

$arq1="LabBD-Gr5-ProdEletron-Exerc4-login.html";

$_SESSION['usuarioDigitado'] = '';
$_SESSION['senhaDigitada'] = '';
$usuarioDigitado = '';
$senhaDigitada = '';

session_unset();
session_destroy();

header("Location: $arq1");





?>