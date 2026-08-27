<?php
require_once '../includes/header.php';
$page_title = "Our Services - " . SITE_NAME;
?>
<?php
require_once '../config/database.php';

try {
    // Fetch all services and their items in one efficient query
    $stmt = $pdo->query("
        SELECT
            s.id as service_id,
            s.name as service_name,
            s.slug,
            s.description,
            s.featured_image,
            si.name as item_name,
            si.icon as item_icon
        FROM services s
        LEFT JOIN service_items si ON s.id = si.service_id
        ORDER BY s.id, si.display_order ASC
    ");
    $results = $stmt->fetchAll();

    // Group the flat results into a structured array
    $services = [];
    foreach ($results as $row) {
        if (!isset($services[$row['service_id']])) {
            $services[$row['service_id']] = [
                'name' => $row['service_name'], 'slug' => $row['slug'], 'description' => $row['description'],
                'featured_image' => $row['featured_image'], 'items' => []
            ];
        }
        if ($row['item_name']) { $services[$row['service_id']]['items'][] = ['name' => $row['item_name'], 'icon' => $row['item_icon']]; }
    }
} catch (\PDOException $e) { error_log("Services Fetch Error: " . $e->getMessage()); $services = []; }
?>

<!-- Hero Section for Services -->
<?php require_once '../includes/hero_section.php'; ?>

<!-- Services Section -->
<div class="services-page-content">
    <?php if (empty($services)): ?>
        <section class="section">
            <div class="container text-center">
                <h2>Our Services</h2>
                <p>No services have been defined yet. Please check back soon!</p>
            </div>
        </section>
    <?php else: ?>
        <?php $i = 0; foreach ($services as $service): ?>
            <?php
                $section_class = ($i % 2 == 0) ? '' : 'bg-light';
                $grid_class = ($i % 2 == 0) ? '' : 'reverse';
                $fade_direction_img = ($i % 2 == 0) ? 'fade-right' : 'fade-left';
                $fade_direction_content = ($i % 2 == 0) ? 'fade-left' : 'fade-right';
            ?>
            <section class="section service-feature <?php echo $section_class; ?>" id="<?php echo htmlspecialchars($service['slug']); ?>">
                <div class="container">
                    <div class="service-feature-grid <?php echo $grid_class; ?>">
                        <div class="service-feature-image" data-aos="<?php echo $fade_direction_img; ?>">
                            <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($service['featured_image'] ?? 'assets/brand/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($service['name']); ?>">
                        </div>
                        <div class="service-feature-content" data-aos="<?php echo $fade_direction_content; ?>">
                            <h2 class="service-title"><?php echo htmlspecialchars($service['name']); ?></h2>
                            <p><?php echo htmlspecialchars($service['description']); ?></p>
                            <ul class="service-list">
                                <?php foreach ($service['items'] as $item): ?>
                                    <li><i class="<?php echo htmlspecialchars($item['icon'] ?? 'fas fa-check-circle'); ?>"></i> <?php echo htmlspecialchars($item['name']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        <?php $i++; endforeach; ?>
    <?php endif; ?>
</div>

<!-- EIA Process Flow Chart Section -->
<section class="section" id="eia-flowchart">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Zambia EIA Process Flow Chart</h2>
            <p>A simplified visual guide to the Environmental Impact Assessment process in Zambia for client awareness.</p>
        </div>
        <div class="flowchart-container" data-aos="fade-up">
            <!-- Placeholder for the flowchart image. Replace src with the actual image path. -->
            <img src="https://via.placeholder.com/1200x800.png?text=EIA+Process+Flow+Chart+Image" alt="EIA Process Flow Chart for Zambia">
            <p style="margin-top: 1rem; font-style: italic; color: #666;">This flow chart is for informational purposes only and does not constitute legal or regulatory advice.</p>
        </div>
    </div>
</section>

<style>
.service-feature-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
    align-items: center;
}
.service-feature-grid.reverse .service-feature-image {
    order: 2;
}
.service-feature-image img {
    width: 100%;
    height: auto;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
}
.service-title {
    font-size: 2.2rem;
    margin-bottom: 1rem;
}
.service-feature-content p {
    font-size: 1.1rem;
    margin-bottom: 2rem;
}
.service-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.service-list li {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}
.service-list li i {
    width: 30px;
    color: var(--sunset-orange);
}
@media (max-width: 768px) {
    .service-feature-grid,
    .service-feature-grid.reverse {
        grid-template-columns: 1fr;
    }
    .service-feature-grid.reverse .service-feature-image {
        order: 1;
    }
}
.flowchart-container {
    text-align: center;
    background: var(--white);
    padding: 2rem;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
}
.flowchart-container img {
    max-width: 100%;
    height: auto;
    border-radius: var(--radius-md);
    border: 1px solid #eee;
}
</style>

<?php
require_once '../includes/cta-banner.php';
require_once '../includes/footer.php';
?>