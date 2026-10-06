-- GameVault - Script de criação do banco de dados
-- Compatível com MySQL 8+ e MariaDB atuais.

CREATE DATABASE IF NOT EXISTS gamevault
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE gamevault;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    email VARCHAR(190) NOT NULL,
    name VARCHAR(150) NOT NULL,
    cpf CHAR(11) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    favorite_genre VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    UNIQUE KEY uq_users_cpf (cpf)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS games (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(180) NOT NULL,
    platform VARCHAR(100) NOT NULL,
    media_type VARCHAR(20) NOT NULL,
    store VARCHAR(100) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_games_user (user_id),
    KEY idx_games_platform (user_id, platform),
    CONSTRAINT fk_games_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT chk_games_media_type
        CHECK (media_type IN ('online', 'physical'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
