<?php
require_once 'includes/header.php';
require_once '../config/database.php';

// --- SECURITY: Role-Based Access Control ---
// Only allow 'admin' users to access this page.
if ($_SESSION['user_role'] !== 'admin') {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'You do not have permission to access this page.'];
    header('Location: dashboard.php');
    exit;
}

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Function to create a URL-friendly slug from a string
function create_slug($string) {
    $string = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return trim($string, '-');
}

// Handle POST requests for CUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add' || $action === 'update') {
            $name = trim($_POST['name']);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($name)) {
                throw new Exception("Category name is required.");
            }

            $slug = create_slug($name);
            // Check for unique slug
            $slug_check_stmt = $pdo->prepare("SELECT id FROM categories WHERE slug = ? AND id != ?");
            $slug_check_stmt->execute([$slug, $id ?? 0]);
            if ($slug_check_stmt->fetch()) {
                throw new Exception("A category with a similar name already exists.");
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
                $stmt->execute([$name, $slug]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Category added successfully.'];
            } else { // update
                if (!$id) throw new Exception("Invalid category ID.");
                $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
                $stmt->execute([$name, $slug, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Category updated successfully.'];
            }
        } elseif ($action === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid category ID.");
            
            // The ON DELETE CASCADE constraint on the post_categories table will handle removing associations.
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Category deleted successfully.'];
        }
    } catch (Exception $e) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_categories.php");
    exit;
}

// Determine if we are editing or adding
$edit_mode = false;
$category_to_edit = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($edit_id) {
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$edit_id]);
        $category_to_edit = $stmt->fetch();
        if ($category_to_edit) {
            $edit_mode = true;
        }
    }
}

// Fetch all categories with post counts
$categories = $pdo->query("
    SELECT c.id, c.name, c.slug, COUNT(pc.post_id) as post_count
    FROM categories c
    LEFT JOIN post_categories pc ON c.id = pc.category_id
    GROUP BY c.id, c.name, c.slug
    ORDER BY c.name ASC
")->fetchAll();
?>

<style>
    .management-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    .table th { background-color: #f8f9fa; }
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    @media (max-width: 992px) { .management-layout { grid-template-columns: 1fr; } }
</style>

<h1>Manage Categories</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<div class="management-layout">
    <!-- Categories List -->
    <div class="table-wrapper">
        <h2>Existing Categories</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Post Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr><td colspan="4" style="text-align: center;">No categories found.</td></tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($category['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($category['slug']); ?></td>
                            <td><?php echo $category['post_count']; ?></td>
                            <td>
                                <a href="manage_categories.php?action=edit&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i> Edit</a>
                                <form action="manage_categories.php" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this category? All posts will be un-categorized.');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Form -->
    <div class="form-card">
        <h2><?php echo $edit_mode ? 'Edit Category' : 'Add New Category'; ?></h2>
        <form action="manage_categories.php" method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'add'; ?>">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($category_to_edit['id']); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($category_to_edit['name'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update Category' : 'Add Category'; ?></button>
                <?php if ($edit_mode): ?>
                    <a href="manage_categories.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<style>
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-danger:hover { background-color: #c82333; }
    .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem; }
</style>

<?php
require_once 'includes/footer.php';
?>