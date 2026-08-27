<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
    require_once '../config/database.php';

    try {
        $pdo->beginTransaction();

        // Update text-based settings
        $update_stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

        foreach ($_POST as $key => $value) {
            if ($key === 'action') continue; // Skip the action field
            $update_stmt->execute([$key, trim($value)]);
        }

        // Handle logo upload
        if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['site_logo'];
            $upload_dir = '../assets/brand/';
            
            // Basic validation
            $allowed_types = ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'];
            if (!in_array($file['type'], $allowed_types)) {
                throw new Exception('Invalid file type for logo. Only PNG, JPG, GIF, and SVG are allowed.');
            }
            if ($file['size'] > 20971520) { // 20MB limit
                throw new Exception('Logo file size cannot exceed 20MB.');
            }

            // Create a unique filename
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = 'logo_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;

            // Get old logo path to delete it after successful upload
            $old_logo_stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'site_logo'");
            $old_logo_stmt->execute();
            $old_logo_path_full = $old_logo_stmt->fetchColumn();
            
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Update database with new path
                $logo_path_for_db = 'assets/brand/' . $new_filename;
                $update_stmt->execute(['site_logo', $logo_path_for_db]);

                // Delete old logo file if it exists and is not the default
                if ($old_logo_path_full && file_exists('../' . $old_logo_path_full) && $old_logo_path_full !== 'assets/brand/logo2.png') {
                    unlink('../' . $old_logo_path_full);
                }
            } else {
                throw new Exception('Failed to upload new logo.');
            }
        }

        $pdo->commit();
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Site settings updated successfully.'];

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: site_settings.php");
    exit;
}

require_once 'includes/header.php';
require_once '../config/database.php';

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
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

// Fetch all settings from the database
$settings_stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
$settings_list = $settings_stmt->fetchAll();
$settings = [];
foreach ($settings_list as $setting) {
    $settings[$setting['setting_key']] = $setting['setting_value'];
}
?>

<style>
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
    .logo-preview { max-width: 200px; max-height: 80px; margin-top: 1rem; background: #f8f9fa; padding: 1rem; border-radius: var(--radius-sm); }
</style>

<h1>Site Settings</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form action="site_settings.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_settings">
        
        <h2>General Settings</h2>
        <div class="form-grid">
            <div class="form-group">
                <label for="site_name">Site Name</label>
                <input type="text" id="site_name" name="site_name" class="form-control" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="site_tagline">Site Tagline</label>
                <input type="text" id="site_tagline" name="site_tagline" class="form-control" value="<?php echo htmlspecialchars($settings['site_tagline'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="site_logo">Site Logo</label>
                <input type="file" id="site_logo" name="site_logo" class="form-control">
                <p>Current Logo:</p>
                <img src="../<?php echo htmlspecialchars($settings['site_logo'] ?? 'assets/brand/logo2.png'); ?>" alt="Current Logo" class="logo-preview">
            </div>
        </div>

        <hr style="margin: 2rem 0;">

        <h2>Contact & Social Media</h2>
        <div class="form-grid">
            <div class="form-group">
                <label for="contact_email">Contact Email</label>
                <input type="email" id="contact_email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="contact_phone">Contact Phone</label>
                <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
            </div>
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="contact_address">Contact Address</label>
                <textarea id="contact_address" name="contact_address" class="form-control" rows="3"><?php echo htmlspecialchars($settings['contact_address'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="facebook_url">Facebook URL</label>
                <input type="url" id="facebook_url" name="facebook_url" class="form-control" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="instagram_url">Instagram URL</label>
                <input type="url" id="instagram_url" name="instagram_url" class="form-control" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="twitter_url">X (Twitter) URL</label>
                <input type="url" id="twitter_url" name="twitter_url" class="form-control" value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="linkedin_url">LinkedIn URL</label>
                <input type="url" id="linkedin_url" name="linkedin_url" class="form-control" value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="office_hours_mon_sat">Office Hours (Mon-Sat)</label>
                <input type="text" id="office_hours_mon_sat" name="office_hours_mon_sat" class="form-control" value="<?php echo htmlspecialchars($settings['office_hours_mon_sat'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="office_hours_sunday">Office Hours (Sunday)</label>
                <input type="text" id="office_hours_sunday" name="office_hours_sunday" class="form-control" value="<?php echo htmlspecialchars($settings['office_hours_sunday'] ?? ''); ?>">
            </div>
        </div>

        <hr style="margin: 2rem 0;">

        <h2>Homepage Content</h2>
        <div class="form-group">
            <label for="homepage_sdg_text">"Our Commitment to the SDGs" Text</label>
            <textarea id="homepage_sdg_text" name="homepage_sdg_text" class="form-control" rows="4"><?php echo htmlspecialchars($settings['homepage_sdg_text'] ?? 'We are dedicated to advancing the Sustainable Development Goals (SDGs) through our core services. Our work in environmental consulting, sustainability advisory, and capacity building directly contributes to creating a more sustainable and equitable future for all.'); ?></textarea>
        </div>


        <div class="form-group" style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary btn-large">Save Settings</button>
        </div>
    </form>
</div>

<?php
require_once 'includes/footer.php';
?>