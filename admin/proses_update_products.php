<?php

include '../components/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_product'])) {
        $id = $_POST['id'];
        $product_name = $_POST['name'];
        $product_category = $_POST['category'];
        $product_price = $_POST['price'];

        // Check if a new image file is uploaded
        if ($_FILES['productImage']['name'] != '') {
            $product_image = $_FILES['productImage']['name'];
            $image_temp = $_FILES['productImage']['tmp_name'];

            // Move the uploaded image to the "uploaded_img" folder
            move_uploaded_file($image_temp, '../uploaded_img/' . $product_image);
        } else {
            // Keep the existing image if no new image is uploaded
            $product_image = $_POST['oldImage'];
        }

        // Update product data in the database
        $update_product = $conn->prepare("UPDATE products SET name=?, category=?, price=?, image=? WHERE id=?");
        $update_product->execute([$product_name, $product_category, $product_price, $product_image, $id]);

        header('Location: products.php');
    } else {
        echo 'Invalid request';
    }
} else {
    echo 'Invalid request';
}
