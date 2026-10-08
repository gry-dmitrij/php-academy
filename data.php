<?php
function get_db(): mysqli {
    static $con = null;
    if ($con === null) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $con = mysqli_connect("127.0.1.18:3306", "root", "", "yeticave");
        mysqli_set_charset($con, "utf8mb4");
    }
    return $con;
}
