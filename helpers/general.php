<?php
const SECONDS_IN_MINUTE = 60;

// пороги для «N минут назад / через N минут» (в секундах)
const TIME_AGO_EXACT_MINUTES_LIMIT = 5 * SECONDS_IN_MINUTE;          // до него: 1…5 минут поштучно
const TIME_AGO_FIVE_MINUTES_LIMIT  = (int) (7.5 * SECONDS_IN_MINUTE);  // до него: «5 минут»
const TIME_AGO_TEN_MINUTES_LIMIT   = 55 * SECONDS_IN_MINUTE;         // до него: 10, 20 … 50 минут
const TIME_AGO_HOUR_LIMIT          = 90 * SECONDS_IN_MINUTE;         // до него: «1 час», дальше календарный формат


/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && date_get_last_errors() === false;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

function format_relative_minutes(int $diff_seconds): string {
    $minute_words = ['минуту', 'минуты', 'минут'];
    $hour_words = ['час', 'часа', 'часов'];
    $is_future = $diff_seconds > 0;
    $abs_diff_seconds = abs($diff_seconds);
    $words = $abs_diff_seconds < TIME_AGO_TEN_MINUTES_LIMIT ? $minute_words : $hour_words;
    if ($abs_diff_seconds < TIME_AGO_EXACT_MINUTES_LIMIT) {
        $value = max((int) round($abs_diff_seconds / SECONDS_IN_MINUTE), 1);
    } elseif ($abs_diff_seconds < TIME_AGO_FIVE_MINUTES_LIMIT) {
        $value = 5;
    } elseif ($abs_diff_seconds < TIME_AGO_TEN_MINUTES_LIMIT) {
        $value = (int) round($abs_diff_seconds / (SECONDS_IN_MINUTE * 10)) * 10;
    } elseif ($abs_diff_seconds < TIME_AGO_HOUR_LIMIT) {
        $value = 1;
    } else {
        throw new RangeException("Интервал $diff_seconds вне диапазона (-" . TIME_AGO_HOUR_LIMIT . ' < diff_seconds < ' . TIME_AGO_HOUR_LIMIT . ')');
    }

    $result = ($value > 1 ? "$value " : '') . get_noun_plural_form($value, ...$words);
    $result = $is_future ? 'через ' . $result : $result . ' назад';
    $result = mb_ucfirst($result);
    return $result;
}

function format_relative_day(DateTimeImmutable $date, DateTimeImmutable $base_date): string {
    $date_day = $date->setTime(0, 0);
    $base_day = $base_date->setTime(0, 0);
    if ($date_day == $base_day) {
        return 'Сегодня, в ' . $date->format('H:i');
    }

    $is_future = $date > $base_date;
    $base_next_day = $base_day->modify('1 day');
    $base_prev_day = $base_day->modify('-1 day');
    if ($date_day == $base_prev_day || $date_day == $base_next_day) {
        return ($is_future ? 'Завтра' : 'Вчера') . ', в ' . $date->format('H:i');
    }

    return $date->format('d.m.y в H:i');
}

function format_relative_date(string $date, ?string $base_date = null): string {
    $date = new DateTimeImmutable($date);
    $base_date = $base_date !== null ? new DateTimeImmutable($base_date) : new DateTimeImmutable();
    $diff_seconds = $date->getTimestamp() - $base_date->getTimestamp();
    if (abs($diff_seconds) < TIME_AGO_HOUR_LIMIT) {
        return format_relative_minutes($diff_seconds);
    }

    return format_relative_day($date, $base_date);
}

function format_price($price): string {
    return number_format($price, 2, "."," ")." ₽";
}

function get_dt_range(string $time) {
    $now = date_create("now");
    $expired = date_create($time);
    $interval = date_diff($expired, $now);
    if ($interval->invert == 0) {
        return [0, 0];
    }
    $arr_time[0] = $interval->days * 24 + $interval->h;
    $arr_time[1] = $interval->i;
    return $arr_time;
}

function get_min_bet(string $price, string $step): string {
    return number_format((float) $price + (float) $step, 2, '.', '');
}
