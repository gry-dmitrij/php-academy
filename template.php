<?php
/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($__template_path, array $data = []) {
    $__template_path = __DIR__ . '/templates/' . $__template_path;
    $result = '';

    if (!is_readable($__template_path)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $__template_path;

    $result = ob_get_clean();

    return $result;
}

function render_layout(array $common, string $title, string $content): string {
    return include_template('layout.php', array_merge($common, [
        "title" => $title,
        'content' => $content,
    ]));
}
