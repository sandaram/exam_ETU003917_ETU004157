<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Prefixe - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/operateur_navbar') ?>

<main class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h3 mb-4">Modifier le prefixe</h1>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <div><?= esc($error) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('operateur/prefixes/update/' . $prefixe['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="prefixe" class="form-label">Prefixe</label>
                            <input type="text" class="form-control" id="prefixe" name="prefixe" maxlength="3" value="<?= esc($prefixe['prefixe']) ?>" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="<?= base_url('operateur/prefixes') ?>" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
