<?php
    require_once('data.php');
    require_once('helpers.php');

    $is_auth = rand(0, 1);

    $user_name = 'Dmitrij'; // укажите здесь ваше имя

    $page_content = include_template('main.php', ["goods" => $goods, "categories" => $categories]);

    $layout = include_template('layout.php', [
        "title" => "Главная",
        "is_auth" => $is_auth,
        "user_name" => $user_name,
        "categories" => $categories,
        "content" => $page_content
        ]);

    print($layout);