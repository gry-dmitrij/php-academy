<?php
function normalize_strings(array $data): array {
    return array_map(fn($value) => is_string($value) ? $value : "", $data);
}

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