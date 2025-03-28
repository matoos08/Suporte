<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Consulta todas as reparações (tabela "equipamentos")
$stmt = $pdo->query("SELECT * FROM equipamentos");
$reparacoes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <title>Gestão de Reparações</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" type="imagex/png" href="img/Logo.png">
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
      transition: transform 0.5s;
    }
    .div-background:hover {
      box-shadow: 0 8px 16px rgba(0, 0, 0, .6);
      transform: scale(1.05);
    }
    header .logo img {
      width: 50px;
    }
    .report-card {
      background: white;
      border: none;
      padding: 15px;
      border-radius: 8px;
      text-align: center;
      margin-bottom: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .report-card i {
      margin-bottom: 10px;
    }
    .report-card h4 {
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">
      <button onclick="location.href='dashboard_tecnico.php'" class="logo-button">
        <img src="img/Logo.png" alt="Logo">
      </button>
    </div>
    <h1>Sistema Portal de Ocorrências</h1>
    <a href="logout.php" class="btn btn-danger">Logout</a>
  </header>
  <nav class="menu">
    <a href="gestao_perfil_tec.php">
      <button class="menu-item">
        <img src="img/logos/Gest_perfil.png" alt="Gestão de Perfil" class="menu-item-img">
        <br>Gestão de Perfil
      </button>
    </a>
    <a href="gestao_ocorrencias_tec.php">
      <button class="menu-item">
        <img src="img/logos/Gest_ocorr.png" alt="Gestão de Ocorrências" class="menu-item-img">
        <br>Gestão de Ocorrências
      </button>
    </a>
    <a href="gestao_salas_tec.php">
      <button class="menu-item">
        <img src="img/logos/Gest_salas.png" alt="Gestão de Salas" class="menu-item-img">
        <br>Gestão de Salas
      </button>
    </a>
    <a href="gestao_equipamentos_tec.php">
      <button class="menu-item">
        <img src="img/logos/Gest_equi.png" alt="Gestão de Equipamentos" class="menu-item-img">
        <br>Gestão de Equipamentos
      </button>
    </a>
    <a href="relatorios_tec.php">
      <button class="menu-item">
        <img src="img/logos/relatorios.png" alt="Relatórios" class="menu-item-img">
        <br>Relatórios
      </button>
    </a>
    <a href="gestao_reparacoes_tec.php">
      <button class="menu-item">
        <img src="img/logos/Gest_repa.png" alt="Gestão de Reparações" class="menu-item-img">
        <br>Gestão de Reparações
      </button>
    </a>
    <a href="gestao_tecnicos_tec.php">
      <button class="menu-item">
        <img src="img/logos/Gest_tec.png" alt="Gestão de Técnicos" class="menu-item-img">
        <br>Gestão de Técnicos
      </button>
    </a>
  </nav>
  <div class="container my-5">
    <h2>Gestão de Reparações</h2>
    <div class="mb-3">
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionarModal">Adicionar Reparação</button>
    </div>
    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Equipamento</th>
          <th>Descrição</th>
          <th>Status</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($reparacoes): ?>
          <?php foreach($reparacoes as $reparacao): ?>
            <tr>
              <td><?php echo $reparacao['Cod_Equipamento']; ?></td>
              <td><?php echo htmlspecialchars($reparacao['Descricao_Equipamento']); ?></td>
              <td><?php echo htmlspecialchars($reparacao['Obs_Equipamento']); ?></td>
              <td><?php echo htmlspecialchars($reparacao['Estado_Equipamento']); ?></td>
              <td>
                <button class="btn btn-primary btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#detalhesModal"
                  data-id="<?php echo $reparacao['Cod_Equipamento']; ?>"
                  data-equipo="<?php echo htmlspecialchars($reparacao['Descricao_Equipamento']); ?>"
                  data-obs="<?php echo htmlspecialchars($reparacao['Obs_Equipamento']); ?>"
                  data-estado="<?php echo htmlspecialchars($reparacao['Estado_Equipamento']); ?>">
                  Detalhes
                </button>
                <button class="btn btn-warning btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#editarModal"
                  data-id="<?php echo $reparacao['Cod_Equipamento']; ?>"
                  data-equipo="<?php echo htmlspecialchars($reparacao['Descricao_Equipamento']); ?>"
                  data-obs="<?php echo htmlspecialchars($reparacao['Obs_Equipamento']); ?>"
                  data-estado="<?php echo htmlspecialchars($reparacao['Estado_Equipamento']); ?>">
                  Editar
                </button>
                <button class="btn btn-danger btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#excluirModal"
                  data-id="<?php echo $reparacao['Cod_Equipamento']; ?>">
                  Excluir
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5">Nenhuma reparação encontrada.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <!-- Modal de Adicionar -->
  <div class="modal fade" id="adicionarModal" tabindex="-1" aria-labelledby="adicionarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="adicionar_reparacao_tec.php" method="POST">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="adicionarModalLabel">Adicionar Reparação</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="adicionar-equipo" class="form-label">Equipamento</label>
              <input type="text" class="form-control" name="Descricao_Equipamento" id="adicionar-equipo" required>
            </div>
            <div class="mb-3">
              <label for="adicionar-obs" class="form-label">Descrição</label>
              <textarea class="form-control" name="Obs_Equipamento" id="adicionar-obs" required></textarea>
            </div>
            <div class="mb-3">
              <label for="adicionar-estado" class="form-label">Status</label>
              <input type="text" class="form-control" name="Estado_Equipamento" id="adicionar-estado" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Adicionar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- Modal de Detalhes -->
  <div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detalhesModalLabel">Detalhes da Reparação</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <p><strong>ID:</strong> <span id="detalhes-id"></span></p>
          <p><strong>Equipamento:</strong> <span id="detalhes-equipo"></span></p>
          <p><strong>Descrição:</strong> <span id="detalhes-obs"></span></p>
          <p><strong>Status:</strong> <span id="detalhes-estado"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal de Edição -->
  <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="editar_reparacao_tec.php" method="POST">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarModalLabel">Editar Reparação</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="Cod_Equipamento" id="editar-id">
            <div class="mb-3">
              <label for="editar-equipo" class="form-label">Equipamento</label>
              <input type="text" class="form-control" name="Descricao_Equipamento" id="editar-equipo" required>
            </div>
            <div class="mb-3">
              <label for="editar-obs" class="form-label">Descrição</label>
              <textarea class="form-control" name="Obs_Equipamento" id="editar-obs" required></textarea>
            </div>
            <div class="mb-3">
              <label for="editar-estado" class="form-label">Status</label>
              <input type="text" class="form-control" name="Estado_Equipamento" id="editar-estado" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-warning">Salvar Alterações</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- Modal de Exclusão -->
  <div class="modal fade" id="excluirModal" tabindex="-1" aria-labelledby="excluirModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="excluir_reparacao_tec.php" method="POST">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="excluirModalLabel">Excluir Reparação</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <p>Tem certeza que deseja excluir esta reparação?</p>
            <input type="hidden" name="Cod_Equipamento" id="excluir-id">
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Excluir</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Modal Detalhes
    var detalhesModal = document.getElementById('detalhesModal');
    detalhesModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var id = button.getAttribute('data-id');
      var equipamento = button.getAttribute('data-equipo');
      var obs = button.getAttribute('data-obs');
      var estado = button.getAttribute('data-estado');
      detalhesModal.querySelector('#detalhes-id').textContent = id;
      detalhesModal.querySelector('#detalhes-equipo').textContent = equipamento;
      detalhesModal.querySelector('#detalhes-obs').textContent = obs;
      detalhesModal.querySelector('#detalhes-estado').textContent = estado;
    });
    // Modal Edição
    var editarModal = document.getElementById('editarModal');
    editarModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var id = button.getAttribute('data-id');
      var equipamento = button.getAttribute('data-equipo');
      var obs = button.getAttribute('data-obs');
      var estado = button.getAttribute('data-estado');
      editarModal.querySelector('#editar-id').value = id;
      editarModal.querySelector('#editar-equipo').value = equipamento;
      editarModal.querySelector('#editar-obs').value = obs;
      editarModal.querySelector('#editar-estado').value = estado;
    });
    // Modal Exclusão
    var excluirModal = document.getElementById('excluirModal');
    excluirModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var id = button.getAttribute('data-id');
      excluirModal.querySelector('#excluir-id').value = id;
    });
  </script>
</body>
</html>
