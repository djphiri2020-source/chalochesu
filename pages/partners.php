<?php
require_once '../includes/header.php';
require_once '../config/database.php';
$page_title = "Our Partners & Stakeholders - " . SITE_NAME;

$stakeholders = $pdo->query("SELECT * FROM stakeholders ORDER BY display_order ASC")->fetchAll();
$partners = $pdo->query("SELECT * FROM partners ORDER BY display_order ASC")->fetchAll();

?>

<!-- Hero Section for Partners -->
<?php require_once '../includes/hero_section.php'; ?>

<!-- Stakeholders Section -->
<section class="section" id="stakeholders">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Stakeholders We Serve</h2>
            <p>We provide expert environmental and sustainability consulting to a diverse range of sectors.</p>
        </div>

        <div class="stakeholders-grid" data-aos="fade-up">
            <?php foreach ($stakeholders as $stakeholder): ?>
                <div class="stakeholder-card">
                    <i class="<?php echo htmlspecialchars($stakeholder['icon'] ?? 'fas fa-building'); ?>"></i><span><?php echo htmlspecialchars($stakeholder['name']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section class="section bg-light" id="partners">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Valued Partners</h2>
            <p>We collaborate with trusted organizations to deliver comprehensive solutions.</p>
        </div>

        <div class="partners-grid" data-aos="fade-up">
            <?php foreach ($partners as $partner): ?>
                <div class="partner-card">
                    <div class="partner-logo">
                        <!-- You can add a logo image here in the future if needed -->
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="partner-info">
                        <h3><?php echo htmlspecialchars($partner['name']); ?></h3>
                        <p><?php echo htmlspecialchars($partner['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Client & Supplier Engagement -->
<section class="section" id="engagement">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Client & Supplier Engagement</h2>
            <p>Fostering collaboration, professionalism, and mutual success.</p>
        </div>
        <div class="engagement-grid">
            <div class="engagement-text" data-aos="fade-right">
                <h3>Building Strong Partnerships</h3>
                <p>Our approach to client and supplier interaction is founded on principles of collaboration, professionalism, and robust stakeholder engagement. We believe that strong partnerships are the cornerstone of successful project delivery and long-term sustainability.</p>
                <p>We work closely with our clients to understand their unique needs and objectives, ensuring our solutions are perfectly tailored. With our suppliers, we foster relationships built on trust and shared values, ensuring a seamless and ethical supply chain.</p>
                <a href="<?php echo BASE_URL; ?>/pages/contact.php" class="btn btn-primary">Partner With Us</a>
            </div>
            <div class="engagement-image" data-aos="fade-left">
                <!-- Placeholder Image: Replace with the actual image when provided -->
                <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=800&q=80" alt="A professional meeting showcasing client and supplier collaboration">
            </div>
        </div>
    </div>
</section>

<style>
/* Stakeholder Card */ /* Already defined in style.css */
.stakeholders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}
.stakeholder-card { /* Already defined in style.css */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem;
    background: var(--white);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    font-size: 1.1rem;
    font-weight: 500;
}
.stakeholder-card i { /* Already defined in style.css */
    font-size: 2.5rem;
    color: var(--primary-green);
    margin-bottom: 1rem;
}
.partners-grid { max-width: 600px; margin: 0 auto; }
.partner-card { /* Already defined in style.css */
    display: flex; align-items: center; gap: 2rem; background: var(--white); padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-md);
}
.partner-logo { /* Already defined in style.css */
    font-size: 3rem; color: var(--medium-green);
}

/* Client & Supplier Engagement */
.engagement-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
    align-items: center;
}
.engagement-image img {
    width: 100%;
    height: auto;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
}
.engagement-text h3 {
    font-size: 1.8rem;
    margin-bottom: 1rem;
}
.engagement-text p {
    font-size: 1.1rem;
    line-height: 1.7;
    margin-bottom: 1.5rem;
}
@media (max-width: 768px) {
    .engagement-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
require_once '../includes/cta-banner.php';
require_once '../includes/footer.php';
?>