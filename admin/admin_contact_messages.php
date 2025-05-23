<?php
require_once '../config.php';
checkAdminAuth();

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get total records count
$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM contact_messages");
$totalRow = $totalQuery->fetch_assoc();
$total = (int)$totalRow['total'];
$total_pages = max(1, ceil($total / $limit));

// Fetch messages with pagination
$sql = "SELECT id, name, phone, role, subject, created_at FROM contact_messages ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Contact Messages - DNSC E-Request</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <style>
    body { background: #f8f9fa; }
    .sidebar {
            min-height: 100vh;
            background-color: #198754;
            color: white;
        }
    .nav-link {
      color: rgba(255,255,255,.8);
    }
    .nav-link:hover {
      color: white;
    }
    .nav-link.active {
      color: white;
      background-color: rgba(255,255,255,.2);
    }
    .dashboard-card {
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .btn-primary {
      background-color: #498428;
      border-color: #498428;
    }
    .btn-primary:hover {
      background-color: #2d5516;
      border-color: #2d5516;
    }
    .sidebar .nav-link {
      position: relative;
    }
    .badge-notification {
      position: absolute;
      top: 5px;
      right: 15px;
      background-color: red;
      color: white;
      font-size: 0.6rem;
      font-weight: 600;
      padding: 2px 6px;
      border-radius: 50%;
    }
    .message-card {
      background: #fff;
      border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      margin-bottom: 1rem;
      padding: 1rem 1.25rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .message-info {
      max-width: 80%;
    }
    .message-name {
      font-weight: 600;
      color: #198754;
      font-size: 1.1rem;
      margin-bottom: 0;
    }
    .message-date {
      font-size: 0.85rem;
      color: #6c757d;
    }
    .btn-primary {
      background-color: #198754;
      border-color: #198754;
    }
    .btn-primary:hover {
      background-color: #146c43;
      border-color: #146c43;
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
      <div class="position-sticky pt-3">
        <div class="text-center mb-4">
          <h5>DNSC E-Request System</h5>
          <p class="text-white">Admin Panel</p>
        </div>
        <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="requests.php">
                            <i class="fas fa-clipboard-list me-2"></i>
                            All Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pending.php">
                            <i class="fas fa-clock me-2"></i>
                            Pending Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="approved.php">
                            <i class="fas fa-check-circle me-2"></i>
                            Approved Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="completed.php">
                            <i class="fas fa-check-double me-2"></i>
                            Completed Requests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="registration_list.php">
                            <i class="fas fa-user-check me-2"></i> Registration List
                        </a>
                    </li>
                      <li class="nav-item">
                        <a class="nav-link  active" href="admin_contact_messages.php">
                            <i class="fas fa-envelope me-2"></i> Messages
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="create_announcement.php">
                            <i class="fas fa-bullhorn me-2"></i>
                            Create Announcement
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="manage_announcements.php">
                            <i class="fas fa-tools me-2"></i>
                            Announcement List
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_notifications.php">
                            <i class="fas fa-bell me-2"></i> Notifications
                            <?php
                            // Get notification count
                            $notifCountQuery = $conn->query("SELECT COUNT(*) as count FROM admin_notifications WHERE is_read = 0");
                            $notifCount = $notifCountQuery->fetch_assoc()['count'];
                            if($notifCount > 0): ?>
                                <span class="badge bg-danger rounded-pill position-absolute top-50 end-0 translate-middle-y me-3"><?php echo $notifCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item mt-5">
                        <a class="nav-link" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </a>
                    </li>          
                </ul>
      </div>
    </div>

    <!-- Main content -->
    <main class="col-md-10 ms-sm-auto px-md-4 py-4">
      <h2>Contact Messages</h2>
      <?php if (!$messages): ?>
        <div class="alert alert-info">No messages found.</div>
      <?php else: ?>
        <?php foreach ($messages as $msg): ?>
          <div class="message-card">
  <div class="message-info">
    <p class="message-name mb-1"><?= htmlspecialchars($msg['name']) ?></p>
    <small class="message-date"><?= date("F j, Y g:i A", strtotime($msg['created_at'])) ?></small>
  </div>
  <div>
    <button 
      class="btn btn-primary btn-sm me-2" 
      onclick="viewMessage(<?= $msg['id'] ?>)"
      data-bs-toggle="modal" 
      data-bs-target="#messageModal"
    >
      View
    </button>
   <button 
      class="btn btn-danger btn-sm" 
      onclick="openDeleteModal(<?= $msg['id'] ?>)"
      data-bs-toggle="modal" 
      data-bs-target="#deleteConfirmModal"
    >
      Delete
    </button>

  </div>
</div>
        <?php endforeach; ?>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
          <nav aria-label="Page navigation">
            <ul class="pagination">
              <?php for ($i=1; $i<=$total_pages; $i++): ?>
                <li class="page-item <?= ($page === $i) ? 'active' : '' ?>">
                  <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php endif; ?>
      <?php endif; ?>
    </main>
  </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="messageModalLabel"><i class="fas fa-envelope-open-text me-2"></i>Message Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>From:</strong> <span id="modalName"></span> (<span id="modalRole"></span>)</p>
        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
        <p id="modalPhoneContainer"><strong>Phone:</strong> <span id="modalPhone"></span></p>
        <p><strong>Received:</strong> <span id="modalDate"></span></p>
        <h5 id="modalSubject" class="text-success"></h5>
        <hr/>
        <p id="modalMessage"></p>
        <hr/>
        <div id="replySection" style="display:none;">
          <label for="replyText" class="form-label"><strong>Reply Message</strong></label>
          <textarea id="replyText" class="form-control" rows="4" placeholder="Type your reply here..."></textarea>
          <div class="mt-3 text-end">
            <button class="btn btn-success" onclick="sendReply()">Send Reply</button>
          </div>
        </div>
        <div id="replyStatus" class="mt-2"></div>
      </div>
    </div>
  </div>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
   <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this message?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  async function viewMessage(id) {
    // Clear previous reply status & textarea
    document.getElementById('replyStatus').textContent = '';
    document.getElementById('replyText').value = '';
    document.getElementById('replySection').style.display = 'none';

    // Fetch message details via AJAX
    try {
      const response = await fetch('fetch_contact_message.php?id=' + id);
      if (!response.ok) throw new Error('Network response was not ok');
      const data = await response.json();

      if (data.error) {
        alert(data.error);
        return;
      }

   
      // Populate modal fields
    document.getElementById('modalSubject').textContent = data.subject;
    document.getElementById('modalName').textContent = data.name;
    document.getElementById('modalRole').textContent = data.role;
    document.getElementById('modalEmail').textContent = data.email;
    document.getElementById('modalDate').textContent = new Date(data.created_at).toLocaleString();
    document.getElementById('modalMessage').textContent = data.message;

    // Conditionally show/hide phone
    if (data.phone && data.phone.trim() !== '') {
      document.getElementById('modalPhone').textContent = data.phone;
      document.getElementById('modalPhoneContainer').style.display = 'block';
    } else {
      document.getElementById('modalPhoneContainer').style.display = 'none';
    }

    // Show reply box only if role is student or alumni
    if (data.role === 'student' || data.role === 'alumni') {
      document.getElementById('replySection').style.display = 'block';
      document.getElementById('replySection').dataset.messageId = id;
    } else {
      document.getElementById('replySection').style.display = 'none';
    }

    } catch (err) {
      alert('Failed to load message details.');
      console.error(err);
    }
  }

  async function sendReply() {
    const replyText = document.getElementById('replyText').value.trim();
    const messageId = document.getElementById('replySection').dataset.messageId;

    if (!replyText) {
      alert('Reply message cannot be empty.');
      return;
    }

    try {
      const formData = new FormData();
      formData.append('message_id', messageId);
      formData.append('reply_message', replyText);

    const response = await fetch('send_contact_reply.php', {
  method: 'POST',
  headers: {
    'X-Requested-With': 'XMLHttpRequest'
  },
  body: formData
});



      const result = await response.json();

      if (result.success) {
        document.getElementById('replyStatus').textContent = 'Reply sent successfully!';
        document.getElementById('replyStatus').className = 'text-success';
        document.getElementById('replyText').value = '';
      } else {
        const errMsg = result.error || 'Failed to send reply.';
  document.getElementById('replyStatus').textContent = errMsg;
  document.getElementById('replyStatus').className = 'text-danger';
      }
    } catch (err) {
      document.getElementById('replyStatus').textContent = 'Error sending reply.';
      document.getElementById('replyStatus').className = 'text-danger';
      console.error(err);
    }
  }
</script>
<script>
  let deleteMessageId = null;

  function openDeleteModal(id) {
    deleteMessageId = id;
  }

  document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
    if (!deleteMessageId) return;

    try {
      const response = await fetch('delete_contact_message.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: `id=${encodeURIComponent(deleteMessageId)}`
      });

      const result = await response.json();
      if (result.success) {
        // Optionally close the modal
        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
        deleteModal.hide();

        // Refresh the page or remove the deleted card dynamically
        location.reload();
      } else {
        alert(result.error || 'Failed to delete message.');
      }
    } catch (err) {
      alert('An error occurred while deleting the message.');
      console.error(err);
    }
  });
</script>


</body>
</html>
