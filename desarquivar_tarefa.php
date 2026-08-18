<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Altera o status da tarefa de volta para ativa (0)
    $sql = "UPDATE tarefas SET arquivada = 0 WHERE id = $id";

    if (mysqli_query($conexao, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao desarquivar tarefa: " . mysqli_error($conexao);
    }
}
?>