<?php
if (!isset($item)) {
    return;
}

$is_favorite = in_array($item['id'], $favorites ?? [], true);
?>
<article
    class="content-card"
    data-content-card
    data-category="<?= e($item['category']) ?>"
    data-search="<?= e($item['title'] . ' ' . $item['category'] . ' ' . $item['genre'] . ' ' . $item['description']) ?>"
>
    <?php if (logged_in()): ?>
        <form method="post" action="favorito.php" class="favorite-form">
            <?= csrf_field() ?>
            <input type="hidden" name="content_id" value="<?= e($item['id']) ?>">
            <input
                type="hidden"
                name="return"
                value="<?= e($_SERVER['REQUEST_URI'] ?? 'index.php') ?>"
            >
            <button
                class="favorite-btn <?= $is_favorite ? 'active' : '' ?>"
                title="<?= $is_favorite ? 'Remover dos favoritos' : 'Adicionar aos favoritos' ?>"
                aria-label="Favorito"
            ><?= $is_favorite ? '♥' : '+' ?></button>
        </form>
    <?php endif; ?>

    <a class="poster-link" href="detalhe.php?id=<?= urlencode($item['id']) ?>">
        <img
            class="poster"
            loading="lazy"
            src="<?= e($item['image']) ?>"
            alt="Capa de <?= e($item['title']) ?>"
            onerror="this.onerror=null;this.src='poster.php?title=<?= rawurlencode($item['title']) ?>&amp;category=<?= rawurlencode($item['category']) ?>'"
        >
        <div class="card-overlay">
            <span>▶ Ver detalhes</span>
        </div>
    </a>

    <div class="card-title" title="<?= e($item['title']) ?>">
        <?= e($item['title']) ?>
    </div>
    <div class="card-meta">
        <span class="star">★ <?= e($item['rating']) ?></span>
        <span><?= e($item['year']) ?></span>
        <span>· <?= e($item['category']) ?></span>
    </div>
</article>
