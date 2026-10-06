<?php
require_once __DIR__ . '/../app/layout.php';
$current = require_auth();
$q = trim((string)($_GET['q'] ?? ''));
if ($q !== '') {
    $stmt=db()->prepare('SELECT * FROM games WHERE user_id=? AND (name LIKE ? OR platform LIKE ? OR store LIKE ?) ORDER BY name');
    $like='%'.$q.'%'; $stmt->execute([$current['id'],$like,$like,$like]);
} else {
    $stmt=db()->prepare('SELECT * FROM games WHERE user_id=? ORDER BY name'); $stmt->execute([$current['id']]);
}
$games=$stmt->fetchAll();
render_header('Meus jogos', true);
?>
<section class="page-head"><div><span class="eyebrow">Biblioteca</span><h1>Meus jogos</h1><p><?= count($games) ?> item(ns) encontrado(s).</p></div><a class="btn" href="/game_form.php">+ Cadastrar jogo</a></section>
<form method="get" class="search"><input name="q" value="<?= e($q) ?>" placeholder="Buscar por jogo, plataforma ou loja"><button class="btn btn-secondary" type="submit">Buscar</button></form>
<section class="panel">
<?php if (!$games): ?><p class="empty">Nenhum jogo encontrado.</p><?php else: ?><div class="table-wrap"><table><thead><tr><th>Nome</th><th>Plataforma</th><th>Formato</th><th>Loja</th><th>Ações</th></tr></thead><tbody>
<?php foreach($games as $game): ?><tr><td><strong><?= e($game['name']) ?></strong><?php if($game['notes']): ?><small><?= e($game['notes']) ?></small><?php endif; ?></td><td><?= e($game['platform']) ?></td><td><?= e(media_label($game['media_type'])) ?></td><td><?= e($game['store'] ?: '—') ?></td><td class="actions"><a href="/game_form.php?id=<?= (int)$game['id'] ?>">Editar</a><form method="post" action="/game_delete.php" onsubmit="return confirm('Excluir este jogo?')"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$game['id'] ?>"><button class="danger-link">Excluir</button></form></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?>
</section>
<?php render_footer(); ?>
