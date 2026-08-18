<?php
include 'db.php'; // Liga o arquivo do banco de dados

// Detecta se a requisição enviada pela tela foi via POST ou GET
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'POST') {
    //qual ação o usuário quer fazer
    $acao = isset($_POST['acao']) ? $_POST['acao'] : '';

    // AÇÃO 1: CADASTRAR TAREFA
    if ($acao == 'cadastrar') {
        $titulo = mysqli_real_escape_string($conexao, $_POST['titulo']);
        $descricao = mysqli_real_escape_string($conexao, $_POST['descricao']);
        $usuario_id = !empty($_POST['usuario_id']) ? intval($_POST['usuario_id']) : "NULL";
        $status = 'A Fazer'; // Toda tarefa nova começa no "A Fazer"

        $sql = "INSERT INTO tarefas (titulo, descricao, status, usuario_id) VALUES ('$titulo', '$descricao', '$status', $usuario_id)";
        
        if (mysqli_query($conexao, $sql)) {
            header("Location: index.php"); // Recarrega index
            exit();
        } else {
            echo "Erro ao cadastrar tarefa: " . mysqli_error($conexao);
        }
    }

    // AÇÃO 2: ATUALIZAR STATUS (Drag and Drop do JScript)
    elseif ($acao == 'atualizar_status') {
        $id = intval($_POST['id']);
        $status = mysqli_real_escape_string($conexao, $_POST['status']);

        // Valida o status arrastado é um dos permitidos no banco
        if (in_array($status, ['A Fazer', 'Em Andamento', 'Concluído'])) {
            $sql = "UPDATE tarefas SET status = '$status' WHERE id = $id";
            
            if (mysqli_query($conexao, $sql)) {
    
                echo json_encode(["sucesso" => true]);
                exit();
            } else {
                echo json_encode(["sucesso" => false, "erro" => mysqli_error($conexao)]);
                exit();
            }
        }
    }
} 

elseif ($metodo == 'GET') {
    // Se for uma requisição GET, processa ações de links (excluir, arquivar, desarquivar)
    if (isset($_GET['id']) && isset($_GET['acao'])) {
        $id = intval($_GET['id']);
        $acao = $_GET['acao'];

        switch ($acao) {
        
            // AÇÃO 3: ARQUIVAR TAREFA
            case 'arquivar':
                $sql = "UPDATE tarefas SET arquivada = 1 WHERE id = $id";
                break;
            
            // AÇÃO 4: DESARQUIVAR TAREFA
            case 'desarquivar':
                $sql = "UPDATE tarefas SET arquivada = 0 WHERE id = $id";
                break;
            
            // AÇÃO 5: EXCLUIR TAREFA
            case 'excluir':
                $sql = "DELETE FROM tarefas WHERE id = $id";
                break;
                
            default:
                die("Ação não identificada!");
        }

        // Executa qualquer uma das queries do switch e recarrega a página
        if (mysqli_query($conexao, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Erro ao executar ação: " . mysqli_error($conexao);
        }
    }
}
?>
