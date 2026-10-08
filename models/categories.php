<?php
function get_categories(mysqli $con): array {
    $sql = "SELECT id, character_code, name_category FROM categories;";
    $result = mysqli_query($con, $sql);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}
