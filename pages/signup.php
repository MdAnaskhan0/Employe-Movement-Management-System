<?php
session_start(); // Ensure session is started if using session

include('../includes/header.php');
include('../db_config/db.php');

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Check if username or email exists
        $stmt = $conn->prepare("SELECT userID FROM usersdata WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username or Email already exists.";
        } else {
            // Insert new user (plain password)
            $stmt = $conn->prepare("INSERT INTO usersdata (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password);

            if ($stmt->execute()) {
                $_SESSION['username'] = $username;
                header("Location: dashboard.php");
                exit;
            } else {
                $message = "Error during registration.";
            }
        }
        $stmt->close();
    }
}
?>

<main>
    <h2>Signup</h2>
    <?php if ($message): ?>
        <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br><br>
        <button type="submit">Signup</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</main>

<?php include('../includes/footer.php'); ?>
