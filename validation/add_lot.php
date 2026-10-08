<?php
function validate_lot(array $data, array $files, array $categories): array {
    $new_data = trim_fields(normalize_strings($data));

    $required = ['lot-name', 'message', 'lot-rate', 'lot-step', 'lot-date'];

    $errors = validate_required($new_data, $required);

    $float_conditions = [
        'lot-rate' => ['min_value' => 10, 'max_value' => MAX_PRICE],
        'lot-step' => ['min_value' => 10, 'max_value' => MAX_PRICE]
    ];
    ['data' => $new_data, 'errors' => $float_errors] = validate_floats($new_data, $float_conditions);
    $errors += $float_errors;
    $error = validate_list_item($new_data['category'] ?? '', $categories, 'Выберите категорию');
    if ($error !== '') {
        $errors['category'] = $error;
    }
    $errors += validate_dates($new_data, ['lot-date' => ['min_date' => new DateTimeImmutable('tomorrow')]]);

    $error = validate_image($files['lot-img'] ?? null, array_keys(ALLOWED_IMAGE_TYPES));
    if ($error !== '') {
        $errors['lot-img'] = $error;
    }

    return ["errors" => $errors, "data" => $new_data];
}
