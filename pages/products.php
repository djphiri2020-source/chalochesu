<?php
require_once '../includes/header.php';
$page_title = "Our Products - " . SITE_NAME;
require_once '../config/database.php';

try {
    // Fetch all products from the database
    $stmt = $pdo->query("SELECT name, description, icon FROM products ORDER BY created_at ASC");
    $products = $stmt->fetchAll();
} catch (\PDOException $e) {
    error_log("Products Fetch Error: " . $e->getMessage());
    $products = [];
}
?>

<!-- Hero Section for Products -->
<?php require_once '../includes/hero_section.php'; ?>

<!-- Products Section -->
<section class="section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Innovative and Sustainable Products</h2>
            <p>We provide a range of high-quality products designed to support sustainable development and reduce environmental impact.</p>
        </div>

        <?php if (empty($products)): ?>
            <div class="text-center" data-aos="fade-up">
                <p>Our products will be listed here soon. Please check back later.</p>
            </div>
        <?php else: ?>
            <div class="grid-4" data-aos="fade-up">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-icon"><i class="<?php echo htmlspecialchars($product['icon'] ?? 'fas fa-box-open'); ?>"></i></div>
                        <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Product Card */ /* Already defined in style.css */
.product-card {
    background: var(--white);
    padding: 2rem;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-md);
    text-align: center;
}
.product-icon { /* Already defined in style.css */
    font-size: 3rem;
    color: var(--primary-green);
    margin-bottom: 1rem;
}
</style>

<?php
require_once '../includes/cta-banner.php';
require_once '../includes/footer.php';
?>