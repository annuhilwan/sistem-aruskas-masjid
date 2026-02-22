<?php
class Transaksi {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Tambah transaksi
    public function add($tanggal, $keterangan, $tipe, $jumlah) {
        $tanggal = $this->conn->real_escape_string($tanggal);
        $keterangan = $this->conn->real_escape_string($keterangan);
        $tipe = $this->conn->real_escape_string($tipe);
        $jumlah = (float)$jumlah;
        
        $query = "INSERT INTO transaksi (tanggal, keterangan, tipe, jumlah) 
                  VALUES ('$tanggal', '$keterangan', '$tipe', $jumlah)";
        
        if ($this->conn->query($query)) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    // Edit transaksi
    public function edit($id, $tanggal, $keterangan, $tipe, $jumlah) {
        $id = (int)$id;
        $tanggal = $this->conn->real_escape_string($tanggal);
        $keterangan = $this->conn->real_escape_string($keterangan);
        $tipe = $this->conn->real_escape_string($tipe);
        $jumlah = (float)$jumlah;
        
        $query = "UPDATE transaksi SET tanggal='$tanggal', keterangan='$keterangan', 
                  tipe='$tipe', jumlah=$jumlah WHERE id=$id";
        
        if ($this->conn->query($query)) {
            return true;
        }
        
        return false;
    }
    
    // Hapus transaksi
    public function delete($id) {
        $id = (int)$id;
        $query = "DELETE FROM transaksi WHERE id=$id";
        
        if ($this->conn->query($query)) {
            return true;
        }
        
        return false;
    }
    
    // Get transaksi by ID
    public function get($id) {
        $id = (int)$id;
        $query = "SELECT * FROM transaksi WHERE id=$id";
        return $this->conn->query($query)->fetch_assoc();
    }
    
    // Get semua transaksi
    public function get_all($order = 'DESC') {
        $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        $query = "SELECT * FROM transaksi ORDER BY tanggal $order, id $order";
        $result = $this->conn->query($query);
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    // Get transaksi berdasarkan tipe
    public function get_by_type($tipe) {
        $tipe = $this->conn->real_escape_string($tipe);
        $query = "SELECT * FROM transaksi WHERE tipe='$tipe' ORDER BY tanggal DESC";
        $result = $this->conn->query($query);
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    // Get transaksi berdasarkan tanggal
    public function get_by_date($start_date, $end_date) {
        $start_date = $this->conn->real_escape_string($start_date);
        $end_date = $this->conn->real_escape_string($end_date);
        $query = "SELECT * FROM transaksi WHERE tanggal BETWEEN '$start_date' AND '$end_date' ORDER BY tanggal DESC";
        $result = $this->conn->query($query);
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    // Hitung total pemasukan
    public function total_masuk() {
        $query = "SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='masuk'";
        $result = $this->conn->query($query)->fetch_assoc();
        return $result['total'] ?? 0;
    }
    
    // Hitung total pengeluaran
    public function total_keluar() {
        $query = "SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='keluar'";
        $result = $this->conn->query($query)->fetch_assoc();
        return $result['total'] ?? 0;
    }
    
    // Hitung saldo
    public function saldo() {
        return $this->total_masuk() - $this->total_keluar();
    }
}
?>
