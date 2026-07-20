<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titre ?? 'Porte mon I') ?> · Porte mon « I »</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('css/theme.css') ?>" rel="stylesheet">
</head>

<body>
    <?php
    $espace = $espace ?? 'client'; // 'client' | 'operateur' — pour l'état actif
    $current = uri_string();
    ?>
    <div class="app-shell" id="appShell">
        <div class="app-backdrop" data-sidebar-close></div>

        <aside class="app-sidebar">
            <div class="app-sidebar__brand">
                <span class="brand-mark"><?= view('partials/logo') ?></span>
                <span class="brand-text">Porte mon <span class="accent">« I »</span></span>
            </div>

            <?php if ($espace === 'client') : ?>
                <div class="app-sidebar__section">Espace client</div>
                <ul class="app-nav">
                    <li>
                        <a class="app-nav__link <?= $current === 'home' ? 'active' : '' ?>" href="<?= base_url('home') ?>">
                            <i class="bi bi-house-door"></i><span>Mon compte</span>
                        </a>
                    </li>
                    <li>
                        <a class="app-nav__link <?= $current === 'historique' ? 'active' : '' ?>" href="<?= base_url('historique') ?>">
                            <i class="bi bi-clock-history"></i><span>Historique</span>
                        </a>
                    </li>
                </ul>
            <?php elseif ($espace === 'operateur') : ?>
                <div class="app-sidebar__section">Espace opérateur</div>
                <ul class="app-nav">
                    <li>
                        <a class="app-nav__link <?= $current === 'operateur/situation' ? 'active' : '' ?>" href="<?= base_url('operateur/situation') ?>">
                            <i class="bi bi-graph-up"></i><span>Situation</span>
                        </a>
                    </li>
                    <li>
                        <a class="app-nav__link <?= $current === 'operateur/config' ? 'active' : '' ?>" href="<?= base_url('operateur/config') ?>">
                            <i class="bi bi-hash"></i><span>Préfixes</span>
                        </a>
                    </li>
                    <li>
                        <a class="app-nav__link <?= $current === 'operateur/frais' ? 'active' : '' ?>" href="<?= base_url('operateur/frais') ?>">
                            <i class="bi bi-cash-stack"></i><span>Barèmes de frais</span>
                        </a>
                    </li>
                    <li>
                        <a class="app-nav__link <?= $current === 'operateur/operateurs' ? 'active' : '' ?>" href="<?= base_url('operateur/operateurs') ?>">
                            <i class="bi bi-diagram-3"></i><span>Opérateurs</span>
                        </a>
                    </li>
                </ul>
            <?php endif; ?>

            <div class="app-sidebar__footer">
                <a class="app-nav__link" href="<?= base_url('logout') ?>">
                    <i class="bi bi-box-arrow-right"></i><span class="label">Déconnexion</span>
                </a>
            </div>
        </aside>

        <div class="app-main">
            <header class="app-topbar">
                <button class="app-topbar__toggle" type="button" id="sidebarToggle" aria-label="Basculer le menu">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="app-topbar__title"><?= esc($titre ?? 'Porte mon « I »') ?></h1>
            </header>

            <div class="app-content">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-1"></i><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <?= $this->renderSection('contenu') ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            var shell = document.getElementById('appShell');
            var toggle = document.getElementById('sidebarToggle');
            var isMobile = function () { return window.matchMedia('(max-width: 768px)').matches; };

            toggle.addEventListener('click', function () {
                // Sur mobile : ouvre/ferme l'overlay. Sur desktop : réduit/agrandit.
                shell.classList.toggle(isMobile() ? 'is-open' : 'is-collapsed');
            });

            document.querySelectorAll('[data-sidebar-close]').forEach(function (el) {
                el.addEventListener('click', function () { shell.classList.remove('is-open'); });
            });
        })();
    </script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>
