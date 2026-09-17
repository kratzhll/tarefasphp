<?php

ob_start();

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/app/Models/Tarefa.php";
require_once __DIR__ . "/app/Models/TarefaDAO.php";

$dao = new TarefaDAO();


// ============================
// PROCESSAR EXPORTAÇÃO
// ============================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recebe o tipo selecionado
    $tipo = $_POST["tipo"] ?? "";

    // Busca todas as tarefas
    $tarefas = $dao->listar();


    // Filtra as tarefas
    switch ($tipo) {

        case "todas":

            break;


        case "concluidas":

            $tarefas = array_filter(
                $tarefas,
                fn($tarefa) => $tarefa["concluida"] == true
            );

            break;


        case "pendentes":

            $tarefas = array_filter(
                $tarefas,
                fn($tarefa) => $tarefa["concluida"] == false
            );

            break;


        default:

            exit("Tipo de exportação inválido.");

    }


    // ============================
    // CRIAR XML
    // ============================

    $oXml = new SimpleXMLElement(
        '<?xml version="1.0" encoding="UTF-8"?><tarefas/>'
    );


    foreach ($tarefas as $tarefa) {

        $oTarefa = $oXml->addChild("tarefa");


        $oTarefa->addChild(
            "id",
            (string) $tarefa["id"]
        );


        $oTarefa->addChild(
            "titulo",
            (string) $tarefa["titulo"]
        );


        $oTarefa->addChild(
            "concluida",
            $tarefa["concluida"] ? "1" : "0"
        );

    }


    // ============================
    // DOWNLOAD DO XML
    // ============================

    $conteudoXml = $oXml->asXML();

    ob_clean();

    header("Content-Type: application/xml; charset=UTF-8");

    header(
    'Content-Disposition: attachment; filename="tarefas.xml"'
    );

    echo $conteudoXml;

    exit;

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="views/style.css">

    <title>Exportação XML</title>

</head>

<body>


    <h1>Exportação XML</h1>


    
<form action="exportarxml.php" method="POST">

    <div class="linha-exportacao">

        <label class="label-exportacao">
            Selecione o tipo de exportação:
        </label>

        <label class="radio-exportacao">
            <input
                type="radio"
                name="tipo"
                value="todas"
                checked
            >
            Todas as tarefas
        </label>

        <button type="submit">
            Confirmar exportação
        </button>

    </div>

</form>

<a href="index.php">
    Voltar para tarefas
</a>


</body>

</html>