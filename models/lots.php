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

function get_lot_by_id(mysqli $con, string $id, bool $lock = false): ?array {
    $sql = "SELECT l.*, name_category
        FROM lots l
        JOIN categories c ON c.id=l.category_id
        WHERE l.id=?";
    if ($lock) {
        $sql .= " FOR UPDATE";
    }
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

function get_lots_by_search(mysqli $con, string $search, int $page = 1, int $limit = 9): array {
    $sql = "SELECT l.*, c.name_category, c.character_code,
                   MATCH(title, lot_description) AGAINST(?) score
            FROM lots l
            JOIN categories c ON c.id=l.category_id
            WHERE MATCH(title, lot_description) AGAINST(?) AND l.date_finish > CURRENT_TIMESTAMP
            ORDER BY score DESC, l.date_creation DESC, l.date_finish DESC, l.id
            LIMIT ? OFFSET ?";
    if ($page < 1) {
        $page = 1;
    }
    $offset = ($page - 1) * $limit;
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'ssii', $search, $search, $limit, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

function count_lots_by_search(mysqli $con, string $search): int {
    $sql = "SELECT COUNT(*) count
            FROM lots l
            WHERE MATCH(title, lot_description) AGAINST(?) AND l.date_finish > CURRENT_TIMESTAMP";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt,'s', $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $res_array = $result ? mysqli_fetch_assoc($result) : ["count" => 0];
    return (int) $res_array['count'];
}