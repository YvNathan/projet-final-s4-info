<?php
$prefixes = $prefixes ?? [];
$operateurs = $operateurs ?? [];
$nomsById = $nomsById ?? [];
?>
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
                    <th>Opérateur</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($prefixes)) : ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Aucun préfixe enregistré.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($prefixes as $prefixe) : ?>
                        <tr>
                            <td class="font-mono"><?= esc($prefixe['prefixe']) ?></td>
                            <td><?= esc($nomsById[$prefixe['id_operateur']] ?? '—') ?></td>
                            <td class="text-center text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    data-bs-toggle="modal" data-bs-target="#modalModifierPrefixe"
                                    data-id="<?= esc($prefixe['id']) ?>"
                                    data-prefixe="<?= esc($prefixe['prefixe']) ?>"
                                    data-id-operateur="<?= esc($prefixe['id_operateur']) ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal" data-bs-target="#modalSupprimerPrefixe"
                                    data-id="<?= esc($prefixe['id']) ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouterPrefixe" tabindex="-1" aria-labelledby="modalAjouterPrefixeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('operateur/config') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAjouterPrefixeLabel">Ajouter un préfixe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_prefixe" class="form-label">Préfixe</label>
                        <input type="text" name="prefixe" id="add_prefixe" class="form-control" maxlength="10" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_id_operateur" class="form-label">Opérateur</label>
                        <select name="id_operateur" id="add_id_operateur" class="form-select" required>
                            <?php foreach ($operateurs as $operateur) : ?>
                                <option value="<?= esc($operateur['id']) ?>"><?= esc($operateur['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Modifier (réutilisé pour toutes les lignes, pré-rempli en JS) -->
<div class="modal fade" id="modalModifierPrefixe" tabindex="-1" aria-labelledby="modalModifierPrefixeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formModifierPrefixe" action="<?= base_url('operateur/config') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModifierPrefixeLabel">Modifier le préfixe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_prefixe" class="form-label">Préfixe</label>
                        <input type="text" name="prefixe" id="edit_prefixe" class="form-control" maxlength="10" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_operateur" class="form-label">Opérateur</label>
                        <select name="id_operateur" id="edit_id_operateur" class="form-select" required>
                            <?php foreach ($operateurs as $operateur) : ?>
                                <option value="<?= esc($operateur['id']) ?>"><?= esc($operateur['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Supprimer (réutilisé pour toutes les lignes) -->
<div class="modal fade" id="modalSupprimerPrefixe" tabindex="-1" aria-labelledby="modalSupprimerPrefixeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formSupprimerPrefixe" action="<?= base_url('operateur/config') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSupprimerPrefixeLabel">Supprimer le préfixe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Confirmez-vous la suppression de ce préfixe ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var modalModifier = document.getElementById('modalModifierPrefixe');
    modalModifier.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('formModifierPrefixe').action =
            '<?= base_url('operateur/config') ?>/' + button.getAttribute('data-id') + '/update';
        document.getElementById('edit_prefixe').value = button.getAttribute('data-prefixe');
        document.getElementById('edit_id_operateur').value = button.getAttribute('data-id-operateur');
    });

    var modalSupprimer = document.getElementById('modalSupprimerPrefixe');
    modalSupprimer.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('formSupprimerPrefixe').action =
            '<?= base_url('operateur/config') ?>/' + button.getAttribute('data-id') + '/delete';
    });
</script>
<?= $this->endSection() ?>
