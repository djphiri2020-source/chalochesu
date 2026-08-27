<?php

// Function to create a URL-friendly slug from a string
function create_slug($string) {
    $string = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return trim($string, '-');
}

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
        $pdo->beginTransaction();

        if ($action === 'add' || $action === 'update') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $icon = trim($_POST['icon']);
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $service_items = $_POST['service_items'] ?? [];
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($name)) {
                throw new Exception("Service Name is required.");
            }

            $slug = create_slug($name);
            // Check for unique slug
            $slug_check_stmt = $pdo->prepare("SELECT id FROM services WHERE slug = ? AND id != ?");
            $slug_check_stmt->execute([$slug, $id ?? 0]);
            if ($slug_check_stmt->fetch()) {
                $slug .= '-' . time(); // Append timestamp to make it unique
            }

            $featured_image_path = $_POST['current_featured_image'] ?? null;

            // Handle featured image upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['featured_image'];
                $upload_dir = '../uploads/services/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $allowed_types = ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'];
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
                    $featured_image_path = 'uploads/services/' . $new_filename;
                } else {
                    throw new Exception('Failed to upload featured image.');
                }
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO services (name, slug, description, icon, featured_image, is_featured) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $slug, $description, $icon, $featured_image_path, $is_featured]);
                $service_id = $pdo->lastInsertId();

                // Add service items
                $item_stmt = $pdo->prepare("INSERT INTO service_items (service_id, name, icon, display_order) VALUES (?, ?, ?, ?)");
                foreach ($service_items as $index => $item) {
                    if (!empty($item['name'])) {
                        $item_stmt->execute([$service_id, trim($item['name']), trim($item['icon']), $index]);
                    }
                }
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Service created successfully.'];
            } else { // update
                if (!$id) throw new Exception("Invalid service ID.");
                $stmt = $pdo->prepare("UPDATE services SET name = ?, slug = ?, description = ?, icon = ?, featured_image = ?, is_featured = ? WHERE id = ?");
                $stmt->execute([$name, $slug, $description, $icon, $featured_image_path, $is_featured, $id]);

                // Update service items: delete old and insert new
                $pdo->prepare("DELETE FROM service_items WHERE service_id = ?")->execute([$id]);
                $item_stmt = $pdo->prepare("INSERT INTO service_items (service_id, name, icon, display_order) VALUES (?, ?, ?, ?)");
                foreach ($service_items as $index => $item) {
                    if (!empty($item['name'])) {
                        $item_stmt->execute([$id, trim($item['name']), trim($item['icon']), $index]);
                    }
                }
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Service updated successfully.'];
            }
        } elseif ($action === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid service ID.");

            $img_stmt = $pdo->prepare("SELECT featured_image FROM services WHERE id = ?");
            $img_stmt->execute([$id]);
            $image_to_delete = $img_stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
            $stmt->execute([$id]);

            if ($image_to_delete && file_exists('../' . $image_to_delete)) {
                unlink('../' . $image_to_delete);
            }

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Service deleted successfully.'];
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_services.php");
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
$service_to_edit = null;
$service_items_to_edit = [];
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($edit_id) {
        $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$edit_id]);
        $service_to_edit = $stmt->fetch();

        if ($service_to_edit) {
            $edit_mode = true;
            // Fetch associated service items
            $items_stmt = $pdo->prepare("SELECT * FROM service_items WHERE service_id = ? ORDER BY display_order ASC");
            $items_stmt->execute([$edit_id]);
            $service_items_to_edit = $items_stmt->fetchAll();
        }
    }
}

// Fetch all services for display
$services = $pdo->query("SELECT * FROM services ORDER BY is_featured DESC, created_at DESC")->fetchAll();
?>

