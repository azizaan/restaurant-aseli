<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the category parameter from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const category = urlParams.get('category');

            // Select the title element
            const titleElement = document.querySelector('#judul');

            // Set the title text based on the category
            if (category) {
                titleElement.textContent = category;
            }
        });
    </script>
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

<link rel="stylesheet" href="css/style.css">
</head>

<body>
    
    <?php include 'components/user_header.php'; ?>

    <!-- section category start -->
    <section class="category">

        <h1 class="title">food category</h1>

        <div class="box-container">

            <a href="category.php?category=fast food" class="box">
                <img src="images/cat-1.png" alt="">
                <h3>fast food</h3>
            </a>

            <a href="category.php?category=main dish" class="box">
                <img src="images/cat-2.png" alt="">
                <h3>main dishes</h3>
            </a>

            <a href="category.php?category=drinks" class="box">
                <img src="images/cat-3.png" alt="">
                <h3>drinks</h3>
            </a>

            <a href="category.php?category=desserts" class="box">
                <img src="images/cat-4.png" alt="">
                <h3>desserts</h3>
            </a>

        </div>

    </section>
    <!-- section category end -->

    <!-- section category start -->
    <section class="products">

        <h1 class="title" id="judul">food category</h1>

        <div class="box-container">
            <?php
            $category = $_GET['category'];
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
            $select_products->execute([$category]);
            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {

            ?>
                    <form action="" method="POST" class="box">
                        <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                        <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                        <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                        <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
                        <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
                        <button type="submit" name="add_to_cart" class="fas fa-shopping-cart"></button>
                        <img src="uploaded_img/<?= $fetch_products['image']; ?>" class="image" alt="">
                        <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
                        <div class="name"><?= $fetch_products['name']; ?></div>
                        <div class="flex">
                            <div class="price"><span>Rp</span><?= $fetch_products['price']; ?></div>
                            <input type="number" name="qty" id="qty" class="qty" value="1" min="1" max="99" maxlength="2">
                        </div>
                    </form>
            <?php
                }
            } else {
                echo '<div class="empty">belum ada produk yang ditambahkan!</div>';
            }
            ?>

        </div>

        <div class="more-btn">
            <a href="menu.php" class="btn">view all</a>
        </div>

    </section>

    <!-- section products end -->

    <?php include 'components/footer.php'; ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

    <script src="js/script.js"></script>


</body>

</html>