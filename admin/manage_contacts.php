<?php
require_once __DIR__ . '/includes/auth-check.php';


// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agharas_bd";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle message status update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if ($action == 'mark_read') {
        $stmt = $conn->prepare("UPDATE contacts SET status = 'read' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'mark_replied') {
        $stmt = $conn->prepare("UPDATE contacts SET status = 'replied' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'delete') {
        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: manage_contacts.php");
    exit();
}

// Fetch all contacts
$query = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - AGHARAS</title>

    <link rel="shortcut icon" href="../pages/images/LogoFv.png" type="image/x-icon"> 

    <link rel="stylesheet" href="../pages/css/admin.css" />
    <link rel="stylesheet" href="../pages/css/style.css" />



    <link href="..\pages\css\lib\fontawesome\webfonts\all.min.css" rel="stylesheet">
    <link href="..\pages\css\bootstrap.css"  rel="stylesheet">
    
</head>
<body>
    <header class="admin-header">
        <div class="container">
            <h1>AGHARAS Admin</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="manage_users.php">Utilisateurs</a></li>
                    <li><a href="manage_trajets.php">Trajets</a></li>
                    <li><a href="manage_reservations.php">Reservations</a></li>
                    <li><a href="manage_contacts.php">Messages</a></li>
                    <li><a href="logout.php"> Déconnexion</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="container main-content">


    <div class="container mt-4">
        <h2>Contact Messages</h2>
        
        <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="status-<?php echo $row['status']; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <?php if ($row['status'] != 'read'): ?>
                                <a href="manage_contacts.php?action=mark_read&id=<?php echo $row['id']; ?>" 
                                class="badge bg-success text-white text-decoration-none" 
                                title="Mark as Read">
                                    <i class="fas fa-check me-1"></i>Read
                                </a>
                                <?php endif; ?>
                                
                                <a href="manage_contacts.php?action=mark_replied&id=<?php echo $row['id']; ?>" 
                                class="badge bg-primary text-white text-decoration-none" 
                                title="Mark as Replied">
                                    <i class="fas fa-reply me-1"></i>Replied
                                </a>
                                <button onclick="silentCopyWithFeedback('<?= $row['email'] ?>', this)" 
                                        class="badge bg-info text-white text-decoration-none">
                                    <i class="fas fa-envelope me-1"></i> Copy Email
                                </button>


                        
                                

                                
                                <a href="manage_contacts.php?action=delete&id=<?php echo $row['id']; ?>" 
                                class="badge bg-danger text-white text-decoration-none" 
                                title="Delete"
                                onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash-alt me-1"></i>Supprimer
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="alert alert-info">No contact messages found.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
<?php require_once 'includes/footer-admin.php'; ?>
<script>
function silentCopyWithFeedback(email, button) {
    // Copy logic (same as above)
    
    // Visual feedback
    button.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
    setTimeout(() => {
        button.innerHTML = '<i class="fas fa-envelope me-1"></i> Copy Email';
    }, 2000);
}
</script>
                         
<style>
</style>