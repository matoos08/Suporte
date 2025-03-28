<?php
session_start();
require 'conexao.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$login = $_POST['login'];
$password = md5($_POST['pass']);
$stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE login = ? AND pass
= ?");
$stmt->execute([$login, $password]);
$user = $stmt->fetch();
if ($user) {
$_SESSION['user'] = $user;
switch ($user['nivel']) {
    case
        header("Location: dashboard_admin.php");
        break;
    case 'tecnico' ;
        header("location: dashboard_tecnico.php");   
        break;
    case 'utilizador' ;
header("location: dashboard_user.php");
        break;
    default:
        $error = "Nível de acesso desconhecido!";
}
exit;
} else {
$error = "Credenciais inválidas!";
}
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="imagex/png" href="img/Logo.png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #dac8b3;
        }

        header {
            background: linear-gradient(90deg, rgba(81,101,141,1) 0%, rgba(228,0,69,1) 33%, rgba(255, 204, 0,1) 66%, rgba(186,193,113,1) 100%);
            color: white;
            padding: 20px;
        }

        header .logo img {
            width: 50px;
        }

        .nav-link {
            background-color: rgba(51, 51, 51, .3);
            color: white;
            font-weight: bold;
            border-radius: 5px;
            padding: 5px 10px;
        }

        .nav-link:hover {
            color: rgba(0, 0, 0, 1);
            background-color: rgba(255, 255, 255, .3);
            transition: all .5s;
        }

        .login-btn a {
            background-color: #ffffff;
            color: rgba(81,101,141,1);
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .login-btn a:hover {
            background-color: rgba(81,101,141,1);
            color: white;
            transition: .4s;
        }

        .div-background {
            padding: 20px;
            border: 1px solid rgba(81,101,141,1);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, .4);
            background-color: white;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .div-background:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, .6);
            transform: scale(1.05);
        }

        form button {
            background-color: #008080;
            color: white;
            border: none;
            border-radius: 5px;
        }

        form button:hover {
            background-color: #005959;
        }
    </style>
</head>
<body>
    <header class="d-flex justify-content-between align-items-center">
        <div class="logo">
            <img src="img/Logo.png" alt="Logo">
        </div>
        <nav>
            <ul class="nav">
                <li class="nav-item"><a href="index.html" class="nav-link">Início</a></li>
                <li class="nav-item"><a href="sobre.html" class="nav-link">Sobre</a></li>
            </ul>
        </nav>
        <div class="login-btn">
            <a href="login.php" class="btn btn-primary">Login</a>
        </div>
    </header>

    <section class="d-flex justify-content-center align-items-center vh-100">
        <div class="div-background">
            <form method="POST" action="">
                <h2 class="text-center text-primary mb-4">Login</h2>
                <div class="mb-3">
                    <label for="login" class="form-label">Login:</label>
                    <input type="text" id="login" name="login" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Palavra-passe:</label>
                    <input type="password" id="pass" name="pass" class="form-control" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn">Entrar</button>
                </div>
                <?php if (isset($error)) echo "<p class='text-danger mt-3'>$error</p>"; ?>
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
