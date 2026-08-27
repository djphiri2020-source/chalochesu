<?php
// Set the correct header for XML output
header("Content-Type: application/xml; charset=utf-8");

require_once 'config/database.php';
require_once 'config/constants.php';

// The base URL for your website. Ensure this is correct in constants.php for production.
$base_url = SITE_URL;

/**
 * Helper function to generate a URL entry in the sitemap.
 *
 * @param string $loc        The URL location.
 * @param string $lastmod    The last modification date (YYYY-MM-DD).
 * @param string $changefreq The change frequency (e.g., 'daily', 'monthly').
 * @param string $priority   The priority (0.0 to 1.0).
 */
function sitemap_url($loc, $lastmod, $changefreq, $priority) {
    echo "    <url>\n";
    echo "        <loc>" . htmlspecialchars($loc) . "</loc>\n";
    echo "        <lastmod>" . $lastmod . "</lastmod>\n";
    echo "        <changefreq>" . $changefreq . "</changefreq>\n";
    echo "        <priority>" . $priority . "</priority>\n";
    echo "    </url>\n";
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// --- 1. Static Pages ---
$static_pages = [
    'pages/index.php' => ['1.0', 'daily'],
    'pages/about.php' => ['0.8', 'monthly'],
    'pages/services.php' => ['0.9', 'monthly'],
    'pages/products.php' => ['0.9', 'monthly'],
    'pages/blog.php' => ['0.9', 'weekly'],
    'pages/partners.php' => ['0.7', 'monthly'],
    'pages/contact.php' => ['0.6', 'yearly'],
    // Add other static pages like privacy policy if they exist
    // 'pages/privacy-policy.php' => ['0.3', 'yearly'],
    // 'pages/terms-and-conditions.php' => ['0.3', 'yearly'],
];

$today = date('Y-m-d');

foreach ($static_pages as $path => $meta) {
    sitemap_url($base_url . '/' . $path, $today, $meta[1], $meta[0]);
}


// --- 2. Dynamic Pages from Database ---

try {
    // Fetch published blog posts
    $posts_stmt = $pdo->query("SELECT slug, updated_at FROM posts WHERE status = 'published' ORDER BY updated_at DESC");
    while ($post = $posts_stmt->fetch()) {
        $loc = $base_url . '/pages/post.php?slug=' . $post['slug'];
        $lastmod = date('Y-m-d', strtotime($post['updated_at']));
        sitemap_url($loc, $lastmod, 'weekly', '0.8');
    }

    // Fetch services
    $services_stmt = $pdo->query("SELECT slug, updated_at FROM services ORDER BY updated_at DESC");
    while ($service = $services_stmt->fetch()) {
        // Link to the service anchor on the services page
        $loc = $base_url . '/pages/services.php#' . $service['slug'];
        $lastmod = date('Y-m-d', strtotime($service['updated_at']));
        sitemap_url($loc, $lastmod, 'monthly', '0.7');
    }

    // Fetch products (assuming they don't have individual pages)
    // If products get their own pages in the future, this can be updated.
    // For now, the main products.php link is sufficient and already included above.

} catch (PDOException $e) {
    // Log the error, but don't break the sitemap generation
    error_log("Sitemap generation failed for dynamic content: " . $e->getMessage());
}

echo '</urlset>';
?>