
<?php

require_once __DIR__ . '/includes/auth-check.php';

require_once 'includes/db.php';


// Get counts for dashboard
$users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$active_trips = $pdo->query("SELECT COUNT(*) FROM trajet WHERE status = 'active'")->fetchColumn();
$completed_trips = $pdo->query("SELECT COUNT(*) FROM trajet WHERE status = 'completed'")->fetchColumn();
$reservations = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$contacts = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();

?>






<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - Agharas</title>
    <link rel="shortcut icon" href="../pages/images/LogoFv.png" type="image/x-icon"> 

    <link rel="stylesheet" href="../public/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    
    
    <!-- Recent activities or other dashboard widgets -->

    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-car"></i> ⴰⵖⴰⵔⴰⵙ   </h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Tableau </a></li>
                <li><a href="manage_users.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
                <li><a href="manage_trajets.php"><i class="fas fa-route"></i> Trajets</a></li>
                <li><a href="manage_reservations.php"><i class="fas fa-ticket-alt"></i> Réservations</a></li>
                <li><a href="manage_contacts.php"><i class="fas fa-envelope"></i> Messages</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1>BIENVENUE  </h1>
                <div class="user-menu">
                    <img src="..\pages\images\me.jpg" alt="Admin">
                    <span>Soufian-Anas</span>
                </div>
            </header>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card users-card">
                    <h3>Utilisateurs</h3>
                    <div class="value"><?php echo $users_count; ?></div>
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                
                <div class="card trips-card">
                    <h3>Trajets Actifs</h3>
                    <div class="value"><?php echo $active_trips; ?></div>
                    <div class="card-icon">
                        <i class="fas fa-route"></i>
                    </div>
                </div>
                
                <div class="card reservations-card">
                    <h3>Réservations</h3>
                    <div class="value"><?php echo $reservations; ?></div>
                    <div class="card-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
                
                <div class="card contacts-card">
                    <h3>Messages</h3>
                    <div class="value"><?php echo $contacts; ?></div>
                    <div class="card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>

           
        </main>
    </div>

<style>

/*  Admin Dashboard */
:root {
    --primary-color: #fdd120;
    --secondary-color:#152329 ;
    --success-color: #fdd120;
    --danger-color: #fdd120;
    --warning-color: #fdd120;
    --light-color: #ecf0f1;
    --dark-color: #34495e;
    --sidebar-width: 250px;
}

/* Base Styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f7fa;
    color: #333;
    line-height: 1.6;
}



a {
    text-decoration: none;
    color: var(--primary-color);
}

/* Admin Layout */
.admin-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.admin-sidebar {
    width: var(--sidebar-width);
    background-color: var(--secondary-color);
    color: white;
    position: fixed;
    height: 100vh;
    padding: 20px 0;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    z-index: 100;
}

.sidebar-header {
    padding: 0 20px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    margin-bottom: 20px;
}

.sidebar-header h2 {
    color: white;
    margin: 0;
    font-size: 1.3rem;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu li {
    margin-bottom: 5px;
}

.sidebar-menu a {
    display: block;
    padding: 12px 20px;
    color: var(--light-color);
    transition: all 0.3s;
}

.sidebar-menu a:hover, 
.sidebar-menu a.active {
    background-color: rgba(255,255,255,0.1);
    border-left: 4px solid var(--primary-color);
    padding-left: 16px;
}

.sidebar-menu i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

/* Main Content */
.admin-main {
    flex: 1;
    margin-left: var(--sidebar-width);
    padding: 20px;
    font-weight:bold;
}

/* Header */
.admin-header {
    background-color: white;
    padding: 15px 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.admin-header h1 {
    margin: 0;
    font-size: 1.5rem;
    color: var(--secondary-color);
}

.user-menu {
    display: flex;
    align-items: center;
}

.user-menu img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

/* Dashboard Cards */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.card {
    background-color: white;
    border-radius: 5px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: transform 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.card h3 {
    margin-top: 0;
    color: var(--dark-color);
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.card .value {
    font-size: 2rem;
    font-weight: bold;
    margin: 10px 0;
    color: var(--secondary-color);
}

.card .card-icon {
    font-size: 2.5rem;
    color: var(--primary-color);
    float: right;
}

.card.users-card {
    border-top: 4px solid var(--primary-color);
}

.card.trips-card {
    border-top: 4px solid var(--success-color);
}

.card.reservations-card {
    border-top: 4px solid var(--warning-color);
}

.card.contacts-card {
    border-top: 4px solid var(--danger-color);
}

/* Recent Activity */
.recent-activity {
    background-color: white;
    border-radius: 5px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.recent-activity h2 {
    margin-top: 0;
    color: var(--secondary-color);
    font-size: 1.3rem;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.activity-list {
    list-style: none;
    padding: 0;
}

.activity-item {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: var(--light-color);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
}

.activity-icon.user {
    background-color: var(--primary-color);
}

.activity-icon.trip {
    background-color: var(--success-color);
}

.activity-icon.reservation {
    background-color: var(--warning-color);
}

.activity-details {
    flex: 1;
}

.activity-details p {
    margin: 0;
}

.activity-time {
    color: #95a5a6;
    font-size: 0.8rem;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 8px 15px;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.btn-danger {
    background-color: var(--danger-color);
    color: white;
}

.btn-danger:hover {
    background-color: #c0392b;
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-container {
        flex-direction: column;
    }
    
    .admin-sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }
    
    .admin-main {
        margin-left: 0;
    }
    
    .dashboard-cards {
        grid-template-columns: 1fr;
    }
}
</style>
</body>
</html>