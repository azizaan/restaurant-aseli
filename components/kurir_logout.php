<?php

include 'connect.php';

session_start();
session_unset();
session_destroy();

header('location:../kurir/kurir_login.php');

?>