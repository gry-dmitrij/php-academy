<?php

function validate_search(array $data): array {
    $errors = [];
    $fields = ['search', 'page'];
    $new_data = trim_fields(normalize_strings($data), $fields);
    $errors += validate_max_len($new_data, ['search' => MAX_SEARCH_STRING]);
    if (isset($data['page'])) {
        if (!validate_int($new_data['page'], 1)) {
            $new_data['page'] = (int) $new_data['page'];
        } else {
            unset($new_data['page']);
        }
    }

    return ["errors" => $errors, "data" => $new_data];
}