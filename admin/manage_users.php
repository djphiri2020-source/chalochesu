<?php
session_start(); // Session must be started to check the role.

// --- SECURITY: Role-Based Access Control ---
// Only allow 'admin' and 'super_admin' users to access this page.
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'super_admin'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'You do not have permission to access this page.'];
    header('Location: dashboard.php');
    exit;
}

require_once '../config/database.php'; // Initialize $pdo here

// Handle POST requests for CUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        $pdo->beginTransaction();

        if ($action === 'add' || $action === 'update') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $full_name = trim($_POST['full_name']);
            $role = $_POST['role'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            // Basic validation
            if (empty($username) || empty($email) || !in_array($role, ['super_admin', 'admin', 'editor', 'author'])) {
                throw new Exception("Username, Email, and a valid Role are required.");
            }
            if ($action === 'add' && empty($password)) {
                throw new Exception("Password is required for new users.");
            }
            if (!empty($password) && $password !== $confirm_password) {
                throw new Exception("Passwords do not match.");
            }

            // Check for unique username/email
            $sql = "SELECT id FROM users WHERE (username = ? OR email = ?)";
            $params = [$username, $email];
            if ($id) {
                $sql .= " AND id != ?";
                $params[] = $id;
            }
            $check_stmt = $pdo->prepare($sql);
            $check_stmt->execute($params);
            if ($check_stmt->fetch()) {
                throw new Exception("Username or Email already exists.");
            }

            if ($action === 'add') {
                // Generate a secure hash for the new password
                $options = ['cost' => 12]; // A higher cost is more secure
                $password_hash = password_hash($password, PASSWORD_DEFAULT, $options);

                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, full_name, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $password_hash, $full_name, $role]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'User added successfully.'];
            } else { // update
                if (!$id) throw new Exception("Invalid user ID.");
                
                // Prevent an admin from changing their own role
                if ($id === $_SESSION['user_id']) {
                    $user_being_edited_stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
                    $user_being_edited_stmt->execute([$id]);
                    if (($user_being_edited_stmt->fetchColumn()) !== $role) {
                        throw new Exception("You cannot change your own role.");
                    }
                }

                if (!empty($password)) {
                    // Generate a secure hash for the updated password
                    $options = ['cost' => 12];
                    $password_hash = password_hash($password, PASSWORD_DEFAULT, $options);
                    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password_hash = ?, full_name = ?, role = ? WHERE id = ?");
                    $stmt->execute([$username, $email, $password_hash, $full_name, $role, $id]);
                } else {
                    // Update without changing password
                    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, full_name = ?, role = ? WHERE id = ?");
                    $stmt->execute([$username, $email, $full_name, $role, $id]);
                }
                $_SESSION['message'] = ['type' => 'success', 'text' => 'User updated successfully.'];
            }
        } elseif ($action === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid user ID.");

            // Prevent an admin from deleting their own account
            if ($id === $_SESSION['user_id']) {
                throw new Exception("You cannot delete your own account.");
            }

            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['message'] = ['type' => 'success', 'text' => 'User deleted successfully.'];
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    // Redirect back to the same page to prevent form resubmission
    header("Location: manage_users.php");
    exit;
}

require_once 'includes/header.php'; // This remains here to prevent "headers already sent"
// This part should come after the POST handling logic
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Determine if we are in edit mode for the form
$edit_mode = false;
$user_to_edit = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($edit_id) {
        $stmt = $pdo->prepare("SELECT id, username, email, full_name, role FROM users WHERE id = ?");
        $stmt->execute([$edit_id]);
        $user_to_edit = $stmt->fetch();
        if ($user_to_edit) {
            $edit_mode = true;
        }
    }
}

// Fetch all users from the database
$sql = "SELECT id, full_name, username, email, role, created_at FROM users";
// Hide super_admin from the list unless the logged-in user is also a super_admin
if ($_SESSION['user_role'] !== 'super_admin') {
    $sql .= " WHERE role != 'super_admin'";
}
$sql .= " ORDER BY created_at DESC";
$users_stmt = $pdo->query($sql);
$users = $users_stmt->fetchAll();
?>

<style>
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; }
    .table th { background-color: #f8f9fa; }
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 2rem; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-group.full-width { grid-column: 1 / -1; }
    .password-note { font-size: 0.9rem; color: #6c757d; }
</style>

<h1>Manage Users</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<!-- Add/Edit Form -->
<div class="form-card">
    <h2><?php echo $edit_mode ? 'Edit User' : 'Add New User'; ?></h2>
    <form action="manage_users.php" method="POST">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'add'; ?>">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user_to_edit['id']); ?>">
        <?php endif; ?>

        <div class="form-grid">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user_to_edit['username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user_to_edit['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user_to_edit['full_name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" class="form-control">
                    <?php if ($_SESSION['user_role'] === 'super_admin'): ?>
                        <option value="super_admin" <?php echo ($user_to_edit['role'] ?? '') === 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                    <?php endif; ?>
                    <option value="admin" <?php echo ($user_to_edit['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="editor" <?php echo ($user_to_edit['role'] ?? '') === 'editor' ? 'selected' : ''; ?>>Editor</option>
                    <option value="author" <?php echo ($user_to_edit['role'] ?? '') === 'author' ? 'selected' : ''; ?>>Author</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" <?php echo $edit_mode ? '' : 'required'; ?>>
                <?php if ($edit_mode): ?>
                    <p class="password-note">Leave blank to keep the current password.</p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" <?php echo $edit_mode ? '' : 'required'; ?>>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update User' : 'Add User'; ?></button>
            <?php if ($edit_mode): ?>
                <a href="manage_users.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Users List -->
<div class="table-wrapper">
    <h2>Existing Users</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Member Since</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No users found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($user['role'])); ?></td>
                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <a href="manage_users.php?action=edit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <?php if ($user['id'] !== $_SESSION['user_id']): // Don't show delete button for self ?>
                                <form action="manage_users.php" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            <?php endif; ?>
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

<?php
require_once 'includes/footer.php';
?>