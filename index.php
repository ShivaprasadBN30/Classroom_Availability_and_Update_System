<?php
session_start();
include("config/db.php");

/* -------- Time format helper (12-hour) -------- */
function formatTime12($time) {
    return date("h:i A", strtotime($time));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>UVCE Classroom Availability</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <script src="assets/js/auto_refresh.js"></script>
</head>
<body>

<h1>University Visvesvaraya College of Engineering</h1>
<h2>Department of Computer Science and Engineering</h2>

<!-- ================= SUCCESS MESSAGE ================= -->
<?php
if (isset($_SESSION['success_message'])) {
    echo "<div class='success-msg'>".$_SESSION['success_message']."</div>";
    unset($_SESSION['success_message']);
}
?>

<!-- ================= CR DASHBOARD LINK (CR ONLY) ================= -->
<?php
if (
    isset($_SESSION['user_id']) &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'CR'
) {
?>
    <p style="text-align:center; margin:15px;">
        <a href="dashboard.php" style="font-weight:600;">
            Go to CR Dashboard
        </a>
    </p>
<?php } ?>

<!-- ================= CLASSROOM CARDS ================= -->
<div class="card-container">

<?php
$classrooms = mysqli_query($conn, "
    SELECT room_id, room_number, status
    FROM classrooms
    ORDER BY room_number
");

while ($room = mysqli_fetch_assoc($classrooms)) {

    $room_id = $room['room_id'];
    $status  = $room['status'];
    $statusClass = strtolower($status);

    // Defaults
    $subject = null;
    $start_time = null;
    $end_time = null;
    $cr_name = null;

    /* -------- Fetch schedule only if occupied -------- */
    if ($status === 'Occupied') {
        $schedule = mysqli_query($conn, "
            SELECT s.subject, s.start_time, s.end_time, u.name AS cr_name
            FROM class_schedule s
            JOIN users u ON s.updated_by = u.user_id
            WHERE s.room_id = '$room_id'
            ORDER BY s.schedule_id DESC
            LIMIT 1
        ");

        if ($row = mysqli_fetch_assoc($schedule)) {
            $subject    = $row['subject'];
            $start_time = $row['start_time'];
            $end_time   = $row['end_time'];
            $cr_name    = $row['cr_name'];
        }
    }
?>

    <div class="card <?php echo $statusClass; ?>">
        <div class="card-header">
            <h3><?php echo $room['room_number']; ?></h3>
            <span class="status-badge <?php echo $statusClass; ?>">
                <?php echo $status; ?>
            </span>
        </div>

        <div class="card-body">
            <?php if ($status === 'Occupied') { ?>
                <p><strong>Subject:</strong> <?php echo $subject; ?></p>
                <p><strong>Time:</strong>
                    <?php
                        echo formatTime12($start_time) . " - " . formatTime12($end_time);
                    ?>
                </p>
                <p><strong>Updated by:</strong> <?php echo $cr_name; ?></p>
            <?php } else { ?>
                <p><strong>Subject:</strong> —</p>
                <p><strong>Time:</strong> —</p>
            <?php } ?>
        </div>
    </div>

<?php } ?>

</div>

</body>
</html>
