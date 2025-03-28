<?php
require 'conexao.php';
require 'valida_session.php';

// Verifica se o ID (Cod_Equipamento) foi enviado
if (isset($_POST['Cod_Equipamento'])) {
    $id = $_POST['Cod_Equipamento'];

    // Monta a query de DELETE
    $sql = "DELETE FROM equipamentos WHERE Cod_Equipamento = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('Location: gestao_reparacoes.php'); // Redireciona de volta
        exit;
    } else {
        echo "Erro ao excluir reparação.";  
    }
} else {
    echo "ID da reparação não fornecido.";
}
?>
