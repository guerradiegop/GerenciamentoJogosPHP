<?php
require_once __DIR__ . '/../app/layout.php';
$current = require_auth();
$pdo = db(); $uid = (int)$current['id'];

$stmt = $pdo->prepare("SELECT COUNT(*) total, SUM(media_type='online') digital, SUM(media_type='physical') physical, COUNT(DISTINCT platform) platforms FROM games WHERE user_id=?");
$stmt->execute([$uid]);
$kpi = $stmt->fetch();
$kpi = array_map(fn($v)=>(int)$v, $kpi ?: ['total'=>0,'digital'=>0,'physical'=>0,'platforms'=>0]);

function group_stats(PDO $pdo, int $uid, string $field, string $extra='1=1'): array {
    $allowed = ['platform','store','media_type'];
    if (!in_array($field, $allowed, true)) return [];
    $sql = "SELECT COALESCE(NULLIF($field,''),'Não informado') label, COUNT(*) value FROM games WHERE user_id=? AND $extra GROUP BY $field ORDER BY value DESC";
    $s=$pdo->prepare($sql); $s->execute([$uid]); return $s->fetchAll();
}
$platforms = group_stats($pdo,$uid,'platform');
$stores = group_stats($pdo,$uid,'store',"media_type='online'");
$formats = group_stats($pdo,$uid,'media_type');
$recentStmt=$pdo->prepare('SELECT * FROM games WHERE user_id=? ORDER BY created_at DESC, id DESC LIMIT 5'); $recentStmt->execute([$uid]); $recent=$recentStmt->fetchAll();
render_header('Dashboard', true);
?>
<section class="page-head"><div><span class="eyebrow">Visão geral</span><h1>Sua dashboard</h1><p>Resumo da biblioteca de <?= e($current['name']) ?>.</p></div><a class="btn" href="/game_form.php">+ Novo jogo</a></section>
<section class="kpi-grid">
    <article class="kpi"><span>Total de jogos</span><strong><?= $kpi['total'] ?></strong></article>
    <article class="kpi"><span>Digitais</span><strong><?= $kpi['digital'] ?></strong></article>
    <article class="kpi"><span>Físicos</span><strong><?= $kpi['physical'] ?></strong></article>
    <article class="kpi"><span>Plataformas</span><strong><?= $kpi['platforms'] ?></strong></article>
</section>
<section class="chart-grid">
    <article class="panel"><h2>Jogos por plataforma</h2><div class="chart-wrap"><canvas id="platformChart"></canvas></div></article>
    <article class="panel"><h2>Formato da coleção</h2><div class="chart-wrap"><canvas id="formatChart"></canvas></div></article>
    <article class="panel span-2"><h2>Lojas dos jogos digitais</h2><div class="chart-wrap wide"><canvas id="storeChart"></canvas></div></article>
</section>
<section class="panel"><div class="panel-title"><h2>Cadastros recentes</h2><a href="/games.php">Ver todos</a></div>
<?php if (!$recent): ?><p class="empty">Sua coleção ainda está vazia. Cadastre o primeiro jogo.</p><?php else: ?><div class="table-wrap"><table><thead><tr><th>Jogo</th><th>Plataforma</th><th>Formato</th><th>Loja</th></tr></thead><tbody><?php foreach($recent as $g): ?><tr><td><?= e($g['name']) ?></td><td><?= e($g['platform']) ?></td><td><?= e(media_label($g['media_type'])) ?></td><td><?= e($g['store'] ?: '—') ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
const platforms=<?= json_encode($platforms, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG) ?>;
const stores=<?= json_encode($stores, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG) ?>;
const formats=<?= json_encode(array_map(fn($x)=>['label'=>$x['label']==='online'?'Digital / Online':'Disco físico','value'=>$x['value']],$formats), JSON_UNESCAPED_UNICODE|JSON_HEX_TAG) ?>;
function chart(id,type,rows){const el=document.getElementById(id);if(!el||!rows.length)return;new Chart(el,{type,data:{labels:rows.map(x=>x.label),datasets:[{label:'Jogos',data:rows.map(x=>x.value)}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:type==='doughnut'}}}})}
chart('platformChart','bar',platforms); chart('formatChart','doughnut',formats); chart('storeChart','bar',stores);
</script>
<?php render_footer(); ?>
