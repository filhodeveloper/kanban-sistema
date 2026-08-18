<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "kanban_sistema"; // Nome do banco que você criou no phpMyAdmin

// Executa a conexão física
$conexao = mysqli_connect($servidor, $usuario, $senha, $banco);

// Se houver algum erro de conexão, o sistema avisa na hora
if (!$conexao) {
    die("Erro na conexão com o Banco de Dados: " . mysqli_connect_error());
}
?>