<?php

function validate_registration(array $data): array {
    $trim_fields = ['email', 'name', 'message'];
    $new_data = trim_fields(normalize_strings($data), $trim_fields);

    $required = ['email', 'password', 'name', 'message'];

    $errors = validate_required($new_data, $required);
    $errors += validate_max_len($new_data, [
        'email' => MAX_EMAIL_LEN,
        'name' => MAX_USER_NAME_LEN,
        'message' => MAX_CONTACTS_LEN,
    ]);
    $errors += validate_registration_email($new_data);
    $errors += validate_registration_password($new_data);

    return ["errors" => $errors, "data" => $new_data];
}

function validate_registration_email(array $data): array {
    if (!is_email($data['email'] ?? '')) {
        return ['email' => 'Неверный формат'];
    }
    return [];
}

function validate_registration_password(array $data): array {
    $field_name = 'password';
    $field = $data[$field_name] ?? '';
    if (mb_strlen($field) < MIN_PASS_LEN) {
        return [$field_name => "Минимальная длина пароля " . MIN_PASS_LEN . " символов"];
    }
    if (mb_strlen($field) > MAX_PASS_LEN) {
        return [$field_name => "Максимальная длина пароля " . MAX_PASS_LEN . " символов"];
    }
    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[_?!@])/';
    if (!preg_match($pattern, $field)) {
        return [$field_name => "Пароль должен содержать заглавную и строчную латинские буквы, цифру и знак _ ? ! @"];
    }
    return [];
}

function check_user_exist_by_email(mysqli $con, string $email): array {
    if (get_user_by_email($con, $email) !== null) {
        return ['email' => "Пользователь с таким email уже существует"];
    }
    return [];
}
