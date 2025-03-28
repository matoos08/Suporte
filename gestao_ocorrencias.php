<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

if ($_SESSION['user']['nivel'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

// Processamento de ações (adicionar, editar, excluir)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Conversão dos campos datetime-local para o formato MySQL
    function converteData($data) {
        return !empty($data) ? date('Y-m-d H:i:s', strtotime($data)) : null;
    }

    if ($action == 'add') {
        // Campos do formulário de adição
        $idutil          = $_POST['idutil'];
        $equipamento     = $_POST['equipamento'];
        $prob_utilizador = $_POST['prob_utilizador'];
        $prob_tecnico    = $_POST['prob_tecnico'];
        $descricao       = $_POST['descricao']; // utilizamos o campo "contato" para armazenar a descrição
        $solucao         = $_POST['solucao'];
        $data_abertura   = converteData($_POST['data_abertura']);
        $data_finalizada = converteData($_POST['data_finalizada']);
        $tecnico         = $_POST['tecnico'];
        $estado          = 'ABERTO';
        
        try {
            $stmt = $pdo->prepare("INSERT INTO ocorrencias (idutil, contato, prob_utilizador, prob_encontrado, solucao, estado, data_abertura, data_finalizada, tecnico, equipamento) VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$idutil, $descricao, $prob_utilizador, $prob_tecnico, $solucao, $estado, $data_abertura, $data_finalizada, $tecnico, $equipamento]);
            $_SESSION['msg'] = "Ocorrência adicionada com sucesso!";
        } catch (Exception $e) {
            $_SESSION['msg'] = "Erro ao adicionar ocorrência: " . $e->getMessage();
        }
    } elseif ($action == 'edit') {
        // Campos do formulário de edição
        $id_ocorrencia   = $_POST['id_ocorrencia'];
        $idutil          = $_POST['idutil'];
        $equipamento     = $_POST['equipamento'];
        $prob_utilizador = $_POST['prob_utilizador'];
        $prob_tecnico    = $_POST['prob_tecnico'];
        $descricao       = $_POST['descricao'];
        $solucao         = $_POST['solucao'];
        $data_abertura   = converteData($_POST['data_abertura']);
        $data_finalizada = converteData($_POST['data_finalizada']);
        $tecnico         = $_POST['tecnico'];
        
        try {
            $stmt = $pdo->prepare("UPDATE ocorrencias SET idutil = ?, contato = ?, prob_utilizador = ?, prob_encontrado = ?, solucao = ?, data_abertura = ?, data_finalizada = ?, tecnico = ?, equipamento = ? WHERE id_ocorrencia = ?");
            $stmt->execute([$idutil, $descricao, $prob_utilizador, $prob_tecnico, $solucao, $data_abertura, $data_finalizada, $tecnico, $equipamento, $id_ocorrencia]);
            $_SESSION['msg'] = "Ocorrência atualizada com sucesso!";
        } catch (Exception $e) {
            $_SESSION['msg'] = "Erro ao atualizar ocorrência: " . $e->getMessage();
        }
    } elseif ($action == 'delete') {
        $id_ocorrencia = $_POST['id_ocorrencia'];
        try {
            $stmt = $pdo->prepare("DELETE FROM ocorrencias WHERE id_ocorrencia = ?");
            $stmt->execute([$id_ocorrencia]);
            $_SESSION['msg'] = "Ocorrência excluída com sucesso!";
        } catch (Exception $e) {
            $_SESSION['msg'] = "Erro ao excluir ocorrência: " . $e->getMessage();
        }
    }
    header("Location: gestao_ocorrencias.php");
    exit;
}

// Consulta para listagem de ocorrências com join nas tabelas relacionadas
$sql = "SELECT o.*, u.nome AS nome_utilizador, s.Nome_sala, p.Descricao_piso 
        FROM ocorrencias o 
        JOIN utilizadores u ON o.idutil = u.id
        LEFT JOIN equipamentos e ON e.Descricao_Equipamento = o.equipamento
        LEFT JOIN equipamentos_localizacao el ON e.Cod_Equipamento = el.Cod_Equipamento AND el.Data_Fim IS NULL
        LEFT JOIN salas s ON el.Cod_Sala = s.cod_sala
        LEFT JOIN pisos p ON s.Piso_sala = p.Cod_piso
        ORDER BY o.data_abertura DESC";
$ocorrenciasStmt = $pdo->query($sql);
$ocorrencias = $ocorrenciasStmt->fetchAll();

// Consulta para popular o select de utilizadores
$usersStmt = $pdo->query("SELECT id, nome FROM utilizadores WHERE status = 'ativo'");
$users = $usersStmt->fetchAll();

// Consulta para popular o select de equipamentos
$equipStmt = $pdo->query("SELECT Cod_Equipamento, Descricao_Equipamento FROM equipamentos");
$equipamentos = $equipStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Sistema Portal de Ocorrências</title>
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
            <h1 class="text-center">Gestão de Ocorrências</h1>
            <?php if (isset($_SESSION['msg'])): ?>
                <div class="alert alert-info"><?= $_SESSION['msg'] ?></div>
                <?php unset($_SESSION['msg']); ?>
            <?php endif; ?>

            <div class="mb-3">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdicionar">Adicionar Ocorrência</button>
            </div>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>ID do Utilizador</th>
                        <th>Utilizador</th>
                        <th>Equipamento</th>
                        <th>Problema (Utilizador)</th>
                        <th>Problema (Técnico)</th>
                        <th>Piso</th>
                        <th>Sala</th>
                        <th>Descrição</th>
                        <th>Solução</th>
                        <th>Data Abertura</th>
                        <th>Data Finalizada</th>
                        <th>Técnico</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ocorrencias as $ocorrencia): ?>
                    <tr>
                        <td><?= $ocorrencia['id_ocorrencia'] ?></td>
                        <td><?= $ocorrencia['idutil'] ?></td>
                        <td><?= $ocorrencia['nome_utilizador'] ?></td>
                        <td><?= $ocorrencia['equipamento'] ?></td>
                        <td><?= $ocorrencia['prob_utilizador'] ?></td>
                        <td><?= $ocorrencia['prob_encontrado'] ?></td>
                        <td><?= $ocorrencia['Descricao_piso'] ?? '-' ?></td>
                        <td><?= $ocorrencia['Nome_sala'] ?? '-' ?></td>
                        <td><?= $ocorrencia['contato'] ?></td>
                        <td><?= $ocorrencia['solucao'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($ocorrencia['data_abertura'])) ?></td>
                        <td><?= !empty($ocorrencia['data_finalizada']) ? date('d/m/Y H:i', strtotime($ocorrencia['data_finalizada'])) : '-' ?></td>
                        <td><?= $ocorrencia['tecnico'] ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEditar" 
                                data-id="<?= $ocorrencia['id_ocorrencia'] ?>"
                                data-idutil="<?= $ocorrencia['idutil'] ?>"
                                data-equipamento="<?= $ocorrencia['equipamento'] ?>"
                                data-prob_utilizador="<?= htmlspecialchars($ocorrencia['prob_utilizador']) ?>"
                                data-prob_tecnico="<?= htmlspecialchars($ocorrencia['prob_encontrado']) ?>"
                                data-contato="<?= htmlspecialchars($ocorrencia['contato']) ?>"
                                data-solucao="<?= htmlspecialchars($ocorrencia['solucao']) ?>"
                                data-data_abertura="<?= date('Y-m-d\TH:i', strtotime($ocorrencia['data_abertura'])) ?>"
                                data-data_finalizada="<?= !empty($ocorrencia['data_finalizada']) ? date('Y-m-d\TH:i', strtotime($ocorrencia['data_finalizada'])) : '' ?>"
                                data-tecnico="<?= htmlspecialchars($ocorrencia['tecnico']) ?>"
                            >Editar</button>
                            <button class="btn btn-danger btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalExcluir" 
                                data-id="<?= $ocorrencia['id_ocorrencia'] ?>"
                            >Excluir</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Adicionar Ocorrência -->
    <div class="modal fade" id="modalAdicionar" tabindex="-1" aria-labelledby="modalAdicionarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="gestao_ocorrencias.php" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAdicionarLabel">Adicionar Ocorrência</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>ID do Utilizador:</label>
                            <select name="idutil" class="form-select" required>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>"><?= $user['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Equipamento:</label>
                            <select name="equipamento" class="form-select" required>
                                <?php foreach ($equipamentos as $equip): ?>
                                    <option value="<?= $equip['Descricao_Equipamento'] ?>"><?= $equip['Descricao_Equipamento'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Problema (Utilizador):</label>
                            <textarea name="prob_utilizador" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Problema (Técnico):</label>
                            <textarea name="prob_tecnico" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Descrição:</label>
                            <input type="text" name="descricao" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Solução:</label>
                            <textarea name="solucao" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Data Abertura:</label>
                            <input type="datetime-local" name="data_abertura" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Data Finalizada:</label>
                            <input type="datetime-local" name="data_finalizada" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Técnico:</label>
                            <input type="text" name="tecnico" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Adicionar Ocorrência</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Ocorrência -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="gestao_ocorrencias.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id_ocorrencia" id="editId_ocorrencia">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarLabel">Editar Ocorrência</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>ID do Utilizador:</label>
                            <select name="idutil" id="editIdutil" class="form-select" required>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>"><?= $user['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Equipamento:</label>
                            <select name="equipamento" id="editEquipamento" class="form-select" required>
                                <?php foreach ($equipamentos as $equip): ?>
                                    <option value="<?= $equip['Descricao_Equipamento'] ?>"><?= $equip['Descricao_Equipamento'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Problema (Utilizador):</label>
                            <textarea name="prob_utilizador" id="editProb_utilizador" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Problema (Técnico):</label>
                            <textarea name="prob_tecnico" id="editProb_tecnico" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Descrição:</label>
                            <input type="text" name="descricao" id="editDescricao" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Solução:</label>
                            <textarea name="solucao" id="editSolucao" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Data Abertura:</label>
                            <input type="datetime-local" name="data_abertura" id="editData_abertura" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Data Finalizada:</label>
                            <input type="datetime-local" name="data_finalizada" id="editData_finalizada" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Técnico:</label>
                            <input type="text" name="tecnico" id="editTecnico" class="form-control">
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

    <!-- Modal Excluir Ocorrência -->
    <div class="modal fade" id="modalExcluir" tabindex="-1" aria-labelledby="modalExcluirLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="gestao_ocorrencias.php" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id_ocorrencia" id="deleteId_ocorrencia">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalExcluirLabel">Excluir Ocorrência</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir esta ocorrência?</p>
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
        // Preenche o modal de edição com os dados da ocorrência selecionada
        var modalEditar = document.getElementById('modalEditar');
        modalEditar.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id_ocorrencia = button.getAttribute('data-id');
            var idutil = button.getAttribute('data-idutil');
            var equipamento = button.getAttribute('data-equipamento');
            var prob_utilizador = button.getAttribute('data-prob_utilizador');
            var prob_tecnico = button.getAttribute('data-prob_tecnico');
            var contato = button.getAttribute('data-contato');
            var solucao = button.getAttribute('data-solucao');
            var data_abertura = button.getAttribute('data-data_abertura');
            var data_finalizada = button.getAttribute('data-data_finalizada');
            var tecnico = button.getAttribute('data-tecnico');

            document.getElementById('editId_ocorrencia').value = id_ocorrencia;
            document.getElementById('editIdutil').value = idutil;
            document.getElementById('editEquipamento').value = equipamento;
            document.getElementById('editProb_utilizador').value = prob_utilizador;
            document.getElementById('editProb_tecnico').value = prob_tecnico;
            document.getElementById('editDescricao').value = contato;
            document.getElementById('editSolucao').value = solucao;
            document.getElementById('editData_abertura').value = data_abertura;
            document.getElementById('editData_finalizada').value = data_finalizada;
            document.getElementById('editTecnico').value = tecnico;
        });

        // Preenche o modal de exclusão com o id da ocorrência selecionada
        var modalExcluir = document.getElementById('modalExcluir');
        modalExcluir.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id_ocorrencia = button.getAttribute('data-id');
            document.getElementById('deleteId_ocorrencia').value = id_ocorrencia;
        });
    </script>
</body>
</html>
