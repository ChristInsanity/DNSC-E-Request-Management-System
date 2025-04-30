<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/dashboard.php');
        exit;
    } else {
        header('Location: student/dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNSC E-Request Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: white;
            padding: 80px 0;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .hero-title {
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .feature-card {
            border-radius: 10px;
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: #198754;
            margin-bottom: 15px;
        }
        
        .login-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }
        
        .btn-success:hover {
            background-color: #146c43;
            border-color: #146c43;
        }
        
        .btn-outline-success {
            color: #198754;
            border-color: #198754;
        }
        
        .btn-outline-success:hover {
            background-color: #198754;
            border-color: #198754;
        }
        
        .footer {
            background-color: #343a40;
            color: rgba(255, 255, 255, 0.7);
            padding: 20px 0;
            margin-top: 80px;
        }
        
        .team-section {
            padding: 60px 0;
            background-color: #f8f9fa;
        }
        
        .team-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            height: 100%;
        }
        
        .team-card:hover {
            transform: translateY(-5px);
        }
        
        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 200px;
            object-fit: cover;
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 50px 0;
            }
            
            .hero-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-file-alt me-2"></i>
                DNSC E-Request
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#team">Our Team</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-success" href="login.php">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-light" href="register.php">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">DNSC E-Request Management System</h1>
                    <p class="lead mb-4">A streamlined solution for processing registrar-related documents and certificates for students and graduates.</p>
                    <div class="d-flex gap-3">
                        <a href="login.php" class="btn btn-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="register.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="" alt="E-Request System" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">System Features</h2>
                <p class="text-muted">Efficient document processing and tracking at your fingertips</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card p-4">
                        <div class="text-center">
                            <i class="fas fa-file-alt feature-icon"></i>
                            <h4>Online Request Submission</h4>
                            <p class="text-muted">Submit requests for certificates, transcripts, and other documents online without visiting the office.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card p-4">
                        <div class="text-center">
                            <i class="fas fa-clock feature-icon"></i>
                            <h4>Real-time Status Tracking</h4>
                            <p class="text-muted">Track the status of your requests in real-time, from submission to approval and completion.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card p-4">
                        <div class="text-center">
                            <i class="fas fa-bell feature-icon"></i>
                            <h4>Notifications</h4>
                            <p class="text-muted">Receive notifications about request status updates, approvals, or rejections.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card p-4">
                        <div class="text-center">
                            <i class="fas fa-history feature-icon"></i>
                            <h4>Transaction History</h4>
                            <p class="text-muted">Access a complete history of all your past and ongoing document requests.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card p-4">
                        <div class="text-center">
                            <i class="fas fa-calendar-check feature-icon"></i>
                            <h4>Scheduled Pickup</h4>
                            <p class="text-muted">Get assigned pickup dates and times for collecting your requested documents.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card p-4">
                        <div class="text-center">
                            <i class="fas fa-shield-alt feature-icon"></i>
                            <h4>Secure Access</h4>
                            <p class="text-muted">Role-based secure access for students, graduates, and registrar staff.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5 bg-light" id="how-it-works">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">How It Works</h2>
                <p class="text-muted">Simple steps to request and receive your documents</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-4">
                                <div class="bg-success text-white rounded-circle p-3 me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <h4 class="m-0">1</h4>
                                </div>
                                <div>
                                    <h4>Login to your account</h4>
                                    <p class="text-muted">Access the system using your student credentials. If you don't have an account, contact the registrar's office.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-4">
                                <div class="bg-success text-white rounded-circle p-3 me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <h4 class="m-0">2</h4>
                                </div>
                                <div>
                                    <h4>Select the document type</h4>
                                    <p class="text-muted">Choose from various document types such as Certificate of Enrollment, Certificate of Ratings, Academic Transcripts, etc.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-4">
                                <div class="bg-success text-white rounded-circle p-3 me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <h4 class="m-0">3</h4>
                                </div>
                                <div>
                                    <h4>Submit your request</h4>
                                    <p class="text-muted">Fill in the required information and submit your request for processing.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-4">
                                <div class="bg-success text-white rounded-circle p-3 me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <h4 class="m-0">4</h4>
                                </div>
                                <div>
                                    <h4>Pay at the cashier</h4>
                                    <p class="text-muted">Visit the cashier to make the payment for your requested documents.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex">
                                <div class="bg-success text-white rounded-circle p-3 me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <h4 class="m-0">5</h4>
                                </div>
                                <div>
                                    <h4>Collect your documents</h4>
                                    <p class="text-muted">Once your request is processed, you'll receive a notification with a pickup date and time. Visit the registrar's office to collect your documents.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-5" id="team">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Meet Our Team</h2>
                <p class="text-muted">The Team behind the DNSC E-Request System</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card team-card">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle overflow-hidden mx-auto mb-3" style="width: 120px; height: 120px; background-color: #e9ecef;">
                                <img src="assets/img/Duran.jpg" alt="Christian Duran" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <h5 class="card-title">Christian Duran</h5>
                            <p class="card-text text-muted">Project Leader</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card team-card">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle overflow-hidden mx-auto mb-3" style="width: 120px; height: 120px; background-color: #e9ecef;">
                                <img src="assets/img/ako.jpg" alt="John Lyold C. Lozada" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <h5 class="card-title">John Lyold C. Lozada</h5>
                            <p class="card-text text-muted">Backend Developer</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card team-card">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle overflow-hidden mx-auto mb-3" style="width: 120px; height: 120px; background-color: #e9ecef;">
                                <img src="assets/img/arjean1.jpg" alt="Arjean G. Logrosa" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <h5 class="card-title">Arjean G. Logrosa</h5>
                            <p class="card-text text-muted">Frontend Developer</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card team-card">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle overflow-hidden mx-auto mb-3" style="width: 120px; height: 120px; background-color: #e9ecef;">
                                <img src="assets/img/Kent.jpg" alt="Stephanie Kate O. Losabia" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <h5 class="card-title">Stephanie Kate O. Losabia</h5>
                            <p class="card-text text-muted">UI/UX Designer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Section - Restored and updated -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card login-card p-4">
                        <div class="row g-0">
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="p-4">
                                    <h3 class="fw-bold mb-4">Ready to get started?</h3>
                                    <p class="text-muted mb-4">Access your account to submit and track your document requests. The DNSC E-Request System is designed to make your registrar transactions faster and more efficient.</p>
                                    <div class="d-grid gap-2">
                                        <a href="login.php" class="btn btn-success btn-lg">
                                            <i class="fas fa-sign-in-alt me-2"></i>Login to Your Account
                                        </a>
                                        <p class="text-center mt-3 mb-0">Don't have an account?</p>
                                        <a href="register.php" class="btn btn-outline-success">
                                            <i class="fas fa-user-plus me-2"></i>Register Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-none d-md-block">
                                <img src="https://img.freepik.com/free-vector/mobile-login-concept-illustration_114360-83.jpg?w=826&t=st=1716211640~exp=1716212240~hmac=f3a31df2af851f58d8c0ed9f87b06f44eb0e9c5badd65a062d9a75fdef8c2ed0" alt="Login" class="img-fluid rounded-end" style="height: 100%; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h5 class="text-white">DNSC E-Request Management System</h5>
                    <p>A web-based platform designed to streamline registrar-related transactions for students and graduates.</p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-white">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#features" class="text-white-50">Features</a></li>
                        <li class="mb-2"><a href="#how-it-works" class="text-white-50">How It Works</a></li>
                        <li class="mb-2"><a href="#team" class="text-white-50">Our Team</a></li>
                        <li class="mb-2"><a href="login.php" class="text-white-50">Login</a></li>
                        <li><a href="register.php" class="text-white-50">Register</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-white">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Davao del Norte State College</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i>+63 951 229 7022</li>
                        <li><i class="fas fa-envelope me-2"></i>supplybridge@dnsc.edu.ph</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> DNSC E-Request Management System. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="#" class="text-white-50"><i class="fab fa-facebook fa-lg"></i></a></li>
                        <li class="list-inline-item"><a href="#" class="text-white-50"><i class="fab fa-twitter fa-lg"></i></a></li>
                        <li class="list-inline-item"><a href="#" class="text-white-50"><i class="fab fa-instagram fa-lg"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
