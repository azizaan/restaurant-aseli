<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
    }
}
?>
<style>
    .profile {
        text-align: center;
    }

    .profile-picture-container {
        width: 120px;
        /* Sesuaikan dengan ukuran yang diinginkan */
        height: 120px;
        /* Sesuaikan dengan ukuran yang diinginkan */
        overflow: hidden;
        border-radius: 50%;
        margin: 0 auto;
    }

    .profile-picture {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    

    .name {
        font-size: 18px;
        margin-top: 10px;
    }

    .saldo {
        font-size: 18px;
        margin-top: 10px;
    }

    /* .flex {
        margin-top: 20px;
    } */

    /* .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #3498db;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        margin-right: 10px;
    } */

    .delete-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #e74c3c;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
    }
</style>

<header class="header">

    <section class="flex">

        <a href="home.php" class="logo">yum-yum 😋</a>

        <nav class="navbar">
            <a href="home.php">home</a>
            <!-- <a href="about.php">about</a> -->
            <a href="menu.php">menu</a>
            <?php
            // Only display 'orders' and 'riwayat' when the user is logged in
            if ($user_id) {
                echo '<a href="orders.php">orders</a>';
                echo '<a href="riwayat.php">riwayat</a>';
                // echo '<a href="saldo.php">top up saldo</a>';
            }
            ?>

        </nav>

        <div class="icons">
            <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_items = $count_cart_items->rowCount();
            ?>
            <a href="search.php"><i class="fas fa-search"></i></a>
            <a href="<?php echo ($user_id) ? 'cart.php' : 'login.php'; ?>">
                <i class="fas fa-shopping-cart"></i>
                <span>(<?= $total_cart_items; ?>)</span>
            </a>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="menu-btn" class="fas fa-bars"></div>
        </div>

        <div class="profile">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if ($select_profile->rowCount() > 0) {
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
                <div class="profile-picture-container">
                    <img src="<?= $fetch_profile['profile_picture']; ?>" alt="" class="profile-picture">
                </div>
                <p class="name">Hi! <?= $fetch_profile['name']; ?></p>
                <?php
                // Cek apakah saldo null atau 0
                if ($fetch_profile['saldo'] === null || $fetch_profile['saldo'] == 0 || $fetch_profile['saldo'] == '') {
                ?>
                    <p class="saldo">Saldo anda : Rp 0</p>
                <?php
                } else {
                ?>
                    <p class="saldo">Saldo anda : Rp <?= $fetch_profile['saldo']; ?></p>
                <?php
                }
                ?>
                <div class="flex">
                    <a href="profile.php" class="btn">profile</a>
                    <!-- <a href="saldo.php" class="btn-saldo">Top up</a> -->
                    <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
                </div>
            <?php
            } else {
            ?>
                <p class="name">please login first!</p>
                <a href="login.php" class="btn">login</a>
                <p class="account">don't have an account yet? <a href="register.php">register</a></p>
            <?php
            }
            ?>
        </div>


    </section>

</header>