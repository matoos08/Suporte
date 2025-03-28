<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cod_sala = $_POST['cod_sala'];

    $sql = "DELETE FROM salas WHERE cod_sala = :cod_sala";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cod_sala' => $cod_sala
    ]);

    $_SESSION['msg_sala'] = "Sala excluída com sucesso!";
    header("Location: gestao_salas.php");
    exit;
}
?>
