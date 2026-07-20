<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Client - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/client_navbar') ?>

<main class="container py-4">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                        <div>
                            <h1 class="h3 mb-1">Mon espace client</h1>
                            <p class="text-muted mb-0"><?= esc(session()->get('client_phone') ?? 'Numero client') ?></p>
                        </div>
                        <div class="text-md-end">
                            <span class="text-muted small text-uppercase">Solde disponible</span>
                            <div class="display-6 fw-semibold text-primary"><?= number_format($solde ?? 0, 0, ',', ' ') ?> Ar</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <a href="<?= base_url('client/depot') ?>" class="btn btn-outline-primary w-100 py-3">Faire un depot</a>
                </div>
                <div class="col-md-6">
                    <a href="<?= base_url('client/retrait') ?>" class="btn btn-outline-primary w-100 py-3">Faire un retrait</a>
                </div>
                <div class="col-md-6">
                    <a href="<?= base_url('client/transfert') ?>" class="btn btn-outline-primary w-100 py-3">Faire un transfert</a>
                </div>
                <div class="col-md-6">
                    <a href="<?= base_url('client/historique') ?>" class="btn btn-outline-secondary w-100 py-3">Voir l'historique</a>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
