<?php
require_once 'includes/catalog.php';

$page_title = 'Início';
$favorites = user_favorites();
$featured = find_content('duna-parte-dois');

require 'includes/header.php';
?>

<section class="hero">
    <div class="hero-content">
        <span class="eyebrow">Seu próximo universo começa aqui</span>
        <h1>Grandes histórias,<br><span class="purple">em um só lugar.</span></h1>
        <p>Filmes, séries, animes e jogos para quem gosta de descobrir novos mundos. Escolha seu próximo favorito.</p>
        <div class="hero-buttons">
            <a class="btn" href="explorar.php">▶ &nbsp;Explorar catálogo</a>
            <a class="btn btn-ghost" href="detalhe.php?id=duna-parte-dois">＋ &nbsp;Ver destaque</a>
        </div>
        <div class="badge-row">
            <span class="pill">✦ 4 categorias</span>
            <span class="pill">+50 conteúdos</span>
            <span class="pill">Sua lista pessoal</span>
        </div>
    </div>
</section>

<section class="section video-showcase">
    <div class="video-showcase-copy">
        <span class="eyebrow">Uma experiência FilmVerse</span>
        <h2>Entre no clima de <span class="purple">novas histórias.</span></h2>
        <p>Um pequeno convite visual para explorar filmes, séries, animes e jogos.</p>
    </div>
    <div class="video-showcase-frame">
        <video autoplay controls muted loop playsinline preload="metadata" poster="assets/filmverse-intro.png" aria-label="Vídeo de apresentação do FilmVerse">
            <source src="assets/filmverse-intro.mp4" type="video/mp4">
            Seu navegador não suporta a reprodução de vídeo.
        </video>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div>
            <h2>Em alta agora <span class="purple">✦</span></h2>
            <p>Histórias que merecem entrar na sua lista.</p>
        </div>
        <a class="text-link" href="explorar.php">Ver catálogo completo →</a>
    </div>
    <div class="cards">
        <?php foreach (array_slice($catalog, 0, 5) as $item): ?>
            <?php require 'includes/card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div>
            <h2>Explore por <span class="purple">universo</span></h2>
            <p>Tem sempre algo novo para descobrir.</p>
        </div>
    </div>
    <div class="category-strip">
        <?php
        $categories = [
            ['Filmes', '▣', 'Cinema para todas as horas', 'https://image.tmdb.org/t/p/w500/1pdfLvkbY9ohJlCjQH2CZjjYVvJ.jpg'],
            ['Séries', '▤', 'Histórias em episódios', 'https://image.tmdb.org/t/p/w500/fqldf2t8ztc9aiwn3k6mlX3tvRT.jpg'],
            ['Animes', '✦', 'Mundos fora do comum', 'https://static0.gamerantimages.com/wordpress/wp-content/uploads/2023/09/demon-slayer.jpg'],
            ['Jogos', '⌘', 'Descubra novos games', 'https://cdn.akamai.steamstatic.com/steam/apps/1145360/library_600x900.jpg'],
        ];
        foreach ($categories as $category):
        ?>
            <a class="category-tile" href="explorar.php?categoria=<?= urlencode($category[0]) ?>">
                <span class="category-art">
                    <img src="<?= e($category[3]) ?>" alt="" loading="lazy" onerror="this.closest('.category-art').classList.add('image-unavailable')">
                </span>
                <span class="category-symbol" aria-hidden="true"><?= $category[1] ?></span>
                <strong><?= e($category[0]) ?></strong>
                <small><?= e($category[2]) ?></small>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div>
            <h2>Para continuar explorando</h2>
            <p>Uma seleção para encontrar sua próxima obsessão.</p>
        </div>
    </div>
    <div class="cards">
        <?php foreach (array_slice($catalog, 20, 5) as $item): ?>
            <?php require 'includes/card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
