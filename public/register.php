<?php
require_once __DIR__ . '/../app/layout.php';
if (user()) redirect('/dashboard.php');

$errors = [];
$data = ['name'=>'','email'=>'','cpf'=>'','phone'=>'','favorite_genre'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ($data as $key => $_) $data[$key] = trim((string)($_POST[$key] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $confirmation = (string)($_POST['password_confirmation'] ?? '');

    if (mb_strlen($data['name']) < 3) $errors[] = 'Informe seu nome completo.';
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Informe um e-mail válido.';
    if (!valid_cpf($data['cpf'])) $errors[] = 'Informe um CPF válido.';
    if (strlen(only_digits($data['phone'])) < 10) $errors[] = 'Informe um telefone válido.';
    if ($data['favorite_genre'] === '') $errors[] = 'Informe seu gênero de jogos preferido.';
    if (strlen($password) < 8) $errors[] = 'A senha deve possuir pelo menos 8 caracteres.';
    if ($password !== $confirmation) $errors[] = 'As senhas não coincidem.';

    if (!$errors) {
        try {
            $stmt = db()->prepare('INSERT INTO users (email,name,cpf,phone,favorite_genre,password_hash) VALUES (?,?,?,?,?,?)');
            $stmt->execute([
                strtolower($data['email']), $data['name'], only_digits($data['cpf']), $data['phone'],
                $data['favorite_genre'], password_hash($password, PASSWORD_DEFAULT)
            ]);
            flash('success', 'Cadastro realizado. Faça login para continuar.');
            redirect('/login.php');
        } catch (PDOException $e) {
            $errors[] = 'E-mail ou CPF já cadastrado.';
        }
    }
}
render_header('Criar conta');
?>
<section class="auth-card">
    <h1>Crie sua conta</h1><p>Use seus dados para manter a biblioteca vinculada ao seu perfil.</p>
    <?php if ($errors): ?><div class="alert alert-error"><ul><?php foreach($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post" class="form-grid">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label class="span-2">Nome completo<input required name="name" value="<?= e($data['name']) ?>" autocomplete="name"></label>
        <label>E-mail<input required type="email" name="email" value="<?= e($data['email']) ?>" autocomplete="email"></label>
        <label>CPF<input required name="cpf" value="<?= e($data['cpf']) ?>" inputmode="numeric" placeholder="000.000.000-00"></label>
        <label>Telefone<input required name="phone" value="<?= e($data['phone']) ?>" placeholder="(00) 00000-0000"></label>
        <label>Gênero preferido<input required name="favorite_genre" value="<?= e($data['favorite_genre']) ?>" placeholder="RPG, FPS, Estratégia..."></label>
        <label>Senha<input required type="password" name="password" autocomplete="new-password"></label>
        <label>Confirmar senha<input required type="password" name="password_confirmation" autocomplete="new-password"></label>
        <button class="btn span-2" type="submit">Cadastrar</button>
    </form>
</section>
<?php render_footer(); ?>
