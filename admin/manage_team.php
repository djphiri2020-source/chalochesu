<?php

// Handle POST requests first
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
            $full_name = trim($_POST['full_name']);
            $role = trim($_POST['role']);
            $bio = trim($_POST['bio']);
            $linkedin_url = trim($_POST['linkedin_url']);
            $twitter_url = trim($_POST['twitter_url']);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($full_name) || empty($role)) {
                throw new Exception("Full Name and Role are required.");
            }

            $photo_path = $_POST['current_photo'] ?? null;

            // Handle photo upload
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['photo'];
                $upload_dir = '../assets/images/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $allowed_types = ['image/png', 'image/jpeg', 'image/gif'];
                if (!in_array($file['type'], $allowed_types)) throw new Exception('Invalid file type for photo.');
                if ($file['size'] > 5242880) throw new Exception('Photo file size cannot exceed 5MB.');

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = strtolower(str_replace(' ', '-', $full_name)) . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    if ($photo_path && file_exists('../' . $photo_path)) {
                        unlink('../' . $photo_path);
                    }
                    $photo_path = 'assets/images/' . $new_filename;
                } else {
                    throw new Exception('Failed to upload photo.');
                }
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO team_members (full_name, role, bio, photo, linkedin_url, twitter_url) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$full_name, $role, $bio, $photo_path, $linkedin_url, $twitter_url]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Team member added successfully.'];
            } else { // update
                if (!$id) throw new Exception("Invalid team member ID.");
                $stmt = $pdo->prepare("UPDATE team_members SET full_name = ?, role = ?, bio = ?, photo = ?, linkedin_url = ?, twitter_url = ? WHERE id = ?");
                $stmt->execute([$full_name, $role, $bio, $photo_path, $linkedin_url, $twitter_url, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Team member updated successfully.'];
            }
        } elseif ($action === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid team member ID.");

            $img_stmt = $pdo->prepare("SELECT photo FROM team_members WHERE id = ?");
            $img_stmt->execute([$id]);
            $image_to_delete = $img_stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = ?");
            $stmt->execute([$id]);

            if ($image_to_delete && file_exists('../' . $image_to_delete)) {
                unlink('../' . $image_to_delete);
            }

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Team member deleted successfully.'];
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_team.php");
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
$member_to_edit = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($edit_id) {
        $stmt = $pdo->prepare("SELECT * FROM team_members WHERE id = ?");
        $stmt->execute([$edit_id]);
        $member_to_edit = $stmt->fetch();
        if ($member_to_edit) {
            $edit_mode = true;
        }
    }
}

$team_members = $pdo->query("SELECT * FROM team_members ORDER BY display_order ASC, created_at DESC")->fetchAll();
?>

<style>
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; }
    .table th { background-color: #f8f9fa; }
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 2rem; }
    .image-preview { max-width: 100px; max-height: 100px; margin-top: 1rem; border-radius: var(--radius-sm); border: 1px solid #ddd; }
    .table-img-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 50%; }
</style>

<h1>Manage Team Members</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <h2><?php echo $edit_mode ? 'Edit Team Member' : 'Add New Team Member'; ?></h2>
    <form action="manage_team.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'add'; ?>">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($member_to_edit['id']); ?>">
        <?php endif; ?>

        <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo htmlspecialchars($member_to_edit['full_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role / Position</label>
                <input type="text" id="role" name="role" class="form-control" value="<?php echo htmlspecialchars($member_to_edit['role'] ?? ''); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="bio">Biography</label>
            <textarea id="bio" name="bio" class="form-control" rows="4"><?php echo htmlspecialchars($member_to_edit['bio'] ?? ''); ?></textarea>
        </div>

        <div class="form-grid" style="grid-template-columns: 1fr 1fr 1fr;">
            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" id="photo" name="photo" class="form-control">
                <?php if ($edit_mode && !empty($member_to_edit['photo'])): ?>
                    <img src="../<?php echo htmlspecialchars($member_to_edit['photo']); ?>" alt="Current Photo" class="image-preview">
                    <input type="hidden" name="current_photo" value="<?php echo htmlspecialchars($member_to_edit['photo']); ?>">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="linkedin_url">LinkedIn URL</label>
                <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" value="<?php echo htmlspecialchars($member_to_edit['linkedin_url'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="twitter_url">X (Twitter) URL</label>
                <input type="url" id="twitter_url" name="twitter_url" class="form-control" value="<?php echo htmlspecialchars($member_to_edit['twitter_url'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update Member' : 'Add Member'; ?></button>
            <?php if ($edit_mode): ?>
                <a href="manage_team.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="table-wrapper">
    <h2>Existing Team</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($team_members as $member): ?>
                <tr>
                    <td>
                        <img src="../<?php echo htmlspecialchars($member['photo'] ?? 'assets/brand/placeholder-avatar.png'); ?>" alt="<?php echo htmlspecialchars($member['full_name']); ?>" class="table-img-preview">
                    </td>
                    <td><strong><?php echo htmlspecialchars($member['full_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($member['role']); ?></td>
                    <td>
                        <a href="manage_team.php?action=edit&id=<?php echo $member['id']; ?>" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i> Edit</a>
                        <form action="manage_team.php" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this team member?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-danger:hover { background-color: #c82333; }
    .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem; }
</style>

<?php require_once 'includes/footer.php'; ?>