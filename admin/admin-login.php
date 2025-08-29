<?php
// Start session
session_start();

// Include database connection (for other functions, though login is static now)
require_once 'db_connection.php';

// Check if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle login form submission with static credentials
$login_error = '';
if (isset($_POST['login'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $login_error = "Invalid CSRF token!";
        file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Invalid CSRF token attempt\n", FILE_APPEND);
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Static credentials for testing
        $static_email = 'admin@gmail.com';
        $static_password = 'shubh123';
        $static_user = ['id' => 1, 'username' => 'Admin User', 'email' => $static_email];

        file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Login attempt: email=$email, password=$password\n", FILE_APPEND);

        if ($email === $static_email && $password === $static_password) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $static_user['id'];
            $_SESSION['admin_name'] = $static_user['username'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: index.php");
            exit;
        } else {
            $login_error = "Invalid email or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Home Décor</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --dark-color: #2d3436;
            --light-color: #f5f6fa;
            --success-color: #00b894;
            --danger-color: #d63031;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            position: relative;
            animation: fadeInUp 0.6s ease-out;
        }

        .login-header {
            background: var(--primary-color);
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 4px solid var(--secondary-color);
        }

        .login-header h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .login-header p {
            margin: 5px 0 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .login-body {
            padding: 30px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(108, 92, 231, 0.3);
        }

        .form-label {
            font-weight: 500;
            color: var(--dark-color);
        }

        .btn-login {
            background: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-login:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .login-footer {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            font-size: 0.85rem;
            color: var(--dark-color);
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }
            .login-card {
                border-radius: 10px;
            }
            .login-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card animate__animated animate__fadeInUp">
            <div class="login-header">
                <h2>Home Décor</h2>
                <p>Admin Portal - Secure Access</p>
            </div>
            <div class="login-body">
                <?php if ($login_error): ?>
                    <div class="alert alert-danger animate__animated animate__shakeX"><?php echo htmlspecialchars($login_error); ?></div>
                <?php endif; ?>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="admin@gmail.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-login">
                        <i class="fas fa-lock me-2"></i> Sign In
                    </button>
                </form>
            </div>
            <div class="login-footer">
                <p>Restricted Access - Authorized Personnel Only</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>