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
$sql = "SELECT userID, username, email FROM usersdata WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($userID, $db_username, $email);
    $stmt->fetch();
} else {
    echo "<p>User not found.</p>";
    exit;
}
$stmt->close();

// Fetch movement records for this user
$movements = [];
$movement_sql = "SELECT datetime, visitingStatus, placeName, partyName, purpose, remark, punchTime 
                 FROM movementData WHERE userID = ? ORDER BY datetime DESC";
$movement_stmt = $conn->prepare($movement_sql);
$movement_stmt->bind_param("i", $userID);
$movement_stmt->execute();
$result = $movement_stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $movements[] = $row;
}
$movement_stmt->close();

$currentTime = new DateTime();
?>

<main>
    <h2>My Info</h2>
    <p>Welcome, <?php echo htmlspecialchars($db_username); ?>!</p>

    <!-- Display user info -->
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Username</th>
            <td><?php echo htmlspecialchars($db_username); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($email); ?></td>
        </tr>
    </table>

    <!-- Update user Status button -->
    <br>
    <button onclick="location.href='/ManagementProject/pages/movement_status.php'">Update Status</button>

    <!-- Show User all movements -->
    <h3>My Movement Records</h3>
    <?php if (count($movements) > 0): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>Visiting Status</th>
                    <th>Place</th>
                    <th>Party Name</th>
                    <th>Purpose</th>
                    <th>Remark</th>
                    <th>Punch Time</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movements as $move): 
                    $recordTime = new DateTime($move['datetime']);
                    $interval = $currentTime->getTimestamp() - $recordTime->getTimestamp();
                    $canEdit = ($interval <= 600) && ($interval >= 0); // within 10 minutes
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($move['datetime']); ?></td>
                        <td><?php echo htmlspecialchars($move['visitingStatus']); ?></td>
                        <td><?php echo htmlspecialchars($move['placeName']); ?></td>
                        <td><?php echo htmlspecialchars($move['partyName']); ?></td>
                        <td><?php echo htmlspecialchars($move['purpose']); ?></td>
                        <td><?php echo htmlspecialchars($move['remark']); ?></td>
                        <td><?php echo htmlspecialchars($move['punchTime']); ?></td>
                        <td>
                            <?php if ($canEdit): ?>
                                <a href="edit_movement.php?datetime=<?php echo urlencode($move['datetime']); ?>">Edit</a>
                            <?php else: ?>
                                <span style="color:gray;">Not Editable</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No movement records found.</p>
    <?php endif; ?>
</main>

<?php include('../includes/footer.php'); ?>
