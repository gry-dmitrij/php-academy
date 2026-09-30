<?php
require_once __DIR__ . '/../bootstrap.php';

$con = get_db();
$categories = get_categories($con);

$errors = [];
$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ["errors" => $errors, "data" => $data] = validate_registration($_POST);

    if (empty($errors)) {
        $errors += check_user_exist_by_email($con, $data['email'] ?? '');
    }

    if (empty($errors)) {
        try {
            add_user($con, $data['email'], $data['name'], $data['password'], $data['message']);
            header("Location: /", true, 303);
            exit;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                $errors['email'] = 'Пользователь с таким email уже существует';
            } else {
                http_response_code(500);
                exit;
            }
        }
    }
}

$page_content = include_template('sign-up.php', [
    "errors" => $errors,
    "values" => $data,
]);

$layout = render_layout([
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "categories" => $categories,
    "show_nav_menu" => true,
], 'Регистрация', $page_content);

print($layout);