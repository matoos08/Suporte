<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Consulta para obter os detalhes das ocorrências
$stmt = $pdo->query("SELECT * FROM ocorrencias");
$ocorrencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Ocorrências</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Relatório Completo de Ocorrências</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Data</th>
                    <!-- Adicione mais colunas conforme necessário -->
                </tr>
            </thead>
            <tbody>
                <?php foreach($ocorrencias as $ocorrencia): ?>
                    <tr>
                        <td><?php echo $ocorrencia['id']; ?></td>
                        <td><?php echo $ocorrencia['descricao']; ?></td>
                        <td><?php echo $ocorrencia['data']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="relatorios.php" class="btn btn-secondary">Voltar</a>
    </div>
</body>
</html>
