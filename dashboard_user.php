<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Obtém os dados do utilizador da sessão
$user = $_SESSION['user'] ?? [];
$user_name = $user['nome'] ?? 'Utilizador';
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <title>Dashboard do Utilizador</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Fonte Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" type="image/png" href="img/Logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
    body { 
      font-family: 'Poppins', sans-serif; 
      background-color: #dac8b3; 
    }
    header { 
      background: linear-gradient(90deg, rgba(81,101,141,1) 0%, rgba(228,0,69,1) 33%, rgba(255,204,0,1) 66%, rgba(186,193,113,1) 100%);
      color: white; 
      padding: 15px; 
      display: flex; 
      justify-content: space-between; 
      align-items: center;
    }
    header .logo img { width: 50px; }
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
    .menu-item:hover { transform: scale(1.1); }
    .menu-item-img { 
      width: 70px; 
      height: 70px; 
      margin-bottom: 10px; 
      object-fit: contain; 
    }
    .btn.btn-danger { 
      background-color: #ffffff; 
      color: rgba(81,101,141,1); 
      padding: 10px 20px; 
      text-decoration: none;
      border-radius: 5px; 
      font-weight: bold; 
      border: 2px solid rgba(81,101,141,1);
    }
    .btn.btn-danger:hover { 
      background-color: rgba(81,101,141,1); 
      color: white; 
      transition: 0.4s; 
    }
    /* Estilo para a mensagem de boas-vindas */
    .welcome-card {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      text-align: center;
      margin: 30px auto;
      max-width: 600px;
    }
    .welcome-card h2 {
      font-size: 2rem;
      margin-bottom: 10px;
    }
    .welcome-card p {
      font-size: 1.2rem;
      color: #555;
    }
  </style>
</head>
<body>
<header>
    <div class="logo">
      <button onclick="location.href='dashboard_user.php'" class="logo-button" style="background:none; border:none;">
        <img src="img/Logo.png" alt="Logo">
      </button>
    </div>
    <h1>Sistema Portal de Ocorrências</h1>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</header>

<nav class="menu">
    <a href="gestao_perfil_user.php">
      <button class="menu-item">
        <img src="img/logos/Gest_perfil.png" alt="Gestão de Perfil" class="menu-item-img">
        <br>Gestão de Perfil
      </button>
    </a>
    <a href="registar_ocorrencia_utilizador.php">
      <button class="menu-item">
        <img src="img/logos/Gest_ocorr.png" alt="Registar Ocorrência" class="menu-item-img">
        <br>Registar Ocorrência
      </button>
    </a>
    <a href="visualizar_equipamentos.php">
      <button class="menu-item">
        <img src="img/logos/equipamentos.png" alt="Equipamentos" class="menu-item-img">
        <br>Equipamentos
      </button>
    </a>
    <a href="visualizar_salas.php">
      <button class="menu-item">
        <img src="img/logos/salas.png" alt="Salas" class="menu-item-img">
        <br>Salas
      </button>
    </a>

<div class="container my-5">
    <div class="welcome-card">
      <h2>Olá, <?= htmlspecialchars($user_name) ?>!</h2>
      <p>Seja bem-vindo ao Sistema Portal de Ocorrências. Utilize as opções do menu para acessar as funcionalidades e manter tudo em ordem.</p>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
