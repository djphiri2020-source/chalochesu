<?php
session_start();

// --- SECURITY: Authentication Check ---
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// --- SECURITY: Session ID Regeneration ---
if (isset($_SESSION['last_regeneration']) && (time() - $_SESSION['last_regeneration'] > 900)) { // 15 minutes
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

require_once dirname(dirname(__DIR__)) . '/config/constants.php';

$username = htmlspecialchars($_SESSION['username'] ?? 'Admin', ENT_QUOTES, 'UTF-8');
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- FontAwesome Icon Picker -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Keep jQuery for other scripts -->

    <!-- SortableJS for drag-and-drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <style>
        :root {
            --admin-bg: #f4f7f6;
            --sidebar-bg: #2c3e50;
            --sidebar-width: 260px;
            --topbar-height: 60px;
            --primary-green: <?php echo SECONDARY_COLOR; ?>;
            --accent-alt: <?php echo ACCENT_ALT; ?>;
            --white: #FFFFFF;
            --light-gray: #f8f9fa;
            --radius-md: 8px;
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.15);
            --primary-dark: <?php echo TEXT_DARK; ?>;

        }
        body {
            background-color: var(--admin-bg);
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding-left: var(--sidebar-width);
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--white);
            padding-top: 20px;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 0 20px 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header h2 {
            color: var(--white);
            margin: 0;
            font-size: 1.5rem;
        }
        .nav-sidebar {
            flex-grow: 1;
            list-style: none;
            padding: 20px 0;
            margin: 0;
            overflow-y: auto; /* Enable vertical scrolling */
        }
        /* Custom scrollbar for the sidebar navigation */
        .nav-sidebar::-webkit-scrollbar {
            width: 8px;
        }
        .nav-sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
        .nav-sidebar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }
        .nav-sidebar .nav-header {
            padding: 10px 20px;
            font-size: 0.8rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .nav-sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--white);
            text-decoration: none;
            transition: background 0.3s, color 0.3s;
            border-left: 4px solid transparent; /* Placeholder for active state */
        }
        .nav-sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        .nav-sidebar .nav-link.active {
            background: rgba(95, 188, 110, 0.1); /* Subtle green glow */
            border-left-color: var(--primary-green); /* Green outline */
            color: var(--white);
            font-weight: 600;
        }
        .nav-sidebar .nav-icon {
            width: 30px;
            font-size: 1.1rem;
            margin-right: 10px;
            text-align: center;
        }
        .main-content {
            padding: 2rem;
            margin-top: var(--topbar-height);
        }
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: var(--white);
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 0 2rem;
            z-index: 999;
        }
        .topbar .user-info {
            margin-right: 1rem;
        }
        .mobile-menu-toggle-btn {
            display: none; /* Hidden on desktop */
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary-dark);
            cursor: pointer;
            margin-right: auto;
        }

        /* Admin Button Styles */
        .btn {
            border: 2px solid transparent;
            transition: all 0.3s ease;
            border-radius: var(--radius-md);
            cursor: pointer;
        }
        .btn-primary {
            background-color: var(--primary-green);
            color: var(--white);
            border-color: var(--primary-green);
        }
        .btn-primary:hover {
            background-color: var(--accent-alt);
            border-color: var(--accent-alt);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        .btn-secondary {
            background-color: transparent;
            color: var(--primary-green);
            border-color: var(--primary-green);
        }
        .btn-secondary:hover {
            background-color: var(--primary-green);
            color: var(--white);
        }

        /* Responsive Admin Layout */
        @media (max-width: 992px) {
            body {
                padding-left: 0;
            }
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                z-index: 1001;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .topbar {
                left: 0;
            }
            .mobile-menu-toggle-btn {
                display: block;
            }
            .nav-item-danger a {
                background-color: #dc3545 !important;
                color: white !important;
                margin: 0.5rem 1rem;
                border-radius: var(--radius-md);
            }
            .nav-item-danger a:hover {
                background-color: #c82333 !important;
            }

        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2><?php echo SITE_NAME; ?></h2>
        </div>
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <?php require_once 'navigation.php'; ?>
        </ul>
    </aside>

    <header class="topbar">
        <button class="mobile-menu-toggle-btn"><i class="fas fa-bars"></i></button>
        <span class="user-info">Welcome, <strong><?php echo $username; ?></strong></span>
        <a href="logout.php" class="btn btn-secondary btn-sm">Logout</a>
    </header>

    <main class="main-content">