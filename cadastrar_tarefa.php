<?php
include 'db.php'; // Usa a conexão do arquivo anterior

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pega os textos que foram digitados no formulário
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $status = 'A Fazer'; // Toda tarefa nova começa na primeira coluna

    // Proteção básica para o banco de dados contra caracteres especiais
    $titulo = mysqli_real_escape_string($conexao, $titulo);
    $descricao = mysqli_real_escape_string($conexao, $descricao);

    // Comando SQL para inserir a tarefa na tabela
    $sql = "INSERT INTO tarefas (titulo, descricao, status) VALUES ('$titulo', '$descricao', '$status')";

    if (mysqli_query($conexao, $sql)) {
        // Se deu tudo certo, redireciona de volta para a tela principal
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao salvar a tarefa: " . mysqli_error($conexao);
    }
}
?>