<?php
$page_title  = "Master User";
$active_menu = "user";
require_once '../includes/header.php';

$res = mysqli_query($conn, "SELECT * FROM v_user ORDER BY id_user ASC");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Master User</h4>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah User</a>
</div>
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr><th>ID</th><th>Username</th><th>Role</th><th class="text-center">Aksi</th></tr>
            </thead>
            <tbody>
                <?php if ($res && mysqli_num_rows($res) > 0): while ($r = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><?= $r['id_user'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($r['username']) ?></td>
                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($r['nama_role']) ?></span></td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= $r['id_user'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="hapus.php?id=<?= $r['id_user'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data user.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
