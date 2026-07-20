<?php
$montants = $montants ?? [];
$totalMontant = array_sum(array_column($montants, 'montant_transfere'));
$totalCommission = array_sum(array_column($montants, 'commission'));
$totalGeneral = $totalMontant + $totalCommission;
?>
<?= $this->extend('layout/app') ?>

<?= $this->section('contenu') ?>

<div class="card mb-4">
    <div class="card-body">
        <div class="font-mono text-uppercase text-muted" style="font-size:12px; letter-spacing:.18em;">Total à envoyer</div>
        <p class="mb-1" style="font-size:34px; font-weight:800; color:var(--pmi-teal);"><?= number_format($totalGeneral, 0, ',', ' ') ?> Ar</p>
        <p class="text-muted mb-0">dont <strong><?= number_format($totalMontant, 0, ',', ' ') ?> Ar</strong> de montants transférés et <strong><?= number_format($totalCommission, 0, ',', ' ') ?> Ar</strong> de commissions</p>
    </div>
</div>

<div class="mb-4">
    <h5 class="mb-3">Montants à envoyer par opérateur</h5>
    <?php if (empty($montants)) : ?>
        <p class="text-muted mb-0">Aucun montant à envoyer à un autre opérateur.</p>
    <?php else : ?>
        <div class="row g-3">
            <?php foreach ($montants as $montant) : ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="font-mono text-uppercase text-muted" style="font-size:11px; letter-spacing:.14em;"><?= esc($montant['nom']) ?></div>
                            <p class="mb-2 mt-1" style="font-size:26px; font-weight:800; color:var(--pmi-teal);"><?= number_format($montant['total'], 0, ',', ' ') ?> Ar</p>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li>Montant transféré : <?= number_format($montant['montant_transfere'], 0, ',', ' ') ?> Ar</li>
                                <li>Commission : <?= number_format($montant['commission'], 0, ',', ' ') ?> Ar</li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
