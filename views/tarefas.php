<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/style.css">
    <title>Lista de Tarefas</title>
</head>

<body>
    <h1>Nova Tarefa</h1>
    <form id="form">
        <input id="titulo" placeholder="Digite uma tarefa" required>
        <button>Cadastrar</button>
    </form>

    <p id="mensagem"></p>
    <h2>Tarefas</h2>
    <ul id="lista"></ul>
    <script>

        const token = {
            "Authorization": "Bearer qwert"
        };


        /* função carregar foi copiada e adaptada do exercício anterior do loja-exc */

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
            });

        }

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

        function deletar(id) {
            fetch(`index.php?id=${id}`, {
                method: "DELETE",
                headers: token
            })
            .then(() => carregar());
        }

        carregar();
    </script>


    <img
        src="https://th.bing.com/th/id/OIP.k21f09xclyCLKa73nA56VgHaEK?w=319&h=180&c=7&r=0&o=7&pid=1.7&rm=3"
        title="Imagem dos Guri"
        class="imagem-guri"
    >

</body>
</html>