<?php
$page_title = 'AIPS Shop';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? max(1, (int) $_POST['quantity']) : 1;

    if (add_to_cart($product_id, $quantity)) {
        $_SESSION['flash_message'] = 'Product added to cart.';
    } else {
        $_SESSION['flash_message'] = 'Unable to add product. Please try again.';
    }

    header('Location: shop.php');
    exit;
}

$products = get_products();
?>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h1 class="section-title mb-0">Shop Packaging</h1>
                <p class="text-muted mb-0">Select eco-friendly cups, boxes, and containers tailored for your business.</p>
            </div>
            <a class="btn btn-outline-primary" href="cart.php">
                <i class="fa-solid fa-cart-shopping me-2"></i>View Cart
            </a>
        </div>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                echo htmlspecialchars($_SESSION['flash_message']);
                unset($_SESSION['flash_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if (empty($products)): ?>
                <div class="col-12 text-center text-muted">No products in stock just yet.</div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4">
                        <div class="product-card h-100">
                            <img src="<?php echo htmlspecialchars($product['image_path'] ?: 'https://via.placeholder.com/400x250?text=Eco+Packaging'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="card-img-top">
                            <div class="p-4 d-flex flex-column h-100">
                                <h5 class="fw-semibold mb-2"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="text-muted small flex-grow-1"><?php echo htmlspecialchars($product['description']); ?></p>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-bold text-primary"><?php echo format_currency($product['price']); ?></span>
                                    <span class="badge bg-success-subtle text-success">Clean &amp; Safe</span>
                                </div>
                                <form method="post" class="d-flex align-items-center gap-3">
                                    <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">
                                    <label class="visually-hidden" for="quantity-<?php echo (int) $product['id']; ?>">Quantity</label>
                                    <input type="number" id="quantity-<?php echo (int) $product['id']; ?>" name="quantity" value="1" min="1" class="form-control" style="max-width: 90px;">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fa-solid fa-cart-plus me-2"></i>Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
