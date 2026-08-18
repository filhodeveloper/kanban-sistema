<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $status = 'A Fazer'; 
    
    // Captura o usuário selecionado no formulário
    // Se nenhum usuário for escolhido, define como NULL no banco
    $usuario_id = !empty($_POST['usuario_id']) ? intval($_POST['usuario_id']) : "NULL";

    $titulo = mysqli_real_escape_string($conexao, $titulo);
    $descricao = mysqli_real_escape_string($conexao, $descricao);

    // SQL atualizada para incluir a coluna do usuário responsável
    $sql = "INSERT INTO tarefas (titulo, descricao, status, usuario_id) VALUES ('$titulo', '$descricao', '$status', $usuario_id)";

    if (mysqli_query($conexao, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao salvar a tarefa: " . mysqli_error($conexao);
    }
}
?>