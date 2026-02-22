<?php
class User {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Login user
    public function login($username, $password) {
        $username = $this->conn->real_escape_string($username);
        
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = $this->conn->query($query);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        
        return false;
    }
    
    // Get user by ID
    public function get_user($id) {
        $id = (int)$id;
        $query = "SELECT id, username, nama_lengkap, email FROM users WHERE id = $id";
        return $this->conn->query($query)->fetch_assoc();
    }
    
    // Get all users
    public function get_all_users() {
        $query = "SELECT id, username, nama_lengkap, email, created_at FROM users ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        
        if ($result->num_rows > 0) {
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return $users;
        }
        
        return [];
    }
    
    // Register user
    public function register($username, $password, $nama_lengkap, $email) {
        $username = $this->conn->real_escape_string($username);
        $nama_lengkap = $this->conn->real_escape_string($nama_lengkap);
        $email = $this->conn->real_escape_string($email);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $query = "INSERT INTO users (username, password, nama_lengkap, email) 
                  VALUES ('$username', '$hashed_password', '$nama_lengkap', '$email')";
        
        if ($this->conn->query($query)) {
            return true;
        }
        
        return false;
    }
    
    // Add user
    public function add_user($username, $password, $nama_lengkap, $email) {
        // Check if username already exists
        $username_check = $this->conn->real_escape_string($username);
        $query = "SELECT id FROM users WHERE username = '$username_check'";
        $result = $this->conn->query($query);
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Username sudah terdaftar!'];
        }
        
        return $this->register($username, $password, $nama_lengkap, $email) 
            ? ['success' => true, 'message' => 'User berhasil ditambahkan!']
            : ['success' => false, 'message' => 'Gagal menambahkan user!'];
    }
    
    // Update user
    public function update_user($id, $nama_lengkap, $email) {
        $id = (int)$id;
        $nama_lengkap = $this->conn->real_escape_string($nama_lengkap);
        $email = $this->conn->real_escape_string($email);
        
        $query = "UPDATE users SET nama_lengkap = '$nama_lengkap', email = '$email' WHERE id = $id";
        
        return $this->conn->query($query) 
            ? ['success' => true, 'message' => 'User berhasil diperbarui!']
            : ['success' => false, 'message' => 'Gagal memperbarui user!'];
    }
    
    // Change password
    public function change_password($id, $new_password) {
        $id = (int)$id;
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        
        $query = "UPDATE users SET password = '$hashed_password' WHERE id = $id";
        
        return $this->conn->query($query);
    }
    
    // Delete user
    public function delete_user($id) {
        $id = (int)$id;
        
        $query = "DELETE FROM users WHERE id = $id";
        
        return $this->conn->query($query) 
            ? ['success' => true, 'message' => 'User berhasil dihapus!']
            : ['success' => false, 'message' => 'Gagal menghapus user!'];
    }
}
?>

