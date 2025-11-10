<?php
$page_title = 'AIPS | Safety & Clean Packaging';
include 'includes/header.php';
?>
<section class="py-5 hero text-center text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <span class="badge rounded-pill badge-eco mb-3">Eco-Friendly Packaging</span>
                <h1 class="display-4 fw-bold mb-3">Safety &amp; Clean Packaging for a Greener Future</h1>
                <p class="lead mb-4">All In Packaging Solution (AIPS) provides innovative, sustainable packaging designed to protect your products and the planet. Clean materials, safe processes, and green commitments.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="shop.php" class="btn btn-light text-primary fw-semibold">Browse Shop</a>
                    <a href="contact.php" class="btn btn-outline-light">Contact Our Team</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=80" alt="Eco packaging" class="img-fluid rounded-4 shadow-sm">
            </div>
            <div class="col-md-6">
                <h2 class="section-title mb-3">Who We Are</h2>
                <p>AIPS delivers safety-first packaging solutions crafted from recyclable and biodegradable materials. We empower businesses to adopt hygienic, eco-conscious packaging that elevates brand trust and customer confidence.</p>
                <ul class="list-unstyled mt-4">
                    <li class="d-flex align-items-center mb-3"><span class="badge rounded-pill badge-eco me-3"><i class="fa-solid fa-leaf"></i></span> Sustainable materials and responsible sourcing</li>
                    <li class="d-flex align-items-center mb-3"><span class="badge rounded-pill badge-eco me-3"><i class="fa-solid fa-shield-heart"></i></span> Clean production environments that prioritize safety</li>
                    <li class="d-flex align-items-center"><span class="badge rounded-pill badge-eco me-3"><i class="fa-solid fa-truck"></i></span> Reliable delivery with customizable packaging options</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Featured Products</h2>
            <p class="text-muted">Choose from carefully curated packaging designed for food, retail, and takeaway services.</p>
        </div>
        <div class="row g-4">
            <?php
            $featured = array_slice(get_products(), 0, 3);
            if (empty($featured)) {
                echo '<div class="col-12 text-center text-muted">No products available yet. Visit the admin dashboard to add new items.</div>';
            }
            foreach ($featured as $product) {
                ?>
                <div class="col-md-4">
                    <div class="product-card p-3 h-100">
                        <img src="<?php echo htmlspecialchars($product['image_path'] ?: 'https://via.placeholder.com/400x250?text=AIPS+Packaging'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="p-3">
                            <h5 class="fw-semibold"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="text-muted small mb-3"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary"><?php echo format_currency($product['price']); ?></span>
                                <a href="shop.php" class="btn btn-sm btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="contact-card text-center h-100">
                    <i class="fa-solid fa-recycle fa-2x text-success mb-3"></i>
                    <h5>Eco-Focused</h5>
                    <p class="text-muted small">Our packaging solutions reduce environmental impact without compromising quality.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-card text-center h-100">
                    <i class="fa-solid fa-broom fa-2x text-primary mb-3"></i>
                    <h5>Sanitized Processes</h5>
                    <p class="text-muted small">Each product is produced in a controlled environment that prioritizes hygiene.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-card text-center h-100">
                    <i class="fa-solid fa-hand-holding-heart fa-2x text-success mb-3"></i>
                    <h5>Customer Trust</h5>
                    <p class="text-muted small">We support brands that care about their customers' health and the environment.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
include 'includes/footer.php';
?>
