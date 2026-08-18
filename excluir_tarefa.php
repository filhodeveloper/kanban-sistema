<?php
include 'db.php'; // Conecta ao banco de dados

// Verifica se o ID da tarefa foi passado pela URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Garante que o ID seja um número inteiro seguro

    // Executa a exclusão da tarefa correspondente no banco
    $sql = "DELETE FROM tarefas WHERE id = $id";

    if (mysqli_query($conexao, $sql)) {
        // Redireciona de volta para a tela do Kanban atualizada
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao excluir tarefa: " . mysqli_error($conexao);
    }
}
?>