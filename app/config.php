<?php
/** Configurações centrais da aplicação. */
declare(strict_types=1);

const APP_NAME = 'GameVault';

/**
 * Configuração do MySQL/MariaDB.
 *
 * Em produção, prefira definir estas variáveis no ambiente do servidor em vez
 * de gravar credenciais reais no repositório.
 */
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'gamevault');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'samesite' => 'Lax',
    ]);
    session_start();
}
