<?php
require_once('bootstrap.php');

$user = get_auth_user();
$con = get_db();
$categories = get_categories($con);

$goods = get_lots($con);

$page_content = include_template('main.php', ["goods" => $goods, "categories" => $categories]);

$layout = render_layout([
    "user" => $user,
    "categories" => $categories,
    "class" => "container",
], 'Главная', $page_content);

print($layout);
