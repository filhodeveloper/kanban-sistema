<?php
include 'db.php'; // Conecta ao banco de dados

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Garante que o ID seja um número inteiro seguro

    // Altera o status da tarefa para arquivada (1)
    $sql = "UPDATE tarefas SET arquivada = 1 WHERE id = $id";

    if (mysqli_query($conexao, $sql)) {
        header("Location: index.php"); // Redireciona de volta ao painel
        exit();
    } else {
        echo "Erro ao arquivar tarefa: " . mysqli_error($conexao);
    }
}
?>