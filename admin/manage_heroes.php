<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
    require_once '../config/database.php';

    $page_slug = $_POST['page_slug'] ?? '';

    try {
        $pdo->beginTransaction();

        // Update hero text content
        $title = trim($_POST['title']);
        $tagline = trim($_POST['tagline']);

        // Handle Global CTA Banner update
        if (isset($_POST['action']) && $_POST['action'] === 'update_cta') {
            $update_stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

            $cta_fields = ['cta_title', 'cta_text', 'cta_button_text', 'cta_button_icon', 'cta_button2_text', 'cta_button2_icon'];
            foreach ($cta_fields as $field) {
                $update_stmt->execute([$field, trim($_POST[$field] ?? '')]);
            }

            // Handle CTA button 1 URL
            $cta_url1 = $_POST['cta_button_url_select'] ?? '';
            if ($cta_url1 === 'custom') {
                $cta_url1 = trim($_POST['cta_button_url_custom'] ?? '');
            }
            $update_stmt->execute(['cta_button_url', $cta_url1]);

            // Handle CTA button 2 URL
            $cta_url2 = $_POST['cta_button2_url_select'] ?? '';
            if ($cta_url2 === 'custom') {
                $cta_url2 = trim($_POST['cta_button2_url_custom'] ?? '');
            }
            $update_stmt->execute(['cta_button2_url', $cta_url2]);

            // Handle CTA background image upload
            if (isset($_FILES['cta_background_image']) && $_FILES['cta_background_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['cta_background_image'];
                $upload_dir = '../uploads/cta/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $allowed_types = ['image/png', 'image/jpeg', 'image/gif'];
                if (!in_array($file['type'], $allowed_types)) throw new Exception('Invalid file type for CTA image.');

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = 'cta-bg_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                $old_image_path = $_POST['current_cta_background_image'] ?? null;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    $db_path = 'uploads/cta/' . $new_filename;
                    $update_stmt->execute(['cta_background_image', $db_path]);
                    if ($old_image_path && file_exists('../' . $old_image_path) && strpos($old_image_path, 'assets/brand/') === false) {
                        unlink('../' . $old_image_path);
                    }
                } else { throw new Exception('Failed to upload CTA background image.'); }
            }

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Global CTA Banner updated successfully.'];
            $pdo->commit();
            header("Location: manage_heroes.php");
            exit;
        }

        if (empty($page_slug)) {
            throw new Exception("Invalid page selected for hero update.");
        }


        // Determine Button 1 URL
        $button1_url = $_POST['button1_url_select'] ?? '';
        if ($button1_url === 'custom') {
            $button1_url = trim($_POST['button1_url_custom'] ?? '');
        }
        $button1_text = trim($_POST['button1_text']);
        $button1_icon = trim($_POST['button1_icon']);

        // Determine Button 2 URL
        $button2_url = $_POST['button2_url_select'] ?? '';
        if ($button2_url === 'custom') {
            $button2_url = trim($_POST['button2_url_custom'] ?? '');
        }
        $button2_text = trim($_POST['button2_text']);
        $button2_icon = trim($_POST['button2_icon']);

        $hero_stmt = $pdo->prepare("UPDATE heroes SET title = ?, tagline = ?, button1_text = ?, button1_url = ?, button1_icon = ?, button2_text = ?, button2_url = ?, button2_icon = ? WHERE page_slug = ?");
        $hero_stmt->execute([$title, $tagline, $button1_text, $button1_url, $button1_icon, $button2_text, $button2_url, $button2_icon, $page_slug]);

        // Handle image deletions
        if (!empty($_POST['delete_images'])) {
            $delete_ids = $_POST['delete_images'];
            $placeholders = implode(',', array_fill(0, count($delete_ids), '?'));

            // First, get file paths to delete from server
            $img_path_stmt = $pdo->prepare("SELECT image_path FROM hero_images WHERE id IN ($placeholders)");
            $img_path_stmt->execute($delete_ids);
            $images_to_delete = $img_path_stmt->fetchAll(PDO::FETCH_COLUMN);

            // Then, delete from database
            $delete_stmt = $pdo->prepare("DELETE FROM hero_images WHERE id IN ($placeholders)");
            $delete_stmt->execute($delete_ids);

            // Finally, delete files from server
            foreach ($images_to_delete as $image_path) {
                if ($image_path && file_exists('../' . $image_path)) {
                    unlink('../' . $image_path);
                }
            }
        }

        // Handle new image uploads
        if (isset($_FILES['new_images'])) {
            $hero_id_stmt = $pdo->prepare("SELECT id FROM heroes WHERE page_slug = ?");
            $hero_id_stmt->execute([$page_slug]);
            $hero_id = $hero_id_stmt->fetchColumn();

            $upload_dir = '../uploads/heroes/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $insert_image_stmt = $pdo->prepare("INSERT INTO hero_images (hero_id, image_path) VALUES (?, ?)");

            foreach ($_FILES['new_images']['name'] as $key => $name) {
                if ($_FILES['new_images']['error'][$key] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['new_images']['tmp_name'][$key];
                    $file_extension = pathinfo($name, PATHINFO_EXTENSION);
                    $new_filename = $page_slug . '_' . time() . '_' . $key . '.' . $file_extension;
                    $upload_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($tmp_name, $upload_path)) {
                        $db_path = 'uploads/heroes/' . $new_filename;
                        $insert_image_stmt->execute([$hero_id, $db_path]);
                    }
                }
            }
        }

        $pdo->commit();
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Hero section for ' . ucfirst($page_slug) . ' updated successfully.'];

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_heroes.php?page=" . $page_slug);
    exit;
}

