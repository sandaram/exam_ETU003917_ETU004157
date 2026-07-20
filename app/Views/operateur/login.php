<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Operateur - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light auth-shell">
<div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
    <div class="card shadow-sm border-0 auth-card">
        <div class="card-body p-4">
            <h1 class="h4 text-center text-primary mb-1">Mobile Money</h1>
            <p class="text-center text-muted mb-4">Connexion operateur</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger py-2"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <div class="alert alert-info small">
                <div class="fw-semibold mb-1">Identifiants locaux de test</div>
                <div>Identifiant : <strong><?= esc($identifiantDemo) ?></strong></div>
                <div>Mot de passe : <strong><?= esc($motDePasseDemo) ?></strong></div>
            </div>

            <form action="<?= base_url('operateur/login') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="identifiant" class="form-label">Identifiant</label>
                    <input type="text" class="form-control" id="identifiant" name="identifiant" value="<?= old('identifiant') ?>" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="mot_de_passe" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>

            <div class="text-center mt-3">
                <a href="<?= base_url('client/login') ?>" class="link-secondary">Connexion client</a>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
