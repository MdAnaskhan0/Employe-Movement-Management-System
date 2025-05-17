<?php
session_start();
include('../includes/header.php');
include('../db_config/db.php');

if (!isset($_GET['userID'])) {
    die("User ID is required.");
}

$userID = intval($_GET['userID']);
$message = '';

// Fetch user data to populate the form
$stmt = $conn->prepare("SELECT username, email FROM usersdata WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Update the user info
    $updateStmt = $conn->prepare("UPDATE usersdata SET username = ?, email = ? WHERE userID = ?");
    $updateStmt->bind_param("ssi", $username, $email, $userID);

    if ($updateStmt->execute()) {
        $message = "User updated successfully.";
        // Refresh user data
        $user['username'] = $username;
        $user['email'] = $email;
    } else {
        $message = "Error updating user.";
    }
    $updateStmt->close();
}

$stmt->close();
?>

<main>
    <h2>Edit User</h2>

    <?php if ($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>Username:</label><br>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

        <button type="submit">Update User</button>
    </form>

    <p><a href="/ManagementProject/admin/pages/all_use_info.php">Back to User List</a></p>
</main>

<?php include('../includes/footer.php'); ?>
