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

function trim_fields(array $data, array $fields): array {
    foreach ($fields as $field) {
        if (!isset($data[$field])) {
            continue;
        }
        $value = $data[$field] ?? '';
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

function validate_float(string $value, $min_value = -PHP_FLOAT_MAX, $max_value = PHP_FLOAT_MAX): string {
    $FLOAT_ERROR_MESSAGE = "Значение должно быть числом, в дробной части не более двух цифр";
    $value = trim($value);
    if (!preg_match('/^\d+([.,]\d{1,2})?$/', $value)) {
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

function validate_int(string $value, $min_value = - PHP_INT_MAX, $max_value = PHP_INT_MAX): string {
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