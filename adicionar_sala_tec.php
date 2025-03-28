<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Nome_sala  = $_POST['Nome_sala'];
    $Bloco_sala = $_POST['Bloco_sala'];
    $Piso_sala  = $_POST['Piso_sala'];
    $Observacoes = $_POST['Observações'];
    $Estado     = $_POST['Estado'];

    $sql = "INSERT INTO salas (Nome_sala, Bloco_sala, Piso_sala, Observações, Estado) 
            VALUES (:Nome_sala, :Bloco_sala, :Piso_sala, :Observacoes, :Estado)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':Nome_sala'  => $Nome_sala,
        ':Bloco_sala' => $Bloco_sala,
        ':Piso_sala'  => $Piso_sala,
        ':Observacoes'=> $Observacoes,
        ':Estado'     => $Estado
    ]);

    $_SESSION['msg_sala'] = "Sala adicionada com sucesso!";
    header("Location: gestao_salas_tec.php");
    exit;
}
?>
