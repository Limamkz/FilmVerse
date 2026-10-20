<?php
require_once __DIR__ . '/functions.php';

$page_title = $page_title ?? 'Seu universo de entretenimento';
$flash_message = take_flash();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#09070f">
    <title><?= e($page_title) ?> · FilmVerse</title>
    <link rel="icon" href="assets/logo.svg" type="image/svg+xml">
    <link rel="stylesheet" href="assets/style.css?v=20261020-7">
</head>
<body>
    <?php if (basename($_SERVER['PHP_SELF']) === 'index.php' || (($_GET['intro'] ?? '') === '1')): ?>
    <div class="cinematic-intro" id="cinematicIntro" aria-label="Abertura FilmVerse" aria-hidden="false">
        <video class="intro-video" id="introVideo" autoplay muted playsinline webkit-playsinline preload="auto" poster="assets/filmverse-intro.png">
            <source src="assets/filmverse-intro.mp4?v=20261020-6" type="video/mp4">
        </video>
        <img src="assets/logo-filmverse-dark.png" alt="FilmVerse — Seu universo de filmes" class="intro-fallback">
        <button class="intro-skip" id="introSkip" type="button">Pular intro <span aria-hidden="true">↗</span></button>
    </div>
    <?php endif; ?>

    <header class="topbar">
        <a class="brand" href="index.php" aria-label="FilmVerse início">
            <img
                src="assets/logo-filmverse-dark.png"
                alt="FilmVerse — Seu universo de filmes"
                class="brand-full-logo brand-logo-dark"
            >
            <img
                src="assets/logo-filmverse.png"
                alt=""
                class="brand-full-logo brand-logo-light"
            >
        </a>

        <nav class="nav" aria-label="Navegação principal">
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'current' : '' ?>">Início</a>
            <a href="explorar.php" class="<?= basename($_SERVER['PHP_SELF']) === 'explorar.php' && empty($_GET['categoria']) ? 'current' : '' ?>">Explorar</a>
            <a href="explorar.php?categoria=Filmes">Filmes</a>
            <a href="explorar.php?categoria=Séries">Séries</a>
            <a href="explorar.php?categoria=Animes">Animes</a>
            <a href="explorar.php?categoria=Jogos">Jogos</a>
            <?php if (logged_in()): ?>
                <a href="favoritos.php" class="<?= basename($_SERVER['PHP_SELF']) === 'favoritos.php' ? 'current' : '' ?>">Minha lista</a>
            <?php endif; ?>
        </nav>

        <div class="top-actions">
            <label class="searchbox">
                <span>⌕</span>
                <input
                    id="globalSearch"
                    type="search"
                    placeholder="Buscar filmes, séries, jogos..."
                >
            </label>

            <button
                class="icon-btn theme-toggle"
                id="themeToggle"
                aria-label="Alternar tema"
                title="Alternar tema"
            >◐</button>

            <?php if (logged_in()): ?>
                <?php $headerUser = current_user(); ?>
                <details class="profile-menu">
                    <summary class="profile-trigger" aria-label="Abrir perfil" title="Meu perfil">
                        <span class="profile-avatar"><?= e(mb_strtoupper(mb_substr($headerUser['nome'] ?? 'U', 0, 1, 'UTF-8'), 'UTF-8')) ?></span>
                        <span class="profile-name"><?= e($headerUser['nome'] ?? 'Perfil') ?></span>
                        <span class="profile-chevron" aria-hidden="true">⌄</span>
                    </summary>
                    <div class="profile-dropdown">
                        <div class="profile-dropdown-heading">
                            <strong><?= e($headerUser['nome'] ?? 'Usuário') ?></strong>
                            <span><?= e($headerUser['email'] ?? '') ?></span>
                        </div>
                        <a href="perfil.php">Minha conta e senha</a>
                        <a href="favoritos.php">Minha lista</a>
                        <a href="logout.php" class="profile-logout">Sair da conta</a>
                    </div>
                </details>
            <?php else: ?>
                <a class="btn btn-small btn-ghost" href="login.php">Entrar</a>
                <a class="btn btn-small" href="cadastro.php">Cadastrar</a>
            <?php endif; ?>
        </div>

        <button class="menu-toggle" id="menuToggle" aria-label="Abrir menu">☰</button>
    </header>

    <?php if ($flash_message): ?>
        <div class="flash <?= e($flash_message['type']) ?>">
            <?= e($flash_message['message']) ?>
        </div>
    <?php endif; ?>

    <main class="site-main">
