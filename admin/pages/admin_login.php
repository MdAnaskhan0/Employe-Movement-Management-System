<?php 
session_start();
include('../includes/header.php');
include('../db_config/db.php');

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Fetch id and password from usersdata
    $stmt = $conn->prepare("SELECT adminID, password FROM admin_panel WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($adminID, $db_password);
        $stmt->fetch();

        if ($password === $db_password) { 
            $_SESSION['username'] = $username;
            $_SESSION['adminID'] = $adminID;
            header("Location: admin/index.php");
            exit;
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "Username not found.";
    }
    $stmt->close();
}
?>

<main>
    <h2>Login</h2>
    <?php if ($message): ?>
        <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <p>No account? <a href="signup.php">Sign up here</a></p>
</main>

<?php include('../includes/footer.php'); ?>     