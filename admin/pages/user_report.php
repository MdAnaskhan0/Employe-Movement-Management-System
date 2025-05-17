<?php
session_start();
include('../../includes/header.php');  // Adjust path as needed 
include('../../db_config/db.php');

if (!isset($_GET['userID'])) {
    die("User ID is required.");
}

$userID = intval($_GET['userID']);

// Fetch reports for the user, ordered by datetime descending
$stmt = $conn->prepare("
    SELECT username, datetime, visitingStatus, placeName, partyName, purpose, remark, punchTime 
    FROM movementdata 
    WHERE userID = ? 
    ORDER BY datetime DESC
");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Fetch username from usersdata as a fallback
$userStmt = $conn->prepare("SELECT username FROM usersdata WHERE userID = ?");
$userStmt->bind_param("i", $userID);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
?>

<main>
    <h2>Reports for User: <?php echo htmlspecialchars($user['username'] ?? 'Unknown'); ?></h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Punch Time</th>
                    <th>Visiting Status</th>
                    <th>Place Name</th>
                    <th>Party Name</th>
                    <th>Purpose</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php 
                        $dateTime = new DateTime($row['datetime']);
                        $date = $dateTime->format('Y-m-d');
                        $time = $dateTime->format('H:i:s');
                    ?>
                    <tr>
                        <td><?php echo $date; ?></td>
                        <td><?php echo $time; ?></td>
                        <td><?php echo htmlspecialchars($row['punchTime']); ?></td>
                        <td><?php echo htmlspecialchars($row['visitingStatus']); ?></td>
                        <td><?php echo htmlspecialchars($row['placeName']); ?></td>
                        <td><?php echo htmlspecialchars($row['partyName']); ?></td>
                        <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                        <td><?php echo htmlspecialchars($row['remark']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>                
        </table>
    <?php else: ?>
        <p>No reports found for this user.</p>
    <?php endif; ?>

    <p><a href="/ManagementProject/admin/pages/all_use_info.php">Back to User List</a></p>
</main>

<?php
$userStmt->close();
$stmt->close();
$conn->close();
include('../../includes/footer.php');
?>
