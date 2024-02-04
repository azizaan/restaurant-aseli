<?php
include '../components/connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $select_order_details = $conn->prepare("SELECT * FROM `orders` WHERE id = :id");
    $select_order_details->bindParam('id', $id, PDO::PARAM_INT);
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
