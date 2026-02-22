<?php
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/User.php';

check_login();

$page_title = 'Kelola User';
require_once 'pages/header.php';

$user = new User($conn);
$users = $user->get_all_users();
?>

<div class="main-content">
    <div class="container">
        <div class="card">
            <h2>👥 Kelola User</h2>
            
            <div class="btn-group">
                <a href="tambah_user.php" class="btn btn-success">➕ Tambah User</a>
            </div>
            
            <?php
            if (isset($_GET['status'])) {
                if ($_GET['status'] == 'added') {
                    echo '<div class="alert alert-success">✅ User berhasil ditambahkan!</div>';
                } elseif ($_GET['status'] == 'updated') {
                    echo '<div class="alert alert-success">✅ User berhasil diperbarui!</div>';
                } elseif ($_GET['status'] == 'deleted') {
                    echo '<div class="alert alert-success">✅ User berhasil dihapus!</div>';
                }
            }
            ?>
            
            <?php if (!empty($users) && count($users) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($u['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($u['nama_lengkap']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $u['id']; ?>" class="btn btn-warning" style="padding: 0.5rem 1rem; font-size: 0.9rem;">✏️ Edit</a>
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <a href="hapus_user.php?id=<?php echo $u['id']; ?>" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.9rem;" onclick="return confirm('Yakin ingin menghapus user ini?');">🗑️ Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="padding: 2rem; text-align: center; color: #999;">Belum ada user terdaftar.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'pages/footer.php'; ?>
