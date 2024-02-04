<?php

include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['password']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    // print_r($pass);
    // exit;
    $select_kurir = $conn->prepare("SELECT * FROM `kurir` WHERE name = ? AND password = ?");
    $select_kurir->execute([$name, $pass]);

    if ($select_kurir->rowCount() > 0) {
        $fetch_kurir_id = $select_kurir->fetch(PDO::FETCH_ASSOC);
        $_SESSION['kurir_id'] = $fetch_kurir_id['kurir_id'];
        header('location:kurir_dashboard.php');
    } else {
        $message[] = 'incorrect username or password!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login kurir</title>
    <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <style>
        .message {
            position: relative;
            padding: 10px 20px;
            margin: 15px 0;
            background-color: #f44336;
            color: #fff;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .message span {
            flex-grow: 1;
            margin-right: 10px;
        }

        .message i {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
        <div class="container">
            <div class="card login-card">
                <div class="row no-gutters">
                    <div class="col-md-5">
                        <img src="assets/images/kurir.webp" alt="login" class="login-card-img">
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <div class="brand-wrapper">
                                <img src="assets/images/logo.svg" alt="logo" class="logo">
                            </div>
                            <?php
                            if (isset($message)) {
                                foreach ($message as $msg) {
                                    echo '<div class="message"><span>' . $msg . '</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
                                }
                            }
                            ?>
                            <p class="login-card-description">Sign into your account</p>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="name" class="sr-only">username</label>
                                    <input type="name" name="name" id="name" class="form-control" placeholder="name" autocomplete="off">
                                </div>
                                <div class="form-group mb-4">
                                    <label for="password" class="sr-only">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="password">
                                </div>
                                <input name="submit" id="submit" class="btn btn-block login-btn mb-4" type="submit" value="submit">
                            </form>
                            <!-- <a href="#!" class="forgot-password-link">Forgot password?</a>
                     <p class="login-card-footer-text">Don't have an account? <a href="#!" class="text-reset">Register here</a></p> -->
                            <nav class="login-card-footer-nav">
                                <a href="">Terms of use.</a>
                                <a href="">Privacy policy</a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>