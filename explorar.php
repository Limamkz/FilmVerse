<?php
require_once 'includes/catalog.php';

$page_title = 'Explorar';
$favorites = user_favorites();
$categories = ['Todos', 'Filmes', 'Séries', 'Animes', 'Jogos'];
$selectedCategory = $_GET['categoria'] ?? 'Todos';
$searchQuery = trim($_GET['q'] ?? '');

if (!in_array($selectedCategory, $categories, true)) {
    $selectedCategory = 'Todos';
}

require 'includes/header.php';
?>

<section class="page-head explore-head">
    <span class="eyebrow">O catálogo FilmVerse</span>
    <h1>Encontre sua próxima <span class="purple">obsessão.</span></h1>
    <p>Explore histórias, mundos e experiências escolhendo uma categoria ou pesquisando um título.</p>

    <label class="searchbox explore-search">
        <span aria-hidden="true">⌕</span>
        <input
            id="catalogSearch"
            type="search"
            value="<?= e($searchQuery) ?>"
            placeholder="Buscar título, gênero ou categoria..."
            aria-label="Buscar no catálogo"
        >
    </label>

    <div class="filterbar" role="group" aria-label="Filtrar por categoria">
        <?php foreach ($categories as $category): ?>
            <button
                class="filter-chip <?= $selectedCategory === $category ? 'active' : '' ?>"
                data-filter="<?= e($category) ?>"
                aria-pressed="<?= $selectedCategory === $category ? 'true' : 'false' ?>"
                type="button"
            ><?= e($category) ?></button>
        <?php endforeach; ?>
    </div>
</section>

<section class="catalog-section explore-results" aria-label="Resultados do catálogo">
    <div class="catalog-results-head">
        <div>
            <h2>Catálogo <span class="purple">completo</span></h2>
            <p id="searchStatus" class="search-status" aria-live="polite"></p>
        </div>
        <span class="catalog-note">Novas descobertas começam aqui</span>
    </div>

    <div class="cards" id="catalogGrid">
        <?php foreach ($catalog as $item): ?>
            <?php require 'includes/card.php'; ?>
        <?php endforeach; ?>
    </div>

    <div id="noResults" class="empty-state hidden">
        <div class="category-symbol">⌕</div>
        <h3>Nenhum conteúdo encontrado</h3>
        <p>Tente outro título ou selecione uma categoria diferente.</p>
        <button class="btn" type="button" id="resetCatalog">Limpar filtros</button>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectedCategory = <?= json_encode($selectedCategory, JSON_UNESCAPED_UNICODE) ?>;
    const searchQuery = <?= json_encode($searchQuery, JSON_UNESCAPED_UNICODE) ?>;
    const categoryButton = document.querySelector(`[data-filter="${CSS.escape(selectedCategory)}"]`);
    const searchInput = document.getElementById('catalogSearch');

    if (categoryButton) {
        categoryButton.click();
    }

    if (searchQuery && searchInput) {
        searchInput.value = searchQuery;
        searchInput.dispatchEvent(new Event('input', { bubbles: true }));
    }

    document.getElementById('resetCatalog')?.addEventListener('click', () => {
        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        document.querySelector('[data-filter="Todos"]')?.click();
    });
});
</script>

<?php require 'includes/footer.php'; ?>
