<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SESSION['user']['nivel'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $status = $_POST['status'];
    $nivel = $_POST['nivel'];

    try {
        $stmt = $pdo->prepare("UPDATE utilizadores SET nome = ?, login = ?, status = ?, nivel = ? WHERE id = ?");
        $stmt->execute([$nome, $login, $status, $nivel, $id]);
        $_SESSION['msg'] = "Utilizador atualizado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['msg'] = "Erro ao atualizar o utilizador: " . $e->getMessage();
    }

    header("Location: dashboard_admin.php");
    exit;
}

$utilizadoresStmt = $pdo->query("SELECT * FROM utilizadores");
$utilizadores = $utilizadoresStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Painel de Administração</title>
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
        <div class="div-background bg-white p-4 rounded shadow">
            <h1 class="text-center">Gestão do Sistema - Utilizadores</h1>

            <?php if (isset($_SESSION['msg'])): ?>
                <div class="alert alert-info"><?= $_SESSION['msg'] ?></div>
                <?php unset($_SESSION['msg']); ?>
            <?php endif; ?>

            <div class="mb-3">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdicionar">Adicionar Utilizador</button>
            </div>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>Status</th>
                        <th>Nível</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($utilizadores as $utilizador): ?>
                    <tr>
                        <td><?= $utilizador['id'] ?></td>
                        <td><?= $utilizador['nome'] ?></td>
                        <td><?= $utilizador['login'] ?></td>
                        <td><?= $utilizador['status'] ?></td>
                        <td><?= $utilizador['nivel'] ?></td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetalhes" data-id="<?= $utilizador['id'] ?>" data-nome="<?= $utilizador['nome'] ?>" data-login="<?= $utilizador['login'] ?>" data-status="<?= $utilizador['status'] ?>" data-nivel="<?= $utilizador['nivel'] ?>">Detalhes</button>
                            <button class="btn btn-primary btn-sm" onclick="editarUsuario(<?= $utilizador['id'] ?>, '<?= $utilizador['nome'] ?>', '<?= $utilizador['login'] ?>', '<?= $utilizador['status'] ?>', '<?= $utilizador['nivel'] ?>')">Editar</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalExcluir" data-id="<?= $utilizador['id'] ?>">Excluir</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

 <!-- Modal Adicionar Utilizador -->
<div class="modal fade" id="modalAdicionar" tabindex="-1" aria-labelledby="modalAdicionarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="adicionar_utilizador.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdicionarLabel">Adicionar Utilizador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nomeUtilizador" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="nomeUtilizador" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginUtilizador" class="form-label">Login</label>
                        <input type="text" class="form-control" name="login" id="loginUtilizador" required>
                    </div>
                    <div class="mb-3">
                        <label for="passUtilizador" class="form-label">Senha</label>
                        <input type="password" class="form-control" name="pass" id="passUtilizador" required>
                    </div>
                    <div class="mb-3">
                        <label for="statusUtilizador" class="form-label">Status</label>
                        <select class="form-select" name="status" id="statusUtilizador">
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nivelUtilizador" class="form-label">Nível</label>
                        <select class="form-select" name="nivel" id="nivelUtilizador">
                            <option value="administrador">Administrador</option>
                            <option value="tecnico">Técnico</option>
                            <option value="utilizador">Utilizador</option>
                        </select>
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


    <!-- Modal Detalhes do Utilizador -->
    <div class="modal fade" id="modalDetalhes" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Utilizador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> <span id="detalhes-id"></span></p>
                    <p><strong>Nome:</strong> <span id="detalhes-nome"></span></p>
                    <p><strong>Login:</strong> <span id="detalhes-login"></span></p>
                    <p><strong>Status:</strong> <span id="detalhes-status"></span></p>
                    <p><strong>Nível:</strong> <span id="detalhes-nivel"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Utilizador -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="atualizar_utilizador.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Utilizador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">
                        <div class="mb-3">
                            <label for="editNome" class="form-label">Nome</label>
                            <input type="text" id="editNome" name="nome" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLogin" class="form-label">Login</label>
                            <input type="text" id="editLogin" name="login" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select id="editStatus" name="status" class="form-select">
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editNivel" class="form-label">Nível</label>
                            <select id="editNivel" name="nivel" class="form-select">
                                <option value="administrador">Administrador</option>
                                <option value="tecnico">Técnico</option>
                                <option value="utilizador">Utilizador</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Excluir Utilizador -->
    <div class="modal fade" id="modalExcluir" tabindex="-1" aria-labelledby="modalExcluirLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="excluir_utilizador.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir Utilizador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir este utilizador?</p>
                        <input type="hidden" name="id" id="excluirId">
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
        // Função para preencher e abrir o modal de edição
        function editarUsuario(id, nome, login, status, nivel) {
            document.getElementById('editId').value = id;
            document.getElementById('editNome').value = nome;
            document.getElementById('editLogin').value = login;
            document.getElementById('editStatus').value = status;
            document.getElementById('editNivel').value = nivel;
            new bootstrap.Modal(document.getElementById('modalEditar')).show();
        }

        // Preenchimento do modal de detalhes
        var modalDetalhes = document.getElementById('modalDetalhes');
        modalDetalhes.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nome = button.getAttribute('data-nome');
            var login = button.getAttribute('data-login');
            var status = button.getAttribute('data-status');
            var nivel = button.getAttribute('data-nivel');
            
            modalDetalhes.querySelector('#detalhes-id').textContent = id;
            modalDetalhes.querySelector('#detalhes-nome').textContent = nome;
            modalDetalhes.querySelector('#detalhes-login').textContent = login;
            modalDetalhes.querySelector('#detalhes-status').textContent = status;
            modalDetalhes.querySelector('#detalhes-nivel').textContent = nivel;
        });

        // Preenchimento do modal de exclusão
        var modalExcluir = document.getElementById('modalExcluir');
        modalExcluir.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            modalExcluir.querySelector('#excluirId').value = id;
        });
    </script>
</body>
</html>
