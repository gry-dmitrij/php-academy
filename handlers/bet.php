<?php
function handle_lot_bet(mysqli $con, int $lot_id, ?array $user, array $data): array {
    if (!isset($user)) {
        return ['data' => [], 'errors' => ['cost' => 'Только авторизованные пользователи могут делать ставки']];
    }
    $committed = false;
    mysqli_begin_transaction($con);
    try {
        $lot = get_lot_by_id($con, $lot_id, true);
        if (!$lot) {
            throw new UnexpectedValueException('Lot not found');
        }
        if ($lot['user_id'] === $user['id']) {
            return ['data' => $data, 'errors' => ['cost' => 'Нельзя делать ставки на свои лоты']];
        }
        $max_bet = get_max_bet($con, $lot_id);
        $max_bet_value = $max_bet['price_bet'] ?? $lot['start_price'];

        ['data' => $data, 'errors' => $errors] = validate_lot_bet($data, $max_bet_value, $lot['step']);
        if (!empty($errors)) {
            return ['data' => $data, 'errors' => $errors];
        }
        add_bet($con, $lot['id'], $user['id'], $data['cost']);
        mysqli_commit($con);
        $committed = true;
        return ['data' => $data, 'errors' => $errors];
    } catch (Throwable $e) {
        throw $e;
    } finally {
        if (!$committed) {
            mysqli_rollback($con);
        }
    }
}