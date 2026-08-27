<?php
// pages/about.php
require_once '../includes/header.php';
require_once '../config/database.php';
$page_title = "About Us - " . SITE_NAME;

try {
    $stmt = $pdo->query("SELECT * FROM team_members ORDER BY display_order ASC, created_at ASC");
    $team_members = $stmt->fetchAll();
} catch (\PDOException $e) {
    error_log("Team Fetch Error: " . $e->getMessage());
    $team_members = [];
}

// Fetch all about page content
$about_content_raw = $pdo->query("SELECT content_key, content_value FROM about_page_content")->fetchAll();
$about_content = array_column($about_content_raw, 'content_value', 'content_key');
$timeline_events = $pdo->query("SELECT * FROM timeline_events ORDER BY display_order ASC")->fetchAll();
$core_values = $pdo->query("SELECT * FROM core_values ORDER BY display_order ASC")->fetchAll();

?>

<!-- About Hero -->
<?php require_once '../includes/hero_section.php'; ?>

<!-- Vision & Mission -->
<section class="section" id="story">
    <div class="container">
        <div class="vision-mission-grid">
            <div class="vision-mission-image" data-aos="fade-right">
                <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($about_content['vision_mission_image'] ?? 'assets/brand/environment4.jpg'); ?>" alt="Sustainable landscape reflecting the company vision">
            </div>
            <div class="vision-mission-text" data-aos="fade-left">
                <div class="vm-item">
                    <div class="icon-large"><i class="<?php echo htmlspecialchars($about_content['vision_mission_icon'] ?? 'fas fa-eye'); ?>"></i></div>
                    <div>
                        <h3>Our Vision</h3>
                        <p><?php echo htmlspecialchars($about_content['vision'] ?? 'Vision statement to be updated.'); ?></p>
                    </div>
                </div>
                <div class="vm-item">
                    <div class="icon-large"><i class="fas fa-bullseye"></i></div>
                    <div>
                        <h3>Our Mission</h3>
                        <p><?php echo htmlspecialchars($about_content['mission'] ?? 'Mission statement to be updated.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Approach -->
<section class="section bg-light" id="approach">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Approach</h2>
            <p>A structured process for delivering sustainable and impactful solutions.</p>
        </div>
        <div class="timeline" data-aos="fade-up">
            <?php foreach ($timeline_events as $event): ?>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <h4><?php echo htmlspecialchars($event['year']); ?>: <?php echo htmlspecialchars($event['title']); ?></h4>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="section" id="values">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Core Values</h2>
            <p>The principles that guide everything we do.</p>
        </div>
        
        <div class="values-grid" data-aos="fade-up">
            <?php foreach ($core_values as $value): ?>
                <div class="value-card">
                    <div class="value-icon"><i class="<?php echo htmlspecialchars($value['icon'] ?? 'fas fa-star'); ?>"></i></div>
                    <h3><?php echo htmlspecialchars($value['title']); ?></h3>
                    <p><?php echo htmlspecialchars($value['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Our Team -->
<section class="section bg-light" id="team">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Meet Our Team</h2>
            <p>The experts driving our mission for a sustainable future.</p>
        </div>
        
        <div class="team-grid" data-aos="fade-up">
            <?php if (empty($team_members)): ?>
                <p>Our team information will be updated soon.</p>
            <?php else: ?>
                <?php foreach ($team_members as $member): ?>
                    <div class="team-member">
                        <div class="member-photo">
                            <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($member['photo'] ?? 'assets/brand/placeholder-avatar.png'); ?>" alt="<?php echo htmlspecialchars($member['full_name']); ?>">
                        </div>
                        <div class="member-info">
                            <h3><?php echo htmlspecialchars($member['full_name']); ?></h3>
                            <p class="member-role"><?php echo htmlspecialchars($member['role']); ?></p>
                            <p class="member-bio"><?php echo htmlspecialchars($member['bio']); ?></p>
                            <div class="member-social">
                                <?php if (!empty($member['linkedin_url'])): ?><a href="<?php echo htmlspecialchars($member['linkedin_url']); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                                <?php if (!empty($member['twitter_url'])): ?><a href="<?php echo htmlspecialchars($member['twitter_url']); ?>" target="_blank"><i class="fab fa-x-twitter"></i></a><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- About Page Styles -->
<style>
    /* New Vision & Mission Layout */
    .vision-mission-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: var(--spacing-lg);
        align-items: center;
    }
    .vision-mission-image img {
        width: 100%;
        height: auto;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-xl);
    }
    .vision-mission-text {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    .vm-item {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
    }
    .vm-item .icon-large {
        flex-shrink: 0;
    }
    .vm-item p {
        font-size: 1.1rem;
        line-height: 1.7;
    }
    
    /* Values Grid */
    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }    
    .value-card { /* Already defined in style.css */
        text-align: center;
        padding: 2rem;
        background: var(--off-white);
        border-radius: var(--radius-md);
        border: 1px solid #eee;
    }
    .value-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }    
    .value-icon { /* Already defined in style.css */
        font-size: 3rem;
        color: var(--primary-green);
        margin-bottom: 1rem;
    }
    .value-card h3 {
        color: var(--primary-dark);
    }
    
    /* Timeline */
    .timeline {
        position: relative;
        max-width: 1000px;
        margin: 2rem auto;
    }
    .timeline::after {
        content: '';
        position: absolute;
        width: 3px;
        background-color: var(--primary-green);
        top: 0;
        bottom: 0;
        left: 50%;
        margin-left: -1.5px;
    }
    .timeline-item {
        padding: 10px 40px;
        position: relative;
        width: 50%;
    }
    .timeline-item:nth-child(odd) {
        left: 0;
    }
    .timeline-item:nth-child(even) {
        left: 50%;
    }
    .timeline-item::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        right: -10px;
        background-color: var(--white);
        border: 4px solid var(--primary-green);
        top: 15px;
        border-radius: 50%;
        z-index: 1;
    }
    .timeline-item:nth-child(even)::after {
        left: -10px;
    }
    .timeline-content {
        padding: 20px 30px;
        background-color: white;
        position: relative;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-md);
    }

    /* Team Grid */ /* Already defined in style.css */
    .team-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;
    }
    .team-member { /* Already defined in style.css */
        background: var(--white); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md); text-align: center; transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .team-member:hover { /* Already defined in style.css */
        transform: translateY(-10px); box-shadow: var(--shadow-lg);
    }
    .member-photo { /* Already defined in style.css */
        height: 350px; position: relative;
    }
    .member-photo img { /* Already defined in style.css */
        width: 100%; height: 100%; object-fit: cover; object-position: center;
    }
    .member-social {
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.75rem;
        background: rgba(0,0,0,0.5);
        padding: 0.5rem 1rem;
        border-radius: var(--radius-xl);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .team-member:hover .member-social {
        opacity: 1;
    }
    .member-info { /* Already defined in style.css */
        padding: 2rem 1.5rem; background: var(--off-white);
    }
    .member-role { /* Already defined in style.css */
        color: var(--primary-green);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .member-bio { /* Already defined in style.css */
        font-size: 0.95rem;
        margin-bottom: 1rem;
        color: #555;
    }
    .member-social a { /* Already defined in style.css */
        color: var(--white);
        font-size: 1rem;
        transition: color 0.3s ease;
        width: 30px;
        height: 30px;
        line-height: 30px;
    }
    .member-social a:hover { /* Already defined in style.css */
        color: var(--primary-green);
    }
    
    @media (max-width: 1024px) {
        .values-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .vision-mission-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .values-grid {
            grid-template-columns: 1fr;
        }
        .timeline::after {
            left: 20px;
        }
        .timeline-item {
            width: 100%;
            padding-left: 50px;
            padding-right: 10px;
        }
        .timeline-item:nth-child(odd), .timeline-item:nth-child(even) {
            left: 0;
        }
        .timeline-item::after {
            left: 10px;
        }
        .timeline-content {
            padding: 15px 20px;
        }
    }
</style>

<?php
// Include CTA and Footer
require_once '../includes/cta-banner.php';
require_once '../includes/footer.php';
?>