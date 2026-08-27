<?php
// includes/header.php

define('BASE_URL', '/chalochesu'); // Define the base URL for the project

session_start(); // Start session to check for logged-in super_admin

// Get the current page URL to set active nav link
$current_page = $_SERVER['REQUEST_URI'];

// The path to constants.php needs to be relative to this file (header.php)
if (file_exists(__DIR__ . '/../config/constants.php')) {
    require_once __DIR__ . '/../config/constants.php';
}

// --- Fetch Site Settings from Database ---
require_once __DIR__ . '/../config/database.php';
$settings = [];
$stmt = $pdo->query("SELECT setting_key, setting_value FROM settings"); // Corrected table name
if ($stmt) {
    $settings_list = $stmt->fetchAll();
    foreach ($settings_list as $setting) {
        $settings[$setting['setting_key']] = $setting['setting_value'];
    }
}

// --- SITE CLOSED / MAINTENANCE MODE CHECK ---
$is_site_closed = !empty($settings['site_closed']) && $settings['site_closed'] == '1';

// Allow access if the site is NOT closed, or if a super_admin is logged in.
$is_super_admin = (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'super_admin');
$allow_access = !$is_site_closed || $is_super_admin;

if (!$allow_access) {
    $site_name_for_title = !empty($settings['site_name']) ? $settings['site_name'] : (defined('SITE_NAME') ? SITE_NAME : 'Our Site');
    $site_logo_path = !empty($settings['site_logo']) ? $settings['site_logo'] : 'assets/brand/logo4.png';
    $maintenance_message = $settings['site_closed_message'] ?? 'Our website is currently down for maintenance. We will be back shortly!';
    $contact_email = !empty($settings['contact_email']) ? $settings['contact_email'] : CONTACT_EMAIL;
    $contact_phone = !empty($settings['contact_phone']) ? $settings['contact_phone'] : CONTACT_PHONE;
    $contact_address = !empty($settings['contact_address']) ? $settings['contact_address'] : CONTACT_ADDRESS;
    // Display a simple maintenance page and stop execution
    http_response_code(503); // Service Unavailable
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Maintenance - {$site_name_for_title}</title>
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #f4f7f6; color: #333; text-align: center; padding: 2rem; }
        .maintenance-container { max-width: 800px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h1 { font-size: 2.5rem; color: #344C3F; margin-bottom: 1rem; }
        p { font-size: 1.1rem; color: #555; line-height: 1.6; margin-bottom: 2rem; }
        .logo { margin-bottom: 2rem; }
        .logo img { height: 75px; width: auto; }
        .contact-info { margin-top: 2rem; border-top: 1px solid #eee; padding-top: 2rem; }
        .contact-item { margin-bottom: 0.75rem; }
        .contact-item i { color: #5FBC6E; margin-right: 0.5rem; }
        .contact-item a { color: #5FBC6E; text-decoration: none; }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="logo">
            <img src="<?php echo SITE_URL; ?>/{$site_logo_path}" alt="{$site_name_for_title} Logo">
        </div>
        <h1>We&rsquo;ll be back soon!</h1>
        <p>{$maintenance_message}</p>
        <div class="contact-info">
            <h3>Contact Information</h3>
            <div class="contact-item">
                <i class="fas fa-envelope"></i> Email: <a href="mailto:{$contact_email}">{$contact_email}</a>
            </div>
            <div class="contact-item">
                <i class="fas fa-phone"></i> Phone: {$contact_phone}
            </div>
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i> Address: {$contact_address}
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</body>
</html>
HTML;
    exit(); // Stop rendering the rest of the page
}
// Use database values if they exist, otherwise fall back to constants
$facebook_url = !empty($settings['facebook_url']) ? $settings['facebook_url'] : FACEBOOK_URL;
$linkedin_url = !empty($settings['linkedin_url']) ? $settings['linkedin_url'] : LINKEDIN_URL;
$instagram_url = !empty($settings['instagram_url']) ? $settings['instagram_url'] : INSTAGRAM_URL;
$twitter_url = !empty($settings['twitter_url']) ? $settings['twitter_url'] : TWITTER_URL;
$site_name = !empty($settings['site_name']) ? $settings['site_name'] : SITE_NAME;
$site_tagline = !empty($settings['site_tagline']) ? $settings['site_tagline'] : SITE_TAGLINE;
$site_logo_path = !empty($settings['site_logo']) ? $settings['site_logo'] : 'assets/brand/logo2.png';

// Determine the current page's slug for fetching hero data
$page_path_relative_to_base = str_replace(BASE_URL, '', $current_page);
$page_filename = basename($page_path_relative_to_base);
$page_slug_for_hero = str_replace('.php', '', $page_filename);

// Special handling for homepage if its slug is 'home' in the DB
if ($page_slug_for_hero === 'index' || $page_slug_for_hero === '') { // Handle both /index.php and /
    $page_slug_for_hero = 'home'; 
}

// --- Fetch Hero Section Data from Database ---
$hero_data = null;
$hero_images = [];

if (!empty($page_slug_for_hero)) {
    $hero_stmt = $pdo->prepare("SELECT * FROM heroes WHERE page_slug = ?");
    $hero_stmt->execute([$page_slug_for_hero]);
    $hero_data = $hero_stmt->fetch();

    if ($hero_data) {
        $images_stmt = $pdo->prepare("SELECT * FROM hero_images WHERE hero_id = ? ORDER BY display_order ASC");
        $images_stmt->execute([$hero_data['id']]);
        $hero_images = $images_stmt->fetchAll();
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Chalochesu is a leading environmental consulting firm providing solutions for sustainable resource management, EIAs, feasibility studies, and climate services.">
    <meta name="keywords" content="environmental consulting, sustainable resource management, EIA, feasibility study, climate services, sustainability">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://chalochesu.com<?php echo $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo isset($page_title) ? $page_title : htmlspecialchars($site_name); ?>">
    <meta property="og:description" content="Chalochesu is a leading environmental consulting firm providing solutions for sustainable resource management, EIAs, feasibility studies, and climate services.">
    <meta property="og:image" content="https://chalochesu.com<?php echo BASE_URL; ?>/assets/brand/og-image.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://chalochesu.com<?php echo $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo isset($page_title) ? $page_title : htmlspecialchars($site_name); ?>">
    <meta property="twitter:description" content="Chalochesu is a leading environmental consulting firm providing solutions for sustainable resource management, EIAs, feasibility studies, and climate services.">
    <meta property="twitter:image" content="https://chalochesu.com<?php echo BASE_URL; ?>/assets/brand/og-image.jpg">

    <title><?php echo htmlspecialchars($site_name) . ' - ' . htmlspecialchars($site_tagline); ?></title>
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>/assets/brand/favicon.png">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/brand/favicon.png">

    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/responsive.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        /* Dynamic CSS Variables & Preloader Styles */
        :root {
            --primary-dark: <?php echo TEXT_DARK; ?>;
            --primary-green: <?php echo SECONDARY_COLOR; ?>;
            --medium-green: <?php echo PRIMARY_COLOR; ?>;
            --dark-bg: <?php echo BACKGROUND_DARK; ?>;
            --accent-bright: <?php echo ACCENT_BRIGHT; ?>;
            --accent-alt: <?php echo ACCENT_ALT; ?>;

            /* Overriding old variables for compatibility */
            --savanna-green: var(--medium-green);
            --sunset-orange: var(--primary-green);
            --charcoal: var(--primary-dark);
            --safari-sand: #EAE8E3;
        }

        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--dark-bg);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.75s ease, visibility 0.75s ease;
        }
        #preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }
        .preloader-content {
            position: relative;
            text-align: center;
        }
        .preloader-logo {
            width: 150px;
            position: relative; /* Ensure logo is on top of the spinner */
            z-index: 2;
            animation: pulse 1.8s infinite ease-in-out;
        }
        .spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            height: 200px;
            margin-top: -100px;
            margin-left: -100px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-top-color: var(--primary-green);
            animation: spin 1.5s linear infinite;
            z-index: 1;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 0.8; }
        }
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Robust Preloader Script -->
    <script>
        function showPage() {
            const preloader = document.getElementById('preloader');
            if (preloader && !preloader.classList.contains('hidden')) {
                preloader.classList.add('hidden');
                document.body.style.opacity = '1';
                document.body.style.visibility = 'visible';
            }
        }

        // 1. Hide when all page content (including images) is loaded.
        window.addEventListener('load', showPage);

        // 2. Failsafe: Force hide the preloader after 10 seconds in case the 'load' event fails.
        setTimeout(showPage, 10000);

        // 3. Show preloader instantly when navigating away from the page.
        window.addEventListener('beforeunload', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                // This makes the preloader visible for the brief moment before the next page starts loading.
                // We don't hide the body here as the browser will replace it anyway.
                preloader.classList.remove('hidden');
            }
        });

        // We need to prevent the 'beforeunload' event from firing on mailto/tel links.
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href^="mailto:"], a[href^="tel:"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.stopImmediatePropagation();
                });
            });
        });
    </script>
