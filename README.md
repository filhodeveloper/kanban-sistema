# 📋 Quadro Kanban Virtual - Sistema de Gestão Ágil

Projeto desenvolvido como Trabalho de Conclusão de Curso (TCC) focado na aplicação da metodologia ágil **Kanban** para gerenciamento visual de tarefas. A aplicação utiliza tecnologias web nativas e arquitetura clássica server-side.

---

## 🛠️ Tecnologias Utilizadas

* **Linguagem Server-Side:** PHP 8.0+
* **Banco de Dados:** MySQL (via XAMPP)
* **Front-end:** HTML5, CSS3, JavaScript puro (Drag and Drop nativo)
* **Servidor Web Local:** Apache (XAMPP)

---

## 📌 Status do Desenvolvimento

### ✅ Funcionalidades Implementadas
* [x] Modelagem do banco de dados relacional (`usuarios` e `tarefas` com `FOREIGN KEY`).
* [x] Visualização do quadro Kanban dividido em colunas (`A Fazer`, `Em Andamento`, `Concluído`).
* [x] Movimentação interativa de cartões entre colunas via **Drag and Drop** (JavaScript).

### 🔄 Próximas Etapas (Em Desenvolvimento)
* [ ] Persistência da atualização do status da tarefa via AJAX/PHP no MySQL.
* [ ] Funcionalidade de exclusão e edição de tarefas no quadro.
* [ ] Sistema de Autenticação (Tela de Login/Cadastro com hash de senha).
* [ ] Atribuição de tarefas para usuários cadastrados (`usuario_id`).
* [ ] Controle de acesso por nível de permissão (`usuario` e `adm`).
