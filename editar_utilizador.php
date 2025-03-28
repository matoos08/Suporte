<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SESSION['user']['nivel'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: painel_admin.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE id = ?");
$stmt->execute([$id]);
$utilizador = $stmt->fetch();

if (!$utilizador) {
    echo "Utilizador não encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $status = $_POST['status'];
    $nivel = $_POST['nivel'];

    $updateStmt = $pdo->prepare("UPDATE utilizadores SET nome = ?, login = ?, status = ?, nivel = ? WHERE id = ?");
    $updateStmt->execute([$nome, $login, $status, $nivel, $id]);

    header("Location: painel_admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="imagex/png" href="img/Logo.png">
    <title>Editar Utilizador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #dac8b3;
            color: #333;
            padding: 20px;
        }

        header {
            background: linear-gradient(90deg, rgba(81,101,141,1) 0%, rgba(228,0,69,1) 33%, rgba(255, 204, 0,1) 66%, rgba(186,193,113,1) 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-section {
            background-color: white;
            border: 1px solid rgba(81,101,141,1);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, .2);
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-label {
            font-weight: bold;
            color: #008080;
        }

        .btn-custom {
            background-color: #008080;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: rgba(81,101,141,1);
            transform: scale(1.05);
        }

        .btn-back {
            background-color: #ccc;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #888;
            color: white;
        }
    </style>
</head>
<body>
<header>
    <h1>Editar Utilizador</h1>
</header>
<section class="form-section">
    <form method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($utilizador['nome']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="login" class="form-label">Login:</label>
            <input type="text" class="form-control" id="login" name="login" value="<?= htmlspecialchars($utilizador['login']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select class="form-select" id="status" name="status" required>
                <option value="ativo" <?= $utilizador['status'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                <option value="inativo" <?= $utilizador['status'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="nivel" class="form-label">Nível:</label>
            <select class="form-select" id="nivel" name="nivel" required>
                <option value="administrador" <?= $utilizador['nivel'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
                <option value="utilizador" <?= $utilizador['nivel'] === 'utilizador' ? 'selected' : '' ?>>Utilizador</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-custom">Salvar Alterações</button>
            <a href="painel_admin.php" class="btn btn-back">Voltar à Gestão de Utilizadores</a>
        </div>
    </form>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
