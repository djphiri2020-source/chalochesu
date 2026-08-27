<?php

// Function to create a URL-friendly slug from a string
function create_slug($string) {
    $string = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return trim($string, '-');
}

// Handle POST requests for CUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
    require_once '../config/database.php';

    $action = $_POST['action'] ?? '';

    try {
        $pdo->beginTransaction();

        if ($action === 'add' || $action === 'update') {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $excerpt = trim($_POST['excerpt']);
            $status = $_POST['status'];
            $category_ids = $_POST['categories'] ?? [];
            $author_id = $_SESSION['user_id'];
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($title) || empty($content) || !in_array($status, ['draft', 'published', 'archived'])) {
                throw new Exception("Title, Content, and a valid Status are required.");
            }

            $slug = create_slug($title);
            // Check for unique slug
            $slug_check_stmt = $pdo->prepare("SELECT id FROM posts WHERE slug = ? AND id != ?");
            $slug_check_stmt->execute([$slug, $id ?? 0]);
            if ($slug_check_stmt->fetch()) {
                $slug .= '-' . time(); // Append timestamp to make it unique
            }

            $featured_image_path = $_POST['current_featured_image'] ?? null;

            // Handle featured image upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['featured_image'];
                $upload_dir = '../uploads/posts/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $allowed_types = ['image/png', 'image/jpeg', 'image/gif'];
                if (!in_array($file['type'], $allowed_types)) throw new Exception('Invalid file type for image.');
                if ($file['size'] > 20971520) throw new Exception('Image file size cannot exceed 20MB.');

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = $slug . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    // Delete old image if it exists
                    if ($featured_image_path && file_exists('../' . $featured_image_path)) {
                        unlink('../' . $featured_image_path);
                    }
                    $featured_image_path = 'uploads/posts/' . $new_filename;
                } else {
                    throw new Exception('Failed to upload featured image.');
                }
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO posts (title, slug, content, excerpt, featured_image, author_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $slug, $content, $excerpt, $featured_image_path, $author_id, $status]);
                $post_id = $pdo->lastInsertId();

                // Link categories
                $cat_stmt = $pdo->prepare("INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)");
                foreach ($category_ids as $cat_id) {
                    $cat_stmt->execute([$post_id, $cat_id]);
                }
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Blog post created successfully.'];
            } else { // update
                if (!$id) throw new Exception("Invalid post ID.");
                $stmt = $pdo->prepare("UPDATE posts SET title = ?, slug = ?, content = ?, excerpt = ?, featured_image = ?, status = ? WHERE id = ?");
                $stmt->execute([$title, $slug, $content, $excerpt, $featured_image_path, $status, $id]);

                // Update categories: delete old and insert new
                $pdo->prepare("DELETE FROM post_categories WHERE post_id = ?")->execute([$id]);
                $cat_stmt = $pdo->prepare("INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)");
                foreach ($category_ids as $cat_id) {
                    $cat_stmt->execute([$id, $cat_id]);
                }
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Blog post updated successfully.'];
            }
        } elseif ($action === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid post ID.");

            // Get image path before deleting post
            $img_stmt = $pdo->prepare("SELECT featured_image FROM posts WHERE id = ?");
            $img_stmt->execute([$id]);
            $image_to_delete = $img_stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$id]);

            // Delete the associated image file
            if ($image_to_delete && file_exists('../' . $image_to_delete)) {
                unlink('../' . $image_to_delete);
            }

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Blog post deleted successfully.'];
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_posts.php");
    exit;
}

require_once 'includes/header.php';
require_once '../config/database.php';

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Handle POST requests for CUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        $pdo->beginTransaction();

        if ($action === 'add' || $action === 'update') {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);
            $excerpt = trim($_POST['excerpt']);
            $status = $_POST['status'];
            $category_ids = $_POST['categories'] ?? [];
            $author_id = $_SESSION['user_id'];
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($title) || empty($content) || !in_array($status, ['draft', 'published', 'archived'])) {
                throw new Exception("Title, Content, and a valid Status are required.");
            }

            $slug = create_slug($title);
            // Check for unique slug
            $slug_check_stmt = $pdo->prepare("SELECT id FROM posts WHERE slug = ? AND id != ?");
            $slug_check_stmt->execute([$slug, $id ?? 0]);
            if ($slug_check_stmt->fetch()) {
                $slug .= '-' . time(); // Append timestamp to make it unique
            }

            $featured_image_path = $_POST['current_featured_image'] ?? null;

            // Handle featured image upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['featured_image'];
                $upload_dir = '../uploads/posts/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $allowed_types = ['image/png', 'image/jpeg', 'image/gif'];
                if (!in_array($file['type'], $allowed_types)) throw new Exception('Invalid file type for image.');
                if ($file['size'] > 20971520) throw new Exception('Image file size cannot exceed 20MB.');

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = $slug . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    // Delete old image if it exists
                    if ($featured_image_path && file_exists('../' . $featured_image_path)) {
                        unlink('../' . $featured_image_path);
                    }
                    $featured_image_path = 'uploads/posts/' . $new_filename;
                } else {
                    throw new Exception('Failed to upload featured image.');
                }
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO posts (title, slug, content, excerpt, featured_image, author_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $slug, $content, $excerpt, $featured_image_path, $author_id, $status]);
                $post_id = $pdo->lastInsertId();

                // Link categories
                $cat_stmt = $pdo->prepare("INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)");
                foreach ($category_ids as $cat_id) {
                    $cat_stmt->execute([$post_id, $cat_id]);
                }
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Blog post created successfully.'];
            } else { // update
                if (!$id) throw new Exception("Invalid post ID.");
                $stmt = $pdo->prepare("UPDATE posts SET title = ?, slug = ?, content = ?, excerpt = ?, featured_image = ?, status = ? WHERE id = ?");
                $stmt->execute([$title, $slug, $content, $excerpt, $featured_image_path, $status, $id]);

                // Update categories: delete old and insert new
                $pdo->prepare("DELETE FROM post_categories WHERE post_id = ?")->execute([$id]);
                $cat_stmt = $pdo->prepare("INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)");
                foreach ($category_ids as $cat_id) {
                    $cat_stmt->execute([$id, $cat_id]);
                }
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Blog post updated successfully.'];
            }
        } elseif ($action === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid post ID.");

            // Get image path before deleting post
            $img_stmt = $pdo->prepare("SELECT featured_image FROM posts WHERE id = ?");
            $img_stmt->execute([$id]);
            $image_to_delete = $img_stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$id]);

            // Delete the associated image file
            if ($image_to_delete && file_exists('../' . $image_to_delete)) {
                unlink('../' . $image_to_delete);
            }

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Blog post deleted successfully.'];
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_posts.php");
    exit;
}

