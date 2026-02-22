<?php
class Settings {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Get setting by key
    public function get_setting($key) {
        $key = $this->conn->real_escape_string($key);
        $query = "SELECT setting_value FROM settings WHERE setting_key = '$key'";
        $result = $this->conn->query($query);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['setting_value'];
        }
        
        return null;
    }
    
    // Get all settings
    public function get_all_settings() {
        $query = "SELECT setting_key, setting_value FROM settings";
        $result = $this->conn->query($query);
        
        $settings = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        }
        
        return $settings;
    }
    
    // Update setting
    public function update_setting($key, $value) {
        $key = $this->conn->real_escape_string($key);
        $value = $this->conn->real_escape_string($value);
        
        // Check if setting exists
        $check = $this->conn->query("SELECT id FROM settings WHERE setting_key = '$key'");
        
        if ($check->num_rows > 0) {
            // Update existing
            $query = "UPDATE settings SET setting_value = '$value' WHERE setting_key = '$key'";
        } else {
            // Insert new
            $query = "INSERT INTO settings (setting_key, setting_value) VALUES ('$key', '$value')";
        }
        
        return $this->conn->query($query) 
            ? ['success' => true, 'message' => 'Setting berhasil disimpan!']
            : ['success' => false, 'message' => 'Gagal menyimpan setting!'];
    }
}
?>
