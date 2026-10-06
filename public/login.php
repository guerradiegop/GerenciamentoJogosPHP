<?php
require_once __DIR__ . '/../app/layout.php';
if (user()) redirect('/dashboard.php');
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if (login_user((string)($_POST['email'] ?? ''), (string)($_POST['password'] ?? ''))) {
        redirect('/dashboard.php');
    }
    $error = 'E-mail ou senha inválidos.';
}
render_header('Entrar');
?>
<section class="auth-card auth-small">
    <h1>Entrar</h1><p>Acesse sua coleção pessoal.</p>
    <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="stack-form">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>E-mail<input required type="email" name="email" autocomplete="email"></label>
        <label>Senha<input required type="password" name="password" autocomplete="current-password"></label>
        <button class="btn" type="submit">Entrar</button>
    </form>
    <p class="muted">Ainda não tem conta? <a href="/register.php">Cadastre-se</a>.</p>
</section>
<?php render_footer(); ?>
