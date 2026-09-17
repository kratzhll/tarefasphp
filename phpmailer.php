<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'fernandofalencardoso@gmail.com';
        $mail->Password = 'pihz xely irdt jgtn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('fernandofalencardoso@gmail.com', 'Envio de XML do Sistema de Tarefas');
        $mail->addAddress($_POST["destinatario"]);

        $dados = json_decode(file_get_contents(__DIR__ . '/persistencia/dados.json'), true);
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><tarefas/>');

        foreach ($dados as $tarefa) {
            $novaTarefa = $xml->addChild('tarefa');
            $novaTarefa->addChild('id', $tarefa['id']);
            $novaTarefa->addChild('titulo', $tarefa['titulo']);
            $novaTarefa->addChild('concluida', $tarefa['concluida'] ? '1' : '0');
        }

        $mail->isHTML(true);
        $mail->Subject = 'Exportação de Tarefas';
        $mail->Body = 'Segue em anexo o arquivo XML das tarefas.';
        $mail->AltBody = 'Segue em anexo o arquivo XML das tarefas.';
        $mail->addStringAttachment($xml->asXML(), 'tarefas.xml', 'base64', 'application/xml');
        $mail->send();

        echo 'E-mail enviado com sucesso!';
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Enviar XML</title>
</head>
<body>
    <h2>Enviar tarefas por e-mail</h2>
    <form method="POST">
        <label>E-mail do destinatário:</label>
        <input type="email" name="destinatario" required>
        <button type="submit">Enviar XML</button>
    </form>
</body>
</html>