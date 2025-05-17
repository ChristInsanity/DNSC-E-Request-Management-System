<?php
require_once '../config.php';
checkStudentAuth();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $announcement_id = (int) $_GET['id'];
    $user_id = $_SESSION['user_id'];
    
    // Check if view already exists
    $stmt = $conn->prepare("SELECT id FROM announcement_views WHERE user_id = ? AND announcement_id = ?");
    $stmt->bind_param("ii", $user_id, $announcement_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        // Insert new view record
        $stmt = $conn->prepare("INSERT INTO announcement_views (user_id, announcement_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $announcement_id);
        $stmt->execute();
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => true, 'already_viewed' => true]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid announcement ID']);
}
?>