</head>
<body class="frontend">
    <!-- Preloader -->
    <div id="preloader">
        <div class="preloader-content">
            <div class="spinner"></div>
            <img src="<?php echo BASE_URL; ?>/assets/brand/logo4.png" alt="Chalochesu Loading..." class="preloader-logo">
        </div>
    </div>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="contact-info">
                <span><i class="fas fa-phone"></i> <?php echo CONTACT_PHONE; ?></span>
                <span><i class="fas fa-envelope"></i> <?php echo CONTACT_EMAIL; ?></span>
            </div>
            <div class="social-links">
                <a href="<?php echo htmlspecialchars($facebook_url); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="<?php echo htmlspecialchars($linkedin_url); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                <a href="<?php echo htmlspecialchars($instagram_url); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="<?php echo htmlspecialchars($twitter_url); ?>" target="_blank"><i class="fab fa-x-twitter"></i></a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header">
        <div class="container">
            <!-- Logo -->
            <div class="logo"> 
                <a href="<?php echo BASE_URL; ?>/">
                    <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($site_logo_path); ?>" alt="<?php echo htmlspecialchars($site_name); ?> Logo" class="header-logo-img">
                </a>
            </div>

            <?php require_once __DIR__ . '/navigation.php'; ?>

            <!-- Booking Button -->
            <div class="header-cta">
                <a href="<?php echo BASE_URL; ?>/pages/contact.php" class="btn btn-primary">
                    <i class="fas fa-envelope"></i> Get a Quote
                </a>
            </div>
        </div>
    </header>

<style>
    .header-logo-img {
        height: 50px;
        width: auto;
    }
</style>