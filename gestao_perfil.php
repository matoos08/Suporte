<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Apenas administradores podem acessar
if ($_SESSION['user']['nivel'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

// Define a variável $user para facilitar o acesso aos dados
$user = $_SESSION['user'];

$user_id = $user['id'];
$nome    = $user['nome'];
$login   = $user['login'];
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe e sanitiza os dados do formulário
    $nome                = trim($_POST['nome']);
    $login               = trim($_POST['login']);
    $nova_pass           = trim($_POST['nova_pass']);
    $confirmar_nova_pass = trim($_POST['confirmar_nova_pass']);

    if (empty($nome) || empty($login)) {
        $mensagem = "Nome e Login são obrigatórios.";
    } else {
        // Se for informada uma nova senha, valida se a confirmação confere
        if (!empty($nova_pass)) {
            if ($nova_pass !== $confirmar_nova_pass) {
                $mensagem = "A nova senha e a confirmação não coincidem.";
            } else {
                // Usando MD5 para codificar a senha (atenção: MD5 não é recomendado para produção)
                $pass_hash = md5($nova_pass);
                $sql = "UPDATE utilizadores SET nome = :nome, login = :login, pass = :pass WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                if ($stmt->execute([
                    'nome'  => $nome,
                    'login' => $login,
                    'pass'  => $pass_hash,
                    'id'    => $user_id
                ])) {
                    $mensagem = "Perfil atualizado com sucesso.";
                    $_SESSION['user']['nome'] = $nome;
                    $_SESSION['user']['login'] = $login;
                } else {
                    $mensagem = "Erro ao atualizar o perfil. Tente novamente.";
                }
            }
        } else {
            // Atualiza os dados sem alterar a senha
            $sql = "UPDATE utilizadores SET nome = :nome, login = :login WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([
                'nome'  => $nome,
                'login' => $login,
                'id'    => $user_id
            ])) {
                $mensagem = "Perfil atualizado com sucesso.";
                $_SESSION['user']['nome'] = $nome;
                $_SESSION['user']['login'] = $login;
            } else {
                $mensagem = "Erro ao atualizar o perfil. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <title>Gestão de Perfil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" type="imagex/png" href="img/Logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #dac8b3;
    }
    header {
        background: linear-gradient(90deg, rgba(81,101,141,1) 0%, rgba(228,0,69,1) 33%, rgba(255, 204, 0,1) 66%, rgba(186,193,113,1) 100%);
        color: white;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .menu {
        display: flex;
        justify-content: center;  
        padding: 20px;
        flex-wrap: wrap;  
        gap: 20px;  
    }
    .menu-item {
        background: white;
        border: none;
        padding: 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s;
        margin: 10px;
        text-align: center;  
        display: flex;
        flex-direction: column;
        align-items: center;  
        width: 150px;
        height: 180px;
    }
    .menu-item:hover {
        transform: scale(1.1);
    }
    .menu-item-img {
        width: 70px; 
        height: 70px; 
        margin-bottom: 10px;  
        object-fit: contain; 
    }
    .btn.btn-danger {
        background-color: #ffffff; 
        color: rgba(81, 101, 141, 1); 
        padding: 10px 20px; 
        text-decoration: none;
        border-radius: 5px; 
        font-weight: bold; 
        border: 2px solid rgba(81, 101, 141, 1); 
    }
    .btn.btn-danger:hover {
        background-color: rgba(81, 101, 141, 1); 
        color: white; 
        transition: 0.4s; 
    }
    .div-background {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    .div-background:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, .6);
        transform: scale(1.05);
    }
    header .logo img {
        width: 50px;
    }
  </style>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body>
  <header>
    <div class="logo">
      <button onclick="location.href='dashboard_admin.php'" class="logo-button">
        <img src="img/Logo.png" alt="Logo">
      </button>
    </div>
    <h1>Sistema Portal de Ocorrências</h1>
    <a href="logout.php" class="btn btn-danger">Logout</a>
  </header>
  <nav class="menu">
    <a href="gestao_perfil.php">
      <button class="menu-item">
        <img src="img/logos/Gest_perfil.png" alt="Gestão de Perfil" class="menu-item-img">
        <br>Gestão de Perfil
      </button>
    </a>
    <a href="gestao_ocorrencias.php">
      <button class="menu-item">
        <img src="img/logos/Gest_ocorr.png" alt="Gestão de Ocorrências" class="menu-item-img">
        <br>Gestão de Ocorrências
      </button>
    </a>
    <a href="gestao_salas.php">
      <button class="menu-item">
        <img src="img/logos/Gest_salas.png" alt="Gestão de Salas" class="menu-item-img">
        <br>Gestão de Salas
      </button>
    </a>
    <a href="gestao_equipamentos.php">
      <button class="menu-item">
        <img src="img/logos/Gest_equi.png" alt="Gestão de Equipamentos" class="menu-item-img">
        <br>Gestão de Equipamentos
      </button>
    </a>
    <a href="gestao_utilizadores.php">
      <button class="menu-item">
        <img src="img/logos/Gest_utili.png" alt="Gestão de Utilizadores" class="menu-item-img">
        <br>Gestão de Utilizadores
      </button>
    </a>
    <a href="relatorios.php">
      <button class="menu-item">
        <img src="img/logos/relatorios.png" alt="Relatórios" class="menu-item-img">
        <br>Relatórios
      </button>
    </a>
    <a href="gestao_reparacoes.php">
      <button class="menu-item">
        <img src="img/logos/Gest_repa.png" alt="Gestão de Reparações" class="menu-item-img">
        <br>Gestão de Reparações
      </button>
    </a>
    <a href="gestao_tecnicos.php">
      <button class="menu-item">
        <img src="img/logos/Gest_tec.png" alt="Gestão de Técnicos" class="menu-item-img">
        <br>Gestão de Técnicos
      </button>
    </a>
  </nav>
  <div class="container my-5">
    <h2>Gestão de Perfil</h2>
    <?php if (!empty($mensagem)) : ?>
      <div class="alert alert-info">
        <?php echo $mensagem; ?>
      </div>
    <?php endif; ?>
    <form method="POST" action="gestao_perfil.php">
      <div class="mb-3">
        <label for="nome" class="form-label">Nome:</label>
        <input type="text" id="nome" name="nome" class="form-control" value="<?php echo htmlspecialchars($user['nome']); ?>" required>
      </div>
      <div class="mb-3">
        <label for="login" class="form-label">Login:</label>
        <input type="text" id="login" name="login" class="form-control" value="<?php echo htmlspecialchars($user['login']); ?>" required>
      </div>
      <div class="mb-3">
        <label for="nova_pass" class="form-label">Nova Senha:</label>
        <input type="password" id="nova_pass" name="nova_pass" class="form-control" placeholder="Nova senha (deixe em branco para manter)">
      </div>
      <div class="mb-3">
        <label for="confirmar_nova_pass" class="form-label">Confirmar Nova Senha:</label>
        <input type="password" id="confirmar_nova_pass" name="confirmar_nova_pass" class="form-control" placeholder="Confirme a nova senha">
      </div>
      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
