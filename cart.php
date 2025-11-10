<?php
$page_title = 'Your Cart | AIPS';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart']) && isset($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $product_id => $qty) {
            update_cart_item((int) $product_id, max(0, (int) $qty));
        }
        $_SESSION['flash_message'] = 'Cart updated successfully.';
    }

    if (isset($_POST['remove_item'])) {
        remove_cart_item((int) $_POST['remove_item']);
        $_SESSION['flash_message'] = 'Item removed from cart.';
    }

    if (isset($_POST['clear_cart'])) {
        clear_cart();
        $_SESSION['flash_message'] = 'Cart cleared.';
    }

    header('Location: cart.php');
    exit;
}

$cart_items = get_cart_items();
$cart_total = get_cart_total();
?>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h1 class="section-title mb-0">Shopping Cart</h1>
                <p class="text-muted mb-0">Review items before checkout.</p>
            </div>
            <a href="shop.php" class="btn btn-outline-primary"><i class="fa-solid fa-arrow-left me-2"></i>Continue Shopping</a>
        </div>

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php
                echo htmlspecialchars($_SESSION['flash_message']);
                unset($_SESSION['flash_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <div class="alert alert-warning">Your cart is empty. Add eco-friendly packaging products to proceed to checkout.</div>
        <?php else: ?>
            <form method="post">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col" class="text-center">Price</th>
                                <th scope="col" class="text-center">Quantity</th>
                                <th scope="col" class="text-end">Subtotal</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $product_id => $item): ?>
                                <?php $product = $item['product']; ?>
                                <tr>
                                    <td class="fw-semibold">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?php echo htmlspecialchars($product['image_path'] ?: 'https://via.placeholder.com/120x120?text=Eco'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="rounded" width="80" height="80" style="object-fit: cover;">
                                            <div>
                                                <div><?php echo htmlspecialchars($product['name']); ?></div>
                                                <small class="text-muted">Clean &amp; Safe Packaging</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><?php echo format_currency($product['price']); ?></td>
                                    <td class="text-center" style="width: 140px;">
                                        <input type="number" class="form-control text-center" min="0" name="quantities[<?php echo (int) $product_id; ?>]" value="<?php echo (int) $item['quantity']; ?>">
                                    </td>
                                    <td class="text-end fw-semibold"><?php echo format_currency($product['price'] * $item['quantity']); ?></td>
                                    <td class="text-end">
                                        <button type="submit" name="remove_item" value="<?php echo (int) $product_id; ?>" class="btn btn-link text-danger">Remove</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mt-4">
                    <div class="d-flex gap-2">
                        <button type="submit" name="update_cart" class="btn btn-primary">Update Cart</button>
                        <button type="submit" name="clear_cart" class="btn btn-outline-danger">Clear Cart</button>
                    </div>
                    <div class="text-end">
                        <h5 class="mb-1">Cart Total</h5>
                        <p class="fs-4 fw-bold text-primary"><?php echo format_currency($cart_total); ?></p>
                        <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
