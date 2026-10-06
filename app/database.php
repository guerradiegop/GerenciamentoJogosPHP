<?php
/** Camada central de conexão PDO com MySQL/MariaDB. */
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Retorna uma única conexão PDO reutilizada durante a requisição.
 *
 * O schema não é criado automaticamente pela aplicação. Antes do primeiro
 * acesso, importe database/schema.sql no servidor MySQL/MariaDB.
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    );

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
