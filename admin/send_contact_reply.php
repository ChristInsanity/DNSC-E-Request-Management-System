<?php
require_once '../config.php';
checkAdminAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id'], $_POST['reply_message'])) {
    $message_id = (int)$_POST['message_id'];
    $reply_message = trim($_POST['reply_message']);

    if ($reply_message === '') {
        echo json_encode(['success' => false, 'error' => 'Reply message cannot be empty.']);
        exit;
    }

    // Get original message details
    $stmt = $conn->prepare("SELECT user_id, role, subject, message, created_at FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $msg = $result->fetch_assoc();
    $stmt->close();

    if (!$msg) {
        echo json_encode(['success' => false, 'error' => 'Message not found.']);
        exit;
    }

    $user_id = $msg['user_id'];
    $role = $msg['role'];
    $subject = $msg['subject'];
    $original_message = $msg['message'];
    $submitted_at = date("F j, Y g:i A", strtotime($msg['created_at']));
    $replied_at = date("F j, Y g:i A");

    if (!in_array($role, ['student', 'alumni'])) {
        echo json_encode(['success' => false, 'error' => 'Cannot send reply to this role.']);
        exit;
    }

    // Create formatted notification message
         $notification_message = <<<EOD
Reply from Admin

------------------------------
Your Message:
{$subject}

------------------------------
Admin Response:
{$reply_message}
------------------------------
EOD;




    // Insert into notifications table
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, is_read, created_at) VALUES (?, ?, 0, NOW())");
    $stmt->bind_param("is", $user_id, $notification_message);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save notification.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
?>
