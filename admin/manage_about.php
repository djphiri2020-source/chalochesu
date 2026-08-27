<?php

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

        if ($action === 'update_vision_mission') {
            $vision = trim($_POST['vision']);
            $mission = trim($_POST['mission']);
            $current_image = $_POST['current_vision_mission_image'] ?? null;
            $image_path = $current_image;

            // Handle image upload
            if (isset($_FILES['vision_mission_image']) && $_FILES['vision_mission_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['vision_mission_image'];
                $upload_dir = '../uploads/about/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($file['type'], $allowed_types)) {
                    throw new Exception('Invalid file type for Vision & Mission image.');
                }
                if ($file['size'] > 5242880) { // 5MB limit
                    throw new Exception('Image file size cannot exceed 5MB.');
                }

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = 'vision-mission-' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    // Delete old image if it exists and is not the default
                    if ($current_image && file_exists('../' . $current_image) && strpos($current_image, 'assets/brand/') === false) {
                        unlink('../' . $current_image);
                    }
                    $image_path = 'uploads/about/' . $new_filename;
                } else {
                    throw new Exception('Failed to upload image.');
                }
            }

            $stmt = $pdo->prepare("INSERT INTO about_page_content (content_key, content_value) VALUES (?, ?), (?, ?), (?, ?) ON DUPLICATE KEY UPDATE content_value = VALUES(content_value)");
            $stmt->execute(['vision', $vision, 'mission', $mission, 'vision_mission_image', $image_path]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Vision & Mission updated.'];
        }

        // --- Core Values ---
        if ($action === 'add_value' || $action === 'update_value') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $icon = trim($_POST['icon']);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if ($action === 'add_value') {
                $stmt = $pdo->prepare("INSERT INTO core_values (title, description, icon) VALUES (?, ?, ?)");
                $stmt->execute([$title, $description, $icon]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Core Value added.'];
            } else {
                $stmt = $pdo->prepare("UPDATE core_values SET title = ?, description = ?, icon = ? WHERE id = ?");
                $stmt->execute([$title, $description, $icon, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Core Value updated.'];
            }
        } elseif ($action === 'delete_value') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $stmt = $pdo->prepare("DELETE FROM core_values WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Core Value deleted.'];
        }

        // --- Timeline Events ---
        if ($action === 'add_event' || $action === 'update_event') {
            $year = trim($_POST['year']);
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if ($action === 'add_event') {
                $stmt = $pdo->prepare("INSERT INTO timeline_events (year, title, description) VALUES (?, ?, ?)");
                $stmt->execute([$year, $title, $description]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Timeline event added.'];
            } else {
                $stmt = $pdo->prepare("UPDATE timeline_events SET year = ?, title = ?, description = ? WHERE id = ?");
                $stmt->execute([$year, $title, $description, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Timeline event updated.'];
            }
        } elseif ($action === 'delete_event') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $stmt = $pdo->prepare("DELETE FROM timeline_events WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Timeline event deleted.'];
        }

        // --- Stakeholders ---
        if ($action === 'add_stakeholder' || $action === 'update_stakeholder') {
            $name = trim($_POST['name']);
            $icon = trim($_POST['icon']);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if ($action === 'add_stakeholder') {
                $stmt = $pdo->prepare("INSERT INTO stakeholders (name, icon) VALUES (?, ?)");
                $stmt->execute([$name, $icon]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Stakeholder added.'];
            } else {
                $stmt = $pdo->prepare("UPDATE stakeholders SET name = ?, icon = ? WHERE id = ?");
                $stmt->execute([$name, $icon, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Stakeholder updated.'];
            }
        } elseif ($action === 'delete_stakeholder') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $stmt = $pdo->prepare("DELETE FROM stakeholders WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Stakeholder deleted.'];
        }

        // --- Partners ---
        if ($action === 'add_partner' || $action === 'update_partner') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if ($action === 'add_partner') {
                $stmt = $pdo->prepare("INSERT INTO partners (name, description) VALUES (?, ?)");
                $stmt->execute([$name, $description]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Partner added.'];
            } else {
                $stmt = $pdo->prepare("UPDATE partners SET name = ?, description = ? WHERE id = ?");
                $stmt->execute([$name, $description, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Partner updated.'];
            }
        } elseif ($action === 'delete_partner') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $stmt = $pdo->prepare("DELETE FROM partners WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Partner deleted.'];
        }

        // --- Clients ---
        if ($action === 'add_client' || $action === 'update_client') {
            $name = trim($_POST['name']);
            $website_url = trim($_POST['website_url']);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $current_logo = $_POST['current_logo'] ?? null;
            $logo_path = $current_logo;

            if (empty($name)) {
                throw new Exception("Client Name is required.");
            }

            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['logo'];
                $upload_dir = '../uploads/clients/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $allowed_types = ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'];
                if (!in_array($file['type'], $allowed_types)) throw new Exception('Invalid file type for logo.');
                if ($file['size'] > 20971520) throw new Exception('Logo file size cannot exceed 20MB.'); // 20MB limit

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = strtolower(str_replace(' ', '-', $name)) . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    if ($logo_path && file_exists('../' . $logo_path)) unlink('../' . $logo_path);
                    $logo_path = 'uploads/clients/' . $new_filename;
                } else { throw new Exception('Failed to upload logo.'); }
            } elseif ($action === 'add_client') {
                // If adding a client, logo is required unless it's an external URL
                if (empty($logo_path) && (empty($website_url) || strpos($website_url, 'http') !== 0)) {
                     throw new Exception('Logo is required for new clients.');
                }
            }

            if ($action === 'add_client') {
                $stmt = $pdo->prepare("INSERT INTO clients (name, website_url, logo) VALUES (?, ?, ?)");
                $stmt->execute([$name, $website_url, $logo_path]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Client added.'];
            } else {
                $stmt = $pdo->prepare("UPDATE clients SET name = ?, website_url = ?, logo = ? WHERE id = ?");
                $stmt->execute([$name, $website_url, $logo_path, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Client updated.'];
            }
        } elseif ($action === 'delete_client') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid client ID.");
            $logo_stmt = $pdo->prepare("SELECT logo FROM clients WHERE id = ?");
            $logo_stmt->execute([$id]);
            if ($logo_path = $logo_stmt->fetchColumn()) {
                // Only delete if it's a local file, not an external URL
                if (strpos($logo_path, 'http') !== 0 && file_exists('../' . $logo_path)) unlink('../' . $logo_path);
            }
            $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Client deleted.'];
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_about.php");
    exit;
}

require_once 'includes/header.php';
require_once '../config/database.php';

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// --- Handle Edit Mode ---
$edit_mode = $_GET['action'] ?? null;
$item_to_edit = null;
$edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($edit_id && $edit_mode) {
    $table_map = [
        'edit_value' => 'core_values',
        'edit_event' => 'timeline_events',
        'edit_stakeholder' => 'stakeholders',
        'edit_partner' => 'partners',
        'edit_client' => 'clients',
    ];

    if (isset($table_map[$edit_mode])) {
        $table = $table_map[$edit_mode];
        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE id = ?");
        $stmt->execute([$edit_id]);
        $item_to_edit = $stmt->fetch();
    }
}
// --- End Handle Edit Mode ---

// Fetch all content for the about page
$about_content_raw = $pdo->query("SELECT content_key, content_value FROM about_page_content")->fetchAll();
$about_content = array_column($about_content_raw, 'content_value', 'content_key');

$timeline_events = $pdo->query("SELECT * FROM timeline_events ORDER BY display_order ASC")->fetchAll();
$core_values = $pdo->query("SELECT * FROM core_values ORDER BY display_order ASC")->fetchAll();
$stakeholders = $pdo->query("SELECT * FROM stakeholders ORDER BY display_order ASC")->fetchAll();
$partners = $pdo->query("SELECT * FROM partners ORDER BY display_order ASC")->fetchAll();
$clients = $pdo->query("SELECT * FROM clients ORDER BY display_order ASC")->fetchAll();

?>

<style>
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 2rem; }
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; }
    .table th { background-color: #f8f9fa; }
    .management-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
    .icon-preview-box { font-size: 1.5rem; width: 40px; text-align: center; color: var(--primary-green); }
    .d-flex { display: flex; align-items: center; gap: 1rem; }
    .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem; }
    .image-preview { max-width: 100px; max-height: 100px; margin-top: 1rem; border-radius: var(--radius-sm); border: 1px solid #ddd; object-fit: cover; }
    .btn-danger { background-color: #dc3545; color: white; }
    @media (max-width: 992px) { .management-grid { grid-template-columns: 1fr; } }
</style>

<h1>Manage "About Us" Page Content</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<!-- Vision & Mission -->
<div class="form-card">
    <h2>Vision & Mission</h2>
    <form action="manage_about.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_vision_mission">
        <input type="hidden" name="current_vision_mission_image" value="<?php echo htmlspecialchars($about_content['vision_mission_image'] ?? ''); ?>">

        <div class="form-group">
            <label for="vision">Our Vision</label>
            <textarea id="vision" name="vision" class="form-control" rows="3"><?php echo htmlspecialchars($about_content['vision'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="mission">Our Mission</label>
            <textarea id="mission" name="mission" class="form-control" rows="3"><?php echo htmlspecialchars($about_content['mission'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="vision_mission_image">Vision & Mission Image</label>
            <input type="file" id="vision_mission_image" name="vision_mission_image" class="form-control">
            <?php if (!empty($about_content['vision_mission_image'])): ?>
                <div style="margin-top: 1rem;">
                    <p>Current Image:</p>
                    <img src="../<?php echo htmlspecialchars($about_content['vision_mission_image']); ?>" alt="Current Vision & Mission Image" class="image-preview">
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Save Vision & Mission</button>
    </form>
</div>

<div class="management-grid">
    <!-- Core Values -->
    <div class="table-wrapper">
        <h2 class="mb-2">Core Values <?php if ($edit_mode === 'edit_value') echo '(Editing)'; ?></h2>
        <div class="form-card" style="padding: 1rem; margin-bottom: 1rem;">
            <form action="manage_about.php" method="POST">
                <input type="hidden" name="action" value="<?php echo ($edit_mode === 'edit_value') ? 'update_value' : 'add_value'; ?>">
                <?php if ($edit_mode === 'edit_value'): ?>
                    <input type="hidden" name="id" value="<?php echo $item_to_edit['id']; ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($item_to_edit['title'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($item_to_edit['description'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Icon</label>
                    <div class="d-flex">
                        <div class="icon-preview-box"><i id="value_icon_preview" class="<?php echo htmlspecialchars($item_to_edit['icon'] ?? 'fas fa-question-circle'); ?>"></i></div>
                        <input type="hidden" id="value_icon_input" name="icon" value="<?php echo htmlspecialchars($item_to_edit['icon'] ?? ''); ?>">
                        <button type="button" class="btn btn-secondary open-icon-picker" data-input-id="value_icon_input" data-preview-id="value_icon_preview">Select Icon</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><?php echo ($edit_mode === 'edit_value') ? 'Update Value' : 'Add Value'; ?></button>
                <?php if ($edit_mode === 'edit_value'): ?>
                    <a href="manage_about.php" class="btn btn-secondary btn-sm">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
        <table class="table">
            <thead><tr><th>Icon</th><th>Title</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($core_values as $value): ?>
                <tr>
                    <td><i class="<?php echo htmlspecialchars($value['icon']); ?>"></i></td>
                    <td><?php echo htmlspecialchars($value['title']); ?></td>
                    <td>
                        <a href="?action=edit_value&id=<?php echo $value['id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                        <form action="manage_about.php" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                            <input type="hidden" name="action" value="delete_value">
                            <input type="hidden" name="id" value="<?php echo $value['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Timeline -->
    <div class="table-wrapper">
        <h2 class="mb-2">Our Approach <?php if ($edit_mode === 'edit_event') echo '(Editing)'; ?></h2>
        <div class="form-card" style="padding: 1rem; margin-bottom: 1rem;">
            <form action="manage_about.php" method="POST">
                <input type="hidden" name="action" value="<?php echo ($edit_mode === 'edit_event') ? 'update_event' : 'add_event'; ?>">
                <?php if ($edit_mode === 'edit_event'): ?>
                    <input type="hidden" name="id" value="<?php echo $item_to_edit['id']; ?>">
                <?php endif; ?>
                <div class="form-group"><label>Step / Phase</label><input type="text" name="year" class="form-control" value="<?php echo htmlspecialchars($item_to_edit['year'] ?? ''); ?>" required></div>
                <div class="form-group"><label>Title</label><input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($item_to_edit['title'] ?? ''); ?>" required></div>
                <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="2" required><?php echo htmlspecialchars($item_to_edit['description'] ?? ''); ?></textarea></div>
                <button type="submit" class="btn btn-primary btn-sm"><?php echo ($edit_mode === 'edit_event') ? 'Update Event' : 'Add Event'; ?></button>
                <?php if ($edit_mode === 'edit_event'): ?>
                    <a href="manage_about.php" class="btn btn-secondary btn-sm">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
        <table class="table">
            <thead><tr><th>Step / Phase</th><th>Title</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($timeline_events as $event): ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['year']); ?></td>
                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                    <td>
                        <a href="?action=edit_event&id=<?php echo $event['id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                        <form action="manage_about.php" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                            <input type="hidden" name="action" value="delete_event">
                            <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Stakeholders -->
    <div class="table-wrapper">
        <h2 class="mb-2">Stakeholders We Serve <?php if ($edit_mode === 'edit_stakeholder') echo '(Editing)'; ?></h2>
        <div class="form-card" style="padding: 1rem; margin-bottom: 1rem;">
            <form action="manage_about.php" method="POST">
                <input type="hidden" name="action" value="<?php echo ($edit_mode === 'edit_stakeholder') ? 'update_stakeholder' : 'add_stakeholder'; ?>">
                <?php if ($edit_mode === 'edit_stakeholder'): ?>
                    <input type="hidden" name="id" value="<?php echo $item_to_edit['id']; ?>">
                <?php endif; ?>
                <div class="form-group"><label>Name</label><input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($item_to_edit['name'] ?? ''); ?>" required></div>
                <div class="form-group">
                    <label>Icon</label>
                    <div class="d-flex">
                        <div class="icon-preview-box"><i id="stakeholder_icon_preview" class="<?php echo htmlspecialchars($item_to_edit['icon'] ?? 'fas fa-question-circle'); ?>"></i></div>
                        <input type="hidden" id="stakeholder_icon_input" name="icon" value="<?php echo htmlspecialchars($item_to_edit['icon'] ?? ''); ?>">
                        <button type="button" class="btn btn-secondary open-icon-picker" data-input-id="stakeholder_icon_input" data-preview-id="stakeholder_icon_preview">Select Icon</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><?php echo ($edit_mode === 'edit_stakeholder') ? 'Update Stakeholder' : 'Add Stakeholder'; ?></button>
                <?php if ($edit_mode === 'edit_stakeholder'): ?>
                    <a href="manage_about.php" class="btn btn-secondary btn-sm">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
        <table class="table">
            <thead><tr><th>Icon</th><th>Name</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($stakeholders as $stakeholder): ?>
                <tr>
                    <td><i class="<?php echo htmlspecialchars($stakeholder['icon']); ?>"></i></td>
                    <td><?php echo htmlspecialchars($stakeholder['name']); ?></td>
                    <td>
                        <a href="?action=edit_stakeholder&id=<?php echo $stakeholder['id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                        <form action="manage_about.php" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                            <input type="hidden" name="action" value="delete_stakeholder">
                            <input type="hidden" name="id" value="<?php echo $stakeholder['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Partners -->
    <div class="table-wrapper">
        <h2 class="mb-2">Our Partners <?php if ($edit_mode === 'edit_partner') echo '(Editing)'; ?></h2>
        <div class="form-card" style="padding: 1rem; margin-bottom: 1rem;">
            <form action="manage_about.php" method="POST">
                <input type="hidden" name="action" value="<?php echo ($edit_mode === 'edit_partner') ? 'update_partner' : 'add_partner'; ?>">
                <?php if ($edit_mode === 'edit_partner'): ?>
                    <input type="hidden" name="id" value="<?php echo $item_to_edit['id']; ?>">
                <?php endif; ?>
                <div class="form-group"><label>Name</label><input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($item_to_edit['name'] ?? ''); ?>" required></div>
                <div class="form-group"><label>Description</label><input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($item_to_edit['description'] ?? ''); ?>"></div>
                <button type="submit" class="btn btn-primary btn-sm"><?php echo ($edit_mode === 'edit_partner') ? 'Update Partner' : 'Add Partner'; ?></button>
                <?php if ($edit_mode === 'edit_partner'): ?>
                    <a href="manage_about.php" class="btn btn-secondary btn-sm">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
        <table class="table">
            <thead><tr><th>Name</th><th>Description</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($partners as $partner): ?>
                <tr>
                    <td><?php echo htmlspecialchars($partner['name']); ?></td>
                    <td><?php echo htmlspecialchars($partner['description']); ?></td>
                    <td>
                        <a href="?action=edit_partner&id=<?php echo $partner['id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                        <form action="manage_about.php" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                            <input type="hidden" name="action" value="delete_partner">
                            <input type="hidden" name="id" value="<?php echo $partner['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Clients -->
<div class="table-wrapper" style="margin-top: 2rem;">
    <h2 class="mb-2">Clients <?php if ($edit_mode === 'edit_client') echo '(Editing)'; ?></h2>
    <div class="form-card" style="padding: 1rem; margin-bottom: 1rem;">
        <form action="manage_about.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo ($edit_mode === 'edit_client') ? 'update_client' : 'add_client'; ?>">
            <?php if ($edit_mode === 'edit_client'): ?>
                <input type="hidden" name="id" value="<?php echo $item_to_edit['id']; ?>">
                <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($item_to_edit['logo'] ?? ''); ?>">
            <?php endif; ?>
            <div class="form-group"><label>Client Name</label><input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($item_to_edit['name'] ?? ''); ?>" required></div>
            <div class="form-group"><label>Website URL (Optional)</label><input type="url" name="website_url" class="form-control" value="<?php echo htmlspecialchars($item_to_edit['website_url'] ?? ''); ?>"></div>
            <div class="form-group">
                <label>Logo</label>
                <input type="file" name="logo" class="form-control" <?php if ($edit_mode !== 'edit_client') echo 'required'; ?>>
                <?php if ($edit_mode === 'edit_client' && !empty($item_to_edit['logo'])): ?>
                    <p style="margin-top:1rem; margin-bottom:0.5rem;">Current Logo:</p>
                    <img src="<?php echo (strpos($item_to_edit['logo'], 'http') === 0 ? '' : '../') . htmlspecialchars($item_to_edit['logo']); ?>" alt="Current Logo" class="image-preview" style="max-height: 80px; width: auto;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><?php echo ($edit_mode === 'edit_client') ? 'Update Client' : 'Add Client'; ?></button>
            <?php if ($edit_mode === 'edit_client'): ?>
                <a href="manage_about.php" class="btn btn-secondary btn-sm">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
    <table class="table">
        <thead><tr><th>Logo</th><th>Name</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><img src="<?php echo (strpos($client['logo'], 'http') === 0 ? '' : '../') . htmlspecialchars($client['logo']); ?>" alt="<?php echo htmlspecialchars($client['name']); ?>" style="height: 40px; max-width: 100px; object-fit: contain; background: #eee;"></td>
                <td><a href="<?php echo htmlspecialchars($client['website_url']); ?>" target="_blank"><?php echo htmlspecialchars($client['name']); ?></a></td>
                <td>
                    <a href="?action=edit_client&id=<?php echo $client['id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                    <form action="manage_about.php" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                        <input type="hidden" name="action" value="delete_client">
                        <input type="hidden" name="id" value="<?php echo $client['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
    .password-note { font-size: 0.9rem; color: #6c757d; margin-bottom: 1rem; }
    .image-management-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; }
    .image-thumbnail { position: relative; }
    .image-thumbnail img { width: 100%; height: 80px; object-fit: contain; border-radius: var(--radius-sm); }
</style>

<?php require_once 'includes/footer.php'; ?>