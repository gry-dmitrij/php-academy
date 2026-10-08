<?php
function validate_login(array $data): array {
    $trim_fields = ['email'];
    $new_data = trim_fields(normalize_strings($data), $trim_fields);

    $required = ['email', 'password'];
    $errors = validate_required($new_data, $required);
    return ['data' => $new_data, 'errors' => $errors];
}
