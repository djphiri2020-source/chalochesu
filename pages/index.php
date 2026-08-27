<?php
// index.php (root file)
require_once '../includes/header.php';
$page_title = "Home - " . SITE_NAME;
?>
<?php
require_once '../config/database.php';
try {
    // Fetch up to 3 featured services, ordered by most recent
    $stmt = $pdo->query("SELECT name, description, featured_image FROM services WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 3");
    $featured_services = $stmt->fetchAll();
} catch (\PDOException $e) {
    error_log("Featured Services Fetch Error: " . $e->getMessage());
    $featured_services = [];
}
try {
    // Fetch the latest approved testimonial
    $testimonial_stmt = $pdo->query("SELECT author_name, author_position, author_image, content FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC LIMIT 1");
    $testimonial = $testimonial_stmt->fetch();
} catch (\PDOException $e) {
    error_log("Homepage Testimonial Fetch Error: " . $e->getMessage());
    $testimonial = null;
}

$core_values = $pdo->query("SELECT * FROM core_values ORDER BY display_order ASC LIMIT 4")->fetchAll();
$stakeholders = $pdo->query("SELECT * FROM stakeholders ORDER BY display_order ASC")->fetchAll();

// Fetch SDGs
try {
    $sdgs_stmt = $pdo->query("SELECT * FROM sdgs ORDER BY display_order ASC");
    $sdgs = $sdgs_stmt->fetchAll();
} catch (\PDOException $e) {
    error_log("SDG Fetch Error: " . $e->getMessage());
    $sdgs = [];
}
?>

<!-- Hero Section -->
<?php require_once '../includes/hero_section.php'; ?>

<!-- Featured Services -->
<section class="section bg-light" id="services">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Core Services</h2>
            <p>Delivering innovative, reliable, and sustainable development solutions.</p>
        </div>
        
        <?php if (!empty($featured_services)): ?>
            <div class="grid-3">
                <?php $delay = 100; foreach ($featured_services as $service): ?>
                    <div class="card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                        <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($service['featured_image'] ?? 'assets/brand/placeholder.jpg'); ?>" 
                             alt="<?php echo htmlspecialchars($service['name']); ?>" class="card-img">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($service['name']); ?></h3>
                            <p class="card-text"><?php echo htmlspecialchars(substr($service['description'], 0, 120)) . '...'; ?></p>
                            <a href="<?php echo BASE_URL; ?>/pages/services.php" class="btn btn-outline">Learn More</a>
                        </div>
                    </div>
                <?php $delay += 100; endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center" data-aos="fade-up">
                <p>Our core services will be listed here soon. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section" id="values">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Core Values</h2>
            <p>The principles that guide our commitment to excellence and sustainability.</p>
        </div>
        
        <div class="values-grid" data-aos="fade-up">
            <?php foreach ($core_values as $value): ?>
                <div class="value-card">
                    <div class="value-icon"><i class="<?php echo htmlspecialchars($value['icon']); ?>"></i></div>
                    <h4><?php echo htmlspecialchars($value['title']); ?></h4>
                    <p><?php echo htmlspecialchars($value['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Stakeholders We Serve -->
<section class="section bg-light" id="stakeholders">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Stakeholders We Serve</h2>
            <p>Providing expert environmental consulting to a diverse range of sectors.</p>
        </div>
        <div class="stakeholder-carousel" data-aos="fade-up">
            <div class="stakeholder-slide-track">
                <?php foreach (array_merge($stakeholders, $stakeholders) as $stakeholder): // Duplicate for seamless loop ?>
                    <div class="stakeholder-card">
                        <i class="<?php echo htmlspecialchars($stakeholder['icon']); ?>"></i>
                        <span><?php echo htmlspecialchars($stakeholder['name']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- SDG Commitment Section -->
<section class="section client-logos-section" id="sdg-commitment">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Commitment to the SDGs</h2>
            <p><?php echo htmlspecialchars($settings['homepage_sdg_text'] ?? 'We are dedicated to advancing the Sustainable Development Goals (SDGs) through our core services. Our work in environmental consulting, sustainability advisory, and capacity building directly contributes to creating a more sustainable and equitable future for all.'); ?></p>
        </div>
        <div class="logo-carousel" data-aos="fade-up">
            <div class="logo-slide-track">
                <?php if (!empty($sdgs)): ?>
                    <?php // Duplicate array for seamless carousel loop
                    $looped_sdgs = array_merge($sdgs, $sdgs);
                    foreach ($looped_sdgs as $sdg): ?>
                        <div class="logo-item">
                            <a href="<?php echo htmlspecialchars($sdg['link_url'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer">
                                <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($sdg['logo_path']); ?>" alt="<?php echo htmlspecialchars($sdg['name']); ?>">
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if ($testimonial): ?>
<!-- Testimonials -->
<section class="section bg-dark">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2 style="color: var(--white);">What Our Clients Say</h2>
            <p style="color: var(--safari-sand);">Hear from organizations who have partnered with us.</p>
        </div>
        
        <div class="testimonial-grid" data-aos="fade-up">
            <!-- Testimonial 1 -->
            <div class="testimonial-card">
                <p class="testimonial-text">"<?php echo htmlspecialchars($testimonial['content']); ?>"</p>
                <div class="testimonial-author">
                    <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($testimonial['author_image'] ?? 'assets/brand/placeholder-avatar.png'); ?>" alt="<?php echo htmlspecialchars($testimonial['author_name']); ?>">
                    <div>
                        <h4><?php echo htmlspecialchars($testimonial['author_name']); ?></h4>
                        <p><?php echo htmlspecialchars($testimonial['author_position']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<style>
    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    /* New styles for stakeholder carousel */
    .stakeholder-carousel {
        overflow: hidden;
        position: relative;
        width: 100%;
        padding: 2rem 0;
        background: var(--light-gray); /* Match section background */
    }
    .stakeholder-carousel::before,
    .stakeholder-carousel::after {
        content: '';
        position: absolute;
        top: 0;
        width: 150px;
        height: 100%;
        z-index: 2;
    }
    .stakeholder-carousel::before {
        left: 0;
        background: linear-gradient(to left, rgba(245, 245, 245, 0), #F5F5F5);
    }
    .stakeholder-carousel::after {
        right: 0;
        background: linear-gradient(to right, rgba(245, 245, 245, 0), #F5F5F5);
    }
    .stakeholder-slide-track {
        display: flex;
        width: calc(250px * 16); /* 250px per card * 16 cards (8 original + 8 duplicates) */
        animation: stakeholder-scroll 60s linear infinite;
    }
    .stakeholder-card {
        width: 250px; /* Fixed width for calculation */
        margin: 0 1rem;
    }
    .stakeholder-card {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        text-align: center; padding: 2rem; background: var(--white); border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm); font-size: 1.1rem; font-weight: 500;
    }
    .stakeholder-card i { font-size: 2.5rem; color: var(--primary-green); margin-bottom: 1rem; }
    .testimonial-grid {
        display: grid;
        max-width: 700px; /* Constrain the width for a single item */
        margin: 0 auto; /* Center the grid */
    }
    @keyframes stakeholder-scroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(calc(-250px * 8)); /* Scroll by the width of the original 8 cards */
        }
    }
    /* Testimonial grid and card styles are already defined in style.css */
</style>

<?php
require_once '../includes/cta-banner.php';
require_once '../includes/footer.php';
?>