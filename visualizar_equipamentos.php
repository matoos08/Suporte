<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Consulta para obter todos os equipamentos
$sql = "SELECT Cod_Equipamento, Descricao_Equipamento, Obs_Equipamento, Estado_Equipamento 
        FROM equipamentos 
        ORDER BY Cod_Equipamento ASC";
$stmt = $pdo->query($sql);
$equipamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <title>Visualizar Equipamentos</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Fonte Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" type="image/png" href="img/Logo.png">
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
    .menu a {
      text-decoration: none; /* Remove sublinhado do link */
      color: inherit;        /* Mantém a cor do texto herdada */
    }
    .menu a:hover, 
    .menu a:focus, 
    .menu a:active {
      text-decoration: none; /* Remove sublinhado no hover/foco */
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
    h1, h2, h3, h4, h5, h6 {
    font-size: inherit;
    font-weight: inherit;
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
</nav>

<div class="container">
  <h2>Lista de Equipamentos</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Código</th>
        <th>Descrição</th>
        <th>Observações</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($equipamentos as $equip): ?>
      <tr>
        <td><?= htmlspecialchars($equip['Cod_Equipamento']) ?></td>
        <td><?= htmlspecialchars($equip['Descricao_Equipamento']) ?></td>
        <td><?= htmlspecialchars($equip['Obs_Equipamento']) ?></td>
        <td><?= htmlspecialchars($equip['Estado_Equipamento']) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="dashboard_user.php" class="btn btn-secondary">Voltar ao Dashboard</a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
