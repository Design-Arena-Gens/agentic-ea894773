<?php
$page_title = 'Contact AIPS';
include 'includes/header.php';

$name = '';
$email = '';
$message = '';
$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please provide a valid email address.';
    }

    if ($message === '') {
        $errors[] = 'Message cannot be empty.';
    }

    if (empty($errors)) {
        $conn = db_connect();
        $stmt = mysqli_prepare($conn, 'INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)');

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $message);
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Thank you for reaching out! Our team will respond shortly.';
                $name = $email = $message = '';
            } else {
                $errors[] = 'Failed to send your message. Please try again later.';
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = 'An unexpected error occurred. Please try again later.';
        }
    }
}
?>
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <h1 class="section-title">Contact Us</h1>
                <p class="text-muted">Need a custom packaging solution? We are ready to help with eco-conscious options.</p>

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

                <div class="contact-card">
                    <form method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" class="form-control" rows="5" required><?php echo htmlspecialchars($message); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="contact-card h-100">
                    <h5>AIPS Headquarters</h5>
                    <p class="text-muted">12 Greenway Business Hub, Eco District, Lagos, Nigeria</p>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <i class="fa-solid fa-phone text-success"></i>
                        <div>
                            <strong>Phone</strong>
                            <div>+234 800 123 4567</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <i class="fa-solid fa-envelope text-primary"></i>
                        <div>
                            <strong>Email</strong>
                            <div>support@aipackaging.com</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <i class="fa-solid fa-clock text-success"></i>
                        <div>
                            <strong>Office Hours</strong>
                            <div>Mon - Fri: 8:00am - 6:00pm</div>
                            <div>Sat: 9:00am - 1:00pm</div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6>Stay Connected</h6>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-success"><i class="fa-brands fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-success"><i class="fa-brands fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-success"><i class="fa-brands fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
