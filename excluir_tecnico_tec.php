<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Apenas administradores podem aceder a esta página
if ($_SESSION['user']['nivel'] !== 'tecnico') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (empty($id)) {
        $_SESSION['msg'] = "ID inválido.";
        header("Location: gestao_tecnicos.php");
        exit;
    }

    try {
        // Exclui o técnico somente se o nível for "tecnico"
        $stmt = $pdo->prepare("DELETE FROM utilizadores WHERE id = ? AND nivel = 'tecnico'");
        $stmt->execute([$id]);
        $_SESSION['msg'] = "Técnico excluído com sucesso!";
    } catch (Exception $e) {
        $_SESSION['msg'] = "Erro ao excluir o técnico: " . $e->getMessage();
    }
    header("Location: gestao_tecnicos_tec.php");
    exit;
} else {
    header("Location: gestao_tecnicos_tec.php");
    exit;
}
?>
