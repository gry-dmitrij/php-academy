<?php
require_once __DIR__ . '/../bootstrap.php';

$user = get_auth_user();
if ($user === null) {
    header("Location: /", true, 303);
    exit;
}

$con = get_db();
$categories = get_categories($con);

$errors = [];
$data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    ["errors" => $errors, "data" => $data] = validate_lot($_POST, $_FILES, array_column($categories, "character_code"));

    if (empty($errors)) {
        $ids_by_code = array_column($categories, 'id', 'character_code');
        $category_id = $ids_by_code[$data['category']] ?? null;
        if ($category_id === null) {
            http_response_code(500);
            exit;
        }
        try {
            $target = save_uploaded_image($_FILES['lot-img']['tmp_name']);
        } catch (Throwable $e) {
            http_response_code(500);
            exit;
        }
        

        http_response_code(500);
        
        try {
            $lot_id = add_lot($con, [
                'title' => $data['lot-name'],
                'description' => $data['message'],
                'file_path' => $target,
                'start_price' => $data['lot-rate'],
                'step' => $data['lot-step'],
                'date_finish' => $data['lot-date'],
                'user_id' => $user['id'],
                'category_id' => $category_id,
            ]);
            header('Location: /lot?id=' . $lot_id, true, 303);
            exit;

        } catch (mysqli_sql_exception $e) {
            delete_uploaded_file($target);
            http_response_code(500);
            exit;
        }
    }
}

$page_content = include_template('add_lot.php', [
    "categories" => $categories,
    "errors" => $errors,
    "values" => $data,
]);

$layout = render_layout([
    "user" => $user,
    "categories" => $categories,
    "show_nav_menu" => true,
    "styles" => ['/css/flatpickr.min.css'],
], 'Добавить', $page_content);

print($layout);