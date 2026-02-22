<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'aruskas');

// Membuat koneksi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");

// Fungsi helper
function escape_string($value) {
    global $conn;
    return $conn->real_escape_string($value);
}

function query($query) {
    global $conn;
    $result = $conn->query($query);
    
    if (!$result) {
        die("Query error: " . $conn->error);
    }
    
    return $result;
}

function fetch_all($query) {
    $result = query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function fetch_one($query) {
    $result = query($query);
    return $result->fetch_assoc();
}

function execute($query) {
    global $conn;
    if (!$conn->query($query)) {
        die("Execute error: " . $conn->error);
    }
    return $conn->affected_rows;
}

function last_insert_id() {
    global $conn;
    return $conn->insert_id;
}
?>
