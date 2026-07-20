<?= $this->extend('layout/app') ?>

<?= $this->section('contenu') ?>

<div class="panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h5 class="mb-0">Préfixes autorisés</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjouterPrefixe">
            <i class="bi bi-plus-lg me-1"></i>Ajouter un préfixe
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Préfixe</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($prefixes)) : ?>
                    <tr>
                        <td class="text-center text-muted py-4">Aucun préfixe enregistré.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($prefixes as $prefixe) : ?>
                        <tr>
                            <td class="font-mono"><?= esc($prefixe['prefixe']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalAjouterPrefixe" tabindex="-1" aria-labelledby="modalAjouterPrefixeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('operateur/config') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAjouterPrefixeLabel">Ajouter un préfixe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <label for="prefixe" class="form-label">Préfixe</label>
                    <input type="text" name="prefixe" id="prefixe" class="form-control" maxlength="10" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
