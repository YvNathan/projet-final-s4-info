<?php
/**
 * Logo « Porte mon I » — marque vectorielle.
 *
 * Variables optionnelles :
 *   $arc   couleur de l'arc de mouvement + flèche (def: #0f766e)
 *   $bar   couleur de la barre « I » + point       (def: #12211f)
 *   $tile  couleur de fond arrondi (def: aucune)
 *   $variant  'plain' (def) | 'appicon' (fond dégradé teal, marque menthe/blanc)
 */
$variant = $variant ?? 'plain';
$arc = $arc ?? '#0f766e';
$bar = $bar ?? '#12211f';
$tile = $tile ?? null;
$gid = 'pmi-grad-' . bin2hex(random_bytes(3));
?>
<svg viewBox="0 0 100 100" width="100%" height="100%" style="display:block" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Porte mon I">
    <?php if ($variant === 'appicon') : ?>
        <defs>
            <linearGradient id="<?= $gid ?>" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#0f766e" />
                <stop offset="100%" stop-color="#0a5c55" />
            </linearGradient>
        </defs>
        <rect x="0" y="0" width="100" height="100" fill="url(#<?= $gid ?>)" />
        <path d="M 50 14 A 36 36 0 1 1 20 32" fill="none" stroke="#5eead4" stroke-width="9" stroke-linecap="round" />
        <path d="M 20 32 l -6 -12 M 20 32 l 13 -3" fill="none" stroke="#5eead4" stroke-width="9" stroke-linecap="round" stroke-linejoin="round" />
        <rect x="45" y="34" width="10" height="32" rx="5" fill="#ffffff" />
        <circle cx="50" cy="26" r="5.5" fill="#ffffff" />
    <?php else : ?>
        <?php if ($tile !== null) : ?>
            <rect x="0" y="0" width="100" height="100" rx="46" fill="<?= esc($tile, 'attr') ?>" />
        <?php endif; ?>
        <path d="M 50 14 A 36 36 0 1 1 20 32" fill="none" stroke="<?= esc($arc, 'attr') ?>" stroke-width="9" stroke-linecap="round" />
        <path d="M 20 32 l -6 -12 M 20 32 l 13 -3" fill="none" stroke="<?= esc($arc, 'attr') ?>" stroke-width="9" stroke-linecap="round" stroke-linejoin="round" />
        <rect x="45" y="34" width="10" height="32" rx="5" fill="<?= esc($bar, 'attr') ?>" />
        <circle cx="50" cy="26" r="5.5" fill="<?= esc($bar, 'attr') ?>" />
    <?php endif; ?>
</svg>
