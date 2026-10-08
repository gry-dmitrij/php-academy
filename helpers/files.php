<?php
function save_uploaded_image(string $source): string {
    $file_name = bin2hex(random_bytes(16));
    $file_type = get_file_type($source);
    $ext = ALLOWED_IMAGE_TYPES[$file_type] ?? null;
    if ($ext === null) {
        throw new RuntimeException("Неподдерживаемый тип файла: $file_type");
    }
    $target = UPLOAD_DIR . '/' . $file_name . '.' . $ext;
    $full_target = ROOT . '/' . $target;
    if (!move_file($source, $full_target)) {
        throw new RuntimeException("Не удалось сохранить файл в $full_target");
    }
    return $target;
}

function delete_uploaded_file(string $path): void {
    $full_path = ROOT . "/$path";
    if (is_file($full_path)) {
        unlink($full_path);
    }
}

function move_file(string $source, string $target): bool {
    $dirname = dirname($target);
    if (empty($dirname) || empty($source)) {
        return false;
    }

    if (!is_dir($dirname) && !mkdir($dirname) && !is_dir($dirname)) {
        return false;
    }

    return move_uploaded_file($source, $target);
}

function get_file_type(string $path): string {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    return finfo_file($finfo, $path) ?: '';
}
