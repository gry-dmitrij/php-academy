<?php
    $con = mysqli_connect("127.0.1.18:3306", "root", "", "yeticave");
    if ($con == false) {
        print("Ошибка подключения: " . mysqli_connect_error());
    }
    mysqli_set_charset($con, "utf8");
?>