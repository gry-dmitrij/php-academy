<?php
function get_bets(mysqli $con, string $lot_id): array {
    $sql = "SELECT b.id, price_bet, date_bet, u.user_name
            FROM bets b
            JOIN users u ON b.user_id=u.id
            WHERE b.lot_id=?
            ORDER BY date_bet DESC";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $lot_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function get_my_bets(mysqli $con, int $user_id, int $page = 1, int $limit = 10): array {
    $sql = "SELECT b.date_bet, b.price_bet, b.lot_id, l.img, l.title, l.date_finish,
                   l.winner_id, c.character_code, c.name_category, u.contacts,
                   m.max_price=b.price_bet is_max
            FROM bets b
            JOIN lots l ON b.lot_id=l.id
            JOIN categories c ON l.category_id=c.id
            JOIN users u ON u.id=l.user_id
            JOIN (
                SELECT lot_id, MAX(price_bet) max_price
                FROM bets
                WHERE lot_id IN (SELECT lot_id FROM bets WHERE user_id=?)
                GROUP BY lot_id
            ) m ON m.lot_id=b.lot_id
            WHERE b.user_id=?
            ORDER BY b.date_bet DESC, b.price_bet DESC, b.id DESC
            LIMIT ? OFFSET ?";
    if ($page < 1) {
        $page = 1;
    }
    $offset = ($page - 1) * $limit;
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'iiii', $user_id, $user_id, $limit, $offset);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function get_max_bet(mysqli $con, string $lot_id): ?array {
    $sql = "SELECT *
            FROM bets b
            WHERE lot_id=?
            ORDER BY price_bet DESC
            LIMIT 1";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $lot_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}

function add_bet(mysqli $con, int $lot_id, int $user_id, float $price): int {
    $sql = "INSERT INTO bets(date_bet, price_bet, user_id, lot_id)
            VALUES (NOW(), ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $price, $user_id, $lot_id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_insert_id($stmt);
}