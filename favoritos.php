<?php
require_once 'includes/catalog.php';
require_login();

$page_title = 'Minha lista';
$favorites = user_favorites();
$myItems = array_values(array_filter(
    $catalog,
    static fn ($item) => in_array($item['id'], $favorites, true)
));

require 'includes/header.php';
?>

<section class="page-head">
    <span class="eyebrow">Sua coleção pessoal</span>
    <h1>Minha <span class="purple">lista.</span></h1>
    <p>Todos os conteúdos que você guardou para rever ou descobrir depois.</p>
</section>

<section class="catalog-section explore-results" aria-label="Conteúdos salvos">
    <?php if (!$myItems): ?>
        <div class="empty-state">
            <div class="category-symbol" aria-hidden="true">♡</div>
            <h3>Sua lista está esperando por você.</h3>
            <p>Explore o catálogo e use o botão de favorito para guardar os conteúdos que chamarem sua atenção.</p>
            <a class="btn" href="explorar.php">Explorar catálogo <span aria-hidden="true">→</span></a>
        </div>
    <?php else: ?>
        <div class="catalog-results-head">
            <div>
                <h2>Seus favoritos <span class="purple">(<?= count($myItems) ?>)</span></h2>
                <p>Seu espaço pessoal de descobertas.</p>
            </div>
            <a class="text-link" href="explorar.php">Adicionar mais conteúdos →</a>
        </div>

        <div class="cards">
            <?php foreach ($myItems as $item): ?>
                <?php require 'includes/card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require 'includes/footer.php'; ?>