// Determine if we are editing or adding
$edit_mode = false;
$post_to_edit = null;
$post_category_ids = [];
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($edit_id) {
        // Fetch post
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$edit_id]);
        $post_to_edit = $stmt->fetch();
        if ($post_to_edit) {
            $edit_mode = true;
            // Fetch associated categories
            $cat_stmt = $pdo->prepare("SELECT category_id FROM post_categories WHERE post_id = ?");
            $cat_stmt->execute([$edit_id]);
            $post_category_ids = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);
        }
    }
}

// Fetch all categories for the form
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// Fetch all posts for display, joining with users table to get author name
$posts = $pdo->query("
    SELECT p.id, p.title, p.status, p.created_at, p.updated_at, u.username as author_name
    FROM posts p
    JOIN users u ON p.author_id = u.id
    ORDER BY p.created_at DESC
")->fetchAll();
?>

<style>
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    .table th { background-color: #f8f9fa; }
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 2rem; }
    .status-published { color: #28a745; font-weight: bold; }
    .status-draft { color: #6c757d; font-weight: bold; }
    .status-archived { color: #dc3545; font-weight: bold; }
    .image-preview { max-width: 200px; max-height: 150px; margin-top: 1rem; border-radius: var(--radius-sm); border: 1px solid #ddd; }
    .category-list { list-style: none; padding: 0; max-height: 150px; overflow-y: auto; border: 1px solid #ddd; padding: 0.5rem; border-radius: var(--radius-sm); }
    .category-list li { margin-bottom: 0.5rem; }
    .form-layout { display: grid; grid-template-columns: 3fr 1fr; gap: 2rem; }
    @media (max-width: 992px) {
        .form-layout { grid-template-columns: 1fr; }
    }
</style>

<h1>Manage Blog Posts</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<!-- Add/Edit Form -->
<div class="form-card">
    <h2><?php echo $edit_mode ? 'Edit Post' : 'Add New Post'; ?></h2>
    <form action="manage_posts.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'add'; ?>">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($post_to_edit['id']); ?>">
        <?php endif; ?>

        <div class="form-layout">
            <div> <!-- Main Content Column -->
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($post_to_edit['title'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" class="form-control" rows="10"><?php echo htmlspecialchars($post_to_edit['content'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="excerpt">Excerpt (Short Summary)</label>
                    <textarea id="excerpt" name="excerpt" class="form-control" rows="3"><?php echo htmlspecialchars($post_to_edit['excerpt'] ?? ''); ?></textarea>
                </div>
            </div>
            <div> <!-- Sidebar Column -->
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="draft" <?php echo ($post_to_edit['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo ($post_to_edit['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                        <option value="archived" <?php echo ($post_to_edit['status'] ?? '') === 'archived' ? 'selected' : ''; ?>>Archived</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Categories</label>
                    <ul class="category-list">
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <label>
                                    <input type="checkbox" name="categories[]" value="<?php echo $category['id']; ?>" <?php echo in_array($category['id'], $post_category_ids) ? 'checked' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="form-group">
                    <label for="featured_image">Featured Image</label>
                    <input type="file" id="featured_image" name="featured_image" class="form-control">
                    <?php if ($edit_mode && !empty($post_to_edit['featured_image'])): ?>
                        <p>Current Image:</p>
                        <img src="../<?php echo htmlspecialchars($post_to_edit['featured_image']); ?>" alt="Current Image" class="image-preview">
                        <input type="hidden" name="current_featured_image" value="<?php echo htmlspecialchars($post_to_edit['featured_image']); ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update Post' : 'Create Post'; ?></button>
            <?php if ($edit_mode): ?>
                <a href="manage_posts.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Posts List -->
<div class="table-wrapper">
    <h2>Existing Posts</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($posts)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No posts found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($post['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($post['author_name']); ?></td>
                        <td>
                            <span class="status-<?php echo htmlspecialchars($post['status']); ?>">
                                <?php echo ucfirst(htmlspecialchars($post['status'])); ?>
                            </span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                        <td><?php echo date('M j, Y', strtotime($post['updated_at'])); ?></td>
                        <td>
                            <a href="manage_posts.php?action=edit&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="manage_posts.php" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-danger:hover { background-color: #c82333; }
    .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem; }
</style>

<?php
require_once 'includes/footer.php';
?>