<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password'];

    if (!empty($nome) && !empty($login) && !empty($password)) {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {

            $stmt = $pdo->prepare("
                INSERT INTO utilizadores (nome, login, pass, status, nivel)
                VALUES (?, ?, ?, 'ativo', 'tecnico')
            ");
            $stmt->execute([$nome, $login, $passwordHash]);

            $success = "Técnico registado com sucesso!";
        } catch (PDOException $e) {

            $error = "Erro ao registar o técnico: " . $e->getMessage();
        }
    } else {
        $error = "Por favor, preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="img/Logo.png">
    <title>Registar Técnico</title>
</head>
<body>
    <h1>Registar Técnico</h1>
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        
        <label for="login">Login:</label>
        <input type="text" id="login" name="login" required>
        
        <label for="password">Palavra-passe:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Submeter</button>
    </form>

    <?php
    if (isset($success)) {
        echo "<p style='color: green;'>$success</p>";
    } elseif (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
    ?>
</body>
</html>
