<?php
include '../components/connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Menggunakan LIKE untuk menangani format baru "ORD001"
    $select_order_details = $conn->prepare("SELECT * FROM `orders` WHERE id LIKE :id");
    $select_order_details->bindParam('id', $id, PDO::PARAM_STR);
    $select_order_details->execute();

    if ($select_order_details->rowCount() > 0) {
        $orderDetails = $select_order_details->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'orderDetails' => $orderDetails]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
