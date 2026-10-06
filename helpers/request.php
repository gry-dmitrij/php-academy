<?php
function normalize_strings(array $data): array {
    return array_map(fn($value) => is_string($value) ? $value : "", $data);
}