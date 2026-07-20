<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <?php
    if (session()->getFlashdata('error')) {
        echo '<p style="color:red;">' . session()->getFlashdata('error') . '</p>';
    }
    ?>
    <form action="<?= base_url('login') ?>" method="post">
        <label for="numero">Numéro de téléphone:</label>
        <input type="text" name="numero" id="numero" required>
        <button type="submit">Se connecter</button>
    </form>
</body>

</html>
<script>
    let submitButton = document.querySelector('button[type="submit"]');
    submitButton.addEventListener('click', function(event) {
        event.preventDefault();
        let numeroInput = document.getElementById("numero");
        let numero = numeroInput.value;
        const motif = "^(?:\\+261|0)\\s?(20|32|33|34|37|38|39)(?:[\\s]?\\d{2})(?:[\\s]?\\d{3})(?:[\\s]?\\d{2})$";
        let regex = new RegExp(motif);
        if (regex.test(numero)) {
            document.getElementById("numero").style.borderColor = "green";
            this.form.submit();
        } else {
            document.getElementById("numero").style.borderColor = "red";
        }
    });
</script>