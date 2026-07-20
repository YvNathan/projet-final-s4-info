<?= $this->extend('layout/app') ?>

<?= $this->section('contenu') ?>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span style="width:44px;height:44px;flex:none;"><?= view('partials/logo') ?></span>
                    <div>
                        <h2 class="h5 mb-0">Bonjour <?= esc(session()->get('client_nom')) ?></h2>
                        <div class="text-muted font-mono" style="font-size:13px;">
                            <i class="bi bi-telephone me-1"></i><?= esc(session()->get('client_numero')) ?>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded" style="background:var(--pmi-ink); color:var(--pmi-ivory);">
                    <div class="font-mono text-uppercase" style="font-size:11px; letter-spacing:.18em; color:var(--pmi-mint);">Solde disponible</div>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <div style="font-size:32px; font-weight:800;">
                            <span id="solde" style="display:none;"><?= number_format($client['solde'], 2, ',', ' ') ?> Ar</span>
                            <span id="soldeCache">•••••••• Ar</span>
                        </div>
                        <button class="btn btn-sm btn-outline-light" onclick="toggleSolde()" type="button">
                            <i class="bi bi-eye" id="soldeIcon"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-body d-grid gap-3">
                <h3 class="h6 text-muted text-uppercase font-mono mb-1" style="font-size:12px; letter-spacing:.18em;">Opérations</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDepot">
                    <i class="bi bi-plus-circle me-1"></i>Faire un dépôt
                </button>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalRetrait">
                    <i class="bi bi-dash-circle me-1"></i>Faire un retrait
                </button>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTransfert">
                    <i class="bi bi-arrow-left-right me-1"></i>Faire un transfert
                </button>
                <a href="<?= base_url('historique') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-clock-history me-1"></i>Voir l'historique
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dépôt -->
<div class="modal fade" id="modalDepot" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('depot') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dépôt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <label for="depot_montant" class="form-label">Montant</label>
                    <input type="number" class="form-control" id="depot_montant" name="montant" min="1" required>
                    <div id="depotPreview" class="alert alert-light border mt-3 p-2 mb-0" style="display:none;">
                        <div class="d-flex justify-content-between small"><span>Frais</span><strong id="depotFrais">0 Ar</strong></div>
                        <div class="d-flex justify-content-between small mt-1"><span>Montant total</span><strong id="depotTotal">0 Ar</strong></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Retrait -->
<div class="modal fade" id="modalRetrait" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('retrait') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Retrait</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <label for="retrait_montant" class="form-label">Montant</label>
                    <input type="number" class="form-control" id="retrait_montant" name="montant" min="1" required>
                    <div id="retraitPreview" class="alert alert-light border mt-3 p-2 mb-0" style="display:none;">
                        <div class="d-flex justify-content-between small"><span>Frais</span><strong id="retraitFrais">0 Ar</strong></div>
                        <div class="d-flex justify-content-between small mt-1"><span>Montant total</span><strong id="retraitTotal">0 Ar</strong></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Transfert -->
