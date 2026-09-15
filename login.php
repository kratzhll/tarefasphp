<?php

session_start();
require_once "usuario.php";

$usuario1 = "ref123";
$senha1 = "123";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = $_POST["usuario"] ?? "";
    $senha = $_POST["senha"] ?? "";

    if ($usuario === $usuario1 && $senha === $senha1) {
        $srzUsuario = new Usuario();

        $srzUsuario->setNome($usuario);
        $srzUsuario->setSenha($senha);

        $_SESSION["usuario"] = serialize($srzUsuario);

        header("Location: index.php");
        exit;

    } else {
        echo 'Você digitou um usuário ou senha incorretos. <a href="login.php">Tente novamente</a>.';
}}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="views/style.css">
</head>

<body>
    <h1>Login</h1>
    <form method="POST">
        <input 
            type="text" 
            name="usuario" 
            placeholder="Digite seu usuário" 
            required
        >

        <input 
            type="password" 
            name="senha" 
            placeholder="Digite sua senha" 
            required
        >
        <button type="submit">Entrar</button>
    </form>

</body>
</html>