<?php

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
            $name = trim($_POST['name']);
            $link_url = trim($_POST['link_url']);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $current_logo = $_POST['current_logo'] ?? null;

            if (empty($name)) {
                throw new Exception("SDG Name is required.");
            }

            $logo_path = $current_logo;

            // Handle logo upload
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['logo'];
                $upload_dir = '../uploads/sdgs/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $allowed_types = ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'];
                if (!in_array($file['type'], $allowed_types)) throw new Exception('Invalid file type for logo. Only PNG, JPG, GIF, and SVG are allowed.');
                if ($file['size'] > 1048576) throw new Exception('Logo file size cannot exceed 1MB.');

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = 'sdg-' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    if ($logo_path && file_exists('../' . $logo_path)) {
                        unlink('../' . $logo_path);
                    }
                    $logo_path = 'uploads/sdgs/' . $new_filename;
                } else {
                    throw new Exception('Failed to upload logo.');
                }
            } elseif ($action === 'add' && empty($logo_path)) {
                throw new Exception('A logo is required when adding a new SDG.');
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO sdgs (name, logo_path, link_url) VALUES (?, ?, ?)");
                $stmt->execute([$name, $logo_path, $link_url]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'SDG added successfully.'];
            } else { // update
                if (!$id) throw new Exception("Invalid SDG ID.");
                $stmt = $pdo->prepare("UPDATE sdgs SET name = ?, logo_path = ?, link_url = ? WHERE id = ?");
                $stmt->execute([$name, $logo_path, $link_url, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'SDG updated successfully.'];
            }
        } elseif ($action === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid SDG ID.");

            $logo_stmt = $pdo->prepare("SELECT logo_path FROM sdgs WHERE id = ?");
            $logo_stmt->execute([$id]);
            $logo_to_delete = $logo_stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM sdgs WHERE id = ?");
            $stmt->execute([$id]);

            if ($logo_to_delete && file_exists('../' . $logo_to_delete)) {
                unlink('../' . $logo_to_delete);
            }

            $_SESSION['message'] = ['type' => 'success', 'text' => 'SDG deleted successfully.'];
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_sdgs.php");
    exit;
}

require_once 'includes/header.php';
require_once '../config/database.php';

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

$edit_mode = false;
$sdg_to_edit = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($edit_id) {
        $stmt = $pdo->prepare("SELECT * FROM sdgs WHERE id = ?");
        $stmt->execute([$edit_id]);
        $sdg_to_edit = $stmt->fetch();
        if ($sdg_to_edit) {
            $edit_mode = true;
        }
    }
}

$sdgs = $pdo->query("SELECT * FROM sdgs ORDER BY display_order ASC, created_at ASC")->fetchAll();
?>

<style>
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; }
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 2rem; }
    .logo-preview { max-height: 60px; width: auto; background: #f0f0f0; padding: 5px; border-radius: 5px; }
    .sortable-ghost { background: #f0f8ff; }
</style>

<h1>Manage SDGs</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <h2><?php echo $edit_mode ? 'Edit SDG' : 'Add New SDG'; ?></h2>
    <form action="manage_sdgs.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'add'; ?>">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($sdg_to_edit['id']); ?>">
            <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($sdg_to_edit['logo_path']); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">SDG Name (e.g., "SDG 13: Climate Action")</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($sdg_to_edit['name'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="logo">Logo (PNG, JPG, SVG)</label>
            <input type="file" id="logo" name="logo" class="form-control" <?php echo !$edit_mode ? 'required' : ''; ?>>
            <?php if ($edit_mode && !empty($sdg_to_edit['logo_path'])): ?>
                <p style="margin-top: 0.5rem;">Current logo: <img src="../<?php echo htmlspecialchars($sdg_to_edit['logo_path']); ?>" class="logo-preview"></p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="link_url">Link URL (Optional)</label>
            <input type="url" id="link_url" name="link_url" class="form-control" value="<?php echo htmlspecialchars($sdg_to_edit['link_url'] ?? ''); ?>" placeholder="https://sdgs.un.org/goals/goal13">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update SDG' : 'Add SDG'; ?></button>
            <?php if ($edit_mode): ?>
                <a href="manage_sdgs.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="table-wrapper">
    <h2>Existing SDGs</h2>
    <p>Drag and drop the rows to reorder the SDGs on the homepage.</p>
    <table class="table">
        <thead>
            <tr><th>Order</th><th>Logo</th><th>Name</th><th>Link</th><th>Actions</th></tr>
        </thead>
        <tbody id="sdg-sortable-list">
            <?php foreach ($sdgs as $sdg): ?>
            <tr data-id="<?php echo $sdg['id']; ?>">
                <td><i class="fas fa-grip-vertical" style="cursor: move;"></i></td>
                <td><img src="../<?php echo htmlspecialchars($sdg['logo_path']); ?>" alt="<?php echo htmlspecialchars($sdg['name']); ?>" class="logo-preview"></td>
                <td><strong><?php echo htmlspecialchars($sdg['name']); ?></strong></td>
                <td><a href="<?php echo htmlspecialchars($sdg['link_url']); ?>" target="_blank"><?php echo htmlspecialchars($sdg['link_url']); ?></a></td>
                <td>
                    <a href="manage_sdgs.php?action=edit&id=<?php echo $sdg['id']; ?>" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i> Edit</a>
                    <form action="manage_sdgs.php" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this SDG?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $sdg['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sortableList = document.getElementById('sdg-sortable-list');
    new Sortable(sortableList, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: function (evt) {
            const order = Array.from(sortableList.children).map(row => row.dataset.id);
            
            fetch('../ajax/update_sdg_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order: order })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Failed to save order: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error saving order:', error);
                alert('An error occurred while saving the new order.');
            });
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>