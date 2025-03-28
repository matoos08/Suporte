<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cod_sala   = $_POST['cod_sala'];
    $Nome_sala  = $_POST['Nome_sala'];
    $Bloco_sala = $_POST['Bloco_sala'];
    $Piso_sala  = $_POST['Piso_sala'];
    $Observacoes = $_POST['Observações'];
    $Estado     = $_POST['Estado'];

    $sql = "UPDATE salas 
            SET Nome_sala = :Nome_sala, 
                Bloco_sala = :Bloco_sala, 
                Piso_sala = :Piso_sala, 
                Observações = :Observacoes, 
                Estado = :Estado 
            WHERE cod_sala = :cod_sala";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':Nome_sala'  => $Nome_sala,
        ':Bloco_sala' => $Bloco_sala,
        ':Piso_sala'  => $Piso_sala,
        ':Observacoes'=> $Observacoes,
        ':Estado'     => $Estado,
        ':cod_sala'   => $cod_sala
    ]);

    $_SESSION['msg_sala'] = "Sala atualizada com sucesso!";
    header("Location: gestao_salas.php");
    exit;
}
?>
