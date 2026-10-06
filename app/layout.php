<?php
/** Componentes compartilhados do HTML. */
declare(strict_types=1);
require_once __DIR__ . '/auth.php';

function render_header(string $title, bool $private = false): void
{
    $current = $private ? user() : null;
    $flashes = pull_flashes();
    ?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="<?= $private ? '/dashboard.php' : '/' ?>">🎮 <?= APP_NAME ?></a>
    <nav>
        <?php if ($private && $current): ?>
            <a href="/dashboard.php">Dashboard</a>
            <a href="/games.php">Meus jogos</a>
            <span class="welcome">Olá, <?= e(explode(' ', $current['name'])[0]) ?></span>
            <form action="/logout.php" method="post" class="inline-form">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <button class="link-button" type="submit">Sair</button>
            </form>
        <?php else: ?>
            <a href="/login.php">Entrar</a>
            <a class="btn btn-small" href="/register.php">Criar conta</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
    <?php foreach ($flashes as $flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endforeach; ?>
<?php
}

function render_footer(): void
{
    ?>
</main>
<footer class="footer">GameVault · catálogo pessoal de jogos · <?= date('Y') ?></footer>
</body>
</html>
<?php
}
