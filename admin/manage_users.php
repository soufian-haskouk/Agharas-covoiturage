
<?php
require_once __DIR__ . '/includes/auth-check.php';


require_once 'includes/db.php';
require_once 'includes/header-admin.php';



$messages = [
    'success' => [
        'user_deleted' => 'User deleted successfully!'
    ],
    'error' => [
        'invalid_id' => 'Invalid user ID',
        'user_not_found' => 'User not found',
        'database_error' => 'Database error occurred',
        'default' => 'An error occurred'
    ]
];

if (isset($_GET['success'])) {
    $message = $messages['success'][$_GET['success']] ?? 'Operation successful';
    echo '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
}

if (isset($_GET['error'])) {
    $message = $messages['error'][$_GET['error']] ?? $messages['error']['default'];
    echo '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';



    if ($_GET['error'] === 'protected_user') {
        echo '<div class="alert alert-warning">The primary admin user cannot be deleted</div>';
    }
}
// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get users
$stmt = $pdo->prepare("SELECT * FROM users LIMIT :offset, :per_page");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':per_page', $per_page, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total users for pagination
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_pages = ceil($total_users / $per_page);
?>

<div class="  content">
    <h1>Manage Users</h1>
    
    <table class="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['telephone']); ?></td>
                <td>
                    <a href="#" class="btn-delete badge btn-delete bg-danger text-white text-decoration-none" title="Delete"
                    data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                    data-userid="<?php echo $user['id']; ?>">
                        <i class="fas fa-trash-alt me-1"></i>Supprimer
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php echo $i === $page ? 'class="active"' : ''; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
        
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimer cet utilisateur ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a id="confirmUserDelete" href="#" class="btn btn-danger">Supprimer</a>
            </div>
        </div>
    </div>
</div>
<style>

</style>
<script>
// Simple debug function to check if clicks are working
document.querySelectorAll('.btn-delete').forEach(link => {
    link.addEventListener('click', function(e) {
        console.log('Delete link clicked for user ID:', this.getAttribute('href').split('=')[1]);
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('deleteUserModal');
    var deleteButtons = document.querySelectorAll('.btn-delete[data-userid]');
    
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var userId = this.getAttribute('data-userid');
            var confirmBtn = document.getElementById('confirmUserDelete');
            confirmBtn.href = 'delete_user.php?id=' + userId;
        });
    });
});
</script>
<?php require_once 'includes/footer-admin.php'; ?>