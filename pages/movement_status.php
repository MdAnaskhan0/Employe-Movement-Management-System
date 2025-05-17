<?php
session_start();
include('../includes/header.php');
include('../db_config/db.php');

$message = '';

// Check if user is logged in
if (!isset($_SESSION['userID']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Get last punch record for the user
$lastPunch = null;
$lastPunchStmt = $conn->prepare("SELECT punchTime FROM movementData WHERE userID = ? ORDER BY datetime DESC LIMIT 1");
$lastPunchStmt->bind_param("i", $_SESSION['userID']);
$lastPunchStmt->execute();
$lastPunchStmt->bind_result($lastPunch);
$lastPunchStmt->fetch();
$lastPunchStmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
    $datetime = date('Y-m-d H:i:s');
    $visitingStatus = $_POST['visiting_status'];

    $placeName = $_POST['place_name'] === 'others' ? $_POST['place_name_other'] : $_POST['place_name'];
    $partyName = $_POST['party_name'] === 'others' ? $_POST['party_name_other'] : $_POST['party_name'];

    $purpose = $_POST['purpose'];
    $remark = $_POST['remark'];
    $punchTime = $_POST['punch_time'];

    $stmt = $conn->prepare("INSERT INTO movementData 
        (userID, username, datetime, visitingStatus, placeName, partyName, purpose, remark, punchTime)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssss", $userID, $username, $datetime, $visitingStatus, $placeName, $partyName, $purpose, $remark, $punchTime);

    if ($stmt->execute()) {
        $message = "Movement data recorded successfully.";
        // Update last punch after insert
        $lastPunch = $punchTime;
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<main>
    <h2>Movement Data Entry</h2>
    <?php if ($message): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if ($lastPunch): ?>
        <!-- <p><strong>Last Punch:</strong> <?php echo htmlspecialchars($lastPunch); ?></p> -->
    <?php endif; ?>

    <form method="post">
        <label>Punch Time:</label><br>
        <select name="punch_time" required>
            <option value="">Select option</option>
            <?php if ($lastPunch !== 'In time'): ?>
                <option value="In time">Punch in</option>
            <?php endif; ?>
            <?php if ($lastPunch !== 'Out time'): ?>
                <option value="Out time">Punch out</option>
            <?php endif; ?>
        </select><br><br>

        <label for="visiting_status">Visiting Status:</label><br>
        <select name="visiting_status" required>
            <option value="">Select option</option>
            <option value="Office in Sclera">Office in Sclera</option>  
            <option value="Office in HQ">Office in HQ</option>  
            <option value="Field">Field</option>
        </select><br><br>

        <label>Place Name:</label><br>
        <select name="place_name" id="place_name" onchange="checkPlace(this.value)" required>
            <option value="">Select option</option>
            <option value="Dhaka">Dhaka</option>
            <option value="Chittagong">Chittagong</option>  
            <option value="Barisal">Barisal</option>
            <option value="Sylhet">Sylhet</option>
            <option value="Rangpur">Rangpur</option>
            <option value="Mymensingh">Mymensingh</option>
            <option value="Tangail">Tangail</option>
            <option value="Comilla">Comilla</option>
            <option value="Cox's Bazar">Cox's Bazar</option>
            <option value="Narayanganj">Narayanganj</option>
            <option value="Jessore">Jessore</option>
            <option value="others">Others</option>
        </select><br><br>

        <div id="other_place_input" style="display:none;">
            <label>Please specify place:</label><br>
            <input type="text" name="place_name_other" id="place_name_other"><br><br>
        </div>

        <label>Party Name:</label><br>
        <select name="party_name" id="party_name" onchange="checkParty(this.value)" required>
            <option value="">Select option</option>
            <option value="Fashion Group">Fashion Group</option>
            <option value="RGC">RGC</option>
            <option value="others">Others</option>
        </select><br><br>

        <div id="other_party_input" style="display:none;">
            <label>Please specify party:</label><br>
            <input type="text" name="party_name_other" id="party_name_other"><br><br>
        </div>

        <label>Purpose:</label><br>
        <input type="text" name="purpose"><br><br>

        <label>Remark:</label><br>
        <input type="text" name="remark"><br><br>

        <button type="submit">Submit</button>
    </form>
</main>

<script>
function checkPlace(value) {
    const otherPlace = document.getElementById('other_place_input');
    const placeInput = document.getElementById('place_name_other');
    if (value === 'others') {
        otherPlace.style.display = 'block';
        placeInput.setAttribute('required', 'required');
    } else {
        otherPlace.style.display = 'none';
        placeInput.removeAttribute('required');
        placeInput.value = '';
    }
}

function checkParty(value) {
    const otherParty = document.getElementById('other_party_input');
    const partyInput = document.getElementById('party_name_other');
    if (value === 'others') {
        otherParty.style.display = 'block';
        partyInput.setAttribute('required', 'required');
    } else {
        otherParty.style.display = 'none';
        partyInput.removeAttribute('required');
        partyInput.value = '';
    }
}
</script>

<?php include('../includes/footer.php'); ?>
