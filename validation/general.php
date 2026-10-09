<?php
function is_filled($value): bool {
    return is_string($value) && trim($value) !== "";
}

function validate_required(array $data, array $fields, string $message = "Это обязательное поле"): array {
    $errors = [];

    foreach ($fields as $field) {
        if (!is_filled($data[$field] ?? '')) {
            $errors[$field] = $message;
        }
    }

    return $errors;
}

function is_email($email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function trim_fields(array $data, ?array $fields = null): array {
    $fields ??= array_keys($data);
    foreach ($fields as $field) {
        if (!isset($data[$field])) {
            continue;
        }
        $value = $data[$field];
        if (!is_string($value)) {
            continue;
        }
        $data[$field] = trim($value);
    }
    return $data;
}

function validate_max_len(array $data, array $conditions): array {
    $errors = [];
    foreach ($conditions as $key => $value) {
        if (mb_strlen($data[$key] ?? '') > $value) {
            $errors[$key] = "Максимальная длина поля $value символов";
        }
    }
    return $errors;
}

function validate_float(string $value, float $min_value = -PHP_FLOAT_MAX, float $max_value = PHP_FLOAT_MAX): string {
    $FLOAT_ERROR_MESSAGE = "Значение должно быть числом, в дробной части не более двух цифр";
    $value = trim($value);
    if (!preg_match('/^-?\d+([.,]\d{1,2})?$/', $value)) {
        return $FLOAT_ERROR_MESSAGE;
    }
    $float = filter_var(str_replace(',', '.', $value), FILTER_VALIDATE_FLOAT);
    if ($float === false) {
        return $FLOAT_ERROR_MESSAGE;
    }
    if ($float < $min_value) {
        return "Величина должна быть не меньше $min_value";
    }
    if ($float > $max_value) {
        return "Величина должна быть не больше $max_value";
    }
    return "";
}

function validate_floats(array $data, array $conditions): array {
    $errors = [];
    $defaults_rules = [
        'min_value' => -PHP_FLOAT_MAX,
        'max_value' => PHP_FLOAT_MAX,
        'clear_on_format_error' => true,
        'clear_on_range_error' => false,
    ];
    foreach ($conditions as $key => $value) {
        [$field, $rules] = is_int($key) ? [$value, []] : [$key, $value];
        $rules += $defaults_rules;
        $input = trim($data[$field] ?? '');

        $error = validate_float($input);
        $clear = $rules['clear_on_format_error'];
        if ($error === '') {
            $error = validate_float($input, $rules['min_value'], $rules['max_value']);
            $clear = $rules['clear_on_range_error'];
        }

        if ($error !== '') {
            $errors[$field] = $error;
            if ($clear) {
                $data[$field] = '';
            }
        } else {
            $data[$field] = str_replace(',', '.', $input);
        }
    }
    return ['data' => $data, 'errors' => $errors];
}

function validate_int(string $value, int $min_value = -PHP_INT_MAX, int $max_value = PHP_INT_MAX): string {
    $value = trim($value);
    $int = filter_var($value, FILTER_VALIDATE_INT);
    if ($int === false) {
        return "Значение должно быть числом";
    }
    if ($int < $min_value) {
        return "Значение должно быть не меньше $min_value";
    }
    if ($int > $max_value) {
        return "Значение должно быть не больше $max_value";
    }
    return "";
}

function validate_list_item(string $item, array $list, string $message = 'Значение не входит в список'): string {
    if (in_array($item, $list, true)) {
        return '';
    }

    return $message;
}

function validate_dates(array $data, array $conditions): array {
    $errors = [];
    $defaults_rules = ['min_date' => null];
    foreach ($conditions as $key => $value) {
        [$field, $rules] = is_int($key) ? [$value, []] : [$key, $value];
        $rules += $defaults_rules;
        $input = trim($data[$field] ?? '');

        $error = validate_date($input, $rules['min_date']);

        if ($error !== '') {
            $errors[$field] = $error;
        }
    }
    return $errors;
}

function validate_date(string $date, ?DateTimeInterface $min_date = null): string {
    if (!is_date_valid($date)) {
        return 'Неверный формат даты';
    }
    if ($min_date === null) {
        return '';
    }
    $check_date = date_create_from_format('!Y-m-d', $date);
    if ($check_date < $min_date) {
        return 'Дата должна быть не раньше ' . $min_date->format('d.m.Y');
    }
    return '';
}

function validate_image(?array $file, array $allowed_types, int $max_size = 1024 * 1024): string {
    $EMPTY_FILE_ERROR = "Добавьте изображение";
    $FILE_SIZE_ERROR = sprintf("Максимальный размер файла %s M", $max_size / (1024 * 1024));
    if (!isset($file)) {
        return $EMPTY_FILE_ERROR;
    }
    $file_error = $file['error'];
    if ($file_error !== UPLOAD_ERR_OK) {
        if ($file_error === UPLOAD_ERR_INI_SIZE || $file_error === UPLOAD_ERR_FORM_SIZE) {
            return $FILE_SIZE_ERROR;
        }
        if ($file_error === UPLOAD_ERR_NO_FILE) {
            return $EMPTY_FILE_ERROR;
        }
        return "Возникла ошибка при загрузке файла";
    }
    if ($file['size'] === 0) {
        return $EMPTY_FILE_ERROR;
    }
    if ($file['size'] > $max_size) {
        return $FILE_SIZE_ERROR;
    }
    $file_type = get_file_type($file['tmp_name']);
    if (!in_array($file_type, $allowed_types, true)) {
        return "Можно загрузить только " . format_allowed_types($allowed_types);
    }
    return '';
}

function format_allowed_types(array $allowed_types): string {
    if (!$allowed_types) {
        return '';
    }
    $last = array_pop($allowed_types);
    $text = $allowed_types ? implode(', ', $allowed_types) . ' или ' . $last : $last;
    return str_replace('image/', '', $text);
}

function validate_page(string $page, ?int $min_page = 1): string {
    $validated_page = filter_var($page, FILTER_SANITIZE_NUMBER_INT);
    if ((int) $validated_page < $min_page) {
        $validated_page = (string) $min_page;
    }
    return $validated_page;
}

function calc_page_count(int $item_count, int $limit): int {
    if ($limit === 0) {
        return 0;
    }
    return (int) ceil($item_count / $limit);
}
