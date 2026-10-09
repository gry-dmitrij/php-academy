<?php
require_once __DIR__ . '/../bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!is_int($id)) {
    http_response_code(404);
    exit;
}
$user = get_auth_user();

$con = get_db();
$lot = get_lot_by_id($con, $id);
if (!$lot) {
    http_response_code(404);
    exit;
}

$errors = [];
$lot_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        ['data' => $lot_data, 'errors' => $errors] = handle_lot_bet($con, $id, $user, $_POST);
        if (empty($errors)) {
            header('Location: ' . create_link('lot', ['id' => $lot['id']]), true, 303);
            exit;
        }
    } catch (Throwable $e) {
        http_response_code(404);
        exit;
    }

}

$bets = get_bets($con, $id);
$max_bet = get_max_bet($con, $lot['id']);

$page_content = include_template('lot_description.php', [
        'lot' => $lot,
        'bets' => $bets,
        'can_bet' => !is_lot_finished($lot) &&
                     $user !== null &&
                     $user['id'] !== $lot['user_id'] &&
                     $user['id'] !== ($max_bet['user_id'] ?? null),
        'values' => $lot_data,
        'errors' => $errors,
    ]);

$categories = get_categories($con);

$layout = render_layout([
    "user" => $user,
    "categories" => $categories,
    "show_nav_menu" => true,
], 'Лот', $page_content);

print($layout);
