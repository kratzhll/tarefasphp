
<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/app/Models/Tarefa.php";
require_once __DIR__ . "/app/Models/TarefaDAO.php";

$dao = new TarefaDAO();

$mensagem = "";


// ============================
// PROCESSAR IMPORTAÇÃO
// ============================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !isset($_FILES["arquivo"]) ||
        $_FILES["arquivo"]["error"] !== UPLOAD_ERR_OK
    ) {

        $mensagem = "Selecione um arquivo XML válido.";

    } else {

        $arquivo = $_FILES["arquivo"]["tmp_name"];

        // Carregar XML
        libxml_use_internal_errors(true);

        $oXml = simplexml_load_file($arquivo);

        if ($oXml === false || $oXml->getName() !== "tarefas") {

            $mensagem = "XML inválido ou estrutura incorreta.";

        } else {

            $tarefasImportadas = [];

            foreach ($oXml->tarefa as $tarefa) {

                $titulo = trim((string) $tarefa->titulo);

                if ($titulo === "") {
                    continue;
                }

                $tarefasImportadas[] = [

                    "titulo" => $titulo,

                    "concluida" =>
                        ((string) $tarefa->concluida === "1")

                ];

            }

            if (empty($tarefasImportadas)) {

                $mensagem = "Nenhuma tarefa encontrada no XML.";

            } else {

                // Importar para o JSON
                $dao->importar($tarefasImportadas);

                // Voltar para a lista de tarefas
                header("Location: index.php");

                exit;

            }

        }

    }

}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="views/style.css">

    <title>Importação XML</title>

</head>

<body>

    <h1>Importação XML</h1>


    <?php if ($mensagem !== ""): ?>

        <p>
            <?= htmlspecialchars($mensagem, ENT_QUOTES, "UTF-8") ?>
        </p>

    <?php endif; ?>


    
<form
    action="importarxml.php"
    method="POST"
    enctype="multipart/form-data"
>

    <div class="linha-importacao">

        <label class="label-importacao" for="arquivo">
            Selecione o arquivo XML:
        </label>

        <input
            type="file"
            id="arquivo"
            name="arquivo"
            accept=".xml,text/xml,application/xml"
            required
        >

        <button type="submit">
            Importar XML
        </button>

    </div>

</form>

<a href="index.php">
    Voltar para tarefas
</a>


</body>

</html>