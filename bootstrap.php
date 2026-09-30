<?php
define('ROOT', __DIR__);
require_once ROOT . '/config.php';

require_once ROOT . '/data.php';
require_once ROOT . '/helpers/all.php';
require_once ROOT . '/template.php';
require_once ROOT . '/models/all.php';

require_once ROOT . '/validation/all.php';

session_start();

try {
    get_db();
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    print('Сайт временно недоступен. Попробуйте позже');
    exit;
}