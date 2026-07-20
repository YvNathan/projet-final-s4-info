<?= $this->extend('layout/app') ?>

<?= $this->section('contenu') ?>

<div class="card mb-4">
    <div class="card-body">
        <div class="font-mono text-uppercase text-muted" style="font-size:12px; letter-spacing:.18em;">Gain total</div>
        <p class="mb-1" style="font-size:34px; font-weight:800; color:var(--pmi-teal);"><?= number_format($gainTotal, 0, ',', ' ') ?> Ar</p>
        <p class="text-muted mb-0">Somme des frais perçus sur l'ensemble des retraits et transferts.</p>
    </div>
</div>

<div class="panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h5 class="mb-0">Comptes clients</h5>
        <span class="text-secondary small"><?= count($clients) ?> client(s) sur cette page</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Numéro</th>
                    <th class="text-end">Solde</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clients)) : ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Aucun client enregistré.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($clients as $client) : ?>
                        <tr>
                            <td><?= esc($client['nom'] ?? '—') ?></td>
                            <td class="font-mono"><?= esc($client['numero']) ?></td>
                            <td class="text-end"><?= number_format($client['solde'], 0, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pager->getPageCount() > 1) : ?>
        <?php $previousPageURI = $pager->getPreviousPageURI(); ?>
        <?php $nextPageURI = $pager->getNextPageURI(); ?>
        <nav aria-label="Pagination des comptes clients" class="mt-2">
            <ul class="pagination pagination-sm mb-0">
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
</div>

<?= $this->endSection() ?>
