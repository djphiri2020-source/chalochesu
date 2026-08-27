<?php

// Function to create a URL-friendly slug from a string
function create_slug($string) {
    $string = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
    return trim($string, '-');
}

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
            $description = trim($_POST['description']);
            $icon = trim($_POST['icon']);
            $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
            $sku = trim($_POST['sku']);
            $stock_quantity = filter_input(INPUT_POST, 'stock_quantity', FILTER_VALIDATE_INT);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($name) || $price === false || $stock_quantity === false) {
                throw new Exception("Product Name, a valid Price, and Stock Quantity are required.");
            }

            $slug = create_slug($name);
            // Check for unique slug
            $slug_check_stmt = $pdo->prepare("SELECT id FROM products WHERE slug = ? AND id != ?");
            $slug_check_stmt->execute([$slug, $id ?? 0]);
            if ($slug_check_stmt->fetch()) {
                $slug .= '-' . time(); // Append timestamp to make it unique
            }

            $featured_image_path = $_POST['current_featured_image'] ?? null;

            // Handle featured image upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['featured_image'];
                $upload_dir = '../uploads/products/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $allowed_types = ['image/png', 'image/jpeg', 'image/gif'];
                if (!in_array($file['type'], $allowed_types)) throw new Exception('Invalid file type for image.');
                if ($file['size'] > 20971520) throw new Exception('Image file size cannot exceed 20MB.');

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = $slug . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    if ($featured_image_path && file_exists('../' . $featured_image_path)) {
                        unlink('../' . $featured_image_path);
                    }
                    $featured_image_path = 'uploads/products/' . $new_filename;
                } else {
                    throw new Exception('Failed to upload featured image.');
                }
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO products (name, slug, description, icon, price, sku, stock_quantity, featured_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $slug, $description, $icon, $price, $sku, $stock_quantity, $featured_image_path]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Product created successfully.'];
            } else { // update
                if (!$id) throw new Exception("Invalid product ID.");
                $stmt = $pdo->prepare("UPDATE products SET name = ?, slug = ?, description = ?, icon = ?, price = ?, sku = ?, stock_quantity = ?, featured_image = ? WHERE id = ?");
                $stmt->execute([$name, $slug, $description, $icon, $price, $sku, $stock_quantity, $featured_image_path, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Product updated successfully.'];
            }
        } elseif ($action === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid product ID.");

            $img_stmt = $pdo->prepare("SELECT featured_image FROM products WHERE id = ?");
            $img_stmt->execute([$id]);
            $image_to_delete = $img_stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);

            if ($image_to_delete && file_exists('../' . $image_to_delete)) {
                unlink('../' . $image_to_delete);
            }

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Product deleted successfully.'];
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_products.php");
    exit;
}

require_once 'includes/header.php';
require_once '../config/database.php';

$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Handle POST requests for CUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        $pdo->beginTransaction();

        if ($action === 'add' || $action === 'update') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $icon = trim($_POST['icon']);
            $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
            $sku = trim($_POST['sku']);
            $stock_quantity = filter_input(INPUT_POST, 'stock_quantity', FILTER_VALIDATE_INT);
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if (empty($name) || $price === false || $stock_quantity === false) {
                throw new Exception("Product Name, a valid Price, and Stock Quantity are required.");
            }

            $slug = create_slug($name);
            // Check for unique slug
            $slug_check_stmt = $pdo->prepare("SELECT id FROM products WHERE slug = ? AND id != ?");
            $slug_check_stmt->execute([$slug, $id ?? 0]);
            if ($slug_check_stmt->fetch()) {
                $slug .= '-' . time(); // Append timestamp to make it unique
            }

            $featured_image_path = $_POST['current_featured_image'] ?? null;

            // Handle featured image upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['featured_image'];
                $upload_dir = '../uploads/products/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $allowed_types = ['image/png', 'image/jpeg', 'image/gif'];
                if (!in_array($file['type'], $allowed_types)) throw new Exception('Invalid file type for image.');
                if ($file['size'] > 20971520) throw new Exception('Image file size cannot exceed 20MB.');

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = $slug . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    if ($featured_image_path && file_exists('../' . $featured_image_path)) {
                        unlink('../' . $featured_image_path);
                    }
                    $featured_image_path = 'uploads/products/' . $new_filename;
                } else {
                    throw new Exception('Failed to upload featured image.');
                }
            }

            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO products (name, slug, description, icon, price, sku, stock_quantity, featured_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $slug, $description, $icon, $price, $sku, $stock_quantity, $featured_image_path]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Product created successfully.'];
            } else { // update
                if (!$id) throw new Exception("Invalid product ID.");
                $stmt = $pdo->prepare("UPDATE products SET name = ?, slug = ?, description = ?, icon = ?, price = ?, sku = ?, stock_quantity = ?, featured_image = ? WHERE id = ?");
                $stmt->execute([$name, $slug, $description, $icon, $price, $sku, $stock_quantity, $featured_image_path, $id]);
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Product updated successfully.'];
            }
        } elseif ($action === 'delete') {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) throw new Exception("Invalid product ID.");

            $img_stmt = $pdo->prepare("SELECT featured_image FROM products WHERE id = ?");
            $img_stmt->execute([$id]);
            $image_to_delete = $img_stmt->fetchColumn();

            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);

            if ($image_to_delete && file_exists('../' . $image_to_delete)) {
                unlink('../' . $image_to_delete);
            }

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Product deleted successfully.'];
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'An error occurred: ' . $e->getMessage()];
    }

    header("Location: manage_products.php");
    exit;
}


