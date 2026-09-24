// URL do teu controlador/API principal em PHP
const URL_API = '../index.php'; 
const TOKEN = 'qwert';

// 1. FUNÇÃO PARA LISTAR TAREFAS (GET)
async function buscarTarefas() {
    try {
        const resposta = await fetch(URL_API, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${TOKEN}`
            }
        });

        if (!resposta.ok) {
            throw new Error(`Erro: ${resposta.status}`);
        }

        const tarefas = await resposta.json();
        console.log('Tarefas carregadas da API:', tarefas);
        
        return tarefas;

    } catch (erro) {
        console.error('Erro ao consumir a API (GET):', erro);
    }
}

// 2. FUNÇÃO PARA ADICIONAR TAREFA (POST)
async function enviarDados(novaTarefa) {
    try {
        const resposta = await fetch(URL_API, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${TOKEN}`
            },
            body: JSON.stringify(novaTarefa)
        });

        if (!resposta.ok) {
            throw new Error(`Erro no servidor: ${resposta.status}`);
        }

        const dadosRetornados = await resposta.json();
        console.log('Sucesso! Tarefa criada na API:', dadosRetornados);

        // Atualiza a lista após inserir
        buscarTarefas();

        return dadosRetornados;

    } catch (erro) {
        console.error('Erro ao enviar dados para a API (POST):', erro);
    }
}