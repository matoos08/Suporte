<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $descricao = $_POST['Descricao_Equipamento'];
    $obs = isset($_POST['Obs_Equipamento']) ? $_POST['Obs_Equipamento'] : '';
    $estado = $_POST['Estado_Equipamento'];

    // Prepara a query para inserir um novo equipamento
    $stmt = $pdo->prepare("INSERT INTO equipamentos (Descricao_Equipamento, Obs_Equipamento, Estado_Equipamento) VALUES (:descricao, :obs, :estado)");
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':obs', $obs);
    $stmt->bindParam(':estado', $estado);

    if($stmt->execute()) {
        $_SESSION['msg'] = "Equipamento adicionado com sucesso!";
    } else {
        $_SESSION['msg'] = "Erro ao adicionar equipamento!";
    }
}

header("Location: gestao_equipamentos.php");
exit();
?>
