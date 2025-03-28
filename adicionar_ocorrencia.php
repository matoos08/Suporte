<?php
session_start();
require 'conexao.php';       // Arquivo que contém a conexão PDO
require 'valida_session.php'; // Arquivo que valida a sessão do usuário

// Verifica se o formulário foi submetido via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados enviados pelo formulário
    $problema = isset($_POST['prob_utilizador']) ? trim($_POST['prob_utilizador']) : '';
    $estado   = isset($_POST['estado']) ? trim($_POST['estado']) : '';
    $tecnico  = isset($_POST['tecnico']) ? trim($_POST['tecnico']) : '';

    // Supondo que o ID do usuário logado esteja armazenado na sessão como 'id'
    $idutil = isset($_SESSION['id']) ? $_SESSION['id'] : null;

    // Validação simples para garantir que os campos não estão vazios e o idutil está definido
    if (!empty($problema) && !empty($estado) && !empty($tecnico) && !empty($idutil)) {
        // Monta a query de inserção, agora incluindo o campo idutil
        $sqlInsert = "INSERT INTO ocorrencias (prob_utilizador, estado, tecnico, idutil) 
                      VALUES (:prob, :estado, :tecnico, :idutil)";
        $stmt = $pdo->prepare($sqlInsert);

        // Executa a query com os valores recebidos
        if ($stmt->execute([
            ':prob'    => $problema,
            ':estado'  => $estado,
            ':tecnico' => $tecnico,
            ':idutil'  => $idutil,
        ])) {
            $_SESSION['msg_ocorrencia'] = "Ocorrência adicionada com sucesso!";
        } else {
            $_SESSION['msg_ocorrencia'] = "Erro ao adicionar ocorrência.";
        }
    } else {
        $_SESSION['msg_ocorrencia'] = "Preencha todos os campos.";
    }
    // Redireciona para a página de gestão de ocorrências para evitar reenvio do formulário
    header("Location: gestao_ocorrencias.php");
    exit;
} else {
    // Se o acesso não for via POST, redireciona para a página de gestão de ocorrências
    header("Location: gestao_ocorrencias.php");
    exit;
}
?>
