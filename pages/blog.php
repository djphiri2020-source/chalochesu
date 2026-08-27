<?php
$page_title = "Research & Development - Insights on Sustainability";
require_once '../includes/header.php';
require_once '../config/database.php';

// Pagination settings
$posts_per_page = 6;
$current_page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
if ($current_page < 1) {
    $current_page = 1;
}

// Get search and category filters
$search_term = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
$category_slug = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_SPECIAL_CHARS);

try {
    // Fetch all categories for the filter list
    $categories = $pdo->query("SELECT name, slug, (SELECT COUNT(*) FROM post_categories pc JOIN posts p ON pc.post_id = p.id WHERE pc.category_id = c.id AND p.status = 'published') as post_count FROM categories c ORDER BY name ASC")->fetchAll();

    // Build the query dynamically
    $base_sql = "
        FROM posts p
        JOIN users u ON p.author_id = u.id
    ";
    $where_clauses = ["p.status = 'published'"];
    $params = [];

    if ($search_term) {
        $where_clauses[] = "(p.title LIKE ? OR p.content LIKE ?)";
        $params[] = "%$search_term%";
        $params[] = "%$search_term%";
    }

    if ($category_slug) {
        $base_sql .= " JOIN post_categories pc ON p.id = pc.post_id JOIN categories c ON pc.category_id = c.id";
        $where_clauses[] = "c.slug = ?";
        $params[] = $category_slug;
    }

    $where_sql = " WHERE " . implode(" AND ", $where_clauses);

    // 1. Get total number of filtered posts
    $count_sql = "SELECT COUNT(DISTINCT p.id) " . $base_sql . $where_sql;
    $total_posts_stmt = $pdo->prepare($count_sql);
    $total_posts_stmt->execute($params);
    $total_posts = $total_posts_stmt->fetchColumn();
    $total_pages = ceil($total_posts / $posts_per_page);

    // Ensure current_page doesn't exceed total_pages
    if ($current_page > $total_pages && $total_pages > 0) {
        $current_page = $total_pages;
    }
    $offset = ($current_page - 1) * $posts_per_page;

    // 2. Fetch posts for the current page
    $posts_sql = "SELECT DISTINCT
            p.title,
            p.slug,
            p.excerpt,
            p.featured_image,
            p.created_at,
            u.full_name as author_name
        " . $base_sql . $where_sql . " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($posts_sql);
    $stmt->bindValue(':limit', $posts_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key + 1, $value);
    }
    $stmt->execute();
    $posts = $stmt->fetchAll();
} catch (\PDOException $e) {
    error_log("Blog Fetch Error: " . $e->getMessage());
    $posts = [];
    $categories = [];
}
?>

<style>
    .page-hero {
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
        height: 220px;
        width: 100%;
        object-fit: cover;
    }
    .post-card-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .post-card-content h3 {
        margin-top: 0;
        font-size: 1.3rem;
    }
    .post-card-meta {
        font-size: 0.9rem;
        color: #777;
        margin-bottom: 1rem;
    }
    .post-card-excerpt {
        flex-grow: 1;
        margin-bottom: 1.5rem;
    }
    .blog-layout {
        display: grid;
        grid-template-columns: 3fr 1fr;
        gap: 3rem;
        padding: 4rem 0;
    }
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
    }
    .blog-sidebar {
        padding-top: 1rem;
    }
    .sidebar-widget {
        background: var(--white);
        padding: 1.5rem;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
    }
    .sidebar-widget h4 {
        margin-top: 0;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }
    .category-list { list-style: none; padding: 0; }
    .category-list a { display: flex; justify-content: space-between; padding: 0.5rem 0; color: var(--primary-dark); }
    .category-list a:hover { color: var(--primary-green); }
    .category-list a.active { font-weight: bold; color: var(--primary-green); }
    .category-count { background: #eee; padding: 0.1rem 0.5rem; border-radius: 10px; font-size: 0.8rem; }

    @media (max-width: 992px) {
        .blog-layout { grid-template-columns: 1fr; }
    }
</style>

<style>
    /* Pagination Styles */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 3rem;
        gap: 0.5rem;
    }
    .pagination a, .pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 10px;
        border: 1px solid var(--light-gray);
        border-radius: var(--radius-md);
        color: var(--primary-dark);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .pagination a:hover {
        background-color: var(--light-gray);
        border-color: var(--primary-green);
    }
    .pagination .current-page {
        background-color: var(--primary-green);
        color: var(--white);
        border-color: var(--primary-green);
    }
