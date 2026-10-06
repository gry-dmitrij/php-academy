<?php
function validate_lot(array $data, array $files, array $categories): array {
    $new_data = normalize_strings($data);

    $required = ['lot-name', 'message', 'lot-rate', 'lot-step', 'lot-date', 'category'];

    $errors = validate_required($new_data, $required);

    validate_floats(['lot-rate', 'lot-step'], $new_data, $errors);
    validate_category($new_data['category'] ?? '', $errors, $categories);
    validate_date($new_data, $errors);
    validate_image($files['lot-img'] ?? null, 'lot-img', $errors, array_keys(ALLOWED_IMAGE_TYPES));

    return ["errors" => $errors, "data" => $new_data];
}

function validate_floats(array $fields, array &$data, array &$errors) {
    foreach ($fields as $field) {
        if (isset($errors[$field])) {
            continue;
        }

        $value = trim($data[$field]);
        $min_value = get_min_value($field);

        $error = validate_float($value, $min_value, MAX_PRICE); 

        if ($error) {
            $errors[$field] = $error;
            $data[$field] = "";
        } else {
            $data[$field] = str_replace(',', '.', $value);
        }

    }
}

function get_min_value(string $field_name): float {
    switch ($field_name) {
        case 'lot-rate':
            return 10;
        case 'lot-step':
            return 10;
        default:
            return 0;
    }
}

function validate_category(string $category, array &$errors, array $categories) {
    if (isset($errors['category'])) {
        return;
    }

    if (in_array($category, $categories, true)) {
        return;
    }

    $errors['category'] = "Выберите категорию";

}

function validate_date(array &$data, array &$errors) {
    $field_name = 'lot-date';
    if (isset($errors[$field_name])) {
        return;
    }
    $date = $data[$field_name];
    if (!is_date_valid($date)) {
        $errors[$field_name] = "Неверный формат даты";
        $data[$field_name] = "";
        return;
    }
    $check_date = date_create_from_format('!Y-m-d', $date);
    $tomorrow = date_create('tomorrow');
    if ($check_date < $tomorrow) {
        $errors[$field_name] = 'Дата должна быть больше текущей даты';
    }
}

function validate_image(?array $file, string $field_name, array &$errors, array $allowed_types, int $max_size = 1024 * 1024) {
    $EMPTY_FILE_ERROR = "Добавьте изображение";
    $FILE_SIZE_ERROR = sprintf("Максимальный размер файла %s M", $max_size / (1024 * 1024));
    if (!isset($file)) {
        $errors[$field_name] = $EMPTY_FILE_ERROR;
        return;
    }
    $file_error = $file['error'];
    if ($file_error !== UPLOAD_ERR_OK) {
        if ($file_error === UPLOAD_ERR_INI_SIZE) {
            $errors[$field_name] = $FILE_SIZE_ERROR;
        } elseif ($file_error === UPLOAD_ERR_NO_FILE) {
            $errors[$field_name] = $EMPTY_FILE_ERROR;
        } else {
            $errors[$field_name] = "Возникла ошибка при загрузке файла";
        }
        return;
    }
    if ($file['size'] === 0) {
        $errors[$field_name] = $EMPTY_FILE_ERROR;
        return;
    }
    if ($file['size'] > $max_size) {
        $errors[$field_name] = $FILE_SIZE_ERROR;
        return;
    }
    $file_type = get_file_type($file['tmp_name']);
    if (!in_array($file_type, $allowed_types, true)) {
        $errors[$field_name] = "Можно загрузить только " . format_allowed_types($allowed_types);
    }
}

function format_allowed_types(array $allowed_types): string {
    if (!$allowed_types) {
        return '';
    }
    $last = array_pop($allowed_types);
    $text = $allowed_types ? implode(', ', $allowed_types) . ' или ' . $last : $last;
    return str_replace('image/', '', $text);
}