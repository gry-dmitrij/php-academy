<?php

function validate_lot_bet(array $data, float $current_bet, $step): array {
    $new_data = trim_fields(normalize_strings($data));
    $errors = [];
    $error = validate_float($data['cost'], $current_bet + $step);
    if ($error) {
        $errors['cost'] = $error;
    }
    return ['data' => $new_data, 'errors' => $errors];
}