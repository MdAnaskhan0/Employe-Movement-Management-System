<?php
session_start();
include('../includes/header.php');
include('../db_config/db.php');

if (!isset($_GET['userID'])) {
    die("User ID is required.");
}

$userID = intval($_GET['userID']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Confirm deletion
    $stmt = $conn->prepare("DELETE FROM usersdata WHERE userID = ?");
    $stmt->bind_param("i", $userID);

    if ($stmt->execute()) {
        header("Location: /ManagementProject/pages/users_list.php?msg=User+deleted+successfully");
        exit;
    } else {
        $error = "Error deleting user.";
    }
    $stmt->close();
} else {
    // Fetch username to confirm
    $stmt = $conn->prepare("SELECT username FROM usersdata WHERE userID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("User not found.");
    }

    $user = $result->fetch_assoc();
    $stmt->close();
}
?>

<main>
    <h2>Delete User</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <p>Are you sure you want to delete user <strong><?php echo htmlspecialchars($user['username']); ?></strong>?</p>

    <form method="post" action="">
        <button type="submit">Yes, Delete</button>
        <a href="/ManagementProject/pages/users_list.php">Cancel</a>
    </form>
</main>

<?php include('../includes/footer.php'); ?>
