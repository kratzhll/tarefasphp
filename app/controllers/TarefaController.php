<?php

class TarefaController
{
    private $dao;

    public function __construct()
    {
        $this->dao = new TarefaDAO();
    }

    // executa a api agora em POO
    public function executar()
    {
        header("Content-Type: application/json; charset=utf-8");

        $this->validarToken();

        $metodo = $_SERVER["REQUEST_METHOD"];

        if ($metodo === "GET") {

            $this->listar();

        } elseif ($metodo === "POST") {

            $this->adicionar();

        } elseif ($metodo === "PUT") {

            $this->atualizar();

        } elseif ($metodo === "DELETE") {

            $this->excluir();

        } else {

            http_response_code(405);

            echo json_encode([
                "erro" => "Método não permitido"
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    // valida o token de autorizacao
    private function validarToken()
    {
        $tokenCorreto = "qwert";

        $headers = getallheaders();
        $authorization = $headers["Authorization"] ?? "";

        if (!str_starts_with($authorization, "Bearer ")) {
            http_response_code(401);

            echo json_encode([
                "status" => "error",
                "message" => "Token não informado"
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $token = substr($authorization, 7);

        if (!hash_equals($tokenCorreto, $token)) {
            http_response_code(401);

            echo json_encode([
                "status" => "error",
                "message" => "Token incorreto"
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // get agora com o dao
    private function listar()
    {
        if (isset($_GET["id"])) {
            $id = (int) $_GET["id"];
            $tarefa = $this->dao->buscarPorId($id);

            if ($tarefa === null) {

                http_response_code(404);

                echo json_encode([
                    "erro" => "Tarefa não encontrada"
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            echo json_encode(
                $tarefa,
                JSON_UNESCAPED_UNICODE
            );

            return;
        }

        echo json_encode(
            $this->dao->listar(),
            JSON_UNESCAPED_UNICODE
        );
    }
    // post agoracom o dao
    private function adicionar()
    {
        $dados = json_decode(
            file_get_contents("php://input"),
            true
        );

        if (
            !isset($dados["titulo"]) ||
            trim($dados["titulo"]) === ""
        ) {

            http_response_code(400);
            echo json_encode([
                "erro" => "O título é obrigatório"
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $tarefa = new Tarefa();
        $tarefa->titulo = $dados["titulo"];

        $novaTarefa = $this->dao->adicionar($tarefa);

        http_response_code(201);

        echo json_encode(
            $novaTarefa,
            JSON_UNESCAPED_UNICODE
        );
    }

    // PUT
    private function atualizar()
    {
        if (!isset($_GET["id"])) {

            http_response_code(400);
            echo json_encode([
                "erro" => "O id é obrigatório"
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        $id = (int) $_GET["id"];

        $dados = json_decode(
            file_get_contents("php://input"),
            true
        );

        $tarefa = $this->dao->atualizar($id, $dados);

        if ($tarefa === null) {

            http_response_code(404);

            echo json_encode([
                "erro" => "Tarefa não encontrada"
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        echo json_encode(
            $tarefa,
            JSON_UNESCAPED_UNICODE
        );
    }

    // USADO PARA MOSTRAR A VIEW
    public function listarView()
    {
        return $this->dao->listar();
    }

    // DELETE
    private function excluir()
    {
        if (!isset($_GET["id"])) {

            http_response_code(400);

            echo json_encode([
                "erro" => "O id é obrigatório"
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        $id = (int) $_GET["id"];

        $encontrou = $this->dao->excluir($id);

        if (!$encontrou) {

            http_response_code(404);

            echo json_encode([
                "erro" => "Tarefa não encontrada"
            ], JSON_UNESCAPED_UNICODE);

            return;
        }

        echo json_encode([
            "mensagem" => "Tarefa removida com sucesso",
            "id" => $id
        ], JSON_UNESCAPED_UNICODE);
    }
}