require_once 'includes/header.php';
require_once '../config/database.php';

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Get the list of all manageable hero pages
$all_pages = $pdo->query("SELECT page_slug, title FROM heroes ORDER BY page_slug ASC")->fetchAll();

$selected_page_slug = $_GET['page'] ?? $all_pages[0]['page_slug'];
$hero_data = null;
$hero_images = [];

if ($selected_page_slug) {
    $hero_stmt = $pdo->prepare("SELECT * FROM heroes WHERE page_slug = ?");
    $hero_stmt->execute([$selected_page_slug]);
    $hero_data = $hero_stmt->fetch();

    if ($hero_data) {
        $images_stmt = $pdo->prepare("SELECT * FROM hero_images WHERE hero_id = ? ORDER BY display_order ASC");
        $images_stmt->execute([$hero_data['id']]);
        $hero_images = $images_stmt->fetchAll();
    }
}

// Fetch CTA settings
$cta_stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'cta_%'");
$cta_list = $cta_stmt->fetchAll();
$cta_settings = [];
foreach ($cta_list as $setting) {
    $cta_settings[$setting['setting_key']] = $setting['setting_value'];
}

$internal_pages = [
    'Home' => '/pages/index.php',
    'Services' => '/pages/services.php',
    'Products' => '/pages/products.php',
    'Blog' => '/pages/blog.php',
    'About' => '/pages/about.php',
    'Partners' => '/pages/partners.php',
    'Contact' => '/pages/contact.php',
];

$page_options = [
    ['title' => 'Home', 'url' => '/pages/index.php', 'icon' => 'fas fa-home'],
    ['title' => 'About Us', 'url' => '/pages/about.php', 'icon' => 'fas fa-info-circle'],
    ['title' => 'Services', 'url' => '/pages/services.php', 'icon' => 'fas fa-cogs'],
    ['title' => 'Products', 'url' => '/pages/products.php', 'icon' => 'fas fa-box-open'],
    ['title' => 'Blog', 'url' => '/pages/blog.php', 'icon' => 'fas fa-newspaper'],
    ['title' => 'Contact Us', 'url' => '/pages/contact.php', 'icon' => 'fas fa-envelope'],
];

$cta_page_options = array_merge($page_options, [
    ['title' => '--- Actions ---', 'url' => '', 'disabled' => true],
    ['title' => 'Email Us', 'url' => 'mailto:' . ($settings['contact_email'] ?? ''), 'icon' => 'fas fa-paper-plane'],
    ['title' => 'Call Us', 'url' => 'tel:' . ($settings['contact_phone'] ?? ''), 'icon' => 'fas fa-phone'],
]);

function is_custom_url($url, $options) {
    return !in_array($url, array_column($options, 'url')) && !empty($url);
}
?>

<style>
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .image-management-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; }
    .image-thumbnail { position: relative; }
    .image-thumbnail img { width: 100%; height: 100px; object-fit: cover; border-radius: var(--radius-sm); }
    .image-thumbnail .delete-checkbox { position: absolute; top: 5px; right: 5px; }
</style>

