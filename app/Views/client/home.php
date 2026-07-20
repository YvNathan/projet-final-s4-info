<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
</head>
<body>
    <h1>Bienvenue, <?= session()->get('client_nom') ?></h1>
    <p>Votre numéro de téléphone : <?= session()->get('client_numero') ?></p>
    <a href="<?= base_url('logout') ?>">Se déconnecter</a>
</body>
</html>