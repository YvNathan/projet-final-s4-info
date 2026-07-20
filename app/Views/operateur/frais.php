<?= $this->extend('layout/app') ?>

<?= $this->section('contenu') ?>

<div class="panel">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h5 class="mb-0">Tranches de frais</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjouterBareme">
            <i class="bi bi-plus-lg me-1"></i>Ajouter une tranche
        </button>
    </div>

    <form action="<?= base_url('operateur/frais') ?>" method="get" class="row g-2 mb-3">
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
            <a href="<?= base_url('operateur/frais') ?>" class="btn btn-outline-secondary w-100"><i class="bi bi-x-circle me-1"></i>Réinitialiser</a>
        </div>
        <noscript>
            <div class="col-auto"><button type="submit" class="btn btn-outline-secondary">Filtrer</button></div>
        </noscript>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Type d'opération</th>
                    <th class="text-end">Borne min</th>
                    <th class="text-end">Borne max</th>
                    <th class="text-end">Frais</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($baremes)) : ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Aucune tranche de frais enregistrée.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($baremes as $bareme) : ?>
                        <tr>
                            <td><?= esc($libellesById[$bareme['id_type_operation']] ?? '—') ?></td>
                            <td class="text-end"><?= number_format($bareme['borne_min'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-end"><?= number_format($bareme['borne_max'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-end"><?= number_format($bareme['frais'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-center text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                    data-bs-toggle="modal" data-bs-target="#modalModifierBareme"
                                    data-id="<?= esc($bareme['id']) ?>"
                                    data-borne-min="<?= esc($bareme['borne_min']) ?>"
                                    data-borne-max="<?= esc($bareme['borne_max']) ?>"
                                    data-frais="<?= esc($bareme['frais']) ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal" data-bs-target="#modalSupprimerBareme"
                                    data-id="<?= esc($bareme['id']) ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pager->getPageCount() > 1) : ?>
        <?php $previousPageURI = $pager->getPreviousPageURI(); ?>
        <?php $nextPageURI = $pager->getNextPageURI(); ?>
        <nav aria-label="Pagination des barèmes de frais" class="mt-2">
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

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouterBareme" tabindex="-1" aria-labelledby="modalAjouterBaremeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('operateur/frais') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAjouterBaremeLabel">Ajouter une tranche de frais</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_id_type_operation" class="form-label">Type d'opération</label>
                        <select name="id_type_operation" id="add_id_type_operation" class="form-select" required>
                            <?php foreach ($typesOperation as $type) : ?>
                                <option value="<?= esc($type['id']) ?>"><?= esc($type['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_borne_min" class="form-label">Borne min</label>
                        <input type="number" step="any" name="borne_min" id="add_borne_min" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_borne_max" class="form-label">Borne max</label>
                        <input type="number" step="any" name="borne_max" id="add_borne_max" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_frais" class="form-label">Frais</label>
                        <input type="number" step="any" name="frais" id="add_frais" class="form-control" required>
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
<div class="modal fade" id="modalModifierBareme" tabindex="-1" aria-labelledby="modalModifierBaremeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formModifierBareme" action="<?= base_url('operateur/frais') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModifierBaremeLabel">Modifier la tranche de frais</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_borne_min" class="form-label">Borne min</label>
                        <input type="number" step="any" name="borne_min" id="edit_borne_min" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_borne_max" class="form-label">Borne max</label>
                        <input type="number" step="any" name="borne_max" id="edit_borne_max" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_frais" class="form-label">Frais</label>
                        <input type="number" step="any" name="frais" id="edit_frais" class="form-control" required>
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
<div class="modal fade" id="modalSupprimerBareme" tabindex="-1" aria-labelledby="modalSupprimerBaremeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formSupprimerBareme" action="<?= base_url('operateur/frais') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSupprimerBaremeLabel">Supprimer la tranche de frais</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Confirmez-vous la suppression de cette tranche de frais ?</p>
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
    var modalModifier = document.getElementById('modalModifierBareme');
    modalModifier.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('formModifierBareme').action =
            '<?= base_url('operateur/frais') ?>/' + button.getAttribute('data-id') + '/update';
        document.getElementById('edit_borne_min').value = button.getAttribute('data-borne-min');
        document.getElementById('edit_borne_max').value = button.getAttribute('data-borne-max');
        document.getElementById('edit_frais').value = button.getAttribute('data-frais');
    });

    var modalSupprimer = document.getElementById('modalSupprimerBareme');
    modalSupprimer.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('formSupprimerBareme').action =
            '<?= base_url('operateur/frais') ?>/' + button.getAttribute('data-id') + '/delete';
    });
</script>
<?= $this->endSection() ?>
