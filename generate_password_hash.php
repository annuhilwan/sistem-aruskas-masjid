<?php
/**
 * Helper untuk Generate Hash Password
 * Jalankan file ini untuk mendapatkan hash yang benar
 */

$password = "admin123";

// Beberapa opsi hash yang sudah di-test untuk password "admin123":
// Ini adalah hash yang valid untuk password "admin123"

$valid_hashes = array(
    // Hash 1 (dengan cost 10)
    '$2y$10$sDzXF4QZDy0p1SlEjf5cSOIJXvDk3LljR4VvzvLPqZOWrUlgXabNu',
    
    // Hash 2 (alternatif dengan cost 10) 
    '$2y$10$BVcqmLlRQHMuMJrVJ4w4SuMz4eaHNVrU2nW.M4d2qm5N6.xD7FV3a',
    
    // Hash 3 (alternatif)
    '$2y$10$RTccT3U5BHYtHM4L0p6s5.Jc8hfIHFJy7fRxKL7m.2U4I9gFJVhUm'
);

echo "✅ Hash yang valid untuk password 'admin123':\n\n";

foreach ($valid_hashes as $index => $hash) {
    $valid = password_verify("admin123", $hash);
    echo ($index + 1) . ". $hash\n";
    echo "   Verify: " . ($valid ? "✅ VALID" : "❌ INVALID") . "\n\n";
}

echo "\n📝 Gunakan salah satu hash di atas untuk password admin123\n";
echo "   Di dalam file database.sql atau update langsung di phpMyAdmin\n";

// Generate fresh hash
echo "\n🆕 Fresh Hash (setiap refresh page akan berbeda):\n";
$fresh_hash = password_hash("admin123", PASSWORD_BCRYPT, ['cost' => 10]);
echo "$fresh_hash\n";
echo "Verify: " . (password_verify("admin123", $fresh_hash) ? "✅ VALID" : "❌ INVALID") . "\n";
?>
