<?php
/** Regras de autenticação e autorização. */
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function user(): ?array
{
    if (empty($_SESSION['user_id'])) return null;
    $stmt = db()->prepare('SELECT id, email, name, cpf, phone, favorite_genre, created_at FROM users WHERE id = ?');
    $stmt->execute([(int)$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function require_auth(): array
{
    $current = user();
    if (!$current) {
        flash('error', 'Faça login para acessar esta área.');
        redirect('/login.php');
    }
    return $current;
}

function login_user(string $email, string $password): bool
{
    $stmt = db()->prepare('SELECT id, password_hash FROM users WHERE email = ?');
    $stmt->execute([trim($email)]);
    $row = $stmt->fetch();
    if (!$row || !password_verify($password, $row['password_hash'])) return false;

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$row['id'];
    return true;
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
