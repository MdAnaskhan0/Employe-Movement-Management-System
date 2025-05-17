<?php 
include('../includes/header.php'); 
include('../db_config/db.php'); // Ensure this file connects correctly to your DB

// Fetch all users
$sql = "SELECT * FROM usersdata";
$result = $conn->query($sql);
?>
<main>
    <h2>All Users</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><a href="/ManagementProject/pages/edit_user.php?id=<?php echo htmlspecialchars($row['id']); ?>">Edit</a></td>
                        <td><a href="/ManagementProject/pages/delete_user.php?id=<?php echo htmlspecialchars($row['id']); ?>">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</main>
<?php include('../includes/footer.php'); ?>
