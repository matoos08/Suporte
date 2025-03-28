<?php
require_once __DIR__ . '/vendor/autoload.php';
require 'conexao.php';
session_start();
require 'valida_session.php';

// Consultas para o relatório
$stmt1 = $pdo->query("SELECT COUNT(*) as total FROM ocorrencias");
$total_ocorrencias = $stmt1->fetch()['total'];

$stmt2 = $pdo->query("SELECT COUNT(*) as total FROM utilizadores");
$total_utilizadores = $stmt2->fetch()['total'];

// Criando a classe personalizada para o PDF
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 10, 'Relatório do Sistema', 0, 1, 'C');
        $this->Ln(5);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Criando PDF
$pdf = new MYPDF();
$pdf->SetMargins(15, 25, 15);
$pdf->SetAutoPageBreak(TRUE, 20);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Índice
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Índice', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, '1. Introdução ................................ 1', 0, 1, 'L');
$pdf->Cell(0, 10, '2. Estatísticas Gerais ........................ 2', 0, 1, 'L');
$pdf->Cell(0, 10, '3. Conclusão ................................ 3', 0, 1, 'L');
$pdf->AddPage();

// Introdução
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, '1. Introdução', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(0, 10, "Este relatório apresenta um resumo das estatísticas do sistema, fornecendo informações essenciais sobre a utilização da plataforma.");
$pdf->Ln(5);

// Estatísticas Gerais
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, '2. Estatísticas Gerais', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, "Total de Ocorrências: $total_ocorrencias", 0, 1, 'L');
$pdf->Cell(0, 10, "Total de Utilizadores: $total_utilizadores", 0, 1, 'L');
$pdf->Ln(5);

// Conclusão
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, '3. Conclusão', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(0, 10, "Os dados apresentados demonstram a relevância do sistema na gestão de ocorrências e utilizadores.");
$pdf->Ln(5);

// Saída do PDF
$pdf->Output('Relatorio_Sistema.pdf', 'D');
