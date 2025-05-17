<?php
require_once '../config.php';
checkAlumniAuth();

$user_id = $_SESSION['user_id'];
$last_check = $_SESSION['last_notification_check'] ?? 0;

// Update the last check time
$_SESSION['last_notification_check'] = time();

// Check for new notifications since last check
$stmt = $conn->prepare("
    SELECT COUNT(*) as count 
    FROM notifications 
    WHERE user_id = ? 
    AND is_read = 0 
    AND UNIX_TIMESTAMP(created_at) > ?
");
$stmt->bind_param("ii", $user_id, $last_check);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['new_notifications' => ($data['count'] > 0)]);
?>
