<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="card shadow">

                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-wallet2"></i>
                            Mobile Money
                        </h3>
                    </div>

                    <div class="card-body">

                        <h4>
                            Bonjour <?= esc(session()->get('client_nom')) ?>
                        </h4>

                        <p class="text-muted mb-4">
                            <i class="bi bi-telephone"></i>
                            <?= esc(session()->get('client_numero')) ?>
                        </p>

                        <div class="alert alert-success">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <strong>Solde :</strong>

                                    <span id="solde" style="display:none;">
                                        <?= number_format($client['solde'], 2, ',', ' ') ?> Ar
                                    </span>

                                    <span id="soldeCache">
                                        ********
                                    </span>

                                </div>

                                <button
                                    class="btn btn-outline-success btn-sm"
                                    onclick="toggleSolde()">

                                    <i class="bi bi-eye"></i>

                                </button>

                            </div>

                        </div>

                        <div class="d-grid gap-3 mt-4">

                            <button class="btn btn-success"
                                data-bs-toggle="modal"
                                data-bs-target="#modalDepot">
                                <i class="bi bi-plus-circle"></i>
                                Faire un dépôt
                            </button>

                            <button class="btn btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#modalRetrait">
                                <i class="bi bi-dash-circle"></i>
                                Faire un retrait
                            </button>

                            <button class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalTransfert">
                                <i class="bi bi-arrow-left-right"></i>
                                Faire un transfert
                            </button>

                        </div>

                        <a href="<?= base_url('historique') ?>" class="btn btn-dark">
                            <i class="bi bi-clock-history"></i>
                            Voir historique
                        </a>
                    </div>


                    <div class="card-footer text-end">

                        <a href="<?= base_url('logout') ?>" class="btn btn-danger">
                            <i class="bi bi-box-arrow-right"></i>
                            Déconnexion
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="modal fade" id="modalDepot">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="<?= base_url('depot') ?>" method="post">

                    <div class="modal-header">
                        <h5>Dépôt</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label>Montant</label>

                        <input
                            type="number"
                            class="form-control"
                            name="montant"
                            min="1"
                            required>

                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Annuler
                        </button>

                        <button class="btn btn-success">
                            Valider
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRetrait">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="<?= base_url('retrait') ?>" method="post">

                    <div class="modal-header">
                        <h5>Retrait</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label>Montant</label>

                        <input
                            type="number"
                            class="form-control"
                            name="montant"
                            min="1"
                            required>

                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Annuler
                        </button>

                        <button class="btn btn-warning">
                            Valider
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTransfert">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="<?= base_url('transfert') ?>" method="post">

                    <div class="modal-header">
                        <h5>Transfert</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label>Numéro destinataire</label>

                        <input
                            type="text"
                            class="form-control mb-3"
                            name="numero"
                            required>

                        <label>Montant</label>

                        <input
                            type="number"
                            class="form-control"
                            name="montant"
                            min="1"
                            required>

                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Annuler
                        </button>

                        <button class="btn btn-primary">
                            Valider
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
    <script>
        function toggleSolde() {

            let solde = document.getElementById("solde");
            let cache = document.getElementById("soldeCache");

            if (solde.style.display == "none") {
                solde.style.display = "inline";
                cache.style.display = "none";
            } else {
                solde.style.display = "none";
                cache.style.display = "inline";
            }

        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>