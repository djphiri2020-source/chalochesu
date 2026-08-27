<?php
session_start();
require_once '../config/database.php';
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_closed = isset($_POST['site_closed']) ? '1' : '0';
    $site_closed_message = trim($_POST['site_closed_message'] ?? 'Our website is currently down for maintenance. We will be back shortly!');

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        
        $stmt->execute(['site_closed', $site_closed]);
        $stmt->execute(['site_closed_message', $site_closed_message]);

        $pdo->commit();
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Site status updated successfully.'];

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: close_site.php");
    exit;
}

require_once 'includes/header.php';

// --- SECURITY: Role-Based Access Control ---
if ($_SESSION['user_role'] !== 'super_admin') {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'You do not have permission to access this page.'];
    header('Location: dashboard.php');
    exit;
}

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Fetch current settings
$settings_stmt = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('site_closed', 'site_closed_message')");
$settings_list = $settings_stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$is_site_closed = (bool)($settings_list['site_closed'] ?? 0);
$site_closed_message = $settings_list['site_closed_message'] ?? 'Our website is currently down for maintenance. We will be back shortly!';
?>

<style>
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .status-indicator { padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; text-align: center; font-weight: bold; font-size: 1.2rem; }
    .status-open { background-color: #d4edda; color: #155724; }
    .status-closed { background-color: #f8d7da; color: #721c24; }
    .toggle-switch { position: relative; display: inline-block; width: 60px; height: 34px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #dc3545; }
    input:checked + .slider:before { transform: translateX(26px); }
</style>

<h1>Site Status Control</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <div class="status-indicator <?php echo $is_site_closed ? 'status-closed' : 'status-open'; ?>">
        Site Status: <?php echo $is_site_closed ? 'CLOSED' : 'OPEN'; ?>
    </div>

    <form action="close_site.php" method="POST">
        <div class="form-group d-flex align-items-center">
            <label for="site_closed" style="margin-right: 1rem; margin-bottom: 0;">Close Website</label>
            <label class="toggle-switch">
                <input type="checkbox" id="site_closed" name="site_closed" value="1" <?php echo $is_site_closed ? 'checked' : ''; ?>>
                <span class="slider"></span>
            </label>
        </div>
        <p class="password-note">When the site is closed, only logged-in Super Admins can view it. All other visitors will see the maintenance message.</p>

        <div class="form-group">
            <label for="site_closed_message">Maintenance Message</label>
            <textarea id="site_closed_message" name="site_closed_message" class="form-control" rows="4"><?php echo htmlspecialchars($site_closed_message); ?></textarea>
        </div>

        <div class="form-group" style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary btn-large">Save Status</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>