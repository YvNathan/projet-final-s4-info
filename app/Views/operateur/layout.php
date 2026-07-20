<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titre ?? 'Espace opérateur') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="<?= base_url('operateur/situation') ?>">Espace opérateur</a>
        <div class="navbar-nav">
            <a class="nav-link <?= (uri_string() === 'operateur/situation') ? 'active' : '' ?>" href="<?= base_url('operateur/situation') ?>">Situation</a>
            <a class="nav-link <?= (uri_string() === 'operateur/config') ? 'active' : '' ?>" href="<?= base_url('operateur/config') ?>">Préfixes</a>
            <a class="nav-link <?= (uri_string() === 'operateur/frais') ? 'active' : '' ?>" href="<?= base_url('operateur/frais') ?>">Barèmes de frais</a>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?= $this->renderSection('contenu') ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
