<?php
session_start();
include('../includes/header.php');
include('../db_config/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Get userID
$sql = "SELECT userID FROM usersdata WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($userID);
$stmt->fetch();
$stmt->close();

if (!isset($_GET['datetime'])) {
    echo "Invalid request.";
    exit;
}

$datetime = $_GET['datetime'];

// Fetch the record
$sql = "SELECT datetime, visitingStatus, placeName, partyName, purpose, remark, punchTime 
        FROM movementData WHERE userID = ? AND datetime = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $userID, $datetime);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Record not found.";
    exit;
}

$record = $result->fetch_assoc();
$stmt->close();

$currentTime = new DateTime();
$recordTime = new DateTime($record['datetime']);
$interval = $currentTime->getTimestamp() - $recordTime->getTimestamp();

if ($interval > 600 || $interval < 0) {
    echo "Editing time window expired.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and get POST data
    $visitingStatus = $_POST['visitingStatus'] ?? '';
    $placeName = $_POST['placeName'] ?? '';
    $partyName = $_POST['partyName'] ?? '';
    $purpose = $_POST['purpose'] ?? '';
    $remark = $_POST['remark'] ?? '';
    $punchTime = $_POST['punchTime'] ?? '';

    // Update record query
    $update_sql = "UPDATE movementData SET visitingStatus = ?, placeName = ?, partyName = ?, purpose = ?, remark = ?, punchTime = ? WHERE userID = ? AND datetime = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssis", $visitingStatus, $placeName, $partyName, $purpose, $remark, $punchTime, $userID, $datetime);

    if ($update_stmt->execute()) {
        echo "<p>Record updated successfully.</p>";
        echo '<p><a href="my_info.php">Back to profile</a></p>';
        $update_stmt->close();
        include('../includes/footer.php');
        exit;
    } else {
        echo "Update failed: " . $conn->error;
    }
    $update_stmt->close();
}
?>

<main>
    <h2>Edit Movement Record</h2>
    <form method="post">
        <label>Visiting Status:</label><br>
        <input type="text" name="visitingStatus" value="<?php echo htmlspecialchars($record['visitingStatus']); ?>" required><br><br>

        <label>Place Name:</label><br>
        <input type="text" name="placeName" value="<?php echo htmlspecialchars($record['placeName']); ?>" required><br><br>

        <label>Party Name:</label><br>
        <input type="text" name="partyName" value="<?php echo htmlspecialchars($record['partyName']); ?>" required><br><br>

        <label>Purpose:</label><br>
        <input type="text" name="purpose" value="<?php echo htmlspecialchars($record['purpose']); ?>" required><br><br>

        <label>Remark:</label><br>
        <input type="text" name="remark" value="<?php echo htmlspecialchars($record['remark']); ?>"><br><br>

        <label>Punch Time:</label><br>
        <input type="text" name="punchTime" value="<?php echo htmlspecialchars($record['punchTime']); ?>" required><br><br>

        <input type="submit" value="Update Record">
        <a href="profile.php" style="margin-left: 10px;">Cancel</a>
    </form>
</main>

<?php include('../includes/footer.php'); ?>
