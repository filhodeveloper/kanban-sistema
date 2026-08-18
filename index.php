<?php include 'db.php'; // Conecta ao banco de dados ?>
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
                <!-- ondragover: Permite que elementos sejam arrastados por cima desta coluna -->
                <!-- ondrop: Executa a função JavaScript ao soltar o cartão aqui dentro -->
                <div class="coluna coluna-fazer" ondragover="allowDrop(event)" ondrop="drop(event, 'A Fazer')">
                    <div class="coluna-titulo">
                        <h3>A Fazer</h3>
                    </div>
                    <div class="area-cartoes">
                        <?php
                        $busca = mysqli_query($conexao, "SELECT * FROM tarefas WHERE status = 'A Fazer' ORDER BY id DESC");
                        while ($tarefa = mysqli_fetch_assoc($busca)) {
                            // draggable='true': Permite arrastar o cartão visualmente
                            // ondragstart: Salva qual cartão está sendo arrastado no início do movimento
                            echo "<div class='cartao card-fazer' draggable='true' ondragstart='drag(event)' id='tarefa-" . $tarefa['id'] . "' data-id='" . $tarefa['id'] . "'>";
                            echo "<h4>" . htmlspecialchars($tarefa['titulo']) . "</h4>";
                            echo "<p>" . htmlspecialchars($tarefa['descricao']) . "</p>";
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
                        $busca = mysqli_query($conexao, "SELECT * FROM tarefas WHERE status = 'Em Andamento' ORDER BY id DESC");
                        while ($tarefa = mysqli_fetch_assoc($busca)) {
                            echo "<div class='cartao card-andamento' draggable='true' ondragstart='drag(event)' id='tarefa-" . $tarefa['id'] . "' data-id='" . $tarefa['id'] . "'>";
                            echo "<h4>" . htmlspecialchars($tarefa['titulo']) . "</h4>";
                            echo "<p>" . htmlspecialchars($tarefa['descricao']) . "</p>";
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
                        $busca = mysqli_query($conexao, "SELECT * FROM tarefas WHERE status = 'Concluído' ORDER BY id DESC");
                        while ($tarefa = mysqli_fetch_assoc($busca)) {
                            echo "<div class='cartao card-concluido' draggable='true' ondragstart='drag(event)' id='tarefa-" . $tarefa['id'] . "' data-id='" . $tarefa['id'] . "'>";
                            echo "<h4>" . htmlspecialchars($tarefa['titulo']) . "</h4>";
                            echo "<p>" . htmlspecialchars($tarefa['descricao']) . "</p>";
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
    // 1. Permite que o navegador aceite soltar elementos nas colunas (por padrão o navegador bloqueia)
    function allowDrop(ev) {
        ev.preventDefault();
    }

    // 2. Guarda temporariamente na memória qual é o ID do cartão que começou a ser arrastado
    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    // 3. Executa a ação de soltar o cartão dentro da coluna de destino
    function drop(ev, novoStatus) {
        ev.preventDefault();
        
        // Recupera o ID do cartão arrastado
        var data = ev.dataTransfer.getData("text");
        var cartaoElemento = document.getElementById(data);
        
        // Encontra a área de cartões interna da coluna onde o usuário soltou o mouse
        var areaCartoesDestino = ev.currentTarget.querySelector('.area-cartoes');
        
        // Move visualmente o cartão para a nova coluna na tela
        areaCartoesDestino.appendChild(cartaoElemento);
        
        // Remove a borda colorida antiga do CSS e coloca a nova cor correspondente ao novo status
        cartaoElemento.className = 'cartao'; // Limpa classes antigas de estilo de cor
        if (novoStatus === 'A Fazer') {
            cartaoElemento.classList.add('card-fazer');
        } else if (novoStatus === 'Em Andamento') {
            cartaoElemento.classList.add('card-andamento');
        } else if (novoStatus === 'Concluído') {
            cartaoElemento.classList.add('card-concluido');
        }

        // Pega o ID numérico da tarefa salvo no atributo 'data-id'
        var tarefaId = cartaoElemento.getAttribute('data-id');
        
        // Envia de forma silenciosa para o banco de dados atualizar o status
        enviarAtualizacaoBanco(tarefaId, novoStatus);
    }

    // 4. Envia os dados para o back-end em PHP via Fetch API (AJAX moderno)
    function enviarAtualizacaoBanco(id, status) {
        // Cria um formulário invisível na memória do navegador
        var dadosFormulario = new FormData();
        dadosFormulario.append('id', id);
        dadosFormulario.append('status', status);

        // Dispara a requisição silenciosa ao arquivo PHP de atualização
        fetch('atualizar_status.php', {
            method: 'POST',
            body: dadosFormulario
        })
        .then(response => response.json()) // Converte a resposta recebida para JSON
        .then(dados => {
            if (dados.sucesso) {
                console.log("Banco de dados atualizado com sucesso!");
            } else {
                alert("Erro ao salvar mudança no banco: " . dados.erro);
            }
        })
        .catch(erro => {
            console.error("Erro na comunicação com o servidor:", erro);
        });
    }
    </script>

</body>
</html>