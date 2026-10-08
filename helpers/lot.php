<?php
function is_lot_finished(array $lot): bool {
    return new DateTimeImmutable($lot['date_finish']) <= new DateTimeImmutable();
}
