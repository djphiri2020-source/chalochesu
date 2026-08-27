<?php
session_start(); // Start session early for messages and authentication
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../config/database.php'; // Database connection needed for export

// --- Handle CSV Export ---
if (isset($_GET['action']) && $_GET['action'] === 'export_csv') {
    try {
        $stmt = $pdo->query("SELECT email, subscribed_at FROM subscribers ORDER BY subscribed_at DESC");
        $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=subscribers_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');

        // Add CSV header
        fputcsv($output, ['Email', 'Subscribed At']);

        // Add data
        foreach ($subscribers as $subscriber) {
            fputcsv($output, $subscriber);
        }

        fclose($output);
    } catch (Exception $e) {
        // Log error and show a message if something goes wrong during export
        error_log("CSV Export Failed: " . $e->getMessage());
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Could not export subscribers. Please check the logs.'];
        header("Location: subscribers.php");
    }
    exit;
}

// --- Handle Deletion ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM subscribers WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Subscriber deleted successfully.'];
        } catch (Exception $e) {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
        }
    }
    header("Location: subscribers.php");
    exit;
}

require_once 'includes/header.php'; // Now include header after all potential header modifications
?>

<style>
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #eee; }
    .table th { background-color: #f8f9fa; }
    .page-actions { margin-bottom: 2rem; text-align: right; }
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-danger:hover { background-color: #c82333; }
    .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem; }
</style>

<?php
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Fetch all subscribers for display
$subscribers = $pdo->query("SELECT * FROM subscribers ORDER BY subscribed_at DESC")->fetchAll();
?>

<h1>Email Subscribers</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<div class="page-actions">
    <a href="subscribers.php?action=export_csv" class="btn btn-primary">
        <i class="fas fa-file-csv"></i> Export as CSV
    </a>
</div>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Email Address</th>
                <th>Subscription Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($subscribers)): ?>
                <tr>
                    <td colspan="3" style="text-align: center;">No subscribers found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($subscribers as $subscriber): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($subscriber['email']); ?></strong></td>
                        <td><?php echo date('F j, Y, g:i a', strtotime($subscriber['subscribed_at'])); ?></td>
                        <td>
                            <form action="subscribers.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this subscriber?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $subscriber['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
require_once 'includes/footer.php';
?>