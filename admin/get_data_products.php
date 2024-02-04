<?php

include '../components/connect.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $select_product = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $select_product->execute([$product_id]);
    // print_r($select_product);

    if ($select_product->rowCount() > 0) {
        $product_data = $select_product->fetch(PDO::FETCH_ASSOC);
        echo json_encode($product_data);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

?>
