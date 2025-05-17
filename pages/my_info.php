<?php
session_start();
include('../includes/header.php');
include('../db_config/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch user info from the database
$sql = "SELECT userID, username, email, password FROM usersdata WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

// Check if user exists
if ($stmt->num_rows === 1) {
    $stmt->bind_result($userID, $db_username, $email, $password);
    $stmt->fetch();
} else {
    echo "<p>User not found.</p>";
    exit;
}
$stmt->close();
?>

<main>
    <h2>My Info</h2>
    <p>Welcome, <?php echo htmlspecialchars($db_username); ?>!</p>

    <!-- Display user info -->
    <table>
        <tr>
            <th>Username</th>
            <td><?php echo htmlspecialchars($db_username); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($email); ?></td>
        </tr>
        <tr>
            <th>Password</th>
            <td><?php echo htmlspecialchars($password); ?></td>
        </tr>
    </table>

    <!-- Update user Status button -->
    <button onclick="location.href='/ManagementProject/pages/movement_status.php'">Update Status</button>
    <button onclick="alert('Feature Coming Soon!')">Today Tasks</button>
    <button onclick="location.href='/ManagementProject/pages/user_report.php'">Reports</button>
    
</main>

<?php include('../includes/footer.php'); ?>
