<?php
session_start();
include("config/db.php");

/* ---------- AUTH CHECK ---------- */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'CR') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ---------- CHECK IF CR ALREADY OCCUPIED A ROOM ---------- */
$occupiedCheck = mysqli_query($conn, "
    SELECT c.room_id, c.room_number
    FROM classrooms c
    JOIN class_schedule s ON c.room_id = s.room_id
    WHERE c.status = 'Occupied'
    AND s.updated_by = '$user_id'
    ORDER BY s.schedule_id DESC
    LIMIT 1
");

$hasOccupiedRoom = (mysqli_num_rows($occupiedCheck) === 1);
$myRoom = $hasOccupiedRoom ? mysqli_fetch_assoc($occupiedCheck) : null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>CR Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css?v=10">
</head>
<body>

<h1 class="dashboard-title">CR Dashboard</h1>
<p class="dashboard-subtitle">Manage Classroom Availability</p>

<!-- ================= OCCUPY CLASSROOM ================= -->
<?php if (!$hasOccupiedRoom) { ?>

<div class="dashboard-container">
<div class="dashboard-card">

<h2>Occupy a Free Classroom</h2>

<form action="actions/update_class.php" method="POST">

    <label>Select Free Classroom</label>
    <select name="room_id" required>
        <option value="">-- Select Classroom --</option>
        <?php
        $freeRooms = mysqli_query($conn, "
            SELECT room_id, room_number
            FROM classrooms
            WHERE status='Free'
            ORDER BY room_number
        ");
        while ($room = mysqli_fetch_assoc($freeRooms)) {
            echo "<option value='{$room['room_id']}'>{$room['room_number']}</option>";
        }
        ?>
    </select>

    <label>Subject</label>
    <input type="text" name="subject" required>

    <label>Start Time</label>
    <input type="time" name="start_time" required>

    <label>End Time</label>
    <input type="time" name="end_time" required>

    <button type="submit" name="action" value="occupy">
        Mark Occupied
    </button>

</form>

</div>
</div>

<?php } ?>

<!-- ================= MY OCCUPIED CLASSROOM ================= -->
<?php if ($hasOccupiedRoom) { ?>

<div class="dashboard-container">
<div class="dashboard-card">

<h2>My Occupied Classroom</h2>

<p style="font-size:16px;">
    <strong>Room:</strong> <?php echo $myRoom['room_number']; ?>
</p>

<form action="actions/update_class.php" method="POST">
    <input type="hidden" name="room_id" value="<?php echo $myRoom['room_id']; ?>">
    <button type="submit" name="action" value="free" style="background:#dc3545;">
        Mark Free
    </button>
</form>

</div>
</div>

<?php } ?>

<p style="text-align:center; margin-top:30px;">
    <a href="logout.php">Logout</a>
</p>

</body>
</html>
