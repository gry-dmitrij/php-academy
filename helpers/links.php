<?php
function create_link(string $path, array $queries = []): string {
    $url = '/' . ltrim($path, '/');
    $query = http_build_query($queries);
    return $query == "" ? $url : "$url?$query";
}