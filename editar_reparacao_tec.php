<?php
require 'conexao.php';
require 'valida_session.php';

// Verifica se o ID (Cod_Equipamento) foi enviado
if (isset($_POST['Cod_Equipamento'])) {
    $id    = $_POST['Cod_Equipamento'];
    $desc  = $_POST['Descricao_Equipamento'];
    $obs   = $_POST['Obs_Equipamento'];
    $estado = $_POST['Estado_Equipamento'];

    // Monta a query de UPDATE
    $sql = "UPDATE equipamentos
            SET Descricao_Equipamento = :desc,
                Obs_Equipamento       = :obs,
                Estado_Equipamento    = :estado
            WHERE Cod_Equipamento    = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':desc', $desc);
    $stmt->bindParam(':obs', $obs);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('Location: gestao_reparacoes_tec.php'); // Redireciona de volta
        exit;
    } else {
        echo "Erro ao editar reparação.";
    }
} else {
    echo "ID da reparação não fornecido.";
}
?>
