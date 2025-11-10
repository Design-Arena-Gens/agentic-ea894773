<?php
$page_title = 'Checkout | AIPS';
include 'includes/header.php';

$cart_items = get_cart_items();
$cart_total = get_cart_total();
$transaction_ref = 'AIPS-' . strtoupper(bin2hex(random_bytes(4))) . '-' . time();

if (isset($_GET['status'])) {
    $status = $_GET['status'];
    if ($status === 'cancelled') {
        $_SESSION['flash_message'] = 'Payment was cancelled.';
    } elseif ($status === 'successful') {
        $_SESSION['flash_message'] = 'Payment successful! Thank you for choosing AIPS.';
        clear_cart();
    }
}
?>
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <h1 class="section-title">Checkout</h1>
                <p class="text-muted">Complete your details to process secure payment via Flutterwave.</p>

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
                    <div class="alert alert-warning">Your cart is empty. <a href="shop.php" class="alert-link">Return to the shop</a> to add products.</div>
                <?php else: ?>
                    <div class="contact-card">
                        <form id="checkout-form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="customer-name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="customer-name" placeholder="Jane Doe" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="customer-email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="customer-email" placeholder="jane@example.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="customer-phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="customer-phone" placeholder="+234 000 0000" required>
                                </div>
                                <div class="col-12">
                                    <label for="customer-address" class="form-label">Delivery Address</label>
                                    <textarea class="form-control" id="customer-address" rows="3" placeholder="Street, City, Country" required></textarea>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="button" id="flutterwave-checkout-button" class="btn btn-success btn-lg">
                                    <i class="fa-solid fa-lock me-2"></i>Pay Securely with Flutterwave
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-5">
                <div class="contact-card">
                    <h5 class="mb-3">Order Summary</h5>
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($cart_items)): ?>
                            <?php foreach ($cart_items as $item): ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="fw-semibold"><?php echo htmlspecialchars($item['product']['name']); ?></span>
                                    <span><?php echo (int) $item['quantity']; ?> x <?php echo format_currency($item['product']['price']); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">No items</li>
                        <?php endif; ?>
                    </ul>
                    <div class="d-flex justify-content-between mt-3">
                        <span class="fw-bold">Total</span>
                        <span class="fs-5 text-primary fw-semibold"><?php echo format_currency($cart_total); ?></span>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Payments are processed securely via Flutterwave. You will receive a confirmation email when the transaction is successful.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script>
    const checkoutButton = document.getElementById('flutterwave-checkout-button');

    if (checkoutButton) {
        checkoutButton.addEventListener('click', function () {
            const nameField = document.getElementById('customer-name');
            const emailField = document.getElementById('customer-email');
            const phoneField = document.getElementById('customer-phone');
            const addressField = document.getElementById('customer-address');

            if (!nameField.value || !emailField.value || !phoneField.value || !addressField.value) {
                alert('Please complete all checkout fields.');
                return;
            }

            FlutterwaveCheckout({
                public_key: '<?php echo FLW_PUBLIC_KEY; ?>',
                tx_ref: '<?php echo $transaction_ref; ?>',
                amount: <?php echo $cart_total; ?>,
                currency: 'USD',
                payment_options: 'card, banktransfer, ussd',
                redirect_url: '<?php echo base_url(); ?>checkout.php?status=successful',
                customer: {
                    email: emailField.value,
                    phonenumber: phoneField.value,
                    name: nameField.value,
                },
                meta: {
                    address: addressField.value,
                },
                customizations: {
                    title: 'All In Packaging Solution',
                    description: 'Eco-friendly packaging purchase',
                    logo: '<?php echo base_url(); ?>assets/images/aips-logo.svg',
                },
                onclose: function () {
                    window.location.href = '<?php echo base_url(); ?>checkout.php?status=cancelled';
                }
            });
        });
    }
</script>
<?php include 'includes/footer.php'; ?>
