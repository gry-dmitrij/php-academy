<?php
require_once __DIR__ . "/../bootstrap.php";

$con = get_db();
$categories = get_categories($con);
$user = get_auth_user();

['data' => $data, 'errors' => $errors] = validate_search($_GET);

$page = $data['page'] ?? 1;
$search_request = $data['search'] ?? '';
$limit = 9;
$can_search = $search_request !== "" && empty($errors);

$lot_count = $can_search ? count_lots_by_search($con, $search_request) : 0;
$page_count = (int) ceil($lot_count / $limit);

$page = min($page, max($page_count, 1));

$lots = $can_search && $lot_count !== 0 ? get_lots_by_search($con, $search_request, $page, $limit) : [];

$page_content = include_template("search.php",[
    "search" => $search_request,
    "page" => $page,
    "page_count" => $page_count,
    "lots" => $lots,
    "errors" => $errors
]);

$layout = render_layout([
    "user" => $user,
    "categories" => $categories,
    "show_nav_menu" => true,
    "search" => $search_request,
], 'Поиск', $page_content);

print($layout);
