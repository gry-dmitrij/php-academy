<?php
require_once __DIR__ . '/../bootstrap.php';

$user = get_auth_user();

if  ($user === null) {
    header('Location: /', true, 303);
    exit;
}

$data = [];

try {
    $data = validate_my_bets($_GET);
} catch (Throwable $e) {
    http_response_code(404);
    exit;
}
$page = (int) $data['page'];
$limit = 10;
$con = get_db();
$my_bets = get_my_bets($con, $user['id'], $page, $limit);
$bet_count = get_my_bets_count($con, $user['id']);
$page_count = calc_page_count($bet_count, $limit);
$page_content = include_template('my_bets.php',[
    'bets' => $my_bets,
    'user_id' => $user['id'],
    'page' => $page,
    'page_count' => $page_count
]);

$categories = get_categories($con);
$layout = render_layout([
    "user" => $user,
    "categories" => $categories,
    "show_nav_menu" => true,
], 'Мои ставки', $page_content);

print($layout);