<h1>Manage Hero Sections</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form action="manage_heroes.php" method="GET" style="margin-bottom: 2rem;">
        <div class="form-group">
            <label for="page_select">Select a page to edit its hero section:</label>
            <select name="page" id="page_select" class="form-control" onchange="this.form.submit()">
                <?php foreach ($all_pages as $page): ?>
                    <option value="<?php echo $page['page_slug']; ?>" <?php echo ($selected_page_slug === $page['page_slug']) ? 'selected' : ''; ?>>
                        <?php echo ucfirst($page['page_slug']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if ($hero_data): ?>
        <form action="manage_heroes.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="page_slug" value="<?php echo htmlspecialchars($selected_page_slug); ?>">
            
            <h3>Editing Hero for: <?php echo ucfirst(htmlspecialchars($selected_page_slug)); ?> Page</h3>

            <div class="form-group">
                <label for="title">Hero Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($hero_data['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="tagline">Hero Tagline</label>
                <textarea id="tagline" name="tagline" class="form-control" rows="3"><?php echo htmlspecialchars($hero_data['tagline']); ?></textarea>
            </div>

            <hr style="margin: 2rem 0;">

            <h4>Manage Buttons</h4>
            <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
                <div>
                    <h5>Primary Button (Green)</h5>
                    <div class="form-group"><label>Button Text</label><input type="text" name="button1_text" class="form-control" value="<?php echo htmlspecialchars($hero_data['button1_text'] ?? ''); ?>"></div>
                    <div class="form-group">
                        <label>Button URL</label>
                        <select name="button1_url_select" class="form-control url-selector" data-custom-target="button1_url_custom">
                            <option value="">-- Select a Page --</option>
                            <?php foreach ($page_options as $option): ?>
                                <option value="<?php echo $option['url']; ?>" <?php echo (($hero_data['button1_url'] ?? '') === $option['url']) ? 'selected' : ''; ?> data-text="<?php echo $option['title']; ?>">
                                    <?php echo $option['title']; ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="custom" <?php echo (!in_array($hero_data['button1_url'] ?? '', $internal_pages) && !empty($hero_data['button1_url'])) ? 'selected' : ''; ?>>Custom URL</option>
                        </select>
                    </div>
                    <div class="form-group custom-url-field" id="button1_url_custom" style="<?php echo (!in_array($hero_data['button1_url'] ?? '', $internal_pages) && !empty($hero_data['button1_url'])) ? '' : 'display: none;'; ?>">
                        <label>Custom Button URL</label>
                        <input type="text" name="button1_url_custom" class="form-control" value="<?php
                            echo (!in_array($hero_data['button1_url'] ?? '', $internal_pages)) ? htmlspecialchars($hero_data['button1_url'] ?? '') : '';
                        ?>" placeholder="e.g., https://example.com or #section-id">
                    </div>
                    <div class="form-group">
                        <label>Button Icon</label>
                        <div class="d-flex align-items-center">
                            <div class="icon-preview-box"><i id="b1_icon_preview" class="<?php echo htmlspecialchars($hero_data['button1_icon'] ?? 'fas fa-question-circle'); ?>"></i></div>
                            <input type="hidden" id="b1_icon_input" name="button1_icon" value="<?php echo htmlspecialchars($hero_data['button1_icon'] ?? ''); ?>"><button type="button" class="btn btn-secondary open-icon-picker" data-input-id="b1_icon_input" data-preview-id="b1_icon_preview">Select Icon</button>
                        </div>
                    </div>
                </div>
                <div>
                    <h5>Secondary Button (Outline)</h5>
                    <div class="form-group"><label>Button Text</label><input type="text" name="button2_text" class="form-control" value="<?php echo htmlspecialchars($hero_data['button2_text'] ?? ''); ?>"></div>
                    <div class="form-group">
                        <label>Button URL</label>
                        <select name="button2_url_select" class="form-control url-selector" data-custom-target="button2_url_custom">
                            <option value="">-- Select a Page --</option>
                            <?php foreach ($page_options as $option): ?>
                                <option value="<?php echo $option['url']; ?>" <?php echo (($hero_data['button2_url'] ?? '') === $option['url']) ? 'selected' : ''; ?> data-text="<?php echo $option['title']; ?>">
                                    <?php echo $option['title']; ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="custom" <?php echo (!in_array($hero_data['button2_url'] ?? '', $internal_pages) && !empty($hero_data['button2_url'])) ? 'selected' : ''; ?>>Custom URL</option>
                        </select>
                    </div>
                     <div class="form-group custom-url-field" id="button2_url_custom" style="<?php echo (!in_array($hero_data['button2_url'] ?? '', $internal_pages) && !empty($hero_data['button2_url'])) ? '' : 'display: none;'; ?>">
                        <label>Custom Button URL</label>
                        <input type="text" name="button2_url_custom" class="form-control" value="<?php
                            echo (!in_array($hero_data['button2_url'] ?? '', $internal_pages)) ? htmlspecialchars($hero_data['button2_url'] ?? '') : '';
                        ?>" placeholder="e.g., tel:+123456789">
                    </div>
                    <div class="form-group">
                        <label>Button Icon</label>
                        <div class="d-flex align-items-center">
                            <div class="icon-preview-box"><i id="b2_icon_preview" class="<?php echo htmlspecialchars($hero_data['button2_icon'] ?? 'fas fa-question-circle'); ?>"></i></div>
                            <input type="hidden" id="b2_icon_input" name="button2_icon" value="<?php echo htmlspecialchars($hero_data['button2_icon'] ?? ''); ?>"><button type="button" class="btn btn-secondary open-icon-picker" data-input-id="b2_icon_input" data-preview-id="b2_icon_preview">Select Icon</button>
                        </div>
                    </div>
                </div>
            </div>

            <hr style="margin: 2rem 0;">

            <h4>Manage Slider Images</h4>
            <div class="form-group">
                <label>Current Images</label>
                <?php if (empty($hero_images)): ?>
                    <p>No images have been uploaded for this hero section.</p>
                <?php else: ?>
                    <p>Select images to delete:</p>
                    <div class="image-management-grid">
                        <?php foreach ($hero_images as $image): ?>
                            <div class="image-thumbnail">
                                <img src="../<?php echo htmlspecialchars($image['image_path']); ?>" alt="Hero Image">
                                <input type="checkbox" name="delete_images[]" value="<?php echo $image['id']; ?>" class="delete-checkbox">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="new_images">Upload New Images</label>
                <input type="file" id="new_images" name="new_images[]" class="form-control" multiple>
                <p class="password-note">You can select multiple images to upload.</p>
            </div>

            <div class="form-group" style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary btn-large">Save Changes</button>
            </div>
        </form>
    <?php else: ?>
        <p>Please select a page to begin editing.</p>
    <?php endif; ?>
</div>

<div class="form-card" style="margin-top: 2rem;">
    <h3>Global CTA Banner</h3>
    <p>This banner appears near the footer on most pages.</p>
    <form action="manage_heroes.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_cta">
        <div class="form-group">
            <label for="cta_title">CTA Title</label>
            <input type="text" id="cta_title" name="cta_title" class="form-control" value="<?php echo htmlspecialchars($cta_settings['cta_title'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="cta_text">CTA Text</label>
            <textarea id="cta_text" name="cta_text" class="form-control" rows="2"><?php echo htmlspecialchars($cta_settings['cta_text'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="cta_background_image">Background Image</label>
            <input type="file" id="cta_background_image" name="cta_background_image" class="form-control">
            <?php if (!empty($cta_settings['cta_background_image'])): ?>
                <p style="margin-top:1rem; margin-bottom:0.5rem;">Current Image:</p>
                <img src="../<?php echo htmlspecialchars($cta_settings['cta_background_image']); ?>" alt="CTA Background" class="image-preview" style="max-width: 200px; height: auto;">
                <input type="hidden" name="current_cta_background_image" value="<?php echo htmlspecialchars($cta_settings['cta_background_image']); ?>">
            <?php endif; ?>
        </div>

        <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
            <div>
                <h5>Primary Button (Green)</h5>
                <div class="form-group"><label>Button Text</label><input type="text" name="cta_button_text" class="form-control" value="<?php echo htmlspecialchars($cta_settings['cta_button_text'] ?? ''); ?>" data-target-for="cta_button_url_select"></div>
                <div class="form-group">
                    <label>Button URL</label>
                    <select name="cta_button_url_select" class="form-control url-selector" data-custom-target="cta_button_url_custom">
                        <option value="">-- Select --</option>
                        <?php foreach ($cta_page_options as $option): ?>
                            <option value="<?php echo $option['url']; ?>" <?php echo (($cta_settings['cta_button_url'] ?? '') === $option['url']) ? 'selected' : ''; ?> <?php echo isset($option['disabled']) ? 'disabled' : ''; ?> data-text="<?php echo $option['title']; ?>" data-icon="<?php echo $option['icon'] ?? ''; ?>"><?php echo $option['title']; ?></option>
                        <?php endforeach; ?>
                        <option value="custom" <?php echo is_custom_url($cta_settings['cta_button_url'] ?? '', $cta_page_options) ? 'selected' : ''; ?>>Custom URL</option>
                    </select>
                </div>
                <div class="form-group custom-url-field" id="cta_button_url_custom" style="<?php echo is_custom_url($cta_settings['cta_button_url'] ?? '', $cta_page_options) ? '' : 'display: none;'; ?>"><label>Custom URL</label><input type="text" name="cta_button_url_custom" class="form-control" value="<?php echo is_custom_url($cta_settings['cta_button_url'] ?? '', $cta_page_options) ? htmlspecialchars($cta_settings['cta_button_url']) : ''; ?>"></div>
                <div class="form-group">
                    <label>Button Icon</label>
                    <div class="d-flex align-items-center">
                        <div class="icon-preview-box"><i id="cta_b1_icon_preview" class="<?php echo htmlspecialchars($cta_settings['cta_button_icon'] ?? 'fas fa-question-circle'); ?>"></i></div>
                        <input type="hidden" id="cta_b1_icon_input" name="cta_button_icon" value="<?php echo htmlspecialchars($cta_settings['cta_button_icon'] ?? ''); ?>"><button type="button" class="btn btn-secondary open-icon-picker" data-input-id="cta_b1_icon_input" data-preview-id="cta_b1_icon_preview">Select Icon</button>
                    </div>
                </div>
            </div>
            <div>
                <h5>Secondary Button (Outline)</h5>
                <div class="form-group"><label>Button Text</label><input type="text" name="cta_button2_text" class="form-control" value="<?php echo htmlspecialchars($cta_settings['cta_button2_text'] ?? ''); ?>" data-target-for="cta_button2_url_select"></div>
                <div class="form-group">
                    <label>Button URL</label>
                    <select name="cta_button2_url_select" class="form-control url-selector" data-custom-target="cta_button2_url_custom">
                        <option value="">-- Select --</option>
                        <?php foreach ($cta_page_options as $option): ?>
                            <option value="<?php echo $option['url']; ?>" <?php echo (($cta_settings['cta_button2_url'] ?? '') === $option['url']) ? 'selected' : ''; ?> <?php echo isset($option['disabled']) ? 'disabled' : ''; ?> data-text="<?php echo $option['title']; ?>" data-icon="<?php echo $option['icon'] ?? ''; ?>"><?php echo $option['title']; ?></option>
                        <?php endforeach; ?>
                        <option value="custom" <?php echo is_custom_url($cta_settings['cta_button2_url'] ?? '', $cta_page_options) ? 'selected' : ''; ?>>Custom URL</option>
                    </select>
                </div>
                <div class="form-group custom-url-field" id="cta_button2_url_custom" style="<?php echo is_custom_url($cta_settings['cta_button2_url'] ?? '', $cta_page_options) ? '' : 'display: none;'; ?>"><label>Custom URL</label><input type="text" name="cta_button2_url_custom" class="form-control" value="<?php echo is_custom_url($cta_settings['cta_button2_url'] ?? '', $cta_page_options) ? htmlspecialchars($cta_settings['cta_button2_url']) : ''; ?>"></div>
                <div class="form-group">
                    <label>Button Icon</label>
                    <div class="d-flex align-items-center">
                        <div class="icon-preview-box"><i id="cta_b2_icon_preview" class="<?php echo htmlspecialchars($cta_settings['cta_button2_icon'] ?? 'fas fa-question-circle'); ?>"></i></div>
                        <input type="hidden" id="cta_b2_icon_input" name="cta_button2_icon" value="<?php echo htmlspecialchars($cta_settings['cta_button2_icon'] ?? ''); ?>"><button type="button" class="btn btn-secondary open-icon-picker" data-input-id="cta_b2_icon_input" data-preview-id="cta_b2_icon_preview">Select Icon</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <button type="submit" class="btn btn-primary">Save CTA Banner</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.url-selector').forEach(function(selector) {
        selector.addEventListener('change', function() {
            const customFieldId = this.dataset.customTarget;
            const customField = document.getElementById(customFieldId);
            if (this.value === 'custom') {
                customField.style.display = 'block';
            } else {
                customField.style.display = 'none';
            }

            // Auto-fill button text and icon if a page is selected
            const selectedOption = this.options[this.selectedIndex];
            const buttonText = selectedOption.dataset.text;
            const buttonIcon = selectedOption.dataset.icon;

            const textTarget = document.querySelector(`input[data-target-for="${this.id}"], input[name="${this.name.replace('_url_select', '_text')}"]`);
            if (textTarget && buttonText) textTarget.value = buttonText;

            const iconInput = this.closest('div').nextElementSibling.querySelector('input[type=hidden]');
            const iconPreview = this.closest('div').nextElementSibling.querySelector('.icon-preview-box i');
            if (iconInput && iconPreview && buttonIcon) { iconInput.value = buttonIcon; iconPreview.className = buttonIcon; }
        });
    });
});
</script>