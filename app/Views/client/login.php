<?= $this->extend('layout/auth') ?>

<?= $this->section('contenu') ?>

<p class="text-muted text-center mb-4">Connectez-vous avec votre numéro de téléphone.</p>

<form action="<?= base_url('login') ?>" method="post" id="loginForm">
    <div class="mb-3">
        <label for="numero" class="form-label">Numéro de téléphone</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="text" name="numero" id="numero" class="form-control" placeholder="034 12 345 67" required>
        </div>
        <div class="form-text">Formats acceptés : 03X XX XXX XX ou +261 3X XX XXX XX</div>
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-box-arrow-in-right me-1"></i>Se connecter
        </button>
    </div>
</form>

<div class="d-grid mt-3">
    <a href="<?= base_url('operateur/situation') ?>" class="btn btn-outline-secondary btn-lg">
        <i class="bi bi-shield-lock me-1"></i>Espace opérateur
    </a>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.getElementById('loginForm').addEventListener('submit', function (event) {
        var numeroInput = document.getElementById('numero');
        var motif = "^(?:\\+261|0)\\s?(20|32|33|34|37|38|39)(?:[\\s]?\\d{2})(?:[\\s]?\\d{3})(?:[\\s]?\\d{2})$";
        if (new RegExp(motif).test(numeroInput.value)) {
            numeroInput.classList.remove('is-invalid');
        } else {
            event.preventDefault();
            numeroInput.classList.add('is-invalid');
        }
    });
</script>
<?= $this->endSection() ?>
