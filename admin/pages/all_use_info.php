<?php 
include('../includes/header.php'); 
include('../db_config/db.php'); // Make sure this path and filename are correct

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
                        <td><?php echo htmlspecialchars($row['userID']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><a href="/ManagementProject/admin/pages/edit_user.php?userID=<?php echo htmlspecialchars($row['userID']); ?>">Edit</a></td>
                        <td><a href="/ManagementProject/admin/pages/delete_user.php?userID=<?php echo htmlspecialchars($row['userID']); ?>">Delete</a></td>
                        <td><a href="/ManagementProject/admin/pages/user_report.php?userID=<?php echo htmlspecialchars($row['userID']); ?>">Reports</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</main>
<?php include('../includes/footer.php'); ?>