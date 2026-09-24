<?php

session_start();

// 1. Verifica PRIMEIRO se é uma requisição da API (via fetch com Token)
$headers = getallheaders();
if (isset($headers["Authorization"])) {
    require_once __DIR__ . "/app/Models/Tarefa.php";
    require_once __DIR__ . "/app/Models/TarefaDAO.php";
    require_once __DIR__ . "/app/Controllers/TarefaController.php";

    $controller = new TarefaController();
    $controller->executar();
    exit; 
}

// 2. Se NÃO estiver logado, redireciona dinamicamente para o login.php correto
if (!isset($_SESSION["usuario"])) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    
    header("Location: $protocol://$host$path/login.php");
    exit;
}

require_once __DIR__ . "/usuario.php";

$objUsuario = unserialize($_SESSION["usuario"]);

require_once __DIR__ . "/app/Models/Tarefa.php";
require_once __DIR__ . "/app/Models/TarefaDAO.php";
require_once __DIR__ . "/app/Controllers/TarefaController.php";

$controller = new TarefaController();

$tarefas = $controller->listarView();

require __DIR__ . "/views/tarefas.php";