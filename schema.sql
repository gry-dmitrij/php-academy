CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE yeticave;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    character_code VARCHAR(128) UNIQUE NOT NULL,
    name_category VARCHAR(128) NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_registration DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    email VARCHAR(128) NOT NULL UNIQUE,
    user_name VARCHAR(128) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    contacts TEXT
);

CREATE TABLE lots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    title VARCHAR(255) NOT NULL,
    lot_description TEXT,
    img VARCHAR(255),
    start_price DECIMAL(11, 2) NOT NULL,
    date_finish DATE NOT NULL,
    step DECIMAL(11, 2) NOT NULL,
    user_id INT NOT NULL,
    winner_id INT,
    category_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (winner_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE bets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_bet DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    price_bet DECIMAL(11, 2) NOT NULL,
    user_id INT NOT NULL,
    lot_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (lot_id) REFERENCES lots(id)
);

CREATE FULLTEXT INDEX lots_name_desc_search on lots(title, lot_description);
