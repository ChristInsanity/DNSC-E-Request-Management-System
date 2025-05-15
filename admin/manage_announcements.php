<?php
require_once '../config.php';
checkAdminAuth();

$result = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-badge {
            font-size: 0.9em;
            padding: 4px 8px;
            border-radius: 6px;
        }
        .status-active {
            background-color: #198754;
            color: white;
        }
        .status-expired {
            background-color: #dc3545;
            color: white;
        }
        .status-upcoming {
            background-color: #ffc107;
            color: black;
        }
        img.thumb {
            height: 60px;
            width: auto;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Manage Announcements</h4>
        <a href="create_announcement.php" class="btn btn-success">+ New Announcement</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-success">
                <tr>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Dates</th>
                    <th>Photo</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $today = date('Y-m-d');
            while ($row = $result->fetch_assoc()):
                $status = '';
                if ($today < $row['start_date']) {
                    $status = '<span class="status-badge status-upcoming">Upcoming</span>';
                } elseif ($today > $row['end_date']) {
                    $status = '<span class="status-badge status-expired">Expired</span>';
                } else {
                    $status = '<span class="status-badge status-active">Active</span>';
                }
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['body'])); ?></td>
                    <td>
                        <strong>From:</strong> <?php echo $row['start_date']; ?><br>
                        <strong>To:</strong> <?php echo $row['end_date']; ?>
                    </td>
                   <td>
                        <?php if (!empty($row['photo']) && file_exists('../' . $row['photo'])): ?>
                            <img src="<?= '../' . htmlspecialchars($row['photo']); ?>" class="thumb" alt="Announcement Photo">
                        <?php else: ?>
                            <span class="text-muted">None</span>
                        <?php endif; ?>
                    </td>

                    <td><?= $status ?></td>
                    <td>
                        <a href="edit_announcement.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="delete_announcement.php?id=<?= htmlspecialchars($row['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
