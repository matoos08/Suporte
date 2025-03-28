<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verifica se o utilizador está logado e define o id do utilizador (supondo que esteja armazenado na sessão)
$idutil = $_SESSION['user']['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário
    $equipamento_id   = $_POST['Cod_Equipamento'] ?? '';
    $prob_utilizador  = htmlspecialchars($_POST['prob_utilizador'] ?? '');
    $sala_id          = $_POST['Cod_Sala'] ?? '';
    $piso_id          = $_POST['Cod_piso'] ?? '';
    $data_ocorrencia  = $_POST['data_ocorrencia'] ?? ''; // Formato: YYYY-MM-DD
    $descricao        = htmlspecialchars($_POST['descricao'] ?? '');

    // Verifica se os campos obrigatórios foram preenchidos
    if (!empty($equipamento_id) && !empty($prob_utilizador) && !empty($sala_id) && !empty($piso_id) && !empty($data_ocorrencia)) {
        // Obter informações para exibição (apenas descritivas) a partir dos dropdowns
        $stmtEquip = $pdo->prepare("SELECT Descricao_Equipamento FROM equipamentos WHERE Cod_Equipamento = ?");
        $stmtEquip->execute([$equipamento_id]);
        $equip = $stmtEquip->fetch(PDO::FETCH_ASSOC);

        $stmtSala = $pdo->prepare("SELECT Nome_sala FROM salas WHERE cod_sala = ?");
        $stmtSala->execute([$sala_id]);
        $sala = $stmtSala->fetch(PDO::FETCH_ASSOC);

        $stmtPiso = $pdo->prepare("SELECT Descricao_piso FROM pisos WHERE Cod_piso = ?");
        $stmtPiso->execute([$piso_id]);
        $piso = $stmtPiso->fetch(PDO::FETCH_ASSOC);

        // Combina sala e piso numa única string (pode ser ajustado conforme a necessidade)
        $localizacao = "Sala: " . ($sala['Nome_sala'] ?? '') . " - Piso: " . ($piso['Descricao_piso'] ?? '');

        // Inserção na tabela ocorrencias
        $sql = "INSERT INTO ocorrencias 
                    (idutil, prob_utilizador, prob_encontrado, estado, data_abertura, tecnico, equipamento)
                VALUES 
                    (:idutil, :prob_utilizador, :prob_encontrado, 'ABERTO', :data_abertura, NULL, :equipamento)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idutil', $idutil, PDO::PARAM_INT);
        $stmt->bindValue(':prob_utilizador', $prob_utilizador, PDO::PARAM_STR);
        // Armazenamos a localização e a descrição juntos; você pode concatená-los se desejar
        $stmt->bindValue(':prob_encontrado', $localizacao . ". Descrição: " . $descricao, PDO::PARAM_STR);
        $stmt->bindValue(':data_abertura', $data_ocorrencia, PDO::PARAM_STR);
        $stmt->bindValue(':equipamento', $equip['Descricao_Equipamento'] ?? '', PDO::PARAM_STR);
        $stmt->execute();

        $success = "Ocorrência registrada com sucesso!";
    } else {
        $error = "Por favor, preencha todos os campos obrigatórios.";
    }
}

// Dropdown: Equipamentos ativos
$sql_equipamentos = "SELECT Cod_Equipamento, Descricao_Equipamento 
                     FROM equipamentos 
                     WHERE Estado_Equipamento = 'Ativo'";
$stmt_equipamentos = $pdo->query($sql_equipamentos);
$equipamentos = $stmt_equipamentos->fetchAll(PDO::FETCH_ASSOC);

// Dropdown: Salas ativas
$sql_salas = "SELECT cod_sala, Nome_sala FROM salas WHERE Estado = 1";
$stmt_salas = $pdo->query($sql_salas);
$salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);

// Dropdown: Pisos ativos
$sql_pisos = "SELECT Cod_piso, Descricao_piso FROM pisos WHERE Estado = 1";
$stmt_pisos = $pdo->query($sql_pisos);
$pisos = $stmt_pisos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <title>Registar Ocorrência</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Fonte Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" type="image/png" href="img/Logo.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #dac8b3; }
    header { 
      background: linear-gradient(90deg, rgba(81,101,141,1) 0%, rgba(228,0,69,1) 33%, rgba(255,204,0,1) 66%, rgba(186,193,113,1) 100%);
      color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; 
    }
    .menu { display: flex; justify-content: center; padding: 20px; flex-wrap: wrap; gap: 20px; }
    .menu-item { 
      background: white; border: none; padding: 15px; border-radius: 8px; cursor: pointer;
      transition: transform 0.3s; margin: 10px; text-align: center; display: flex; flex-direction: column; 
      align-items: center; width: 150px; height: 180px;
    }
    .menu-item:hover { transform: scale(1.1); }
    .menu-item-img { width: 70px; height: 70px; margin-bottom: 10px; object-fit: contain; }
    .btn.btn-danger { 
      background-color: #ffffff; color: rgba(81,101,141,1); padding: 10px 20px; text-decoration: none;
      border-radius: 5px; font-weight: bold; border: 2px solid rgba(81,101,141,1);
    }
    .btn.btn-danger:hover { background-color: rgba(81,101,141,1); color: white; transition: 0.4s; }
    header .logo img { width: 50px; }
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
  <h2>Registar Ocorrência</h2>
  <?php if(isset($error)): ?>
    <div class="alert alert-danger" role="alert">
      <?= $error ?>
    </div>
  <?php endif; ?>
  <?php if(isset($success)): ?>
    <div class="alert alert-success" role="alert">
      <?= $success ?>
    </div>
  <?php endif; ?>
  <form method="POST" action="">
    <!-- Equipamento -->
    <div class="mb-3">
      <label for="equipamento" class="form-label">Equipamento:</label>
      <select class="form-control" id="equipamento" name="Cod_Equipamento" required>
        <option value="">Selecione o Equipamento...</option>
        <?php foreach ($equipamentos as $equip): ?>
          <option value="<?= $equip['Cod_Equipamento'] ?>">
            <?= $equip['Descricao_Equipamento'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <!-- Problema -->
    <div class="mb-3">
      <label for="prob_utilizador" class="form-label">Problema:</label>
      <textarea class="form-control" id="prob_utilizador" name="prob_utilizador" required></textarea>
    </div>
    <!-- Sala -->
    <div class="mb-3">
      <label for="sala" class="form-label">Sala:</label>
      <select class="form-control" id="sala" name="Cod_Sala" required>
        <option value="">Selecione a Sala...</option>
        <?php foreach ($salas as $sala): ?>
          <option value="<?= $sala['cod_sala'] ?>">
            <?= $sala['Nome_sala'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <!-- Piso -->
    <div class="mb-3">
      <label for="piso" class="form-label">Piso:</label>
      <select class="form-control" id="piso" name="Cod_piso" required>
        <option value="">Selecione o Piso...</option>
        <?php foreach ($pisos as $piso): ?>
          <option value="<?= $piso['Cod_piso'] ?>">
            <?= $piso['Descricao_piso'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <!-- Data da Ocorrência -->
    <div class="mb-3">
      <label for="data_ocorrencia" class="form-label">Data:</label>
      <input type="date" class="form-control" id="data_ocorrencia" name="data_ocorrencia" required>
    </div>
    <!-- Descrição Adicional -->
    <div class="mb-3">
      <label for="descricao" class="form-label">Descrição:</label>
      <textarea class="form-control" id="descricao" name="descricao"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submeter</button>
  </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
