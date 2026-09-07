<?php
include('db.php');
include('session_config.php');
session_start(); //

$status = null; // ['type' => 'success'|'error', 'message' => '...', 'redirect' => '...', 'title' => '...']

// 1. Check if the user is logged in
if(!isset($_SESSION['user_id'])){
    $status = ['type' => 'error', 'message' => 'You need to log in first.', 'title' => 'Login required', 'redirect' => 'login.php'];
}

if($status === null && isset($_POST['delete_btn'])){
    $id = (int)$_POST['id']; // Cast to integer for security
    $input_key = isset($_POST['key']) ? mysqli_real_escape_string($conn, $_POST['key']) : null;
    $current_user = $_SESSION['user_id'];

    // 2. Find the record AND ensure it belongs to the logged-in user
    $query = mysqli_query($conn, "SELECT image, delete_key FROM houses WHERE id = $id AND user_id = $current_user");
    
    if(mysqli_num_rows($query) > 0){
        $data = mysqli_fetch_assoc($query);

        // If a key was submitted, verify it; otherwise allow owner deletion
        $allow_delete = false;
        if($input_key !== null && $input_key !== ''){
            if($data['delete_key'] === $input_key){
                $allow_delete = true;
            }
        } else {
            // No key submitted — allow owner deletion (since ownership was checked in query)
            $allow_delete = true;
        }

        if($allow_delete){
            // Delete the physical image file
            if(!empty($data['image']) && file_exists("uploads/" . $data['image'])){
                unlink("uploads/" . $data['image']);
            }
            
            // Delete from database
            mysqli_query($conn, "DELETE FROM houses WHERE id = $id");
            $status = ['type' => 'success', 'message' => 'Your listing was removed successfully.', 'title' => 'Listing deleted', 'redirect' => 'index.php'];
        } else {
            $status = ['type' => 'error', 'message' => 'The secret key you entered is incorrect.', 'title' => 'Incorrect key', 'redirect' => 'index.php'];
        }
    } else {
        // This triggers if the ID doesn't exist OR it belongs to a different landlord
        $status = ['type' => 'error', 'message' => 'You can only delete your own posts.', 'title' => 'Unauthorized', 'redirect' => 'index.php'];
    }
}
?>
<?php if($status !== null): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdamaRent</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body style="margin:0;background:#f8fafc;font-family:'Inter',sans-serif">
    <?php include(__DIR__ . '/popup.php'); ?>
    <script>
        window.addEventListener('DOMContentLoaded', function(){
            showToast(<?php echo json_encode($status['message']); ?>, <?php echo json_encode($status['type']); ?>, <?php echo json_encode($status['title']); ?>);
            setTimeout(function(){ window.location = <?php echo json_encode($status['redirect']); ?>; }, 1800);
        });
    </script>
</body>
</html>
<?php endif; ?>