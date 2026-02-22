<?php
/**
 * Download Laporan ke PDF
 * Akses: http://localhost/aruskas/download_laporan.php
 */

require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/Transaksi.php';

check_login();

// Install TCPDF jika belum ada
$tcpdf_file = 'vendor/tcpdf/tcpdf.php';

// Jika TCPDF belum ada, gunakan cara alternative dengan HTML to PDF
if (!file_exists($tcpdf_file)) {
    // Gunakan cara HTML sederhana dengan mpdf library via CDN atau gunakan script HTML ke PDF sederhana
    // Untuk kemudahan, saya akan buat dengan format HTML yang bisa di-print
    header('Content-Type: text/html; charset=utf-8');
    die('<h2>Error: TCPDF library tidak ditemukan</h2><p>Silakan download TCPDF terlebih dahulu atau gunakan fitur print browser (Ctrl+P) -> Save as PDF</p>');
}

require_once $tcpdf_file;

// Ambil parameter
$tipe_filter = $_GET['tipe'] ?? '';
$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$transaksi = new Transaksi($conn);

// Filter data (sama seperti di laporan.php)
$semua_transaksi = [];

if ($tanggal_awal && $tanggal_akhir) {
    $semua_transaksi = $transaksi->get_by_date($tanggal_awal, $tanggal_akhir);
    
    if ($tipe_filter) {
        $semua_transaksi = array_filter($semua_transaksi, function($t) use ($tipe_filter) {
            return $t['tipe'] === $tipe_filter;
        });
    }
} elseif ($tipe_filter) {
    $semua_transaksi = $transaksi->get_by_type($tipe_filter);
} else {
    $semua_transaksi = $transaksi->get_all('DESC');
}

// Hitung total
$total_masuk_filtered = 0;
$total_keluar_filtered = 0;

foreach ($semua_transaksi as $t) {
    if ($t['tipe'] == 'masuk') {
        $total_masuk_filtered += $t['jumlah'];
    } else {
        $total_keluar_filtered += $t['jumlah'];
    }
}

$saldo_filtered = $total_masuk_filtered - $total_keluar_filtered;

// Buat PDF
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 15);
        $this->Cell(0, 10, 'LAPORAN ARUS KAS MASJID', 0, false, 'C', 0, '', 0, false);
        $this->Ln(10);
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 5, 'Tanggal Cetak: ' . date('d/m/Y H:i:s'), 0, false, 'C', 0, '', 0, false);
        $this->Ln(10);
    }
    
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C');
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_PAGE_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set properties
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 30, 10);
$pdf->SetAutoPageBreak(TRUE, 25);

// Tambah halaman
$pdf->AddPage();

// Filter info
$pdf->SetFont('helvetica', '', 9);
$filter_text = 'Filter: ';
if ($tanggal_awal && $tanggal_akhir) {
    $filter_text .= 'Tanggal ' . date('d/m/Y', strtotime($tanggal_awal)) . ' s/d ' . date('d/m/Y', strtotime($tanggal_akhir));
}
if ($tipe_filter) {
    if ($tanggal_awal || $tanggal_akhir) $filter_text .= ' + ';
    $filter_text .= 'Tipe: ' . ucfirst($tipe_filter);
}
if (empty($tanggal_awal) && empty($tanggal_akhir) && empty($tipe_filter)) {
    $filter_text .= 'Semua Data';
}

$pdf->Cell(0, 5, $filter_text, 0, 1, 'L');
$pdf->Ln(5);

// Tabel header
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(102, 126, 234);
$pdf->SetTextColor(255);

$pdf->Cell(15, 7, 'No', 1, 0, 'C', true);
$pdf->Cell(25, 7, 'Tanggal', 1, 0, 'C', true);
$pdf->Cell(70, 7, 'Keterangan', 1, 0, 'L', true);
$pdf->Cell(25, 7, 'Tipe', 1, 0, 'C', true);
$pdf->Cell(35, 7, 'Jumlah', 1, 1, 'R', true);

$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 9);

// Data transaksi
$no = 1;
foreach ($semua_transaksi as $t) {
    $pdf->Cell(15, 6, $no++, 1, 0, 'C');
    $pdf->Cell(25, 6, date('d/m/Y', strtotime($t['tanggal'])), 1, 0, 'C');
    $pdf->MultiCell(70, 6, $t['keterangan'], 1, 'L');
    $x = $pdf->GetX();
    $y = $pdf->GetY() - 6;
    $pdf->SetXY($x + 70, $y);
    $pdf->Cell(25, 6, ucfirst($t['tipe']), 1, 0, 'C');
    $pdf->Cell(35, 6, 'Rp ' . number_format($t['jumlah'], 0, ',', '.'), 1, 1, 'R');
}

$pdf->Ln(5);

// Ringkasan
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(100, 7, 'RINGKASAN', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(60, 6, 'Total Pemasukan', 0, 0, 'L');
$pdf->Cell(40, 6, 'Rp ' . number_format($total_masuk_filtered, 0, ',', '.'), 0, 1, 'R');

$pdf->Cell(60, 6, 'Total Pengeluaran', 0, 0, 'L');
$pdf->Cell(40, 6, 'Rp ' . number_format($total_keluar_filtered, 0, ',', '.'), 0, 1, 'R');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(60, 6, 'Saldo', 0, 0, 'L');
$pdf->Cell(40, 6, 'Rp ' . number_format($saldo_filtered, 0, ',', '.'), 0, 1, 'R');

// Output PDF
$filename = 'Laporan_Arus_Kas_' . date('Y-m-d_His') . '.pdf';
$pdf->Output($filename, 'D'); // D = download

$conn->close();
?>
