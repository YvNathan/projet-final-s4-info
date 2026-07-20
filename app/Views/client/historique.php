<?= $this->extend('layout/app') ?>

<?= $this->section('contenu') ?>

<?php if (empty($historique)) : ?>
    <div class="alert alert-info"><i class="bi bi-info-circle me-1"></i>Aucune transaction trouvée.</div>
<?php else : ?>
    <div class="panel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h5 class="mb-0">Historique des transactions</h5>
            <span class="text-secondary small"><?= count($historique) ?> transaction(s)</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th class="text-end">Montant</th>
                        <th class="text-end">Frais</th>
                        <th>Destinataire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historique as $transaction) : ?>
                        <tr>
                            <td class="font-mono"><?= date('d/m/Y H:i', strtotime($transaction['date_heure'])) ?></td>
                            <td>
                                <?php if ($transaction['type_operation'] === 'depot') : ?>
                                    <span class="badge-status st-depot">Dépôt</span>
                                <?php elseif ($transaction['type_operation'] === 'retrait') : ?>
                                    <span class="badge-status st-retrait">Retrait</span>
                                <?php elseif ($transaction['type_operation'] === 'transfert') : ?>
                                    <?php if ($transaction['sens'] === 'envoye') : ?>
                                        <span class="badge-status st-transfert"><i class="bi bi-arrow-up-right me-1"></i>Transfert envoyé</span>
                                    <?php else : ?>
                                        <span class="badge-status st-depot"><i class="bi bi-arrow-down-left me-1"></i>Transfert reçu</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ($transaction['type_operation'] === 'transfert' && $transaction['sens'] === 'recu') : ?>
                                    <span class="text-success">+ <?= number_format($transaction['montant'], 2, ',', ' ') ?> Ar</span>
                                <?php elseif ($transaction['type_operation'] === 'transfert') : ?>
                                    <span class="text-danger">- <?= number_format($transaction['montant'], 2, ',', ' ') ?> Ar</span>
                                <?php else : ?>
                                    <?= number_format($transaction['montant'], 2, ',', ' ') ?> Ar
                                <?php endif; ?>
                            </td>
                            <td class="text-end"><?= number_format($transaction['frais_applique'], 2, ',', ' ') ?> Ar</td>
                            <td class="font-mono">
                                <?= $transaction['numero_destinataire'] ? esc($transaction['numero_destinataire']) : '—' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
