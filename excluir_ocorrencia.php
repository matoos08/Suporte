<?php
require 'conexao.php';
require 'valida_session.php';

// Verifica se o ID (id_ocorrencia) foi enviado
if (isset($_POST['id_ocorrencia'])) {
    $id = $_POST['id_ocorrencia'];

    // Monta a query de DELETE
    $sql = "DELETE FROM ocorrencias WHERE id_ocorrencia = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('Location: gestao_ocorrencias.php'); // Redireciona de volta
        exit;
    } else {
        echo "Erro ao excluir ocorrência.";
    }
} else {
    echo "ID da ocorrência não fornecido.";
}
?>
