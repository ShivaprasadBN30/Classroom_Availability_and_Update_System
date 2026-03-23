<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'CR') {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$action  = $_POST['action'];

/* ================= OCCUPY CLASSROOM ================= */
if ($action === 'occupy') {

    $room_id    = $_POST['room_id'];
    $subject    = $_POST['subject'];
    $start_time = $_POST['start_time'];
    $end_time   = $_POST['end_time'];

    /* Insert schedule */
    mysqli_query($conn, "
        INSERT INTO class_schedule
        (room_id, subject, start_time, end_time, updated_by)
        VALUES
        ('$room_id', '$subject', '$start_time', '$end_time', '$user_id')
    ");

    /* Mark room occupied */
    mysqli_query($conn, "
        UPDATE classrooms
        SET status='Occupied'
        WHERE room_id='$room_id'
    ");

    $_SESSION['success_message'] = "Classroom marked as Occupied";
}

/* ================= FREE CLASSROOM ================= */
if ($action === 'free') {

    $room_id = $_POST['room_id'];

    /* Only allow CR to free their own room */
    $check = mysqli_query($conn, "
        SELECT * FROM class_schedule
        WHERE room_id='$room_id'
        AND updated_by='$user_id'
        ORDER BY schedule_id DESC
        LIMIT 1
    ");

    if (mysqli_num_rows($check) === 1) {
        mysqli_query($conn, "
            UPDATE classrooms
            SET status='Free'
            WHERE room_id='$room_id'
        ");

        $_SESSION['success_message'] = "Classroom marked as Free";
    }
}

header("Location: ../index.php");
exit();
?>
