<?php
require('fpdf/fpdf.php');
include '../components/connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $select_order_details = $conn->prepare("SELECT * FROM `orders` WHERE id = :id");
    $select_order_details->bindParam(':id', $id, PDO::PARAM_INT);
    $select_order_details->execute();

    if ($select_order_details->rowCount() > 0) {
        $orderDetails = $select_order_details->fetch(PDO::FETCH_ASSOC);

        // Create a PDF using FPDF with 'portrait' orientation
        $pdf = new FPDF('P', 'mm', array(60, 150)); // Set the page width and height here

        // Set margins
        $pdf->SetMargins(5, 5);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('Arial', '', 9); // Adjust the font size and style

        // Output content
        $pdf->Cell(60, 5, 'Receipt for Order ID: ' . $id, 0, 1, 'C');

        // Add more cells as needed

        // Output the PDF to the browser
        $pdf->Output();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid order ID or no data found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
