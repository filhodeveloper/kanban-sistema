<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Kanban Ágil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="app-container">
        
        <!-- BARRA LATERAL -->
        <aside class="sidebar">
            <div class="logo-area">
                <h2>Kanban Design</h2>
                <span class="subtitle">Protótipo do TCC</span>
            </div>
            
            <form action="cadastrar_tarefa.php" method="POST" class="form-cadastro">
                <h3>Nova Atividade</h3>
                
                <div class="form-group">
                    <label for="titulo">Título da Tarefa:</label>
                    <input type="text" id="titulo" name="titulo" required placeholder="Ex: Criar tela de Login">
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" rows="4" placeholder="Detalhes do que deve ser feito..."></textarea>
                </div>

                <!-- CAMPO DE ATRIBUIÇÃO DE USUÁRIO -->
                <div class="form-group">
                    <label for="usuario_id">Responsável:</label>
                    <select id="usuario_id" name="usuario_id">
                        <option value="">-- Sem responsável --</option>
                        <?php
                        // Busca todos os usuários cadastrados para listar no formulário
                        $usuarios_query = mysqli_query($conexao, "SELECT id, nome FROM usuarios ORDER BY nome ASC");
                        while($user = mysqli_fetch_assoc($usuarios_query)) {
                            echo "<option value='".$user['id']."'>".htmlspecialchars($user['nome'])."</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <button type="submit" class="btn-salvar">Cadastrar Tarefa</button>
            </form>
        </aside>

        <!-- ÁREA PRINCIPAL DO QUADRO -->
        <main class="quadro-container">
            <header class="quadro-header">
                <h1>Painel de Controle Ágil</h1>
            </header>

            <div class="quadro">
                
                <!-- COLUNA: A FAZER -->
                <div class="coluna coluna-fazer" ondragover="allowDrop(event)" ondrop="drop(event, 'A Fazer')">
                    <div class="coluna-titulo">
                        <h3>A Fazer</h3>
                    </div>
                    <div class="area-cartoes">
                        <?php
                        // Faz uma busca (JOIN) unindo tarefas e usuários para pegar o nome do responsável
                        $busca = mysqli_query($conexao, "SELECT t.*, u.nome AS responsavel FROM tarefas t LEFT JOIN usuarios u ON t.usuario_id = u.id WHERE t.status = 'A Fazer' ORDER BY t.id DESC");
                        while ($tarefa = mysqli_fetch_assoc($busca)) {
                            echo "<div class='cartao card-fazer' draggable='true' ondragstart='drag(event)' id='tarefa-" . $tarefa['id'] . "' data-id='" . $tarefa['id'] . "'>";
                            // Botão de excluir tarefa
                            echo "<a href='excluir_tarefa.php?id=" . $tarefa['id'] . "' class='btn-excluir' onclick='return confirm(\"Deseja realmente excluir esta tarefa?\")'>&times;</a>";
                            echo "<h4>" . htmlspecialchars($tarefa['titulo']) . "</h4>";
                            echo "<p>" . htmlspecialchars($tarefa['descricao']) . "</p>";
                            // Exibe o nome do responsável se houver algum associado
                            if(!empty($tarefa['responsavel'])) {
                                echo "<span class='badge-user'>👤 " . htmlspecialchars($tarefa['responsavel']) . "</span>";
                            }
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>

                <!-- COLUNA: EM ANDAMENTO -->
                <div class="coluna coluna-andamento" ondragover="allowDrop(event)" ondrop="drop(event, 'Em Andamento')">
                    <div class="coluna-titulo">
                        <h3>Em Andamento</h3>
                    </div>
                    <div class="area-cartoes">
                        <?php
                        $busca = mysqli_query($conexao, "SELECT t.*, u.nome AS responsavel FROM tarefas t LEFT JOIN usuarios u ON t.usuario_id = u.id WHERE t.status = 'Em Andamento' ORDER BY t.id DESC");
                        while ($tarefa = mysqli_fetch_assoc($busca)) {
                            echo "<div class='cartao card-andamento' draggable='true' ondragstart='drag(event)' id='tarefa-" . $tarefa['id'] . "' data-id='" . $tarefa['id'] . "'>";
                            echo "<a href='excluir_tarefa.php?id=" . $tarefa['id'] . "' class='btn-excluir' onclick='return confirm(\"Deseja realmente excluir esta tarefa?\")'>&times;</a>";
                            echo "<h4>" . htmlspecialchars($tarefa['titulo']) . "</h4>";
                            echo "<p>" . htmlspecialchars($tarefa['descricao']) . "</p>";
                            if(!empty($tarefa['responsavel'])) {
                                echo "<span class='badge-user'>👤 " . htmlspecialchars($tarefa['responsavel']) . "</span>";
                            }
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>

                <!-- COLUNA: CONCLUÍDO -->
                <div class="coluna coluna-concluido" ondragover="allowDrop(event)" ondrop="drop(event, 'Concluído')">
                    <div class="coluna-titulo">
                        <h3>Concluído</h3>
                    </div>
                    <div class="area-cartoes">
                        <?php
                        $busca = mysqli_query($conexao, "SELECT t.*, u.nome AS responsavel FROM tarefas t LEFT JOIN usuarios u ON t.usuario_id = u.id WHERE t.status = 'Concluído' ORDER BY t.id DESC");
                        while ($tarefa = mysqli_fetch_assoc($busca)) {
                            echo "<div class='cartao card-concluido' draggable='true' ondragstart='drag(event)' id='tarefa-" . $tarefa['id'] . "' data-id='" . $tarefa['id'] . "'>";
                            echo "<a href='excluir_tarefa.php?id=" . $tarefa['id'] . "' class='btn-excluir' onclick='return confirm(\"Deseja realmente excluir esta tarefa?\")'>&times;</a>";
                            echo "<h4>" . htmlspecialchars($tarefa['titulo']) . "</h4>";
                            echo "<p>" . htmlspecialchars($tarefa['descricao']) . "</p>";
                            if(!empty($tarefa['responsavel'])) {
                                echo "<span class='badge-user'>👤 " . htmlspecialchars($tarefa['responsavel']) . "</span>";
                            }
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- CÓDIGO JAVASCRIPT DE MOVIMENTAÇÃO -->
    <script>
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function drop(ev, novoStatus) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        var cartaoElemento = document.getElementById(data);
        var areaCartoesDestino = ev.currentTarget.querySelector('.area-cartoes');
        
        areaCartoesDestino.appendChild(cartaoElemento);
        
        cartaoElemento.className = 'cartao';
        if (novoStatus === 'A Fazer') {
            cartaoElemento.classList.add('card-fazer');
        } else if (novoStatus === 'Em Andamento') {
            cartaoElemento.classList.add('card-andamento');
        } else if (novoStatus === 'Concluído') {
            cartaoElemento.classList.add('card-concluido');
        }

        var tarefaId = cartaoElemento.getAttribute('data-id');
        enviarAtualizacaoBanco(tarefaId, novoStatus);
    }

    function enviarAtualizacaoBanco(id, status) {
        var dadosFormulario = new FormData();
        dadosFormulario.append('id', id);
        dadosFormulario.append('status', status);

        fetch('atualizar_status.php', {
            method: 'POST',
            body: dadosFormulario
        })
        .then(response => response.json())
        .then(dados => {
            if (dados.sucesso) {
                console.log("Banco de dados atualizado com sucesso!");
            } else {
                alert("Erro ao salvar mudança no banco: " + dados.erro);
            }
        })
        .catch(erro => {
            console.error("Erro na comunicação com o servidor:", erro);
        });
    }
    </script>

</body>
</html>