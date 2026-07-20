<?php
$gains = $gains ?? ['total' => 0, 'soi_meme' => 0, 'autres_operateurs' => []];
$clients = $clients ?? [];
$pager = $pager ?? null;
$typesOperation = $typesOperation ?? [];
$idTypeOperationFiltre = $idTypeOperationFiltre ?? null;
$gainAutresOperateurs = array_sum(array_column($gains['autres_operateurs'], 'gain'));
?>
<?= $this->extend('layout/app') ?>

<?= $this->section('contenu') ?>

<form action="<?= base_url('operateur/situation') ?>" method="get" class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <select name="type" id="filtre_type" class="form-select" onchange="this.form.submit()">
            <option value="">Tous les types</option>
            <?php foreach ($typesOperation as $type) : ?>
                <option value="<?= esc($type['id']) ?>" <?= $idTypeOperationFiltre === (int) $type['id'] ? 'selected' : '' ?>>
                    <?= esc($type['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-6 col-md-2">
        <a href="<?= base_url('operateur/situation') ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i>Réinitialiser</a>
    </div>
    <noscript>
        <div class="col-auto"><button type="submit" class="btn btn-outline-secondary">Filtrer</button></div>
    </noscript>
</form>

<div class="card mb-4">
    <div class="card-body">
        <div class="font-mono text-uppercase text-muted" style="font-size:12px; letter-spacing:.18em;">Gain total</div>
        <p class="mb-1" style="font-size:34px; font-weight:800; color:var(--pmi-teal);"><?= number_format($gains['total'], 0, ',', ' ') ?> Ar</p>
        <p class="text-muted mb-0">Somme des frais perçus, tous opérateurs confondus.</p>
        <p class="text-muted mb-0 mt-1">dont <strong><?= number_format($gainAutresOperateurs, 0, ',', ' ') ?> Ar</strong> sur autres opérateurs</p>
    </div>
</div>

<div class="mb-4">
    <h5 class="mb-3">Gains par opérateur</h5>
    <div class="row g-3">
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="font-mono text-uppercase text-muted" style="font-size:11px; letter-spacing:.14em;">Soi-même</div>
                    <p class="mb-0 mt-1" style="font-size:22px; font-weight:800; color:var(--pmi-teal);"><?= number_format($gains['soi_meme'], 0, ',', ' ') ?> Ar</p>
                </div>
            </div>
        </div>
        <?php if (empty($gains['autres_operateurs'])) : ?>
            <div class="col-12">
                <p class="text-muted mb-0">Aucun gain lié à un autre opérateur.</p>
            </div>
        <?php else : ?>
            <?php foreach ($gains['autres_operateurs'] as $autreOperateur) : ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="font-mono text-uppercase text-muted" style="font-size:11px; letter-spacing:.14em;"><?= esc($autreOperateur['nom']) ?></div>
                            <p class="mb-0 mt-1" style="font-size:22px; font-weight:800;"><?= number_format($autreOperateur['gain'], 0, ',', ' ') ?> Ar</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
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

    <?php if ($pager !== null && $pager->getPageCount() > 1) : ?>
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
