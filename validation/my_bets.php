<?php
function validate_my_bets(array $data): array {
    $new_data = trim_fields(normalize_strings($data));
    $new_data['page'] = validate_page($new_data['page'] ?? '', 1);
    if (($data['page'] ?? null) !== null && $data['page'] !== $new_data['page']) {
        throw new UnexpectedValueException('Error page number');
    }
    return $new_data;
}
