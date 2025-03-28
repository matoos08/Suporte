<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Consultas para obter dados do relatório
$stmt1 = $pdo->query("SELECT COUNT(*) as total FROM ocorrencias");
$total_ocorrencias = $stmt1->fetch()['total'];

$stmt2 = $pdo->query("SELECT COUNT(*) as total FROM utilizadores");
$total_utilizadores = $stmt2->fetch()['total'];

$stmt3 = $pdo->query("SELECT COUNT(*) as total FROM salas");
$total_salas = $stmt3->fetch()['total'];

$stmt4 = $pdo->query("SELECT COUNT(*) as total FROM equipamentos");
$total_equipamentos = $stmt4->fetch()['total'];
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Relatórios</title>
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
        <a href="gestao_perfil_tec.php"><button class="menu-item"><img src="img/logos/Gest_perfil.png" alt="Gestão de Perfil" class="menu-item-img"><br>Gestão de Perfil</button></a>
        <a href="gestao_ocorrencias_tec.php"><button class="menu-item"><img src="img/logos/Gest_ocorr.png" alt="Gestão de Ocorrências" class="menu-item-img"><br>Gestão de Ocorrências</button></a>
        <a href="gestao_salas_tec.php"><button class="menu-item"><img src="img/logos/Gest_salas.png" alt="Gestão de Salas" class="menu-item-img"><br>Gestão de Salas</button></a>
        <a href="gestao_equipamentos_tec.php"><button class="menu-item"><img src="img/logos/Gest_equi.png" alt="Gestão de Equipamentos" class="menu-item-img"><br>Gestão de Equipamentos</button></a>
        <a href="relatorios_tec.php"><button class="menu-item"><img src="img/logos/relatorios.png" alt="Relatórios" class="menu-item-img"><br>Relatórios</button></a>
        <a href="gestao_reparacoes_tec.php"><button class="menu-item"><img src="img/logos/Gest_repa.png" alt="Gestão de Reparações" class="menu-item-img"><br>Gestão de Reparações</button></a>
        <a href="gestao_tecnicos_tec.php"><button class="menu-item"><img src="img/logos/Gest_tec.png" alt="Gestão de Técnicos" class="menu-item-img"><br>Gestão de Técnicos</button></a>
    </nav>

    <div class="container">
        <h2 class="text-center mb-4">Relatórios do Sistema</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="report-card">
                    <i class="fas fa-exclamation-circle fa-3x text-danger"></i>
                    <div class="div-background">
                        <h4>Total de Ocorrências</h4>
                        <p><strong><?php echo $total_ocorrencias; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="report-card">
                    <i class="fas fa-users fa-3x text-primary"></i>
                    <div class="div-background">
                        <h4>Total de Utilizadores</h4>
                        <p><strong><?php echo $total_utilizadores; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="report-card">
                    <i class="fas fa-door-closed fa-3x text-warning"></i>
                    <div class="div-background">
                        <h4>Total de Salas</h4>
                        <p><strong><?php echo $total_salas; ?></strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="report-card">
                    <i class="fas fa-tools fa-3x text-success"></i>
                    <div class="div-background">
                        <h4>Total de Equipamentos</h4>
                        <p><strong><?php echo $total_equipamentos; ?></strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
