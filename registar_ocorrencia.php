<?php
session_start();
require 'conexao.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$idutil = $_SESSION['user']['id'];
$andar = $_POST['andar'];
$setor = $_POST['setor'];
$contato = $_POST['contato'];
$escritorio = $_POST['escritorio'];
$prob_utilizador = $_POST['prob_utilizador'];
$estado = 'ABERTO';
$equipamento = $_POST['equipamento'];
$stmt = $pdo->prepare("
INSERT INTO registros (idutil, andar, setor, contato, escritorio,
prob_utilizador, estado, equipamento)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$idutil, $andar, $setor, $contato, $escritorio,
$prob_utilizador, $estado, $equipamento]);
header("Location: dashboard.php");
exit;
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
<title>Registar Ocorrência</title>
</head>
<body>
<form method="POST" action="">
<label for="andar">Andar:</label>
<input type="text" id="andar" name="andar" required>
<label for="setor">Setor:</label>
<input type="text" id="setor" name="setor" required>
<label for="contato">Contato:</label>
<input type="text" id="contato" name="contato" required>
<label for="escritorio">Escritório:</label>
<input type="text" id="escritorio" name="escritorio" required>
<label for="prob_utilizador">Problema:</label>
<textarea id="prob_utilizador" name="prob_utilizador"
required></textarea>
<label for="equipamento">Equipamento:</label>
<input type="text" id="equipamento" name="equipamento">
<button type="submit">Submeter</button>
</form>
</body>
</html> 