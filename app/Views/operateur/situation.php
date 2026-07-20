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

<?= $this->endSection() ?>
