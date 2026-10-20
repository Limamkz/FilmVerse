(() => {
    'use strict';

    const body = document.body;
    const themeToggle = document.getElementById('themeToggle');
    const themeStorageKey = 'filmverse-theme';

    function applyTheme(theme) {
        const isLight = theme === 'light';
        body.classList.toggle('light', isLight);

        if (themeToggle) {
            themeToggle.textContent = isLight ? '☀' : '◐';
            themeToggle.setAttribute(
                'aria-label',
                isLight ? 'Ativar tema escuro' : 'Ativar tema claro'
            );
            themeToggle.title = isLight ? 'Ativar tema escuro' : 'Ativar tema claro';
        }

        try {
            localStorage.setItem(themeStorageKey, isLight ? 'light' : 'dark');
        } catch (error) {
            // O tema continua funcional mesmo se o navegador bloquear o armazenamento.
        }
    }

    let savedTheme = 'dark';
    try {
        savedTheme = localStorage.getItem(themeStorageKey) || 'dark';
    } catch (error) {
        // Mantém o tema escuro como padrão.
    }

    applyTheme(savedTheme);
    themeToggle?.addEventListener('click', () => {
        applyTheme(body.classList.contains('light') ? 'dark' : 'light');
    });

    const topbar = document.querySelector('.topbar');
    const menuToggle = document.getElementById('menuToggle');

    menuToggle?.addEventListener('click', () => {
        const isOpen = topbar?.classList.toggle('menu-open') || false;
        menuToggle.setAttribute('aria-expanded', String(isOpen));
        menuToggle.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
        menuToggle.textContent = isOpen ? '×' : '☰';
    });

    topbar?.querySelectorAll('.nav a, .top-actions a').forEach((link) => {
        link.addEventListener('click', () => topbar.classList.remove('menu-open'));
    });

    const globalSearch = document.getElementById('globalSearch');
    globalSearch?.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && globalSearch.value.trim()) {
            window.location.href = 'explorar.php?q=' + encodeURIComponent(globalSearch.value.trim());
        }
    });

    // Pesquisa e filtros locais funcionam em conjunto.
    const localSearch = document.getElementById('catalogSearch');
    const filterButtons = [...document.querySelectorAll('[data-filter]')];
    let activeCategory = filterButtons.find((button) => button.classList.contains('active'))?.dataset.filter || 'Todos';

    function filterCards() {
        const query = (localSearch?.value || '').toLocaleLowerCase('pt-BR').trim();
        const cards = [...document.querySelectorAll('[data-content-card]')];
        let visibleCount = 0;

        cards.forEach((card) => {
            const searchableText = (card.dataset.search || '').toLocaleLowerCase('pt-BR');
            const matchesText = searchableText.includes(query);
            const matchesCategory = activeCategory === 'Todos' || card.dataset.category === activeCategory;
            const isVisible = matchesText && matchesCategory;

            card.classList.toggle('hidden', !isVisible);
            if (isVisible) visibleCount += 1;
        });

        const status = document.getElementById('searchStatus');
        if (status) {
            status.textContent = query || activeCategory !== 'Todos'
                ? `${visibleCount} conteúdo(s) encontrado(s).`
                : '';
        }

        const emptyState = document.getElementById('noResults');
        emptyState?.classList.toggle('hidden', visibleCount !== 0);
    }

    localSearch?.addEventListener('input', filterCards);
    filterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            activeCategory = button.dataset.filter || 'Todos';

            filterButtons.forEach((item) => {
                const isActive = item === button;
                item.classList.toggle('active', isActive);
                item.setAttribute('aria-pressed', String(isActive));
            });

            filterCards();
        });
    });

    if (localSearch || filterButtons.length) filterCards();

    // Abertura em vídeo: só na página inicial (ou em ?intro=1), com autoplay muted e fallback.
    const intro = document.getElementById('cinematicIntro');
    const introVideo = document.getElementById('introVideo');
    const skipIntro = document.getElementById('introSkip');

    if (intro && introVideo) {
        const params = new URLSearchParams(window.location.search);
        const reducedMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;
        let closeTimer = null;
        let closed = false;

        const closeIntro = () => {
            if (closed) return;
            closed = true;
            if (closeTimer) window.clearTimeout(closeTimer);
            intro.classList.add('is-closing');
            intro.setAttribute('aria-hidden', 'true');
            body.classList.remove('intro-playing');
            introVideo.pause();
            try {
                sessionStorage.setItem('filmverse-intro-seen', '1');
            } catch (error) {
                // A navegação não depende do armazenamento.
            }
            window.setTimeout(() => intro.remove(), 650);
        };

        if (reducedMotion) {
            intro.remove();
        } else {
            body.classList.add('intro-playing');
            skipIntro?.addEventListener('click', closeIntro);
            introVideo.addEventListener('ended', closeIntro, { once: true });
            introVideo.addEventListener('error', () => {
                intro.classList.add('intro-video-unavailable');
                // Mantém a tela de marca visível por um instante e não bloqueia o site.
                closeTimer = window.setTimeout(closeIntro, 2200);
            }, { once: true });

            const startPlayback = () => {
                introVideo.muted = true;
                introVideo.playsInline = true;
                const playback = introVideo.play();
                if (playback && typeof playback.catch === 'function') {
                    playback.catch(() => {
                        intro.classList.add('intro-video-unavailable');
                        closeTimer = window.setTimeout(closeIntro, 2200);
                    });
                }
            };

            introVideo.addEventListener('loadeddata', startPlayback, { once: true });
            introVideo.load();
            // Also attempt immediately: some browsers already have enough data cached.
            startPlayback();
            // Last-resort watchdog if the media never fires `ended` or `error`.
            closeTimer = window.setTimeout(closeIntro, 11000);
        }
    }

})();
