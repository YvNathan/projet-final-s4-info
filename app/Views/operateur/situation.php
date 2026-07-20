<?= $this->extend('operateur/layout') ?>

<?= $this->section('contenu') ?>

<h1 class="mb-4">Situation</h1>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Gain total</h5>
        <p class="card-text h3 text-success"><?= number_format($gainTotal, 0, ',', ' ') ?> Ar</p>
        <p class="card-text text-muted">Somme des frais perçus sur l'ensemble des retraits et transferts.</p>
    </div>
</div>

<h2 class="h4 mb-3">Comptes clients</h2>

<table class="table table-striped table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>Nom</th>
            <th>Numéro</th>
            <th class="text-right">Solde</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($clients)) : ?>
            <tr>
                <td colspan="3" class="text-center text-muted">Aucun client enregistré.</td>
            </tr>
        <?php else : ?>
            <?php foreach ($clients as $client) : ?>
                <tr>
                    <td><?= esc($client['nom'] ?? '—') ?></td>
                    <td><?= esc($client['numero']) ?></td>
                    <td class="text-right"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($pager->getPageCount() > 1) : ?>
    <?php $previousPageURI = $pager->getPreviousPageURI(); ?>
    <?php $nextPageURI = $pager->getNextPageURI(); ?>
    <nav aria-label="Pagination des comptes clients">
        <ul class="pagination">
            <li class="page-item <?= $previousPageURI !== null ? '' : 'disabled' ?>">
                <a class="page-link" href="<?= $previousPageURI ?? '#' ?>">Précédent</a>
            </li>
            <?php for ($page = 1; $page <= $pager->getPageCount(); $page++) : ?>
                <li class="page-item <?= $page === $pager->getCurrentPage() ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $pager->getPageURI($page) ?>"><?= $page ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $nextPageURI !== null ? '' : 'disabled' ?>">
                <a class="page-link" href="<?= $nextPageURI ?? '#' ?>">Suivant</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>

<?= $this->endSection() ?>
