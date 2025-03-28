<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $id = $_POST['Cod_Equipamento'];
    $descricao = $_POST['Descricao_Equipamento'];
    $obs = isset($_POST['Obs_Equipamento']) ? $_POST['Obs_Equipamento'] : '';
    $estado = $_POST['Estado_Equipamento'];

    // Prepara a query para atualizar o equipamento
    $stmt = $pdo->prepare("UPDATE equipamentos SET Descricao_Equipamento = :descricao, Obs_Equipamento = :obs, Estado_Equipamento = :estado WHERE Cod_Equipamento = :id");
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':obs', $obs);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':id', $id);

    if($stmt->execute()) {
        $_SESSION['msg'] = "Equipamento atualizado com sucesso!";
    } else {
        $_SESSION['msg'] = "Erro ao atualizar equipamento!";
    }
}

header("Location: gestao_equipamentos.php");
exit();
?>
