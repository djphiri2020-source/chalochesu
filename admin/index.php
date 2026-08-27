<?php
/**
 * Admin directory entry point.
 *
 * This file checks the user's authentication status and redirects them
 * to the appropriate page.
 */

session_start();

// If the user is logged in, redirect to the dashboard.
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: dashboard.php');
} else {
    // If the user is not logged in, redirect to the login page.
    header('Location: login.php');
}
exit();