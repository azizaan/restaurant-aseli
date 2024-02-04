<?php

include '../components/connect.php';

session_start();

$kurir_id = $_SESSION['kurir_id'];

if (!isset($kurir_id)) {
    header('location: kurir_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurir Dashboard</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/kurir_style.css">

</head>

<body>

    <?php include '../components/kurir_header.php'; ?>

    <section class="dashboard">

        <h1 class="heading">dashboard</h1>

        <div class="box-container">
            <div class="box">
                <?php
                $total_pending_orders = 0;
                $select_pending_orders = $conn->prepare("SELECT COUNT(*) as total_pending FROM `orders` WHERE payment_status = ?");
                $select_pending_orders->execute(['pending']);
                $fetch_pending_orders = $select_pending_orders->fetch(PDO::FETCH_ASSOC);
                $total_pending_orders = $fetch_pending_orders['total_pending'];
                ?>
                <h3><?= $total_pending_orders; ?></h3>
                <p>total pending orders</p>
                <a href="placed_orders.php" class="btn">see orders</a>
            </div>

            <div class="box">
                <?php
                $total_completed_orders = 0;
                $select_completed_orders = $conn->prepare("SELECT COUNT(*) as total_completed FROM `orders` WHERE payment_status = 'completed' AND kurir_id = ?");
                $select_completed_orders->execute([$kurir_id]);
                $fetch_completed_orders = $select_completed_orders->fetch(PDO::FETCH_ASSOC);
                $total_completed_orders = $fetch_completed_orders['total_completed'];
                ?>
                <h3><?= $total_completed_orders; ?></h3>
                <p>total completed orders</p>
                <a href="placed_orders.php" class="btn">see orders</a>
            </div>
        </div>


    </section>


    <script src="../js/kurir_script.js"></script>
</body>

</html>