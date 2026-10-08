<?php
require_once __DIR__ . '/../bootstrap.php';

$user = get_auth_user();

if  ($user === null) {
    header('Location: /', true, 303);
    exit;
}
$con = get_db();
$my_bets = get_my_bets($con, $user['id']);
$page_content = include_template('my_bets.php',[
    'bets' => $my_bets,
    'user_id' => $user['id'],
]);

$categories = get_categories($con);
$layout = render_layout([
    "user" => $user,
    "categories" => $categories,
    "show_nav_menu" => true,
], 'Мои ставки', $page_content);

print($layout);
