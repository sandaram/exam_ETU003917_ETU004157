<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depot - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/client_navbar') ?>
<div class="container py-5" style="max-width: 520px;">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4">Faire un depot</h3>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('client/depot') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="montant" class="form-label">Montant</label>
                <input type="number" min="1" step="1" class="form-control" id="montant" name="montant" value="<?= old('montant') ?>" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100">Valider le depot</button>
        </form>
    </div>
</div>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
