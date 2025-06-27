<?php
require 'function.php';
require 'check.php';

// Make sure no output has been sent before this point
if (ob_get_level()) {
    ob_end_clean();
}

require 'vendor/autoload.php';

if (!isAdmin()) {
    header('location:index.php');
    exit();
}

// Get invoice ID from URL
$idmasuk = isset($_GET['id']) ? $_GET['id'] : null;

if (!$idmasuk) {
    die('Invoice ID not provided');
}

// Get transaction details
$query = mysqli_query($conn, "SELECT m.*, b.namabarang, b.merek, b.model, b.harga 
                            FROM masuk m 
                            JOIN barang b ON m.idbarang = b.idbarang 
                            WHERE m.idmasuk = '$idmasuk'");
$data = mysqli_fetch_array($query);

if (!$data) {
    die('Invoice not found');
}

// Extend TCPDF with custom header/footer
class MYPDF extends TCPDF {
    public function Header() {
        // Empty header
    }
    
    public function Footer() {
        // Empty footer
    }
}

// Create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('CENTRAL ELEKTRONIK');
$pdf->SetAuthor('CENTRAL ELEKTRONIK');
$pdf->SetTitle('Invoice #' . $data['nomor_invoice']);

// Set margins
$pdf->SetMargins(15, 15, 15);

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', 'B', 20);

// Company Header
$pdf->Cell(0, 10, 'CENTRAL ELEKTRONIK', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 5, 'Invoice Pembelian Barang', 0, 1, 'C');
$pdf->Ln(10);

// Invoice Details
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(50, 5, 'Invoice Number:', 0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 5, $data['nomor_invoice'], 0, 1);

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(50, 5, 'Date:', 0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 5, date('d/m/Y', strtotime($data['tanggal'])), 0, 1);

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(50, 5, 'Supplier:', 0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 5, $data['pemasok'], 0, 1);
$pdf->Ln(10);

// Table Header
$pdf->SetFillColor(240, 240, 240);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(60, 7, 'Item', 1, 0, 'L', true);
$pdf->Cell(30, 7, 'Model', 1, 0, 'L', true);
$pdf->Cell(30, 7, 'Quantity', 1, 0, 'C', true);
$pdf->Cell(35, 7, 'Price', 1, 0, 'R', true);
$pdf->Cell(35, 7, 'Total', 1, 1, 'R', true);

// Table Content
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(60, 7, $data['namabarang'], 1);
$pdf->Cell(30, 7, $data['model'], 1);
$pdf->Cell(30, 7, $data['qty'], 1, 0, 'C');
$pdf->Cell(35, 7, 'Rp ' . number_format($data['harga'], 0, ',', '.'), 1, 0, 'R');
$pdf->Cell(35, 7, 'Rp ' . number_format($data['total_harga'], 0, ',', '.'), 1, 1, 'R');
$pdf->Ln(10);

// Payment Details
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(50, 5, 'Payment Status:', 0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 5, $data['status_pembayaran'], 0, 1);

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(50, 5, 'Payment Method:', 0);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 5, $data['metode_pembayaran'] ? $data['metode_pembayaran'] : '-', 0, 1);

// Terms and Conditions
$pdf->Ln(20);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 5, 'Terms and Conditions:', 0, 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(5);
$pdf->MultiCell(0, 5, '1. Payment is due within 30 days
2. Please include invoice number in your payment
3. For any questions concerning this invoice, please contact our accounting department', 0, 'L');

// Clear any output buffers
if (ob_get_length()) ob_clean();

// Output PDF
$pdf->Output('Invoice_' . $data['nomor_invoice'] . '.pdf', 'I'); 