<?php
require_once '../config.php';
checkAdminAuth();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Invalid message ID']);
    exit;
}

$id = (int)$_GET['id'];

$sql = "SELECT id, name, email, phone, role, subject, message, created_at FROM contact_messages WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$msg = $result->fetch_assoc();
$stmt->close();

if (!$msg) {
    echo json_encode(['error' => 'Message not found']);
    exit;
}

// Output raw data as JSON; escaping will be done on front-end
echo json_encode($msg);
exit;
