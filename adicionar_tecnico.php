<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Apenas administradores podem aceder a esta página
if ($_SESSION['user']['nivel'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

// Verifica se o formulário foi submetido via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário
    $nome   = trim($_POST['nome'] ?? '');
    $login  = trim($_POST['login'] ?? '');
    $pass   = trim($_POST['pass'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $nivel  = trim($_POST['nivel'] ?? 'tecnico'); // Nível padrão é "tecnico"
    
    // Validação simples dos campos
    if (empty($nome) || empty($login) || empty($pass) || empty($status)) {
        $_SESSION['msg'] = "Por favor, preencha todos os campos.";
        header("Location: gestao_tecnicos.php");
        exit;
    }

    // Codifica a senha utilizando MD5
    $passHash = md5($pass);

    try {
        // Insere o novo técnico na tabela "utilizadores"
        $stmt = $pdo->prepare("INSERT INTO utilizadores (nome, login, pass, status, nivel) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $login, $passHash, $status, $nivel]);
        $_SESSION['msg'] = "Técnico adicionado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['msg'] = "Erro ao adicionar o técnico: " . $e->getMessage();
    }
    header("Location: gestao_tecnicos.php");
    exit;
} else {
    header("Location: gestao_tecnicos.php");
    exit;
}
?>
