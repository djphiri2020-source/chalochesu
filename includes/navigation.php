<?php
// includes/navigation.php

$current_path = $_SERVER['REQUEST_URI'];

$nav_links = [
    'Home' => BASE_URL . '/pages/index.php',
    'About' => BASE_URL . '/pages/about.php',
    'Services' => BASE_URL . '/pages/services.php',
    'Products' => BASE_URL . '/pages/products.php',
    'R&D' => BASE_URL . '/pages/blog.php',
    'Partners' => BASE_URL . '/pages/partners.php',
    'Contact' => BASE_URL . '/pages/contact.php',
];

function render_nav_links($links, $current_path, $is_mobile = false) {
    $link_class = $is_mobile ? 'mobile-nav-link' : 'nav-link';

    foreach ($links as $title => $url) {
        $is_active = ($current_path === parse_url($url, PHP_URL_PATH)) || (basename($current_path) === 'index.php' && $title === 'Home');
        
        if (is_array($url)) { // For future dropdowns
            // Dropdown logic can be re-added here if needed
        } else {
            echo '<li><a href="' . $url . '" class="' . $link_class . ' ' . ($is_active ? 'active' : '') . '">' . $title . '</a></li>';
        }
    }
}
?>

<!-- Desktop Navigation -->
<nav class="main-nav">
    <ul class="nav-list">
        <?php render_nav_links($nav_links, $current_path); ?>
    </ul>
    
    <!-- Mobile Menu Toggle -->
    <div class="mobile-menu-toggle">
        <i class="fas fa-bars"></i>
    </div>
</nav>

<!-- Mobile Navigation -->
<div class="mobile-nav">
    <div class="mobile-nav-header">
        <div class="mobile-logo">
            <a href="<?php echo BASE_URL; ?>/">
                <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($site_logo_path); ?>" alt="<?php echo htmlspecialchars($site_name); ?> Mobile Logo">
            </a>
        </div>
        <div class="mobile-close"><i class="fas fa-times"></i></div>
    </div>
    <ul class="mobile-nav-list">
        <?php render_nav_links($nav_links, $current_path, true); ?>
    </ul>
    <div class="mobile-nav-footer">
        <a href="<?php echo BASE_URL; ?>/pages/contact.php" class="btn btn-primary">
            <i class="fas fa-envelope"></i> Get a Quote
        </a>
    </div>
</div>

<style>
    .mobile-logo img {
        height: 40px;
        width: auto;
    }
</style>