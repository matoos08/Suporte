<?php
require 'conexao.php';
require 'valida_session.php';

// Verifica se todos os campos foram enviados
if (
    isset($_POST['Descricao_Equipamento']) && 
    isset($_POST['Obs_Equipamento']) && 
    isset($_POST['Estado_Equipamento'])
) {
    $desc = $_POST['Descricao_Equipamento'];
    $obs = $_POST['Obs_Equipamento'];
    $estado = $_POST['Estado_Equipamento'];

    // Monta a query de INSERT
    $sql = "INSERT INTO equipamentos (Descricao_Equipamento, Obs_Equipamento, Estado_Equipamento)
            VALUES (:desc, :obs, :estado)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':desc', $desc);
    $stmt->bindParam(':obs', $obs);
    $stmt->bindParam(':estado', $estado);

    if ($stmt->execute()) {
        header('Location: gestao_reparacoes.php'); // Redireciona de volta para a página principal
        exit;
    } else {
        echo "Erro ao adicionar reparação.";
    }
} else {
    echo "Preencha todos os campos.";
}
?>
    