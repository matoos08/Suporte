<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SESSION['user']['nivel'] !== 'tecnico') {
header("Location: login.php");
exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM registros WHERE id = ?");
$stmt->execute([$id]);
$ocorrencia = $stmt->fetch();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$estado = htmlspecialchars($_POST['estado']);
$solucao = htmlspecialchars($_POST['solucao']);
$updateStmt = $pdo->prepare("
UPDATE registros SET estado = ?, solucao = ?, data_finalizada = NOW()
WHERE id = ?
");
$updateStmt->execute([$estado, $solucao, $id]);
header("Location: dashboard_tecnico.php");
exit;
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<link rel="shortcut icon" type="imagex/png" href="img/Logo.png">
<head>
<title>Atualizar Ocorrência</title>
</head>
<body>
<h1>Atualizar Ocorrência</h1>
<form method="POST" action="">
<p>Problema: <?= $ocorrencia['prob_utilizador'] ?></p>
<label for="estado">Estado:</label>
<select id="estado" name="estado" required>
<option value="EM CURSO" <?= $ocorrencia['estado'] === 'EM CURSO' ?
'selected' : '' ?>>Em Curso</option>
<option value="RESOLVIDO" <?= $ocorrencia['estado'] === 'RESOLVIDO'
? 'selected' : '' ?>>Resolvido</option>
</select>
<label for="solucao">Solução:</label>
<textarea id="solucao" name="solucao" required><?=
$ocorrencia['solucao'] ?></textarea>
<button type="submit">Atualizar</button>
</form>
</body>
</html>