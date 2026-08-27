<?php
require_once '../config/database.php';

// Get slug from URL
if (!isset($_GET['slug'])) {
    header("Location: blog.php"); // Redirect to the main R&D page
    exit;
}
$slug = $_GET['slug'];

// Fetch the specific post
try {
    $stmt = $pdo->prepare("
        SELECT
            p.id,
            p.title,
            p.content,
            p.featured_image,
            p.created_at,
            u.full_name as author_name
        FROM posts p
        JOIN users u ON p.author_id = u.id
        WHERE p.slug = ? AND p.status = 'published'
    ");
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
} catch (\PDOException $e) {
    error_log("Single Post Fetch Error: " . $e->getMessage());
    $post = null;
}

// If post not found, redirect to blog index
if (!$post) {
    header("HTTP/1.0 404 Not Found");
    // You can create a 404.php page and include it here
    echo "<h1>404 - Post Not Found</h1><p>The post you are looking for does not exist.</p>";
    exit;
}

$related_posts = [];
if ($post) {
    try {
        // 1. Get categories of the current post
        $cat_stmt = $pdo->prepare("SELECT category_id FROM post_categories WHERE post_id = ?");
        $cat_stmt->execute([$post['id']]);
        $category_ids = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);

        // 2. If the post has categories, find related posts
        if (!empty($category_ids)) {
            $placeholders = implode(',', array_fill(0, count($category_ids), '?'));
            $related_stmt = $pdo->prepare("
                SELECT DISTINCT p.title, p.slug, p.featured_image, p.created_at
                FROM posts p
                JOIN post_categories pc ON p.id = pc.post_id
                WHERE pc.category_id IN ($placeholders) AND p.id != ? AND p.status = 'published'
                ORDER BY p.created_at DESC
                LIMIT 3
            ");
            $related_stmt->execute(array_merge($category_ids, [$post['id']]));
            $related_posts = $related_stmt->fetchAll();
        }
    } catch (\PDOException $e) {
        error_log("Related Posts Fetch Error: " . $e->getMessage());
    }
}

$page_title = htmlspecialchars($post['title']);
require_once '../includes/header.php';
?>

<style>
    .post-header {
        text-align: center;
        padding: 3rem 0;
    }
    .post-meta {
        color: #777;
        margin-bottom: 2rem;
    }
    .post-featured-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        border-radius: var(--radius-lg);
        margin-bottom: 3rem;
    }
    .post-content {
        max-width: 800px;
        margin: 0 auto 4rem auto;
        line-height: 1.8;
    }
    .post-content h2, .post-content h3 {
        margin-top: 2.5rem;
    }
    .related-posts-section {
        padding: 4rem 0;
        background-color: var(--light-gray);
        border-top: 1px solid #ddd;
    }
    .related-posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }
    /* Using post-card styles from blog.php for consistency */
    .post-card {
        background: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .post-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
    }
    .post-card-image {
        height: 200px;
        width: 100%;
        object-fit: cover;
    }
    .post-card-content {
        padding: 1.5rem;
    }
    .post-card-content h3 {
        margin-top: 0;
        font-size: 1.2rem;
    }
</style>

<main class="container">
    <article>
        <header class="post-header">
            <h1 data-aos="fade-up"><?php echo htmlspecialchars($post['title']); ?></h1>
            <div class="post-meta" data-aos="fade-up" data-aos-delay="100">
                <span>By <?php echo htmlspecialchars($post['author_name']); ?></span> |
                <span>Published on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
            </div>
        </header>

        <?php if (!empty($post['featured_image'])): ?>
            <img src="../<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-featured-image" data-aos="zoom-in">
        <?php endif; ?>

        <div class="post-content" data-aos="fade-up">
            <?php echo $post['content']; // Content from TinyMCE is already HTML ?>
        </div>
    </article>
</main>

<?php if (!empty($related_posts)): ?>
<section class="related-posts-section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Related Articles</h2>
            <p>You might also be interested in these topics.</p>
        </div>
        <div class="related-posts-grid">
            <?php foreach ($related_posts as $related_post): ?>
                <article class="post-card" data-aos="fade-up">
                    <a href="post.php?slug=<?php echo htmlspecialchars($related_post['slug']); ?>">
                        <img src="../<?php echo htmlspecialchars($related_post['featured_image'] ?? 'assets/brand/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($related_post['title']); ?>" class="post-card-image">
                    </a>
                    <div class="post-card-content">
                        <small><?php echo date('F j, Y', strtotime($related_post['created_at'])); ?></small>
                        <h3><a href="post.php?slug=<?php echo htmlspecialchars($related_post['slug']); ?>"><?php echo htmlspecialchars($related_post['title']); ?></a></h3>
                        <a href="post.php?slug=<?php echo htmlspecialchars($related_post['slug']); ?>" class="btn btn-secondary btn-sm mt-2">Read More</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>