<?php
include '../components/connect.php';

session_start();

$kasir_id = $_SESSION['kasir_id'];

if (!isset($kasir_id)) {
    header('location: kasir_login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- DataTables CSS file link -->
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.css" rel="stylesheet">

    <!-- jsPDF CDN link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="../css/kasir_style.css">
</head>

<body>

    <?php include '../components/kasir_header.php'; ?>

    <section class="placed-orders">

        <h1 class="heading">Kasir</h1>
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-body">

                        <div class="table-responsive">
                            <table class="table" id="ordersTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>id order</th>
                                        <th>Nama Menu</th>
                                        <th>Harga</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'cooking' AND kasir_id = :kasir_id");
                                    $select_orders->bindParam(':kasir_id', $kasir_id, PDO::PARAM_INT);
                                    $select_orders->execute();

                                    if ($select_orders->rowCount() > 0) {
                                        $no = 1;
                                        while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $fetch_orders['id']; ?></td>
                                                <td><?php echo $fetch_orders['total_products']; ?></td>
                                                <td><?php echo $fetch_orders['total_price']; ?></td>
                                                <td>
                                                    <button class="btn print-button" data-id="<?php echo $fetch_orders['id']; ?>">print</button>
                                                </td>
                                            </tr>
                                    <?php
                                            $no++;
                                        }
                                    } else {
                                        echo '<tr><td colspan="5" class="text-center">No orders placed yet!</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable();

            $('.print-button').on('click', function() {
                var orderId = $(this).data('id');
                console.log(orderId);
                printReceipt(orderId);

            });
        });

        function printReceipt(orderId) {
            $.ajax({
                type: 'GET',
                url: 'fetch_order_details.php',
                data: {
                    id: orderId
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        var orderDetails = data.orderDetails;

                        var id = orderDetails.id; // Assuming the ID is directly available in orderDetails
                        var placed_on = orderDetails.placed_on; // Assuming placed_on is a property in orderDetails
                        var total_products = orderDetails.total_products; // Assuming total_products is a property in orderDetails
                        var total_price = orderDetails.total_price;
                        var method = orderDetails.method;
                        console.log(method);

                        var params = {
                            id: id,
                            placed_on: placed_on,
                            total_products: total_products,
                            total_price: total_price,
                            method: method,
                        };

                        var url = 'struk_pembayaran.php?' + $.param(params);
                        window.open(url, '_blank');


                        setTimeout(function() {
                            win.print();
                        }, 1000);

                        $.ajax({
                            type: 'GET',
                            url: 'generate_pdf.php',
                            data: {
                                id: orderId
                            },
                            success: function(response) {
                                console.log(response);
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', xhr.responseText);
                                alert('Failed to fetch order details. Please try again.');
                            }
                        });

                    } else {
                        alert('Failed to fetch order details. Please try again.');
                    }
                },
                error: function() {
                    alert('Failed to fetch order details. Please try again.');
                }
            });
        }
    </script>

</body>

</html>