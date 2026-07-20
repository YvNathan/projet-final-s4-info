<?= $this->extend('operateur/layout') ?>

<?= $this->section('contenu') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Barèmes de frais</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAjouterBareme">
        Ajouter une tranche
    </button>
</div>

<form action="<?= base_url('operateur/frais') ?>" method="get" class="form-inline mb-3">
    <label for="filtre_type" class="mr-2">Type d'opération</label>
    <select name="type" id="filtre_type" class="form-control mr-2" onchange="this.form.submit()">
        <option value="">Tous</option>
        <?php foreach ($typesOperation as $type) : ?>
            <option value="<?= esc($type['id']) ?>" <?= $idTypeOperationFiltre === (int) $type['id'] ? 'selected' : '' ?>>
                <?= esc($type['libelle']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <noscript><button type="submit" class="btn btn-secondary">Filtrer</button></noscript>
</form>

<table class="table table-striped table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>Type d'opération</th>
            <th class="text-right">Borne min</th>
            <th class="text-right">Borne max</th>
            <th class="text-right">Frais</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($baremes)) : ?>
            <tr>
                <td colspan="5" class="text-center text-muted">Aucune tranche de frais enregistrée.</td>
            </tr>
        <?php else : ?>
            <?php foreach ($baremes as $bareme) : ?>
                <tr>
                    <td><?= esc($libellesById[$bareme['id_type_operation']] ?? '—') ?></td>
                    <td class="text-right"><?= number_format($bareme['borne_min'], 0, ',', ' ') ?> Ar</td>
                    <td class="text-right"><?= number_format($bareme['borne_max'], 0, ',', ' ') ?> Ar</td>
                    <td class="text-right"><?= number_format($bareme['frais'], 0, ',', ' ') ?> Ar</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-toggle="modal" data-target="#modalModifierBareme"
                            data-id="<?= esc($bareme['id']) ?>"
                            data-borne-min="<?= esc($bareme['borne_min']) ?>"
                            data-borne-max="<?= esc($bareme['borne_max']) ?>"
                            data-frais="<?= esc($bareme['frais']) ?>">
                            Modifier
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            data-toggle="modal" data-target="#modalSupprimerBareme"
                            data-id="<?= esc($bareme['id']) ?>">
                            Supprimer
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($pager->getPageCount() > 1) : ?>
    <?php $previousPageURI = $pager->getPreviousPageURI(); ?>
    <?php $nextPageURI = $pager->getNextPageURI(); ?>
    <nav aria-label="Pagination des barèmes de frais">
        <ul class="pagination">
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

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouterBareme" tabindex="-1" role="dialog" aria-labelledby="modalAjouterBaremeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('operateur/frais') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAjouterBaremeLabel">Ajouter une tranche de frais</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add_id_type_operation">Type d'opération</label>
                        <select name="id_type_operation" id="add_id_type_operation" class="form-control" required>
                            <?php foreach ($typesOperation as $type) : ?>
                                <option value="<?= esc($type['id']) ?>"><?= esc($type['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_borne_min">Borne min</label>
                        <input type="number" step="any" name="borne_min" id="add_borne_min" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="add_borne_max">Borne max</label>
                        <input type="number" step="any" name="borne_max" id="add_borne_max" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="add_frais">Frais</label>
                        <input type="number" step="any" name="frais" id="add_frais" class="form-control" required>
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

<!-- Modal Modifier (reutilise pour toutes les lignes, pre-rempli en JS) -->
<div class="modal fade" id="modalModifierBareme" tabindex="-1" role="dialog" aria-labelledby="modalModifierBaremeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formModifierBareme" action="<?= base_url('operateur/frais') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModifierBaremeLabel">Modifier la tranche de frais</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_borne_min">Borne min</label>
                        <input type="number" step="any" name="borne_min" id="edit_borne_min" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_borne_max">Borne max</label>
                        <input type="number" step="any" name="borne_max" id="edit_borne_max" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_frais">Frais</label>
                        <input type="number" step="any" name="frais" id="edit_frais" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Supprimer (reutilise pour toutes les lignes) -->
<div class="modal fade" id="modalSupprimerBareme" tabindex="-1" role="dialog" aria-labelledby="modalSupprimerBaremeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formSupprimerBareme" action="<?= base_url('operateur/frais') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSupprimerBaremeLabel">Supprimer la tranche de frais</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Confirmez-vous la suppression de cette tranche de frais ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $('#modalModifierBareme').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var borneMin = button.data('borne-min');
        var borneMax = button.data('borne-max');
        var frais = button.data('frais');

        document.getElementById('formModifierBareme').action = '<?= base_url('operateur/frais') ?>/' + id + '/update';
        document.getElementById('edit_borne_min').value = borneMin;
        document.getElementById('edit_borne_max').value = borneMax;
        document.getElementById('edit_frais').value = frais;
    });

    $('#modalSupprimerBareme').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');

        document.getElementById('formSupprimerBareme').action = '<?= base_url('operateur/frais') ?>/' + id + '/delete';
    });
</script>
<?= $this->endSection() ?>
