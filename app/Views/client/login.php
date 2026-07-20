<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light auth-shell">

<div class="container d-flex justify-content-center align-items-center min-vh-100 py-4">
    <div class="card shadow-sm p-4 auth-card">
        <h3 class="text-center mb-4 text-primary">Mobile Money</h3>
        <h6 class="text-center text-muted mb-4">Connexion Client</h6>

        <!-- Message d'erreur -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 fs-7" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('client/login') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="telephone" class="form-label">Numéro de téléphone</label>
                <input type="text" 
                       class="form-control" 
                       id="telephone" 
                       name="telephone" 
                       placeholder="Ex: 0331234567" 
                       required 
                       autofocus>
                <div class="form-text">Entrez votre numéro pour vous connecter ou créer un compte.</div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Se connecter / S'inscrire</button>
        </form>

        <div class="text-center mt-3">
            <a href="<?= base_url('operateur/login') ?>" class="link-secondary">Connexion operateur</a>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
