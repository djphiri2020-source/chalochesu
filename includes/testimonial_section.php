<?php
require_once __DIR__ . '/../config/database.php';

try {
    // Fetch only approved testimonials, ordered by creation date descending
    $stmt = $pdo->query("SELECT author_name, author_position, author_image, content, rating FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC");
    $testimonials = $stmt->fetchAll();
} catch (\PDOException $e) {
    // Log the error and prevent it from breaking the page
    error_log("Testimonial Fetch Error: " . $e->getMessage());
    $testimonials = []; // Ensure testimonials is an array to prevent errors below
}
?>

<?php if (!empty($testimonials)): ?>
<style>
    .testimonial-section {
        padding: 6rem 0;
        background-color: #f9f9f9;
    }
    .testimonial-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
    }
    .testimonial-card {
        background: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-md);
        padding: 2rem;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .testimonial-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
    }
    .testimonial-content {
        font-style: italic;
        color: var(--primary-dark);
        margin-bottom: 1.5rem;
        flex-grow: 1;
        position: relative;
        padding-left: 30px;
    }
    .testimonial-content::before {
        content: '\f10d'; /* Font Awesome quote-left */
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 0;
        top: -10px;
        font-size: 1.5rem;
        color: var(--primary-green);
        opacity: 0.5;
    }
    .testimonial-author {
        margin-top: auto;
        text-align: left;
        border-top: 1px solid #eee;
        padding-top: 1rem;
    }
    .testimonial-author-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .testimonial-author-info img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
    .testimonial-author h5 {
        margin: 0;
        color: var(--primary-dark);
        font-weight: 700;
    }
    .testimonial-author p {
        margin: 0;
        color: var(--primary-green);
        font-size: 0.9rem;
    }
</style>

<section class="testimonial-section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>What Our Clients Say</h2>
            <p>Real feedback from partners who trust our expertise.</p>
        </div>

        <div class="testimonial-grid">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-content">
                        <p>"<?php echo htmlspecialchars($testimonial['content']); ?>"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="testimonial-author-info">
                            <img src="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($testimonial['author_image'] ?? 'assets/brand/placeholder-avatar.png'); ?>" alt="<?php echo htmlspecialchars($testimonial['author_name']); ?>">
                            <div>
                                <h5><?php echo htmlspecialchars($testimonial['author_name']); ?></h5>
                                <p><?php echo htmlspecialchars($testimonial['author_position']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>