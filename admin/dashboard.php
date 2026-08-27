<?php
require_once 'includes/header.php';
require_once '../config/database.php';

// Fetch statistics from the database
try {
    $stats = [];
    $stats['contact_submissions'] = $pdo->query("SELECT COUNT(*) FROM contact_submissions")->fetchColumn();
    $stats['subscribers'] = $pdo->query("SELECT COUNT(*) FROM subscribers")->fetchColumn();
    $stats['blog_posts'] = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    $stats['services'] = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
    $stats['products'] = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
} catch (Exception $e) {
    // In case of an error, we can set defaults
    $stats = array_fill_keys(['contact_submissions', 'subscribers', 'blog_posts', 'services', 'products'], 'N/A');
    echo '<div class="alert alert-error">Could not fetch website statistics. Error: ' . $e->getMessage() . '</div>';
}
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    .stat-card {
        background: var(--white);
        padding: 1.5rem;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .stat-card .icon {
        font-size: 2.5rem;
        color: var(--primary-green);
    }
    .stat-card .info .number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-dark);
    }
    .stat-card .info .label {
        font-size: 1rem;
        color: #6c757d;
    }
</style>

<h1>Dashboard</h1>
<p>Welcome to the administrative dashboard. Please use the navigation on the left to manage the website's content.</p>

<div class="stats-grid">
    <div class="stat-card"><div class="icon"><i class="fas fa-envelope-open-text"></i></div><div class="info"><div class="number"><?php echo $stats['contact_submissions']; ?></div><div class="label">Form Submissions</div></div></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-at"></i></div><div class="info"><div class="number"><?php echo $stats['subscribers']; ?></div><div class="label">Newsletter Subscribers</div></div></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-newspaper"></i></div><div class="info"><div class="number"><?php echo $stats['blog_posts']; ?></div><div class="label">Blog Posts</div></div></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-concierge-bell"></i></div><div class="info"><div class="number"><?php echo $stats['services']; ?></div><div class="label">Services</div></div></div>
    <div class="stat-card"><div class="icon"><i class="fas fa-box-open"></i></div><div class="info"><div class="number"><?php echo $stats['products']; ?></div><div class="label">Products</div></div></div>
</div>

<?php
require_once 'includes/footer.php';
?>