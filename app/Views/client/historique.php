<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Historique</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

</head>


<body class="bg-light">


    <div class="container py-5">


        <div class="card shadow">


            <div class="card-header bg-primary text-white">

                <h3 class="mb-0">
                    <i class="bi bi-clock-history"></i>
                    Historique des transactions
                </h3>

            </div>


            <div class="card-body">


                <a href="<?= base_url('home') ?>" class="btn btn-secondary mb-3">

                    <i class="bi bi-arrow-left"></i>
                    Retour

                </a>



                <?php if (empty($historique)): ?>


                    <div class="alert alert-info">

                        Aucune transaction trouvée.

                    </div>


                <?php else: ?>


                    <div class="table-responsive">


                        <table class="table table-bordered table-hover">


                            <thead class="table-dark">

                                <tr>

                                    <th>Date</th>

                                    <th>Type</th>

                                    <th>Montant</th>

                                    <th>Frais</th>

                                    <th>Destinataire</th>

                                </tr>

                            </thead>



                            <tbody>


                                <?php foreach ($historique as $transaction): ?>


                                    <tr>


                                        <td>
                                            <?= date('d/m/Y H:i', strtotime($transaction['date_heure'])) ?>
                                        </td>


                                        <td>

                                            <?php if ($transaction['type_operation'] == "depot"): ?>

                                                <span class="badge bg-success">
                                                    Dépôt
                                                </span>

                                            <?php elseif ($transaction['type_operation'] == "retrait"): ?>

                                                <span class="badge bg-warning text-dark">
                                                    Retrait
                                                </span>

                                            <?php else: ?>

                                                <span class="badge bg-primary">
                                                    Transfert
                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <td>
                                            <?= number_format($transaction['montant'], 2, ',', ' ') ?> Ar
                                        </td>


                                        <td>
                                            <?= number_format($transaction['frais_applique'], 2, ',', ' ') ?> Ar
                                        </td>


                                        <td>

                                            <?php if ($transaction['numero_destinataire']): ?>

                                                <?= esc($transaction['numero_destinataire']) ?>

                                            <?php else: ?>

                                                -

                                            <?php endif; ?>

                                        </td>


                                    </tr>


                                <?php endforeach; ?>


                            </tbody>


                        </table>


                    </div>


                <?php endif; ?>


            </div>


        </div>


    </div>



</body>

</html>