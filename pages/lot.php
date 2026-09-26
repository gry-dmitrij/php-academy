<?php
require_once __DIR__ . '/../bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$con = get_db();
$lot = get_lot_by_id($con, $id);
if (!$lot) {
    http_response_code(404);
    return;
}
$bets = get_bets($con, $id);

$page_content = include_template('lot_description.php', [
        "lot" => $lot,
        "bets" => $bets,
    ]);

$categories = get_categories($con);

$goods = get_lots($con);

$layout = render_layout([
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "categories" => $categories,
    "show_nav_menu" => true,
], 'Лот', $page_content);

print($layout);
