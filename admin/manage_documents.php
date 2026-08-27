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
            $icon = trim($_POST['icon']);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $current_file = $_POST['current_file'] ?? null;

            if (empty($name) || empty($icon)) {
                throw new Exception("Document Name and Icon are required.");
            }

            $file_path = $current_file;

            // Handle file upload
            if (isset($_FILES['document_file']) && $_FILES['document_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['document_file'];
                $upload_dir = '../uploads/documents/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                // Basic validation for PDF, DOC, DOCX
                $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!in_array($file['type'], $allowed_types)) {
                    throw new Exception('Invalid file type. Only PDF, DOC, and DOCX are allowed.');
                }
                if ($file['size'] > 20971520) { // 20MB limit
                    throw new Exception('File size cannot exceed 20MB.');
                }

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = strtolower(str_replace(' ', '-', $name)) . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    // Delete old file if it exists
                    if ($file_path && file_exists('../' . $file_path)) {
                        unlink('../' . $file_path);
                    }
                    $file_path = 'uploads/documents/' . $new_filename;
                } else {
                    throw new Exception('Failed to upload document.');
                }
            } elseif ($action === 'add' && empty($file_path)) {
                throw new Exception('A document file is required when adding a new entry.');
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO footer_documents (name, file_path, icon) VALUES (?, ?, ?)");
                $stmt->execute([$name, $file_path, $icon]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Document link added successfully.'];
            } else { // update
                if (!$id) throw new Exception("Invalid document ID.");
                $stmt = $pdo->prepare("UPDATE footer_documents SET name = ?, file_path = ?, icon = ? WHERE id = ?");
                $stmt->execute([$name, $file_path, $icon, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Document link updated successfully.'];
            }
        } elseif ($action === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid document ID.");

            $file_stmt = $pdo->prepare("SELECT file_path FROM footer_documents WHERE id = ?");
            $file_stmt->execute([$id]);
            $file_to_delete = $file_stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM footer_documents WHERE id = ?");
            $stmt->execute([$id]);

            if ($file_to_delete && file_exists('../' . $file_to_delete)) {
                unlink('../' . $file_to_delete);
            }

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Document link deleted successfully.'];
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_documents.php");
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
$doc_to_edit = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($edit_id) {
        $stmt = $pdo->prepare("SELECT * FROM footer_documents WHERE id = ?");
        $stmt->execute([$edit_id]);
        $doc_to_edit = $stmt->fetch();
        if ($doc_to_edit) {
            $edit_mode = true;
        }
    }
}

$documents = $pdo->query("SELECT * FROM footer_documents ORDER BY display_order ASC, created_at ASC")->fetchAll();
?>

<style>
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; }
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 2rem; }
    .icon-preview-box { font-size: 1.5rem; width: 40px; text-align: center; color: var(--primary-green); }
</style>

<h1>Manage Footer Documents</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <h2><?php echo $edit_mode ? 'Edit Document Link' : 'Add New Document Link'; ?></h2>
    <form action="manage_documents.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'add'; ?>">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($doc_to_edit['id']); ?>">
            <input type="hidden" name="current_file" value="<?php echo htmlspecialchars($doc_to_edit['file_path']); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Document Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($doc_to_edit['name'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="document_file">Document File (PDF, DOC, DOCX)</label>
            <input type="file" id="document_file" name="document_file" class="form-control" <?php echo !$edit_mode ? 'required' : ''; ?>>
            <?php if ($edit_mode && !empty($doc_to_edit['file_path'])): ?>
                <p style="margin-top: 0.5rem;">Current file: <a href="../<?php echo htmlspecialchars($doc_to_edit['file_path']); ?>" target="_blank"><?php echo basename($doc_to_edit['file_path']); ?></a></p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Icon</label>
            <div class="d-flex align-items-center">
                <div class="icon-preview-box"><i id="doc_icon_preview" class="<?php echo htmlspecialchars($doc_to_edit['icon'] ?? 'fas fa-file-alt'); ?>"></i></div>
                <input type="hidden" id="doc_icon_input" name="icon" value="<?php echo htmlspecialchars($doc_to_edit['icon'] ?? 'fas fa-file-alt'); ?>">
                <button type="button" class="btn btn-secondary open-icon-picker" data-input-id="doc_icon_input" data-preview-id="doc_icon_preview">Select Icon</button>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update Document' : 'Add Document'; ?></button>
            <?php if ($edit_mode): ?>
                <a href="manage_documents.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="table-wrapper">
    <h2>Existing Documents</h2>
    <table class="table">
        <thead>
            <tr><th>Icon</th><th>Name</th><th>File Path</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($documents as $doc): ?>
            <tr>
                <td><i class="<?php echo htmlspecialchars($doc['icon']); ?>"></i></td>
                <td><strong><?php echo htmlspecialchars($doc['name']); ?></strong></td>
                <td><a href="../<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank"><?php echo htmlspecialchars($doc['file_path']); ?></a></td>
                <td>
                    <a href="manage_documents.php?action=edit&id=<?php echo $doc['id']; ?>" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i> Edit</a>
                    <form action="manage_documents.php" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this document link?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'includes/footer.php'; ?>