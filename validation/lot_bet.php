<?php

function validate_lot_bet(array $data, float $min_bet): array {
    $new_data = trim_fields(normalize_strings($data));
    $required = ['cost'];
    $errors = validate_required($new_data, $required);
    if (empty($errors)) {
        ['data' => $new_data, 'errors' => $errors] = validate_floats($new_data, ['cost' => ['min_value' => $min_bet]]);
    }

    return ['data' => $new_data, 'errors' => $errors];
}
