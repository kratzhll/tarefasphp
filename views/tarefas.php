
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="views/style.css">

    <title>Lista de Tarefas</title>
</head>

<body>

    

<nav class="menu-principal">

    <div class="links-menu">

        <a href="exportarxml.php">
            Exportação XML
        </a>

        <a href="importarxml.php">
            Importação XML
        </a>

    </div>

    <a href="logout.php" class="link-sair">
        Sair
    </a>

</nav>


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
    <h2>Tarefas</h2>

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

            .then(r => r.json())

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

                mensagem.textContent =
                    "Erro ao carregar as tarefas.";

            });

        }


        // CADASTRAR TAREFA
        form.onsubmit = e => {

            e.preventDefault();

            fetch("index.php", {

                method: "POST",

                headers: {
                    ...token,
                    "Content-Type": "application/json"
                },

                body: JSON.stringify({

                    titulo: titulo.value

                })

            })

            .then(r => r.json())

            .then(() => {

                titulo.value = "";

                carregar();

            });

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


    <!-- IMAGEM DOS GURI -->
    <img
        src="https://th.bing.com/th/id/OIP.k21f09xclyCLKa73nA56VgHaEK?w=319&h=180&c=7&r=0&o=7&pid=1.7&rm=3"
        title="Imagem dos Guri"
        class="imagem-guri"
    >

</body>

</html>