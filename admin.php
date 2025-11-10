<?php
$page_title = 'AIPS Admin';
include 'includes/header.php';

$conn = db_connect();
$errors = [];
$success = '';
$editing_product = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_product'])) {
        $product_id = (int) $_POST['delete_product'];
        $delete_query = 'DELETE FROM products WHERE id = ' . $product_id . ' LIMIT 1';
        if (mysqli_query($conn, $delete_query)) {
            $success = 'Product deleted successfully.';
        } else {
            $errors[] = 'Unable to delete product.';
        }
    } else {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $category = trim($_POST['category'] ?? 'General');
        $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : null;
        $image_path = $_POST['existing_image'] ?? '';

        if ($name === '') {
            $errors[] = 'Product name is required.';
        }

        if ($description === '') {
            $errors[] = 'Description is required.';
        }

        if ($price === '' || !is_numeric($price)) {
            $errors[] = 'Price must be a valid number.';
        }

        if (!empty($_FILES['image']['name'])) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'];
            if (!in_array($_FILES['image']['type'], $allowed_types, true)) {
                $errors[] = 'Unsupported image type. Upload JPG, PNG, WEBP, GIF, or SVG.';
            } else {
                $upload_dir = 'uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($_FILES['image']['name']));
                $target_path = $upload_dir . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                    $image_path = $target_path;
                } else {
                    $errors[] = 'Failed to upload image.';
                }
            }
        }

        $price_value = (float) $price;

        if (empty($errors)) {
            if ($product_id) {
                $stmt = mysqli_prepare($conn, 'UPDATE products SET name = ?, description = ?, price = ?, category = ?, image_path = ? WHERE id = ?');
                $id_param = $product_id;
                mysqli_stmt_bind_param($stmt, 'sssdsi', $name, $description, $price_value, $category, $image_path, $id_param);
            } else {
                $stmt = mysqli_prepare($conn, 'INSERT INTO products (name, description, price, category, image_path) VALUES (?, ?, ?, ?, ?)');
                mysqli_stmt_bind_param($stmt, 'ssdss', $name, $description, $price_value, $category, $image_path);
            }

            if ($stmt && mysqli_stmt_execute($stmt)) {
                $success = $product_id ? 'Product updated successfully.' : 'Product added successfully.';
            } else {
                $errors[] = 'Failed to save product. Please try again.';
            }

            if ($stmt) {
                mysqli_stmt_close($stmt);
            }
        }
    }
}

if (isset($_GET['edit'])) {
    $edit_id = (int) $_GET['edit'];
    $result = mysqli_query($conn, 'SELECT * FROM products WHERE id = ' . $edit_id . ' LIMIT 1');
    if ($result && mysqli_num_rows($result) === 1) {
        $editing_product = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }
}

$products = get_products();
?>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <div>
                <h1 class="section-title mb-0">Product Management</h1>
                <p class="text-muted mb-0">Add, edit, or remove packaging products from the AIPS store.</p>
            </div>
            <a href="admin.php" class="btn btn-outline-primary">New Product</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0 small">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="contact-card mb-5">
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo $editing_product['id'] ?? ''; ?>">
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($editing_product['image_path'] ?? ''); ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="product-name">Product Name</label>
                        <input type="text" id="product-name" name="name" class="form-control" value="<?php echo htmlspecialchars($editing_product['name'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="product-price">Price (USD)</label>
                        <input type="number" step="0.01" id="product-price" name="price" class="form-control" value="<?php echo htmlspecialchars($editing_product['price'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="product-category">Category</label>
                        <select id="product-category" name="category" class="form-select">
                            <?php
                            $categories = ['Cups', 'Boxes', 'Containers', 'Cutlery', 'Accessories', 'General'];
                            $selected = $editing_product['category'] ?? 'General';
                            foreach ($categories as $category_option):
                            ?>
                                <option value="<?php echo $category_option; ?>" <?php echo $selected === $category_option ? 'selected' : ''; ?>><?php echo $category_option; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="product-description">Description</label>
                        <textarea id="product-description" name="description" rows="4" class="form-control" required><?php echo htmlspecialchars($editing_product['description'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="product-image">Product Image</label>
                        <input type="file" id="product-image" name="image" class="form-control">
                        <div class="form-text">Upload product imagery (JPG, PNG, WEBP, GIF, or SVG).</div>
                        <?php if (!empty($editing_product['image_path'])): ?>
                            <div class="mt-3">
                                <img src="<?php echo htmlspecialchars($editing_product['image_path']); ?>" alt="Product image" width="160" class="rounded shadow-sm">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-success"><?php echo $editing_product ? 'Update Product' : 'Add Product'; ?></button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Category</th>
                        <th scope="col">Price</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No products added yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($product['image_path'] ?: 'https://via.placeholder.com/80x80?text=Eco'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" width="70" height="70" class="rounded" style="object-fit: cover;">
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                    <div class="text-muted small">Added <?php echo date('M d, Y', strtotime($product['created_at'])); ?></div>
                                </td>
                                <td><?php echo htmlspecialchars($product['category']); ?></td>
                                <td><?php echo format_currency($product['price']); ?></td>
                                <td class="text-end">
                                    <a href="admin.php?edit=<?php echo (int) $product['id']; ?>" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                        <button type="submit" name="delete_product" value="<?php echo (int) $product['id']; ?>" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
