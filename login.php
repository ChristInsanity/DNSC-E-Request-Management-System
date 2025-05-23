<?php
require_once 'config.php';

// Check if already logged in
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('admin/dashboard.php');
    } elseif (isStudent()) {
        redirect('student/dashboard.php');
    } elseif (isAlumni()) {
        redirect('alumni/dashboard.php');
    } else {
        redirect('select_role.php'); // Handle null or unknown roles
    }
}

$error = '';

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stud_id = sanitize($_POST['stud_id']);
    $password = $_POST['password'];

    $sql = "SELECT id, stud_id, password, role, full_name, verification_status FROM users WHERE stud_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $stud_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($user['verification_status'] === 'pending') {
            $error = 'Your registration is still pending approval.';
        } elseif ($user['verification_status'] === 'rejected') {
            $error = 'Your registration was rejected. Please contact the registrar.';
        } elseif (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['stud_id'] = $user['stud_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                redirect('admin/dashboard.php');
            } elseif ($user['role'] === 'student') {
                redirect('student/dashboard.php');
            } elseif ($user['role'] === 'alumni') {
                redirect('alumni/dashboard.php');
            } else {
                redirect('select_role.php'); // Redirect if role is NULL or unknown
            }
        } else {
            $error = 'Invalid password';
        }
    } else {
        $error = 'Student ID not found';
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DNSC E-Request Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
            padding: 15px 0;
            transition: all 0.4s ease;
            background: linear-gradient(135deg, #2d5516 20%, #388e3c 100%) !important;
        }
        
        .navbar.scrolled {
            padding: 8px 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            background: #2d5516 !important;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        
        .navbar-brand:hover {
            transform: translateY(-2px);
        }
        
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
            filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.2));
        }
        
        .nav-item {
            margin: 0 5px;
            position: relative;
        }
        
        .nav-link {
            font-weight: 500;
            padding: 10px 15px !important;
            border-radius: 6px;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            color: white !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background-color: #fff;
            bottom: 5px;
            left: 15px;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after, .nav-link.active::after {
            width: calc(100% - 30px);
        }
        .navbar .btn {
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .navbar .btn-success {
            background-color: #498428;
            border-color: #498428;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        
        .navbar .btn-success:hover {
            background-color: #549a2d;
            border-color: #549a2d;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
            transform: translateY(-2px);
        }
        
        .navbar .btn-outline-light {
            border-width: 2px;
        }
        
        .navbar .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        /* Mobile nav toggler */
        .navbar-toggler {
            border: none;
            padding: 10px;
            margin-right: 5px;
            position: relative;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* For mobile */
        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: #2d5516;
                padding: 15px;
                border-radius: 10px;
                margin-top: 10px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            }
            
            .nav-link::after {
                display: none;
            }
            
            .navbar .btn {
                margin-top: 10px;
                display: block;
                width: 100%;
            }
        }
        
        :root {
            --primary: #2d5516;
            --secondary: #C1D95C;
            --tertiary: #498428;
        }
        body {
            background: linear-gradient(to right, #C1D95C, #498428);      
        }
        .login-container {
            max-width: 550px;
            margin: 150px auto;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color:#2d5516;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .btn-primary {
            width: 100%;
            background-color: #2d5516;
            border-color: #2d5516;
        }
        .btn-primary:hover {
            background-color: #2d5516;
            border-color: #2d5516;
        }
        a {
            color: #2d5516;
        }
        a:hover {
            color: #2d5516;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/index.php">
                <img src="assets/img/dnsc-logo.png" alt="DNSC Logo" class="d-inline-block align-text-top">
                DNSC E-Request
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact.php">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php#team">Our Team</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container login-container">
        <div class="card">
            <div class="card-header text-center py-3">
                <h4>DNSC E-Request Management System</h4>
            </div>
            <div class="card-body p-4">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form id="loginForm" method="POST" action="">

<!-- wala pa na implement
<div class="mb-3">
                <label for="Account-type" class="form-label">Account Type</label>
                <select class="form-select" id="Account-type" name="account-type" required>
                    <option value="">-- Select Role --</option>
                    <option value="students">Student</option>
                    <option value="alumni">Alumni</option>
                </select>
            </div>

            //Students Input
            <div class="mb-3" id="studentsInputWrapper" style="display: none;">
                <label for="students-role" class="form-label">Identification Number:</label>
                <input type="text" class="form-control" id="students-role" name="students-role" placeholder="Enter your Student ID" required>
            </div>
            // Alumni Input
            <div class="mb-3" id="alumniInputWrapper" style="display: none;">
                <label for="alumni-role" class="form-label">Identification Number:</label>
                <input type="text" class="form-control" id="alumni-role" name="alumni-role" placeholder="Enter your Alumni ID" required>
            </div>

            <script>
                const roleSelect = document.getElementById('Account-type');
                const inputSections = {
                    students: document.getElementById('studentsInputWrapper'),
                    alumni: document.getElementById('alumniInputWrapper'),
                };
                roleSelect.addEventListener('change', function () {
                    const selected = this.value;
                    for (const key in inputSections) {
                        inputSections[key].style.display = (key === selected) ? 'block' : 'none';
                    }
                });
            </script>
wala pa na implement -->

                    <div class="mb-3">
                        <label for="stud_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="stud_id" name="stud_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
                <div class="text-center">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
