<?php
require_once('bootstrap.php');

$con = get_db();
$categories = get_categories($con);

$goods = get_lots($con);

$page_content = include_template('main.php', ["goods" => $goods, "categories" => $categories]);

$layout = render_layout([
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "categories" => $categories,
    "class" => "container",
], 'Главная', $page_content);

print($layout);