<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM utilizadores WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['msg'] = "Utilizador excluído com sucesso!";
    } catch (Exception $e) {
        $_SESSION['msg'] = "Erro ao excluir o utilizador: " . $e->getMessage();
    }
    header("Location: dashboard_admin.php");
    exit;
}
?>