<style>
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    .table th { background-color: #f8f9fa; }
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 2rem; }
    .image-preview { max-width: 200px; max-height: 150px; margin-top: 1rem; border-radius: var(--radius-sm); border: 1px solid #ddd; }
    .icon-preview { font-size: 2rem; margin-left: 1rem; color: var(--primary-green); }
    #service-items-container .item-row { display: flex; gap: 1rem; margin-bottom: 1rem; align-items: center; }
    #service-items-container .item-row input { flex-grow: 1; }
    #service-items-container .item-row .icon-input { flex-grow: 0; width: 180px; }
    .remove-item-btn {
        background: #e74c3c; color: white; border: none; width: 30px; height: 30px;
        border-radius: 50%; cursor: pointer; font-weight: bold;
        display: flex; align-items: center; justify-content: center;
    }
    .iconpicker-popover {
        z-index: 1050; /* Ensure picker appears above other elements */
    }
    /* Style the input group created by the icon picker */
    .input-group-addon {
        padding: 0.5rem 0.75rem;
    }
    /* Ensure the icon picker's button is styled correctly */
    .iconpicker-component {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .icon-preview-box {
        font-size: 2rem;
        width: 50px;
        text-align: center;
        color: var(--primary-green);
    }
</style>

<h1>Manage Services</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<!-- Add/Edit Form -->
<div class="form-card">
    <h2><?php echo $edit_mode ? 'Edit Service' : 'Add New Service'; ?></h2>
    <form action="manage_services.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'add'; ?>">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($service_to_edit['id']); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Service Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($service_to_edit['name'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?php echo htmlspecialchars($service_to_edit['description'] ?? ''); ?></textarea>
        </div>

        <hr style="margin: 2rem 0;">

        <h4>Sub-Services / Features</h4>
        <div id="service-items-container">
            <?php foreach ($service_items_to_edit as $index => $item): ?>
                <div class="item-row">
                    <div class="icon-preview-box">
                        <i class="<?php echo htmlspecialchars($item['icon'] ?? 'fas fa-question-circle'); ?>"></i>
                    </div>
                    <input type="text" name="service_items[<?php echo $index; ?>][name]" class="form-control" placeholder="Feature Name" value="<?php echo htmlspecialchars($item['name']); ?>">
                    <input type="hidden" id="service_item_icon_<?php echo $index; ?>" name="service_items[<?php echo $index; ?>][icon]" value="<?php echo htmlspecialchars($item['icon']); ?>"><button type="button" class="btn btn-secondary open-icon-picker" data-input-id="service_item_icon_<?php echo $index; ?>" data-preview-id="service_item_preview_<?php echo $index; ?>">Select Icon</button>
                    <button type="button" class="remove-item-btn">&times;</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="add-item-btn" class="btn btn-secondary btn-sm" style="margin-top: 1rem;">
            <i class="fas fa-plus"></i> Add Feature
        </button>

        <hr style="margin: 2rem 0;">

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_featured" value="1" <?php echo ($service_to_edit['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                Feature this service on the homepage
            </label>
        </div>

        <div class="form-group">
            <label for="icon">Main Service Icon (Optional, for homepage cards etc.)</label>
            <div class="d-flex align-items-center">
                <div class="icon-preview-box"><i id="main_icon_preview" class="<?php echo htmlspecialchars($service_to_edit['icon'] ?? 'fas fa-question-circle'); ?>"></i></div>
                <input type="hidden" id="main_icon_input" name="icon" value="<?php echo htmlspecialchars($service_to_edit['icon'] ?? ''); ?>"><button type="button" class="btn btn-secondary open-icon-picker" data-input-id="main_icon_input" data-preview-id="main_icon_preview">Select Icon</button>
            </div>
        </div>

        <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
            <div class="form-group">
                <label for="featured_image">Featured Image</label>
                <input type="file" id="featured_image" name="featured_image" class="form-control">
                <?php if ($edit_mode && !empty($service_to_edit['featured_image'])): ?>
                    <p>Current Image:</p>
                    <img src="../<?php echo htmlspecialchars($service_to_edit['featured_image']); ?>" alt="Current Image" class="image-preview">
                    <input type="hidden" name="current_featured_image" value="<?php echo htmlspecialchars($service_to_edit['featured_image']); ?>">
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update Service' : 'Create Service'; ?></button>
            <?php if ($edit_mode): ?>
                <a href="manage_services.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Services List -->
<div class="table-wrapper">
    <h2>Existing Services</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Icon</th>
                <th>Name</th>
                <th width="50%">Description</th>
                <th>Featured</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($services)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No services found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><i class="<?php echo htmlspecialchars($service['icon']); ?> icon-preview"></i></td>
                        <td><strong><?php echo htmlspecialchars($service['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars(substr($service['description'], 0, 150)) . (strlen($service['description']) > 150 ? '...' : ''); ?></td>
                        <td>
                            <?php echo $service['is_featured'] ? '<span style="color: var(--primary-green); font-weight: bold;">Yes</span>' : 'No'; ?>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($service['created_at'])); ?></td>
                        <td>
                            <a href="manage_services.php?action=edit&id=<?php echo $service['id']; ?>" class="btn btn-sm btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="manage_services.php" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
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

<script>
    // This script remains to handle dynamic row adding
    (function($) {
        $(document).ready(function() {
            const container = $('#service-items-container');
            const addItemBtn = $('#add-item-btn');
            let itemIndex = <?php echo count($service_items_to_edit); ?>;

            addItemBtn.on('click', function() {
                const newItem = $(`
                    <div class="item-row">
                        <div class="icon-preview-box"><i id="service_item_preview_${itemIndex}" class="fas fa-question-circle"></i></div>
                        <input type="text" name="service_items[${itemIndex}][name]" class="form-control" placeholder="Feature Name">
                        <input type="hidden" id="service_item_icon_${itemIndex}" name="service_items[${itemIndex}][icon]" value=""><button type="button" class="btn btn-secondary open-icon-picker" data-input-id="service_item_icon_${itemIndex}" data-preview-id="service_item_preview_${itemIndex}">Select Icon</button>
                        <button type="button" class="remove-item-btn">&times;</button>
                    </div>
                `);
                container.append(newItem);
                itemIndex++;
            });

            container.on('click', '.remove-item-btn', function() {
                $(this).closest('.item-row').remove();
            });

            // Use event delegation for the icon picker button
            $('body').on('click', '.open-icon-picker', function() {
                const inputId = $(this).data('inputId');
                const previewId = $(this).data('previewId');
                const pickerUrl = `icon_picker.php?inputId=${inputId}&previewId=${previewId}`;
                const pickerWindow = window.open(pickerUrl, 'IconPicker', 'width=800,height=600,scrollbars=yes');
                pickerWindow.focus();
            });
        });
    })(jQuery);
</script>

<?php
require_once 'includes/footer.php';
?>