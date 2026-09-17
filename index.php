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