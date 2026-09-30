<?php
function validate_login(array $data): array {
    $new_data = normalize_strings($data);

    $required = ['email', 'password'];
    $errors = validate_required($data, $required);
    return ['data' => $new_data, 'errors' => $errors];
}