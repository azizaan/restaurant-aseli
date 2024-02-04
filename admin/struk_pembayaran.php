<?php
require('fpdf/fpdf.php');

class StrukPembayaran extends FPDF {
    private $totalHeight = 0;
    private $isNewPage = true; 

    function Header() {
        // Tidak perlu header pada struk thermal
    }

    function Footer() {
        // Tidak perlu footer pada struk thermal
    }

    public function getTotalHeight() {
        return $this->totalHeight;
    }

    function TambahBaris($text, $align = 'L', $lineBreak = true, $fontSize = 8) {
        $this->SetFont('Arial', '', $fontSize);

        if ($this->isNewPage) {
            $this->AddPage();
            $this->isNewPage = false;
        }

        $this->Cell(0, 6, $text, 0, 1, $align);
        if ($lineBreak) {
            $this->Ln(2);
        }
        $this->totalHeight += ($lineBreak) ? 8 : 6;
    }

    function GarisPutus() {
        $this->SetLineWidth(0.3);
        $this->SetDrawColor(0, 0, 0);

        if ($this->isNewPage) {
            $this->AddPage();
            $this->isNewPage = false;
        }

        $this->Line(10, $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());
        $this->Ln(1);
        $this->totalHeight += 2;
    }

    function AddPage($orientation = '', $size = '', $rotation = 0) {
        parent::AddPage($orientation, $size, $rotation);
        $this->totalHeight = 0;
        $this->isNewPage = true;
    }
}

$id = $_GET['id'];
$waktuOrder = $_GET['placed_on'];
$total_price = $_GET['total_price'];
$total_products = $_GET['total_products'];
$method = $_GET['method'];

if ($total_products === null && json_last_error() !== JSON_ERROR_NONE) {
    die('Gagal mendapatkan data total_products.');
}

$pdf = new StrukPembayaran('P', 'mm', array(60, 150));

$pdf->SetFont('Arial', 'B', 10);
$pdf->TambahBaris('Yum-Yum', 'C', false, 10);
$pdf->TambahBaris('JL Utomo No. 2 Pakijangan ', 'C', false, 8);
$pdf->TambahBaris('Kec. Wonorejo  ', 'C', false, 8);
$pdf->TambahBaris('082143618116', 'C', false, 8);
$pdf->GarisPutus();

$pdf->SetFont('Arial', '', 7);
$pdf->TambahBaris('ID Transaksi: ' . $id, 'L', true, 7);
$pdf->TambahBaris('Waktu Order: ' . $waktuOrder, 'L', true, 7);
$pdf->GarisPutus();

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(17, 7, 'Nama Menu', 0);
$pdf->Cell(10, 7, 'Qty', 0);
$pdf->Cell(15, 7, 'Total', 0);
$pdf->Ln(8);

$pdf->SetFont('Arial', '', 8);
$pdf->GarisPutus();

$items = explode(' - ', $total_products);

foreach ($items as $item) {
    $match = preg_match('/(.+?) \((\d+) x (\d+)\)/', $item, $matches);

    if ($match) {
        $menuText = $matches[1];
        $qtyText = $matches[2] . 'x';
        $totalText = $matches[3];

        $numLines = max(ceil($pdf->GetStringWidth($menuText) / 30),
            ceil($pdf->GetStringWidth($qtyText) / 20),
            ceil($pdf->GetStringWidth($totalText) / 20));

        if ($numLines > 1) {
            $fontSize = 6;
            $pdf->TambahBaris($menuText, 'L', false, $fontSize);
        } else {
            $pdf->Cell(17, 6, $menuText, 0);
            $pdf->Cell(10, 6, $qtyText, 0);
            $pdf->Cell(15, 6, $totalText, 0);
            $pdf->Ln(6);
        }

        if ($pdf->getTotalHeight() > 180) {
            $pdf->AddPage();
        }
    }
}

$pdf->GarisPutus();
$pdf->SetFont('Arial', 'B', 8);
$pdf->TambahBaris('Ringkasan Pembayaran', 'L');
$pdf->TambahBaris('payment : '. $method, 'L');
$pdf->GarisPutus();

$pdf->SetFont('Arial', '', 7);
$pdf->TambahBaris('Subtotal: ' . $total_price, true, 7);
$pdf->TambahBaris('TOTAL: ' . $total_price, true, 7);
$pdf->GarisPutus();

$pdf->SetFont('Arial', 'B', 8);
$pdf->TambahBaris('Terima Kasih', 'C', false, 8);

ob_clean();
$pdf->Output('I');
?>
