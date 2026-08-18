<?php
include 'db.php'; // Conecta ao banco de dados para podermos alterar a tarefa

// Verifica se a requisição foi feita enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Pega o ID da tarefa e converte em número inteiro para segurança
    $id = intval($_POST['id']);
    
    // Pega o novo status (coluna) e limpa o texto contra invasões SQL
    $status = mysqli_real_escape_string($conexao, $_POST['status']);

    // Valida se o status recebido é um dos três permitidos no banco de dados
    if (in_array($status, ['A Fazer', 'Em Andamento', 'Concluído'])) {
        
        // Escreve o comando SQL para atualizar o status da tarefa correspondente ao ID
        $sql = "UPDATE tarefas SET status = '$status' WHERE id = $id";
        
        // Executa a query no MySQL
        if (mysqli_query($conexao, $sql)) {
            // Se der certo, envia uma resposta em formato JSON confirmando o sucesso
            echo json_encode(["sucesso" => true]);
        } else {
            // Se houver erro no banco, envia o erro detalhado
            echo json_encode(["sucesso" => false, "erro" => mysqli_error($conexao)]);
        }
    } else {
        echo json_encode(["sucesso" => false, "erro" => "Status inválido enviado."]);
    }
}
?>