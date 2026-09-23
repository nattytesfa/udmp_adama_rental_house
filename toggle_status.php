<?php
include('includes/session_config.php');
session_start();
include('includes/db.php');
include('includes/security.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$me = (int)$_SESSION['user_id'];
$house_id = (int)($_POST['id'] ?? 0);

if($house_id <= 0){
    header("Location: manage_houses.php");
    exit();
}

csrf_validate();

// Load the house, ensuring it belongs to the current user
$stmt = mysqli_prepare($conn, "SELECT status, is_approved FROM houses WHERE id=? AND user_id=?");
mysqli_stmt_bind_param($stmt, "ii", $house_id, $me);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if(!$row){
    header("Location: manage_houses.php");
    exit();
}

$current = $row['status'];

// Keep this a strict Available <-> Rented toggle. Pending/Rejected listings
// cannot be manually marked available; they must pass through admin approval.
if($current === 'Available'){
    $new_status = 'Rented';
} elseif(strcasecmp($current, 'Rented') === 0){
    $new_status = 'Available';
} else {
    header("Location: manage_houses.php");
    exit();
}

if($new_status === 'Available'){
    // Close any accepted rental agreement for this house so it can be re-listed cleanly
    $stmt2 = mysqli_prepare($conn, "SELECT rr.id, rr.user_id, h.kebele
                                   FROM rental_requests rr
                                   JOIN houses h ON rr.house_id = h.id
                                   WHERE rr.house_id=? AND rr.status='accepted'");
    mysqli_stmt_bind_param($stmt2, "i", $house_id);
    mysqli_stmt_execute($stmt2);
    $active = mysqli_stmt_get_result($stmt2);
    if($active){
        while($a = mysqli_fetch_assoc($active)){
            $tenant_id = (int)$a['user_id'];
            $rr_id = (int)$a['id'];
            $stmt3 = mysqli_prepare($conn, "UPDATE rental_requests SET status='completed' WHERE id=?");
            mysqli_stmt_bind_param($stmt3, "i", $rr_id);
            mysqli_stmt_execute($stmt3);
            $notif_msg = "The property in Kebele {$a['kebele']} has been marked available again by the owner.";
            $stmt4 = mysqli_prepare($conn, "INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, 'info', 'Rental ended', ?, 'index.php')");
            mysqli_stmt_bind_param($stmt4, "is", $tenant_id, $notif_msg);
            mysqli_stmt_execute($stmt4);
        }
    }
    $stmt5 = mysqli_prepare($conn, "UPDATE houses SET status='Available' WHERE id=? AND user_id=?");
    mysqli_stmt_bind_param($stmt5, "ii", $house_id, $me);
    mysqli_stmt_execute($stmt5);
} else {
    $stmt5 = mysqli_prepare($conn, "UPDATE houses SET status='Rented' WHERE id=? AND user_id=?");
    mysqli_stmt_bind_param($stmt5, "ii", $house_id, $me);
    mysqli_stmt_execute($stmt5);
}

header("Location: manage_houses.php?msg=status_saved");
exit();