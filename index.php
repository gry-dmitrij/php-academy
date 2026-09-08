<?php
    require_once('data.php');
    require_once('helpers.php');
    
    $sql = "SELECT id, character_code, name_category FROM categories;";
    $result = mysqli_query($con, $sql);
    $categories = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];

    $sql = "SELECT l.id, title, date_creation, date_finish, start_price, img, character_code, name_category
            FROM lots l
            JOIN categories c ON l.category_id=c.id
            WHERE date_finish > CURRENT_TIMESTAMP
            ORDER BY date_creation DESC;";
    $result = mysqli_query($con, $sql);
    $goods = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];

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