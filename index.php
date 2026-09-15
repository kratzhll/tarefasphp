<?php

session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/usuario.php";

$objUsuario = unserialize($_SESSION["usuario"]);

require_once __DIR__ . "/app/Models/Tarefa.php";
require_once __DIR__ . "/app/Models/TarefaDAO.php";
require_once __DIR__ . "/app/Controllers/TarefaController.php";

$controller = new TarefaController();


// Se tiver Authorization, trata como API
$headers = getallheaders();

if (isset($headers["Authorization"])) {

    $controller->executar();

    exit;
}


// Caso contrário, mostra a página HTML
$tarefas = $controller->listarView();

require __DIR__ . "/views/tarefas.php";


    if (isset($_FILES['arquivo'])) {
        $sConteudo = file_get_contents($_FILES['arquivo']['tmp_name']);
        echo 'Arquivo: ' . $_FILES['arquivo']['name'] . '<br />';
        echo 'Tipo: ' . $_FILES['arquivo']['type'] . '<br />';
        echo 'Tamanho: ' . $_FILES['arquivo']['size'] . ' bites<br />';
        echo 'Conteudo: <br />' . $sConteudo;
        echo '<br /><br />';
    }


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de XML</title>
</head>
<body>

<form action="index.php" method="post" enctype="multipart/form-data">
    <label for="arquivo">Arquivo</label>
    <input type="file" id="arquivo" name="arquivo">
    <input type="submit" value="Enviar">
</form>

</body>
</html>
