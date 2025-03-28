<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id     = $_POST['id'];
    $nome   = trim($_POST['nome'] ?? '');
    $login  = trim($_POST['login'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $nivel  = trim($_POST['nivel'] ?? '');

    if (empty($nome) || empty($login) || empty($status) || empty($nivel)) {
        $_SESSION['msg'] = "Todos os campos são obrigatórios.";
        header("Location: dashboard_admin.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE utilizadores SET nome = ?, login = ?, status = ?, nivel = ? WHERE id = ?");
        $stmt->execute([$nome, $login, $status, $nivel, $id]);
        $_SESSION['msg'] = "Utilizador atualizado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['msg'] = "Erro ao atualizar o utilizador: " . $e->getMessage();
    }
    header("Location: dashboard_admin.php");
    exit;
}
?>
