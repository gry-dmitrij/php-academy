<?php
function get_user_by_email(mysqli $con, string $email): ?array {
    $sql = "SELECT *
            FROM users
            WHERE email=?
            LIMIT 1";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}

function add_user(mysqli $con, string $email, string $user_name, string $password, string $contacts): int {
    $password_hash = password_hash($password, PASSWORD_ALGO);
    $sql = "INSERT INTO users (date_registration, email, user_name, user_password, contacts)
            VALUES (NOW(), ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $email, $user_name, $password_hash, $contacts);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_insert_id($stmt);
}
