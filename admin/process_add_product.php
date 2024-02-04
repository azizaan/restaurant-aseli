<?php
include '../components/connect.php';

$message = array();

if (isset($_POST['add_product'])) {

    $name = $_POST['productName'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['productPrice'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);

    $image = $_FILES['productImage']['name'];
    $image_size = $_FILES['productImage']['size'];
    $image_tmp_name = $_FILES['productImage']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'Product name already exists!';
    } else {
        if ($image_size > 2000000) {
            $message[] = 'Image size is too large';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);

            $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image) VALUES(?,?,?,?)");
            $insert_product->execute([$name, $category, $price, $image]);

            $message[] = 'New product added!';
        }
    }
}

// Redirect back to the page with a success or error message
if (!empty($message)) {
    header("Location: products.php?message=" . urlencode(implode('<br>', $message)));
} else {
    header("Location: products.php");
    exit(); // Make sure to exit after redirect
}
