<?php 
include('../includes/header.php'); 
include('../db_config/db.php'); // Make sure this path and filename are correct

// Fetch total users from the database
$sql = "SELECT COUNT(*) as total FROM usersdata";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_users = $row['total'];
?>

<main class="dashboard" style="min-height: 80vh;">
    <h2>Dashboard</h2>
    
    <div class="dashboard-cards">
        <div class="card" onclick="location.href='/ManagementProject/pages/userinfo.php'">
            <h3>Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>
    </div>

    <section class="quick-actions">
        <h3>Quick Actions</h3>
        <button onclick="location.href='/ManagementProject/pages/add_user.php'">Add New User</button>
        <button onclick="alert('Feature Coming Soon!')">Add New Task</button>
        <button onclick="alert('Feature Coming Soon!')">View Reports</button>
    </section>
</main>
<?php include('../includes/footer.php'); ?>
