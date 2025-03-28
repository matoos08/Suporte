<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém o ID do equipamento a ser excluído
    $id = $_POST['Cod_Equipamento'];

    // Prepara a query para excluir o equipamento
    $stmt = $pdo->prepare("DELETE FROM equipamentos WHERE Cod_Equipamento = :id");
    $stmt->bindParam(':id', $id);

    if($stmt->execute()) {
        $_SESSION['msg'] = "Equipamento excluído com sucesso!";
    } else {
        $_SESSION['msg'] = "Erro ao excluir equipamento!";
    }
}

header("Location: gestao_equipamentos_tec.php");
exit();
?>
