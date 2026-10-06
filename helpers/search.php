<?php
function create_search_link(string $search, int $page): string {
    return '/search?' . http_build_query(['search' => $search, 'page' => $page]);
}

function create_pagination_range(int $page_count, int $page): array {
    $paddings = 2;
    $margins = 2;
    $max_count = 2 * $paddings + 2 * $margins + 3;
    if ($max_count >= $page_count) {
        return range(1, $page_count);
    }
    if ($page > $page_count) {
        $page = $page_count;
    }

    $min_side = $margins;
    $max_side = $max_count - $margins - 1;

    if ($page <= $paddings + $margins + 2) {
        $right_count = $min_side;
        $left_count = $max_side;
        return array_merge(range(1, $left_count), [0], range($page_count - $right_count + 1, $page_count));
    }
    if ($page >= $page_count - $margins - $paddings - 1) {
        $left_count = $min_side;
        $right_count = $max_side;
        return array_merge(range(1, $left_count), [0], range($page_count - $right_count + 1, $page_count));
    }
    return array_merge(range(1, $margins), [0], range($page - $paddings, $page + $paddings), [0], range($page_count - $margins + 1, $page_count));
}