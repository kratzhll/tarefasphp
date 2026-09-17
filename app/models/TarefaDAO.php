
<?php

class TarefaDAO
{
    private $arquivo;

    public function __construct()
    {
        $this->arquivo = __DIR__ . "/../../persistencia/dados.json";
    }

    // LER O DADOS.JSON
    public function listar()
    {
        $dados = file_get_contents($this->arquivo);

        return json_decode($dados, true) ?? [];
    }

    // BUSCAR TAREFA POR ID
    public function buscarPorId($id)
    {
        $tarefas = $this->listar();

        foreach ($tarefas as $tarefa) {

            if ($tarefa["id"] === $id) {
                return $tarefa;
            }
        }

        return null;
    }

    // ADICIONAR TAREFA
    public function adicionar($tarefa)
    {
        $tarefas = $this->listar();

        $ids = array_column($tarefas, "id");

        $novoId = empty($ids)
            ? 1
            : max($ids) + 1;

        $novaTarefa = [
            "id" => $novoId,
            "titulo" => $tarefa->titulo,
            "concluida" => false
        ];

        $tarefas[] = $novaTarefa;

        $this->salvar($tarefas);

        return $novaTarefa;
    }

    // ATUALIZAR TAREFA
    public function atualizar($id, $dados)
    {
        $tarefas = $this->listar();

        foreach ($tarefas as $indice => $tarefa) {

            if ($tarefa["id"] === $id) {

                if (isset($dados["titulo"])) {
                    $tarefas[$indice]["titulo"] = $dados["titulo"];
                }

                if (isset($dados["concluida"])) {
                    $tarefas[$indice]["concluida"] = $dados["concluida"];
                }

                $this->salvar($tarefas);

                return $tarefas[$indice];
            }
        }

        return null;
    }

    // EXCLUIR TAREFA
    public function excluir($id)
    {
        $tarefas = $this->listar();

        foreach ($tarefas as $indice => $tarefa) {

            if ($tarefa["id"] === $id) {

                unset($tarefas[$indice]);

                $tarefas = array_values($tarefas);

                $this->salvar($tarefas);

                return true;
            }
        }

        return false;
    }

    // IMPORTAR TAREFAS DO XML PARA O JSON
    public function importar(array $tarefasImportadas)
    {
        $tarefas = $this->listar();

        $ids = array_column($tarefas, "id");

        $proximoId = empty($ids)
            ? 1
            : max($ids) + 1;

        foreach ($tarefasImportadas as $tarefa) {

            $titulo = trim((string) $tarefa["titulo"]);

            if ($titulo === "") {
                continue;
            }

            $tarefas[] = [
                "id" => $proximoId,
                "titulo" => $titulo,
                "concluida" => (bool) $tarefa["concluida"]
            ];

            $proximoId++;
        }

        $this->salvar($tarefas);

        return true;
    }

    // SALVAR AS TAREFAS NO DADOS.JSON
    private function salvar($tarefas)
    {
        file_put_contents(
            $this->arquivo,
            json_encode(
                $tarefas,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
        );
    }
}