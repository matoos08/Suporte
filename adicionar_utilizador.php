<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verifica se o usuário tem permissão (apenas administradores)
if ($_SESSION['user']['nivel'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome   = $_POST['nome'];
    $login  = $_POST['login'];
    $pass  = $_POST['pass'];
    $status = $_POST['status'];
    $nivel  = $_POST['nivel'];

    // Aqui você pode adicionar validação e sanitização dos dados, se necessário

    // Criptografa a senha utilizando MD5 (atenção: MD5 não é recomendado para ambientes de produção)
    $passHash = md5($pass);

    try {
        $stmt = $pdo->prepare("INSERT INTO utilizadores (nome, login, pass, status, nivel) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $login, $passHash, $status, $nivel]);
        $_SESSION['msg'] = "Utilizador adicionado com sucesso!";
        header("Location: dashboard_admin.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['msg'] = "Erro ao adicionar o utilizador: " . $e->getMessage();
        header("Location: dashboard_admin.php");
        exit;
    }
} else {
    header("Location: dashboard_admin.php");
    exit;
}
?>
