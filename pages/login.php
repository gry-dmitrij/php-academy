<?php
require_once __DIR__ . '/../bootstrap.php';

$user = get_auth_user();
if ($user !== null) {
    header("Location: /", true, 303);
    exit;
}

$con = get_db();
$categories = get_categories($con);

$errors = [];
$data = [];
$user = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ["data" => $data, "errors" => $errors] = validate_login($_POST);
    if (empty($errors)) {
        $user = auth($con, $data['email'] ?? '', $data['password'] ?? '');
        if ($user !== null) {
            $_SESSION['user'] = $user;
            header('Location: /', true, 303);
            exit;
        } else {
            $errors['password'] = "Неверный пароль";
        }
    }
}

$page_content = include_template('login.php', [
    "errors" => $errors,
    "values" => $data,
]);

$layout = render_layout([
    "user" => $user,
    "categories" => $categories,
    "show_nav_menu" => true,
], 'Вход', $page_content);

print($layout);

function auth(mysqli $con, string $email, string $password): ?array {
    $user = get_user_by_email($con, $email);
    if ($user === null) {
        return null;
    }
    return password_verify($password, $user['user_password'] ?? '') ? $user : null;
}