<div class="modal fade" id="modalTransfert" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formTransfert" action="<?= base_url('transfert') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfert</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Destinataire(s)</label>
                        <div id="transfertDestinataires" class="d-grid gap-2">
                            <div class="input-group transfert-destinataire">
                                <input type="text" class="form-control" name="numero" placeholder="Numéro destinataire" required>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="btnAjouterDestinataire">
                            <i class="bi bi-plus-lg me-1"></i>Ajouter un destinataire
                        </button>
                        <div class="form-text">Pour plusieurs destinataires, tous doivent appartenir au même opérateur ; le montant sera réparti équitablement entre eux.</div>
                    </div>
                    <div class="mb-3">
                        <label for="transfert_montant" class="form-label">Montant</label>
                        <input type="number" class="form-control" id="transfert_montant" name="montant" min="1" required>
                        <div class="form-check mt-2" id="inclureFraisRetraitWrapper">
                            <input class="form-check-input" type="checkbox" id="inclure_frais_retrait" name="inclure_frais_retrait" value="1">
                            <label class="form-check-label" for="inclure_frais_retrait">Inclure les frais de retrait</label>
                        </div>
                        <div id="transfertPreview" class="alert alert-light border mt-3 p-2 mb-0" style="display:none;">
                            <div class="d-flex justify-content-between small"><span>Frais transfert</span><strong id="transfertFrais">0 Ar</strong></div>
                            <div class="d-flex justify-content-between small mt-1"><span>Frais retrait</span><strong id="transfertFraisRetrait">0 Ar</strong></div>
                            <div class="d-flex justify-content-between small mt-1"><span>Commission</span><strong id="transfertCommission">0 Ar</strong></div>
                            <div class="d-flex justify-content-between small mt-1"><span>Montant total</span><strong id="transfertTotal">0 Ar</strong></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function toggleSolde() {
        var solde = document.getElementById('solde');
        var cache = document.getElementById('soldeCache');
        var icon = document.getElementById('soldeIcon');
        if (solde.style.display === 'none') {
            solde.style.display = 'inline';
            cache.style.display = 'none';
            icon.className = 'bi bi-eye-slash';
        } else {
            solde.style.display = 'none';
            cache.style.display = 'inline';
            icon.className = 'bi bi-eye';
        }
    }

    function formatAr(value) {
        var amount = Number(value || 0);
        return new Intl.NumberFormat('fr-FR', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(amount) + ' Ar';
    }

    function updatePreview(typeOperation, inputId, previewId, fraisId, totalId, commissionId, fraisRetraitId) {
        var input = document.getElementById(inputId);
        var preview = document.getElementById(previewId);
        var fraisElement = document.getElementById(fraisId);
        var totalElement = document.getElementById(totalId);
        var commissionElement = commissionId ? document.getElementById(commissionId) : null;
        var fraisRetraitElement = fraisRetraitId ? document.getElementById(fraisRetraitId) : null;

        if (!input || !preview || !fraisElement || !totalElement) {
            return;
        }

        var montant = input.value.trim();

        if (!montant || Number(montant) <= 0) {
            preview.style.display = 'none';
            return;
        }

        if (typeOperation === 'transfert' && document.querySelectorAll('#transfertDestinataires input[name="numero"], #transfertDestinataires input[name="numeros[]"]').length > 1) {
            preview.style.display = 'none';
            return;
        }

        var endpoint = '<?= base_url('api/frais/get') ?>?montant=' + encodeURIComponent(montant) + '&type_operation=' + encodeURIComponent(typeOperation);

        if (typeOperation === 'transfert') {
            var numeroDest = document.querySelector('#transfertDestinataires input[name="numero"], #transfertDestinataires input[name="numeros[]"]');
            var includeFees = document.getElementById('inclure_frais_retrait');
            if (numeroDest) {
                endpoint += '&numero_destinataire=' + encodeURIComponent(numeroDest.value);
            }
            if (includeFees) {
                endpoint += '&inclure_frais_retrait=' + (includeFees.checked ? '1' : '0');
            }
        }

        fetch(endpoint, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Erreur de chargement');
                }
                return response.json();
            })
            .then(function (data) {
                var fraisTransfert = Number(data && data.frais_transfert !== undefined ? data.frais_transfert : 0);
                var fraisRetrait = Number(data && data.frais_retrait !== undefined ? data.frais_retrait : 0);
                var commission = Number(data && data.commission !== undefined ? data.commission : 0);
                var total = Number(data && data.montant_total !== undefined ? data.montant_total : Number(montant) + fraisTransfert + fraisRetrait + commission);

                fraisElement.textContent = formatAr(fraisTransfert);
                if (fraisRetraitElement) {
                    fraisRetraitElement.textContent = formatAr(fraisRetrait);
                }
                if (commissionElement) {
                    commissionElement.textContent = formatAr(commission);
                }
                totalElement.textContent = formatAr(total);
                preview.style.display = 'block';
            })
            .catch(function () {
                fraisElement.textContent = formatAr(0);
                if (fraisRetraitElement) {
                    fraisRetraitElement.textContent = formatAr(0);
                }
                if (commissionElement) {
                    commissionElement.textContent = formatAr(0);
                }
                totalElement.textContent = formatAr(Number(montant) || 0);
                preview.style.display = 'block';
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        ['depot_montant', 'retrait_montant', 'transfert_montant'].forEach(function (inputId) {
            var input = document.getElementById(inputId);
            if (!input) {
                return;
            }

            input.addEventListener('input', function () {
                if (inputId === 'depot_montant') {
                    updatePreview('depot', 'depot_montant', 'depotPreview', 'depotFrais', 'depotTotal');
                } else if (inputId === 'retrait_montant') {
                    updatePreview('retrait', 'retrait_montant', 'retraitPreview', 'retraitFrais', 'retraitTotal');
                } else if (inputId === 'transfert_montant') {
                    updatePreview('transfert', 'transfert_montant', 'transfertPreview', 'transfertFrais', 'transfertTotal', 'transfertCommission', 'transfertFraisRetrait');
                }
            });
        });

        var transfertDestinataires = document.getElementById('transfertDestinataires');
        var transfertCheckbox = document.getElementById('inclure_frais_retrait');
        var btnAjouterDestinataire = document.getElementById('btnAjouterDestinataire');
        var formTransfert = document.getElementById('formTransfert');
        var inclureFraisRetraitWrapper = document.getElementById('inclureFraisRetraitWrapper');

        function refreshTransfertPreview() {
            var montantInput = document.getElementById('transfert_montant');
            if (montantInput && montantInput.value.trim()) {
                updatePreview('transfert', 'transfert_montant', 'transfertPreview', 'transfertFrais', 'transfertTotal', 'transfertCommission', 'transfertFraisRetrait');
            }
        }

        function updateTransfertMode() {
            var inputs = transfertDestinataires.querySelectorAll('input[name="numero"], input[name="numeros[]"]');
            var multiple = inputs.length > 1;

            inputs.forEach(function (input) {
                input.name = multiple ? 'numeros[]' : 'numero';
            });

            formTransfert.action = multiple
                ? '<?= base_url('transfert/multiple') ?>'
                : '<?= base_url('transfert') ?>';

            if (inclureFraisRetraitWrapper) {
                inclureFraisRetraitWrapper.style.display = multiple ? 'none' : '';
            }

            refreshTransfertPreview();
        }

        if (transfertDestinataires && btnAjouterDestinataire) {
            btnAjouterDestinataire.addEventListener('click', function () {
                var group = document.createElement('div');
                group.className = 'input-group transfert-destinataire';
                group.innerHTML = '<input type="text" class="form-control" name="numero" placeholder="Numéro destinataire" required>' +
                    '<button type="button" class="btn btn-outline-danger btnRetirerDestinataire"><i class="bi bi-trash"></i></button>';
                transfertDestinataires.appendChild(group);
                updateTransfertMode();
            });

            transfertDestinataires.addEventListener('click', function (event) {
                var btn = event.target.closest('.btnRetirerDestinataire');
                if (!btn) {
                    return;
                }
                btn.closest('.transfert-destinataire').remove();
                updateTransfertMode();
            });

            transfertDestinataires.addEventListener('input', function (event) {
                if (event.target.matches('input[name="numero"], input[name="numeros[]"]')) {
                    refreshTransfertPreview();
                }
            });

            document.getElementById('modalTransfert').addEventListener('hidden.bs.modal', function () {
                transfertDestinataires.querySelectorAll('.transfert-destinataire').forEach(function (group, index) {
                    if (index > 0) {
                        group.remove();
                    }
                });
                updateTransfertMode();
            });
        }

        if (transfertCheckbox) {
            transfertCheckbox.addEventListener('change', refreshTransfertPreview);
        }
    });
</script>
<?= $this->endSection() ?>
