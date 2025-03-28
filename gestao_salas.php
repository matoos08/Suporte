<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Consulta todas as salas (tabela "salas")
$stmt = $pdo->query("SELECT * FROM salas");
$salas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <title>Gestão de Salas</title>
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
    .div-background {
      transition: transform 0.5s;
    }
    .div-background:hover {
      box-shadow: 0 8px 16px rgba(0,0,0,0.6);
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
    <h2>Gestão de Salas</h2>
    <div class="mb-3">
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adicionarModal">Adicionar Sala</button>
    </div>
    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nome da Sala</th>
          <th>Localização</th>
          <th>Capacidade</th>
          <th>Observações</th>
          <th>Estado</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if($salas): ?>
          <?php foreach($salas as $sala): ?>
            <tr>
              <td><?php echo $sala['cod_sala']; ?></td>
              <td><?php echo htmlspecialchars($sala['Nome_sala']); ?></td>
              <td><?php echo htmlspecialchars($sala['Bloco_sala']); ?></td>
              <td><?php echo htmlspecialchars($sala['Piso_sala']); ?></td>
              <td><?php echo htmlspecialchars($sala['Observações']); ?></td>
              <td><?php echo htmlspecialchars($sala['Estado']); ?></td>
              <td>
                <button class="btn btn-info btn-sm" 
                  data-bs-toggle="modal"
                  data-bs-target="#detalhesModal"
                  data-id="<?php echo $sala['cod_sala']; ?>"
                  data-nome="<?php echo htmlspecialchars($sala['Nome_sala']); ?>"
                  data-localizacao="<?php echo htmlspecialchars($sala['Bloco_sala']); ?>"
                  data-capacidade="<?php echo htmlspecialchars($sala['Piso_sala']); ?>"
                  data-obs="<?php echo htmlspecialchars($sala['Observações']); ?>"
                  data-estado="<?php echo htmlspecialchars($sala['Estado']); ?>">
                  Detalhes
                </button>
                <button class="btn btn-warning btn-sm" 
                  data-bs-toggle="modal"
                  data-bs-target="#editarModal"
                  data-id="<?php echo $sala['cod_sala']; ?>"
                  data-nome="<?php echo htmlspecialchars($sala['Nome_sala']); ?>"
                  data-localizacao="<?php echo htmlspecialchars($sala['Bloco_sala']); ?>"
                  data-capacidade="<?php echo htmlspecialchars($sala['Piso_sala']); ?>"
                  data-obs="<?php echo htmlspecialchars($sala['Observações']); ?>"
                  data-estado="<?php echo htmlspecialchars($sala['Estado']); ?>">
                  Editar
                </button>
                <button class="btn btn-danger btn-sm" 
                  data-bs-toggle="modal"
                  data-bs-target="#excluirModal"
                  data-id="<?php echo $sala['cod_sala']; ?>">
                  Excluir
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7">Nenhuma sala encontrada.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <!-- Modal de Adicionar Sala -->
  <div class="modal fade" id="adicionarModal" tabindex="-1" aria-labelledby="adicionarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="adicionar_sala.php" method="POST">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="adicionarModalLabel">Adicionar Sala</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="nomeSala" class="form-label">Nome da Sala</label>
              <input type="text" class="form-control" name="Nome_sala" id="nomeSala" required>
            </div>
            <div class="mb-3">
              <label for="localizacaoSala" class="form-label">Localização/Bloco</label>
              <input type="text" class="form-control" name="Bloco_sala" id="localizacaoSala" required>
            </div>
            <div class="mb-3">
              <label for="capacidadeSala" class="form-label">Capacidade/Piso</label>
              <input type="text" class="form-control" name="Piso_sala" id="capacidadeSala" required>
            </div>
            <div class="mb-3">
              <label for="obsSala" class="form-label">Observações</label>
              <textarea class="form-control" name="Observações" id="obsSala"></textarea>
            </div>
            <div class="mb-3">
              <label for="estadoSala" class="form-label">Estado</label>
              <input type="text" class="form-control" name="Estado" id="estadoSala" required>
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
  <!-- Modal de Detalhes da Sala -->
  <div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detalhesModalLabel">Detalhes da Sala</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <p><strong>ID:</strong> <span id="detalhes-id"></span></p>
          <p><strong>Nome da Sala:</strong> <span id="detalhes-nome"></span></p>
          <p><strong>Localização:</strong> <span id="detalhes-localizacao"></span></p>
          <p><strong>Capacidade:</strong> <span id="detalhes-capacidade"></span></p>
          <p><strong>Observações:</strong> <span id="detalhes-obs"></span></p>
          <p><strong>Estado:</strong> <span id="detalhes-estado"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal de Edição da Sala -->
  <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="atualizar_sala.php" method="POST">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editarModalLabel">Editar Sala</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="cod_sala" id="editar-id">
            <div class="mb-3">
              <label for="editar-nomeSala" class="form-label">Nome da Sala</label>
              <input type="text" class="form-control" name="Nome_sala" id="editar-nomeSala" required>
            </div>
            <div class="mb-3">
              <label for="editar-localizacaoSala" class="form-label">Localização/Bloco</label>
              <input type="text" class="form-control" name="Bloco_sala" id="editar-localizacaoSala" required>
            </div>
            <div class="mb-3">
              <label for="editar-capacidadeSala" class="form-label">Capacidade/Piso</label>
              <input type="text" class="form-control" name="Piso_sala" id="editar-capacidadeSala" required>
            </div>
            <div class="mb-3">
              <label for="editar-obsSala" class="form-label">Observações</label>
              <textarea class="form-control" name="Observações" id="editar-obsSala"></textarea>
            </div>
            <div class="mb-3">
              <label for="editar-estadoSala" class="form-label">Estado</label>
              <input type="text" class="form-control" name="Estado" id="editar-estadoSala" required>
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
  <!-- Modal de Exclusão da Sala -->
  <div class="modal fade" id="excluirModal" tabindex="-1" aria-labelledby="excluirModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="excluir_sala.php" method="POST">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="excluirModalLabel">Excluir Sala</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <p>Tem certeza que deseja excluir esta sala?</p>
            <input type="hidden" name="cod_sala" id="excluir-id">
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
      var nome = button.getAttribute('data-nome');
      var localizacao = button.getAttribute('data-localizacao');
      var capacidade = button.getAttribute('data-capacidade');
      var obs = button.getAttribute('data-obs');
      var estado = button.getAttribute('data-estado');
      detalhesModal.querySelector('#detalhes-id').textContent = id;
      detalhesModal.querySelector('#detalhes-nome').textContent = nome;
      detalhesModal.querySelector('#detalhes-localizacao').textContent = localizacao;
      detalhesModal.querySelector('#detalhes-capacidade').textContent = capacidade;
      detalhesModal.querySelector('#detalhes-obs').textContent = obs;
      detalhesModal.querySelector('#detalhes-estado').textContent = estado;
    });
    // Modal Edição
    var editarModal = document.getElementById('editarModal');
    editarModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var id = button.getAttribute('data-id');
      var nome = button.getAttribute('data-nome');
      var localizacao = button.getAttribute('data-localizacao');
      var capacidade = button.getAttribute('data-capacidade');
      var obs = button.getAttribute('data-obs');
      var estado = button.getAttribute('data-estado');
      editarModal.querySelector('#editar-id').value = id;
      editarModal.querySelector('#editar-nomeSala').value = nome;
      editarModal.querySelector('#editar-localizacaoSala').value = localizacao;
      editarModal.querySelector('#editar-capacidadeSala').value = capacidade;
      editarModal.querySelector('#editar-obsSala').value = obs;
      editarModal.querySelector('#editar-estadoSala').value = estado;
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
