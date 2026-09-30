<?php
function get_auth_user(): ?array {
    return $_SESSION['user'] ?? null;
}