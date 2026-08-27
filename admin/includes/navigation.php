<?php
/**
 * Generates the role-based navigation menu for the admin sidebar.
 */

if (!isset($_SESSION['user_role'])) {
    // Should not happen if user is logged in, but as a safeguard:
    return;
}

$user_role = $_SESSION['user_role'];

// Define all possible navigation items and the roles that can see them.
// An empty array for roles means the link is visible to all authenticated users.
$nav_items = [
    'Dashboard' => [
        'path' => 'dashboard.php',
        'icon' => 'fas fa-tachometer-alt',
        'roles' => [] // All roles can see the dashboard
    ],
    'Content Management' => [
        'is_header' => true,
        'roles' => ['super_admin', 'admin', 'editor', 'author']
    ],
    'Manage About Page' => [
        'path' => 'manage_about.php',
        'icon' => 'fas fa-info-circle',
        'roles' => ['super_admin', 'admin', 'editor']
    ],
    'Manage Heroes' => [
        'path' => 'manage_heroes.php',
        'icon' => 'fas fa-image',
        'roles' => ['super_admin', 'admin', 'editor']
    ],
    'Manage Services' => [
        'path' => 'manage_services.php',
        'icon' => 'fas fa-concierge-bell',
        'roles' => ['super_admin', 'admin', 'editor']
    ],
    'Manage Products' => [
        'path' => 'manage_products.php',
        'icon' => 'fas fa-box-open',
        'roles' => ['super_admin', 'admin', 'editor']
    ],
    'Manage Team' => [
        'path' => 'manage_team.php',
        'icon' => 'fas fa-users',
        'roles' => ['super_admin', 'admin', 'editor']
    ],
    'Manage SDGs' => [
        'path' => 'manage_sdgs.php',
        'icon' => 'fas fa-globe-africa',
        'roles' => ['super_admin', 'admin', 'editor']
    ],
    'Research & Development' => [
        'is_header' => true,
        'roles' => ['super_admin', 'admin', 'editor', 'author']
    ],
    'R&D Posts' => [
        'path' => 'manage_posts.php',
        'icon' => 'fas fa-newspaper',
        'roles' => ['super_admin', 'admin', 'editor', 'author']
    ],
    'Submissions' => [
        'path' => 'form_submissions.php',
        'icon' => 'fas fa-inbox',
        'roles' => ['super_admin', 'admin', 'editor', 'author']
    ],
    'Subscribers' => [
        'path' => 'subscribers.php',
        'icon' => 'fas fa-users',
        'roles' => ['super_admin', 'admin', 'editor', 'author']
    ],
    'Site Administration' => [
        'is_header' => true,
        'roles' => ['super_admin', 'admin']
    ],
    'Manage Users' => [
        'path' => 'manage_users.php',
        'icon' => 'fas fa-users-cog',
        'roles' => ['super_admin', 'admin']
    ],
    'Site Settings' => [
        'path' => 'site_settings.php',
        'icon' => 'fas fa-cogs',
        'roles' => ['super_admin', 'admin'] // Editor cannot see this
    ],
    'Manage Footer' => [
        'path' => 'manage_documents.php',
        'icon' => 'fas fa-shoe-prints',
        'roles' => ['super_admin', 'admin']
    ],
    'Site Status' => [
        'path' => 'close_site.php',
        'icon' => 'fas fa-store-slash',
        'roles' => ['super_admin'],
        'class' => 'nav-item-danger' // Custom class for styling
    ],
];

$current_page = basename($_SERVER['PHP_SELF']);

foreach ($nav_items as $title => $item) {
    // Check if the user's role is allowed to see this item
    if (!empty($item['roles']) && !in_array($user_role, $item['roles'])) {
        continue;
    }

    if (isset($item['is_header']) && $item['is_header']) {
        echo "<li class='nav-header'>$title</li>";
    } else {
        $extra_class = $item['class'] ?? '';
        $is_active = ($current_page === $item['path']) ? 'active' : '';
        echo "<li class='nav-item {$extra_class}'>";
        echo "<a href='{$item['path']}' class='nav-link {$is_active}'>";
        if (isset($item['icon'])) {
            echo "<i class='nav-icon {$item['icon']}'></i>";
        }
        echo "<p>$title</p>";
        echo "</a>";
        echo "</li>";
    }
}
?>