<?= $this->extend('operateur/layout') ?>

<?= $this->section('contenu') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Préfixes</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAjouterPrefixe">
        Ajouter un préfixe
    </button>
</div>

<table class="table table-striped table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>Préfixe</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($prefixes)) : ?>
            <tr>
                <td class="text-center text-muted">Aucun préfixe enregistré.</td>
            </tr>
        <?php else : ?>
            <?php foreach ($prefixes as $prefixe) : ?>
                <tr>
                    <td><?= esc($prefixe['prefixe']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="modal fade" id="modalAjouterPrefixe" tabindex="-1" role="dialog" aria-labelledby="modalAjouterPrefixeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('operateur/config') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAjouterPrefixeLabel">Ajouter un préfixe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="prefixe">Préfixe</label>
                        <input type="text" name="prefixe" id="prefixe" class="form-control" maxlength="10" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
