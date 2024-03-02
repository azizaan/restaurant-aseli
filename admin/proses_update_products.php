<?php

include '../components/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_product'])) {
        $id = $_POST['updateProductID'];
        $product_name = $_POST['updateProductName'];
        $product_category = $_POST['updateProductCategory'];
        $product_price = $_POST['updateProductPrice'];

        // Check if a new image file is uploaded
        if ($_FILES['updateProductImage']['name'] != '') {
            $product_image = $_FILES['updateProductImage']['name'];
            $image_temp = $_FILES['updateProductImage']['tmp_name'];

            // Move the uploaded image to the "uploaded_img" folder
            move_uploaded_file($image_temp, '../uploaded_img/' . $product_image);
        } else {
            // Keep the existing image if no new image is uploaded
            $stmt = $conn->prepare("SELECT image FROM products WHERE id=?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $product_image = $row['image'];
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

?>
