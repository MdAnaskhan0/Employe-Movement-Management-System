<?php
session_start();
include('../includes/header.php');
include('../db_config/db.php');

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Fetch id and password from usersdata
    $stmt = $conn->prepare("SELECT userID, password FROM usersdata WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($userID, $db_password);
        $stmt->fetch();

        if ($password === $db_password) { // ⚠️ No hashing used
            $_SESSION['username'] = $username;
            $_SESSION['userID'] = $userID;
            header("Location: my_info.php");
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
