<?php
$operateurs = $operateurs ?? [];
?>
<?= $this->extend('layout/app') ?>

<?= $this->section('contenu') ?>

<div class="panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h5 class="mb-0">Opérateurs</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjouterOperateur">
            <i class="bi bi-plus-lg me-1"></i>Ajouter un opérateur
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th class="text-end">Commission</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($operateurs)) : ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Aucun opérateur enregistré.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($operateurs as $operateur) : ?>
                        <tr>
                            <td>
                                <?= esc($operateur['nom']) ?>
                                <?php if ((int) $operateur['autre_operateur'] === 0) : ?>
                                    <span class="badge text-bg-secondary ms-1">Soi-même</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ((int) $operateur['autre_operateur'] === 1) : ?>
                                    <?= number_format($operateur['pct_commission'], 2, ',', ' ') ?> %
                                <?php else : ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td class="text-center text-nowrap">
                                <?php if ((int) $operateur['autre_operateur'] === 1) : ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="modal" data-bs-target="#modalModifierOperateur"
                                        data-id="<?= esc($operateur['id']) ?>"
                                        data-nom="<?= esc($operateur['nom']) ?>"
                                        data-pct-commission="<?= esc($operateur['pct_commission']) ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#modalSupprimerOperateur"
                                        data-id="<?= esc($operateur['id']) ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                <?php else : ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouterOperateur" tabindex="-1" aria-labelledby="modalAjouterOperateurLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('operateur/operateurs') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAjouterOperateurLabel">Ajouter un opérateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_nom" class="form-label">Nom</label>
                        <input type="text" name="nom" id="add_nom" class="form-control" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_pct_commission" class="form-label">Commission (%)</label>
                        <input type="number" step="any" min="0" max="100" name="pct_commission" id="add_pct_commission" class="form-control" required>
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
<div class="modal fade" id="modalModifierOperateur" tabindex="-1" aria-labelledby="modalModifierOperateurLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formModifierOperateur" action="<?= base_url('operateur/operateurs') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModifierOperateurLabel">Modifier l'opérateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nom" class="form-label">Nom</label>
                        <input type="text" name="nom" id="edit_nom" class="form-control" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_pct_commission" class="form-label">Commission (%)</label>
                        <input type="number" step="any" min="0" max="100" name="pct_commission" id="edit_pct_commission" class="form-control" required>
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
<div class="modal fade" id="modalSupprimerOperateur" tabindex="-1" aria-labelledby="modalSupprimerOperateurLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formSupprimerOperateur" action="<?= base_url('operateur/operateurs') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSupprimerOperateurLabel">Supprimer l'opérateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Confirmez-vous la suppression de cet opérateur ?</p>
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
    var modalModifier = document.getElementById('modalModifierOperateur');
    modalModifier.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('formModifierOperateur').action =
            '<?= base_url('operateur/operateurs') ?>/' + button.getAttribute('data-id') + '/update';
        document.getElementById('edit_nom').value = button.getAttribute('data-nom');
        document.getElementById('edit_pct_commission').value = button.getAttribute('data-pct-commission');
    });

    var modalSupprimer = document.getElementById('modalSupprimerOperateur');
    modalSupprimer.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('formSupprimerOperateur').action =
            '<?= base_url('operateur/operateurs') ?>/' + button.getAttribute('data-id') + '/delete';
    });
</script>
<?= $this->endSection() ?>
