<?php

// Ativar exibição de erros (útil para testes)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluir PHPMailer
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

// Dados do formulário
$nome     = $_POST['your-name'] ?? '';
$email    = $_POST['your-email'] ?? '';
$telefone = $_POST['your-phone'] ?? '';
$mensagem = $_POST['your-message'] ?? '';

// Criar instância do PHPMailer
$mail = new PHPMailer(true);

try {
    // Habilitar log de debug em arquivo
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {
        file_put_contents('debug_smtp.txt', $str . "\n", FILE_APPEND);
    };

    // Configuração SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.titan.email';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'edson@moveisbelari.com.br';  // seu e-mail Titan
    $mail->Password   = 'Belari1234@';                // sua senha do Titan
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // SSL
    $mail->Port       = 465;                          // Porta SSL

    // Remetente e destinatário
    $mail->setFrom('edson@moveisbelari.com.br', 'Site Moveis Belari');
    $mail->addAddress('contato@moveisbelari.com.br');

    // Conteúdo do e-mail
    $mail->isHTML(false);
    $mail->Subject = 'Novo contato do site Belari Moveis';
    $mail->Body    = "Nome: $nome\nE-mail: $email\nTelefone: $telefone\nMensagem:\n$mensagem";

    // Enviar
    $mail->send();

    // Redirecionar após sucesso
    header("Location: obrigado.html");
    exit;

} catch (Exception $e) {
    // Salvar erro em arquivo
    file_put_contents('erro_envio.txt', "Erro ao enviar e-mail: {$mail->ErrorInfo}\n", FILE_APPEND);
    header("Location: erro.html");
    exit;
}