<?php
function get_lots(mysqli $con): array {
    $sql = "SELECT l.id, title, date_creation, date_finish, start_price, img, character_code, name_category
        FROM lots l
        JOIN categories c ON l.category_id=c.id
        WHERE date_finish > CURRENT_TIMESTAMP
        ORDER BY date_creation DESC;";
    $result = mysqli_query($con, $sql);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

function get_lot_by_id(mysqli $con, string $id): ?array {
    $sql = "SELECT l.*, name_category
        FROM lots l
        JOIN categories c ON c.id=l.category_id
        WHERE l.id=?";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}

function add_lot(mysqli $con, array $lot): int {
    [
        'title' => $title,
        'description' => $description,
        'file_path' => $file_path,
        'start_price' => $start_price,
        'step' => $step,
        'date_finish' => $date_finish,
        'user_id' => $user_id,
        'category_id' => $category_id
    ] = $lot;
    $sql = "INSERT INTO lots (date_creation, title, lot_description, img, start_price, date_finish, step, user_id, category_id)
            VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssssii', $title, $description, $file_path, $start_price, $date_finish, $step, $user_id, $category_id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_insert_id($stmt);
}