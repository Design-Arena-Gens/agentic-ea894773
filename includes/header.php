<?php
require_once __DIR__ . '/functions.php';

if (!isset($page_title)) {
    $page_title = 'All In Packaging Solution (AIPS)';
}

$current_page = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="bg-white shadow-sm sticky-top">
        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="index.php">
                    <img src="assets/images/aips-logo.svg" alt="AIPS Logo" class="logo me-2">
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-primary-text">AIPS</span>
                        <small class="text-muted">Safety &amp; Clean</small>
                    </div>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a href="index.php" class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">Home</a></li>
                        <li class="nav-item"><a href="shop.php" class="nav-link <?php echo $current_page === 'shop.php' ? 'active' : ''; ?>">Shop</a></li>
                        <li class="nav-item"><a href="cart.php" class="nav-link <?php echo $current_page === 'cart.php' ? 'active' : ''; ?>">Cart</a></li>
                        <li class="nav-item"><a href="checkout.php" class="nav-link <?php echo $current_page === 'checkout.php' ? 'active' : ''; ?>">Checkout</a></li>
                        <li class="nav-item"><a href="contact.php" class="nav-link <?php echo $current_page === 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                        <li class="nav-item"><a href="admin.php" class="nav-link <?php echo $current_page === 'admin.php' ? 'active' : ''; ?>">Admin</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="flex-grow-1">
