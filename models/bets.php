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