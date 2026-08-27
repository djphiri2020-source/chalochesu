<?php

// Handle POST requests for CUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // The session must be started before any other logic.
    session_start();
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
    require_once '../config/database.php';

    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add' || $action === 'update') {
            $author_name = trim($_POST['author_name']);
            $author_position = trim($_POST['author_position']);
            $current_image = $_POST['current_author_image'] ?? null;
            $content = trim($_POST['content']);
            $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 5]]);
            $is_approved = isset($_POST['is_approved']) ? 1 : 0;

            if (empty($author_name) || empty($content) || $rating === false) {
                throw new Exception("Author Name, Content, and a valid Rating (1-5) are required.");
            }

            $author_image_path = $current_image;
            // Handle image upload
            if (isset($_FILES['author_image']) && $_FILES['author_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['author_image'];
                $upload_dir = '../uploads/testimonials/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($file['type'], $allowed_types)) throw new Exception('Invalid file type for author image. Only JPG, PNG, GIF are allowed.');
                if ($file['size'] > 20971520) throw new Exception('Image file size cannot exceed 20MB.');

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = 'author_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    if ($author_image_path && file_exists('../' . $author_image_path)) {
                        unlink('../' . $author_image_path);
                    }
                    $author_image_path = 'uploads/testimonials/' . $new_filename;
                } else {
                    throw new Exception('Failed to upload author image.');
                }
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO testimonials (author_name, author_position, author_image, content, rating, is_approved) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$author_name, $author_position, $author_image_path, $content, $rating, $is_approved]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Testimonial added successfully.'];
            } else { // update
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                if (!$id) throw new Exception("Invalid testimonial ID.");
                $stmt = $pdo->prepare("UPDATE testimonials SET author_name = ?, author_position = ?, author_image = ?, content = ?, rating = ?, is_approved = ? WHERE id = ?");
                $stmt->execute([$author_name, $author_position, $author_image_path, $content, $rating, $is_approved, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Testimonial updated successfully.'];
            }
        } elseif ($action === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid testimonial ID.");
            $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Testimonial deleted successfully.'];
        } elseif ($action === 'toggle_approval') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) {
                throw new Exception("Invalid testimonial ID.");
            }
            $stmt = $pdo->prepare("UPDATE testimonials SET is_approved = !is_approved WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Approval status updated.'];
        }
    } catch (Exception $e) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_testimonials.php");
    exit;
}

// If it's not a POST request, we start the normal page load process.
require_once 'includes/header.php';
require_once '../config/database.php';

// Display session messages after the header is included.
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Determine if we are editing or adding
$edit_mode = false;
$testimonial_to_edit = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($edit_id) {
        $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
        $stmt->execute([$edit_id]);
        $testimonial_to_edit = $stmt->fetch();
        if ($testimonial_to_edit) {
            $edit_mode = true;
        }
    }
}

// Fetch all testimonials for display
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll();
?>

<style>
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    .table th { background-color: #f8f9fa; }
    .table td .btn { margin-right: 5px; }
    .status-approved { color: #28a745; font-weight: bold; }
    .status-pending { color: #ffc107; font-weight: bold; }
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 2rem; }
    .image-preview { max-width: 80px; max-height: 80px; border-radius: 50%; margin-top: 1rem; border: 1px solid #ddd; object-fit: cover; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-group.full-width { grid-column: 1 / -1; }
</style>

<h1>Manage Testimonials</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<!-- Add/Edit Form -->
<div class="form-card">
    <h2><?php echo $edit_mode ? 'Edit Testimonial' : 'Add New Testimonial'; ?></h2>
    <form action="manage_testimonials.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'add'; ?>">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($testimonial_to_edit['id']); ?>">
        <?php endif; ?>

        <div class="form-grid">
            <div class="form-group">
                <label for="author_name">Author Name</label>
                <input type="text" id="author_name" name="author_name" class="form-control" value="<?php echo htmlspecialchars($testimonial_to_edit['author_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="author_position">Author Position/Company</label>
                <input type="text" id="author_position" name="author_position" class="form-control" value="<?php echo htmlspecialchars($testimonial_to_edit['author_position'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="author_image">Author Image</label>
                <input type="file" id="author_image" name="author_image" class="form-control">
                <?php if ($edit_mode && !empty($testimonial_to_edit['author_image'])): ?>
                    <p>Current Image:</p>
                    <img src="../<?php echo htmlspecialchars($testimonial_to_edit['author_image']); ?>" alt="Author Image" class="image-preview">
                    <input type="hidden" name="current_author_image" value="<?php echo htmlspecialchars($testimonial_to_edit['author_image']); ?>">
                <?php endif; ?>
            </div>
            <div class="form-group full-width">
                <label for="content">Content</label>
                <textarea id="content" name="content" class="form-control" rows="4" required><?php echo htmlspecialchars($testimonial_to_edit['content'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="rating">Rating (1-5)</label>
                <input type="number" id="rating" name="rating" class="form-control" min="1" max="5" value="<?php echo htmlspecialchars($testimonial_to_edit['rating'] ?? '5'); ?>" required>
            </div>
            <div class="form-group" style="align-self: center;">
                <label>
                    <input type="checkbox" name="is_approved" value="1" <?php echo ($testimonial_to_edit['is_approved'] ?? 0) ? 'checked' : ''; ?>>
                    Approved
                </label>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update Testimonial' : 'Add Testimonial'; ?></button>
            <?php if ($edit_mode): ?>
                <a href="manage_testimonials.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Testimonials List -->
<div class="table-wrapper">
    <h2>Existing Testimonials</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Author</th>
                <th>Position</th>
                <th width="40%">Content</th>
                <th>Rating</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($testimonials)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No testimonials found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($testimonials as $testimonial): ?>
                    <tr>
                        <td>
                            <?php if (!empty($testimonial['author_image'])): ?>
                                <img src="../<?php echo htmlspecialchars($testimonial['author_image']); ?>" alt="Author" class="image-preview" style="width: 50px; height: 50px;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($testimonial['author_name']); ?></td>
                        <td><?php echo htmlspecialchars($testimonial['author_position']); ?></td>
                        <td><?php echo htmlspecialchars(substr($testimonial['content'], 0, 100)) . (strlen($testimonial['content']) > 100 ? '...' : ''); ?></td>
                        <td><?php echo htmlspecialchars($testimonial['rating']); ?>/5</td>
                        <td>
                            <span class="<?php echo $testimonial['is_approved'] ? 'status-approved' : 'status-pending'; ?>">
                                <?php echo $testimonial['is_approved'] ? 'Approved' : 'Pending'; ?>
                            </span>
                        </td>
                        <td>
                            <!-- Toggle Approval Form -->
                            <form action="manage_testimonials.php" method="POST" style="display: inline-block;">
                                <input type="hidden" name="action" value="toggle_approval">
                                <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
                                <button type="submit" class="btn btn-sm <?php echo $testimonial['is_approved'] ? 'btn-secondary' : 'btn-primary'; ?>">
                                    <?php echo $testimonial['is_approved'] ? 'Unapprove' : 'Approve'; ?>
                                </button>
                            </form>

                            <!-- Edit Link -->
                            <a href="manage_testimonials.php?action=edit&id=<?php echo $testimonial['id']; ?>" class="btn btn-sm btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <!-- Delete Form -->
                            <form action="manage_testimonials.php" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
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
    /* A more specific style for the danger button if not already in a global css */
    .btn-danger {
        background-color: #dc3545;
        color: white;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.2rem;
    }
</style>

<?php
require_once 'includes/footer.php';
?>