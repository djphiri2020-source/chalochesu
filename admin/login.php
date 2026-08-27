<?php
session_start();

// Regenerate session ID on each request to help prevent session hijacking
if (isset($_SESSION['last_regeneration'])) {
    if (time() - $_SESSION['last_regeneration'] > 900) { // Regenerate every 15 minutes
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
} else {
    $_SESSION['last_regeneration'] = time();
}

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

// We need the database connection and site constants
require_once '../config/database.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_message = 'Invalid form submission. Please try again.';
    } else {
        // CSRF token is valid, we can unset it now to prevent reuse.
        unset($_SESSION['csrf_token']);

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Fetch user from the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verify user exists and password is correct
        if ($user && password_verify($password, $user['password_hash'])) {
            // Prevent session fixation
            session_regenerate_id(true);

            // Store user information in the session
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['last_regeneration'] = time();

            header('Location: dashboard.php');
            exit;
        } else {
            $error_message = 'Invalid username or password.';
        }
    }
}

// Generate a new CSRF token for the next request.
// This should be done after processing the POST request to avoid overwriting the token before validation.
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo defined('SITE_NAME') ? SITE_NAME : 'Admin'; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Redesigned Login Page Styles */
        :root {
            /* Define colors needed for this page, normally set in header.php */
            --primary-green: <?php echo defined('SECONDARY_COLOR') ? SECONDARY_COLOR : '#5FBC6E'; ?>;
            --medium-green: <?php echo defined('PRIMARY_COLOR') ? PRIMARY_COLOR : '#344C3F'; ?>;
            --primary-dark: <?php echo defined('TEXT_DARK') ? TEXT_DARK : '#333'; ?>;
        }

        body {
            background-color: var(--light-gray, #f8f9fa);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
        }
        .login-container {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            width: 100%;
            max-width: 900px;
            min-height: 550px;
            background: var(--white, #fff);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            overflow: hidden;
        }
        .login-branding {
            background: var(--medium-green, #344C3F);
            color: var(--white, #fff);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            text-align: center;
        }
        .login-logo {
            width: 120px;
            margin-bottom: 1.5rem;
        }
        .login-branding h2 {
            font-size: 1.5rem;
            margin: 0;
        }
        .login-branding p {
            font-size: 1rem;
            opacity: 0.8;
        }
        .login-form-wrapper {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-form-wrapper h1 {
            font-size: 1.8rem;
            color: var(--primary-dark, #333);
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .login-form-wrapper .subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 2rem;
        }
        .alert-error {
            margin-bottom: 1rem;
            text-align: left;
        }
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 420px;
                min-height: auto;
            }
            .login-branding {
                display: none;
            }
            .login-form-wrapper {
                padding: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-branding">
            <img src="../assets/brand/logo4.png" alt="Logo" class="login-logo">
            <h2><?php echo defined('SITE_NAME') ? SITE_NAME : 'Admin Panel'; ?></h2>
            <p>Sustainable Resource Management</p>
        </div>
        <div class="login-form-wrapper">
            <h1>Admin Login</h1>
            <p class="subtitle">Welcome back! Please sign in to continue.</p>
            <?php if ($error_message): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group"><input type="text" name="username" placeholder="Username" class="form-control" required></div>
                <div class="form-group"><input type="password" name="password" placeholder="Password" class="form-control" required></div>
                <button type="submit" class="btn btn-primary btn-large" style="width: 100%;">Login</button>
            </form>
        </div>
    </div>
</body>
</html>