USE yeticave;

INSERT INTO categories (character_code, name_category) VALUES
    ('boards', 'Доски и лыжи'),
    ('attachment', 'Крепления'),
    ('boots', 'Ботинки'),
    ('clothing', 'Одежда'),
    ('tools', 'Инструменты'),
    ('other', 'Разное');

INSERT INTO users (date_registration, email, user_name, user_password, contacts) VALUES
    ('2026-09-01 10:00:05', 'user1@mail.com', 'user1', '1234', ''),
    ('2026-08-27 13:08:13', 'user2@mail.com', 'user2', '1234', ''),
    ('2026-08-25 16:24:27', 'user3@mail.com', 'user3', '1234', '');

INSERT INTO lots (date_creation, title, lot_description, img, start_price, date_finish, step, user_id, winner_id, category_id) VALUES
    ('2026-09-01 14:00:01', '2014 Rossignol District Snowboard', '', 'img/lot-1.jpg', 10999, '2026-09-15', 0, 1, NULL, 1),
    ('2026-09-01 12:37:15', 'DC Ply Mens 2016/2017 Snowboard', '', 'img/lot-2.jpg', 159999, '2026-09-18', 0, 1, NULL, 1),
    ('2026-09-01 09:12:21', 'Крепления Union Contact Pro 2015 года размер L/XL', '', 'img/lot-3.jpg', 8000, '2026-09-11', 0, 2, NULL, 2),
    ('2026-09-01 08:03:11', 'Ботинки для сноуборда DC Mutiny Charocal', '', 'img/lot-4.jpg', 10999, '2026-09-10', 0, 2, NULL, 3),
    ('2026-09-01 01:29:56', 'Куртка для сноуборда DC Mutiny Charocal', '', 'img/lot-5.jpg', 7500, '2026-09-02', 0, 3, NULL, 4),
    ('2026-09-01 05:43:41', 'Маска Oakley Canopy', '', 'img/lot-6.jpg', 5400, '2026-09-02', 0, 1, NULL, 6);

INSERT INTO bets (date_bet, price_bet, user_id, lot_id) VALUES
    ('2026-09-01 09:13:50', 8100, 3, 3),
    ('2026-09-01 12:28:50', 8100, 1, 4);

-- Получить все категории
SELECT name_category FROM categories;

-- Получить открытые лоты
SELECT l.title,
       l.start_price,
       l.img,
       c.name_category,
       COALESCE(MAX(b.price_bet), l.start_price) as current_price
    FROM lots l 
    JOIN categories c ON l.category_id = c.id
    LEFT JOIN bets b ON b.lot_id = l.id
    GROUP BY l.id;

-- Получить лот по ID
SELECT l.id, l.date_creation, l.title, l.lot_description, l.img, l.start_price, l.date_finish, l.step, c.name_category
    FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE l.id = 4;

-- Обновить название лота
UPDATE lots SET title='Ботинки для сноуборда DC Mutiny Charocal' WHERE id=4;

SELECT b.date_bet, b.price_bet, l.title, u.user_name
    FROM bets b
    JOIN lots l ON l.id = b.lot_id
    JOIN users u ON u.id = b.user_id
    WHERE l.id = 4
    ORDER BY b.date_bet DESC;