</style>

<?php require_once '../includes/hero_section.php'; ?>

<main class="container">
    <div class="blog-layout">
        <div class="blog-posts">
            <?php if ($search_term || $category_slug): ?>
                <div class="alert alert-info">
                    <?php if ($search_term): ?>
                        Showing results for "<strong><?php echo htmlspecialchars($search_term); ?></strong>".
                    <?php endif; ?>
                    <?php if ($category_slug): ?>
                        In category "<strong><?php echo htmlspecialchars(ucwords(str_replace('-', ' ', $category_slug))); ?></strong>".
                    <?php endif; ?>
                    <a href="blog.php" style="margin-left: 1rem;">Clear Filters</a>
                </div>
            <?php endif; ?>

            <div class="blog-grid">
                <?php if (empty($posts)): ?>
                    <p>No posts found matching your criteria. Please try a different search or category.</p>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <article class="post-card" data-aos="fade-up">
                            <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>">
                                <img src="../<?php echo htmlspecialchars($post['featured_image'] ?? 'assets/brand/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-card-image">
                            </a>
                            <div class="post-card-content">
                                <div class="post-card-meta">
                                    <span>By <?php echo htmlspecialchars($post['author_name']); ?></span> |
                                    <span><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                                </div>
                                <h3><a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                                <p class="post-card-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                                <a href="post.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="btn btn-secondary">Read More</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <aside class="blog-sidebar">
            <div class="sidebar-widget" data-aos="fade-up">
                <h4>Search R&D</h4>
                <form action="blog.php" method="GET">
                    <div class="form-group">
                        <input type="search" name="search" class="form-control" placeholder="Search articles..." value="<?php echo htmlspecialchars($search_term ?? ''); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;">Search</button>
                </form>
            </div>
            <div class="sidebar-widget" data-aos="fade-up" data-aos-delay="100">
                <h4>Categories</h4>
                <ul class="category-list">
                    <li><a href="blog.php" class="<?php echo !$category_slug ? 'active' : ''; ?>">All Categories</a></li>
                    <?php foreach ($categories as $category): ?>
                        <?php if ($category['post_count'] > 0): ?>
                        <li>
                            <a href="?category=<?php echo $category['slug']; ?>" class="<?php echo $category_slug === $category['slug'] ? 'active' : ''; ?>">
                                <span><?php echo htmlspecialchars($category['name']); ?></span>
                                <span class="category-count"><?php echo $category['post_count']; ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </div>

    <?php if ($total_pages > 1): ?>
        <div class="pagination" data-aos="fade-up">
            <?php
                // Preserve query parameters in pagination links
                $query_params = [];
                if ($search_term) $query_params['search'] = $search_term;
                if ($category_slug) $query_params['category'] = $category_slug;
            ?>
            <?php if ($current_page > 1): ?>
                <a href="?<?php echo http_build_query(array_merge($query_params, ['page' => $current_page - 1])); ?>"><i class="fas fa-chevron-left"></i> Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $current_page): ?>
                    <span class="current-page"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?<?php echo http_build_query(array_merge($query_params, ['page' => $i])); ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?<?php echo http_build_query(array_merge($query_params, ['page' => $current_page + 1])); ?>">Next <i class="fas fa-chevron-right"></i></a>
            <?php endif; ?>
                        </div>
    <?php endif; ?>
</main>

<?php require_once '../includes/footer.php'; ?>