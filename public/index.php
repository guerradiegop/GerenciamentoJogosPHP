<?php
require_once __DIR__ . '/../app/layout.php';
render_header('Organize sua coleção');
?>
<section class="hero">
    <div>
        <span class="eyebrow">Sua biblioteca, do seu jeito</span>
        <h1>Todos os seus jogos em um único catálogo.</h1>
        <p>Controle jogos digitais e físicos, plataformas e lojas como Steam, Epic Games, Battle.net e GOG. Visualize sua coleção com indicadores claros.</p>
        <div class="hero-actions">
            <a class="btn" href="/register.php">Começar gratuitamente</a>
            <a class="btn btn-secondary" href="/login.php">Já tenho uma conta</a>
        </div>
    </div>
    <div class="hero-card">
        <div class="metric"><strong>CRUD completo</strong><span>Cadastre, edite, consulte e exclua jogos.</span></div>
        <div class="metric"><strong>Dashboard</strong><span>KPIs por mídia, plataforma e loja.</span></div>
        <div class="metric"><strong>Conta segura</strong><span>Senha protegida por hash e dados isolados por usuário.</span></div>
    </div>
</section>
<section class="features">
    <article><h2>Organização</h2><p>Encontre rapidamente onde cada jogo está disponível e em qual formato você o possui.</p></article>
    <article><h2>Visibilidade</h2><p>Entenda a distribuição da sua biblioteca com gráficos e indicadores atualizados automaticamente.</p></article>
    <article><h2>Privacidade</h2><p>Somente você acessa os jogos associados à sua conta autenticada.</p></article>
</section>
<?php render_footer(); ?>
