<?php
require_once __DIR__ . '/../config.php';

function get_products()
{
    $conn = db_connect();
    $products = [];
    $sql = 'SELECT * FROM products ORDER BY created_at DESC';

    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        mysqli_free_result($result);
    }

    return $products;
}

function get_product($product_id)
{
    $conn = db_connect();
    $id = (int) $product_id;
    $sql = 'SELECT * FROM products WHERE id = ' . $id . ' LIMIT 1';
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $product = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $product;
    }

    return null;
}

function format_currency($amount)
{
    return '$' . number_format((float) $amount, 2);
}

function add_to_cart($product_id, $quantity = 1)
{
    $product = get_product($product_id);

    if (!$product) {
        return false;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'product' => $product,
            'quantity' => $quantity,
        ];
    }

    return true;
}

function update_cart_item($product_id, $quantity)
{
    if (!isset($_SESSION['cart'][$product_id])) {
        return;
    }

    if ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
        return;
    }

    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
}

function remove_cart_item($product_id)
{
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function clear_cart()
{
    unset($_SESSION['cart']);
}

function get_cart_items()
{
    if (!isset($_SESSION['cart'])) {
        return [];
    }
    return $_SESSION['cart'];
}

function get_cart_total()
{
    $total = 0;
    $items = get_cart_items();

    foreach ($items as $item) {
        $total += ($item['product']['price'] * $item['quantity']);
    }

    return $total;
}

function base_url()
{
    $protocol = 'http';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $protocol = 'https';
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
    }

    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path = '';
    if (isset($_SERVER['SCRIPT_NAME'])) {
        $path = rtrim(str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');
    }

    return rtrim($protocol . '://' . $host . ($path ? '/' . ltrim($path, '/') : ''), '/') . '/';
}
?>
