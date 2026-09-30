<?php
function get_auth_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function auth(mysqli $con, string $email, string $password): ?array {
    $user = get_user_by_email($con, $email);
    if ($user === null) {
        return null;
    }
    return password_verify($password, $user['user_password'] ?? '') ? $user : null;
}