// Determine if we are editing or adding
$edit_mode = false;
$product_to_edit = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $edit_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($edit_id) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$edit_id]);
        $product_to_edit = $stmt->fetch();
        if ($product_to_edit) {
            $edit_mode = true;
        }
    }
}

// Fetch all products for display
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
?>

<style>
    .table-wrapper { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle; }
    .table th { background-color: #f8f9fa; }
    .form-card { background: #fff; padding: 2rem; border-radius: var(--radius-md); box-shadow: var(--shadow-sm); margin-bottom: 2rem; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
    .image-preview { max-width: 150px; max-height: 150px; margin-top: 1rem; border-radius: var(--radius-sm); border: 1px solid #ddd; }
    .table-img-preview { width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius-sm); }
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

<h1>Manage Products</h1>

<?php if ($message): ?>
    <div class="alert alert-<?php echo htmlspecialchars($message['type']); ?>">
        <?php echo htmlspecialchars($message['text']); ?>
    </div>
<?php endif; ?>

<!-- Add/Edit Form -->
<div class="form-card">
    <h2><?php echo $edit_mode ? 'Edit Product' : 'Add New Product'; ?></h2>
    <form action="manage_products.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_mode ? 'update' : 'add'; ?>">
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product_to_edit['id']); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product_to_edit['name'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?php echo htmlspecialchars($product_to_edit['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="icon">Product Icon</label>
            <div class="d-flex align-items-center">
                <div class="icon-preview-box"><i id="product_icon_preview" class="<?php echo htmlspecialchars($product_to_edit['icon'] ?? 'fas fa-question-circle'); ?>"></i></div>
                <input type="hidden" id="product_icon_input" name="icon" value="<?php echo htmlspecialchars($product_to_edit['icon'] ?? ''); ?>"><button type="button" class="btn btn-secondary open-icon-picker" data-input-id="product_icon_input" data-preview-id="product_icon_preview">Select Icon</button>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="price">Price (ZMW)</label>
                <input type="number" step="0.01" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($product_to_edit['price'] ?? '0.00'); ?>" required>
            </div>
            <div class="form-group">
                <label for="sku">SKU (Stock Keeping Unit)</label>
                <input type="text" id="sku" name="sku" class="form-control" value="<?php echo htmlspecialchars($product_to_edit['sku'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="stock_quantity">Stock Quantity</label>
                <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="<?php echo htmlspecialchars($product_to_edit['stock_quantity'] ?? '0'); ?>" required>
            </div>
            <div class="form-group">
                <label for="featured_image">Featured Image</label>
                <input type="file" id="featured_image" name="featured_image" class="form-control">
                <?php if ($edit_mode && !empty($product_to_edit['featured_image'])): ?>
                    <p>Current Image:</p>
                    <img src="../<?php echo htmlspecialchars($product_to_edit['featured_image']); ?>" alt="Current Image" class="image-preview">
                    <input type="hidden" name="current_featured_image" value="<?php echo htmlspecialchars($product_to_edit['featured_image']); ?>">
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo $edit_mode ? 'Update Product' : 'Create Product'; ?></button>
            <?php if ($edit_mode): ?>
                <a href="manage_products.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Products List -->
<div class="table-wrapper">
    <h2>Existing Products</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No products found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php if (!empty($product['featured_image'])): ?>
                                <img src="../<?php echo htmlspecialchars($product['featured_image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="table-img-preview">
                            <?php else: ?>
                                <div class="table-img-preview" style="background:#eee; display:flex; align-items:center; justify-content:center; color:#aaa;"><i class="fas fa-image"></i></div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($product['sku']); ?></td>
                        <td>ZMW <?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                        <td>
                            <a href="manage_products.php?action=edit&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="manage_products.php" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
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

<?php
require_once 'includes/footer.php'; ?>

<script>
    (function($) {
        $(document).ready(function() {
            $('body').on('click', '.open-icon-picker', function() {
                const inputId = $(this).data('inputId');
                const previewId = $(this).data('previewId');
                const pickerUrl = `icon_picker.php?inputId=${inputId}&previewId=${previewId}`;
                window.open(pickerUrl, 'IconPicker', 'width=800,height=600,scrollbars=yes');
            });
        });
    })(jQuery);
</script>