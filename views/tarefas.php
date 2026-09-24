<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/style.css">
    <title>Lista de Tarefas</title>
</head>
<body>

    <!-- CADASTRO DE TAREFAS -->
    <h1>Nova Tarefa</h1>

    <form id="form">
        <input
            id="titulo"
            placeholder="Digite uma tarefa"
            required
        >
        <button type="submit">
            Cadastrar
        </button>
    </form>

    <p id="mensagem"></p>

    <!-- LISTAGEM -->
    <h1>Tarefas</h1>
    <ul id="lista"></ul>

    <script>
        const token = {
            "Authorization": "Bearer qwert"
        };

        const form = document.getElementById("form");
        const titulo = document.getElementById("titulo");
        const lista = document.getElementById("lista");
        const mensagem = document.getElementById("mensagem");

        // CARREGAR TAREFAS
        function carregar() {
            fetch("index.php", {
                headers: token
            })
            .then(r => {
                if (!r.ok) throw new Error("Erro ao carregar");
                return r.json();
            })
            .then(tarefas => {
                lista.innerHTML = "";
                tarefas.forEach(tarefa => {
                    lista.innerHTML += `
                        <li>
                            ${tarefa.id} - ${tarefa.titulo}
                            <button onclick="deletar(${tarefa.id})">
                                Excluir
                            </button>
                        </li>
                    `;
                });
            })
            .catch(() => {
                mensagem.textContent = "Erro ao carregar as tarefas.";
            });
        }

        // --- FUNÇÃO PASSADA PELO PROFESSOR ADAPTADA ---
        async function enviarDados(novaTarefa) {
            try {
                const resposta = await fetch('index.php', {
                    method: 'POST',
                    headers: {
                        ...token, // Mantém o token exigido pelo controller
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(novaTarefa) // Converte o objeto JS para string JSON
                });

                // Verifica se a API respondeu com status de sucesso (código 200-299)
                if (!resposta.ok) {
                    throw new Error(`Erro no servidor: ${resposta.status}`);
                }

                const dadosRetornados = await resposta.json();
                console.log('Sucesso! Dados criados na API:', dadosRetornados);
                
                // Limpa o input e atualiza a tela
                titulo.value = "";
                carregar();

                return dadosRetornados;

            } catch (erro) {
                console.error('Erro ao enviar dados:', erro);
                alert('Erro ao cadastrar a tarefa.');
            }
        }

        // CADASTRAR TAREFA USANDO A FUNÇÃO DO PROFESSOR
        form.onsubmit = e => {
            e.preventDefault();

            const tarefaExemplo = {
                titulo: titulo.value
            };

            enviarDados(tarefaExemplo);
        };

        // EXCLUIR TAREFA
        function deletar(id) {
            fetch(`index.php?id=${id}`, {
                method: "DELETE",
                headers: token
            })
            .then(() => carregar());
        }

        // INICIAR
        carregar();
    </script>

    <nav class="menu-principal">
        <div class="links-menu">
            <a href="exportarxml.php">Exportar XML</a>
            <a href="importarxml.php">Importar XML</a>
            <a href="phpmailer.php">Enviar XML por E-mail</a>
        </div>
        <a href="login.php" class="link-sair">Sair</a>
    </nav>

    <!-- IMAGEM DOS GURI -->
    <img
        src="https://th.bing.com/th/id/OIP.k21f09xclyCLKa73nA56VgHaEK?w=319&h=180&c=7&r=0&o=7&pid=1.7&rm=3"
        title="Imagem dos Guri"
        class="imagem-guri"
    >

    <script src="script.js"></script>
</body>
</html>