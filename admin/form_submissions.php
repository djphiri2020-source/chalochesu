<?php

// Handle POST requests for actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
    require_once '../config/database.php';

    $action = $_POST['action'] ?? '';
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    try {
        if (!$id) {
            throw new Exception("Invalid submission ID.");
        }

        if ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM contact_submissions WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Submission deleted successfully.'];
        } elseif ($action === 'toggle_read') {
            $stmt = $pdo->prepare("UPDATE contact_submissions SET is_read = !is_read WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Submission status updated.'];
        }
    } catch (Exception $e) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: form_submissions.php");
    exit;
}

require_once 'includes/header.php';
require_once '../config/database.php';

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Fetch all submissions
$submissions = $pdo->query("SELECT * FROM contact_submissions ORDER BY submitted_at DESC")->fetchAll();
?>

<style>
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #eee; vertical-align: top; }
    .table th { background-color: #f8f9fa; }
    .table tr.unread td { font-weight: bold; }
    .table tr.read td { color: #6c757d; }
    .message-content {
        max-width: 400px;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    .btn-danger { background-color: #dc3545; color: white; }
    .btn-danger:hover { background-color: #c82333; }
    .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.2rem; }
</style>

<h1>Contact Form Submissions</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>From</th>
                <th>Contact</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Received</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($submissions)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No submissions found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($submissions as $submission): ?>
                    <tr class="<?php echo $submission['is_read'] ? 'read' : 'unread'; ?>">
                        <td><?php echo htmlspecialchars($submission['name']); ?></td>
                        <td>
                            <a href="mailto:<?php echo htmlspecialchars($submission['email']); ?>"><?php echo htmlspecialchars($submission['email']); ?></a>
                            <?php if (!empty($submission['phone'])): ?>
                                <br>
                                <a href="tel:<?php echo htmlspecialchars($submission['phone']); ?>"><?php echo htmlspecialchars($submission['phone']); ?></a>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($submission['subject']); ?></td>
                        <td class="message-content"><?php echo nl2br(htmlspecialchars($submission['message'])); ?></td>
                        <td><?php echo date('M j, Y, g:i a', strtotime($submission['submitted_at'])); ?></td>
                        <td>
                            <!-- Toggle Read/Unread Form -->
                            <form action="form_submissions.php" method="POST" style="display: inline-block; margin-bottom: 5px;">
                                <input type="hidden" name="action" value="toggle_read">
                                <input type="hidden" name="id" value="<?php echo $submission['id']; ?>">
                                <button type="submit" class="btn btn-sm <?php echo $submission['is_read'] ? 'btn-secondary' : 'btn-primary'; ?>">
                                    <?php echo $submission['is_read'] ? 'Mark as Unread' : 'Mark as Read'; ?>
                                </button>
                            </form>

                            <!-- Delete Form -->
                            <form action="form_submissions.php" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this submission? This cannot be undone.');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $submission['id']; ?>">
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

<?php
require_once 'includes/footer.php';
?>