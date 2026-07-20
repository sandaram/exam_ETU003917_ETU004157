<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comptes Clients - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/operateur_navbar') ?>

<main class="container py-4">
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Nombre de clients</p>
                    <div class="h2 mb-0"><?= esc($nombreClients) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Total des soldes clients</p>
                    <div class="h2 mb-0 text-primary"><?= number_format((float) $totalSoldes, 0, ',', ' ') ?> Ar</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 pb-0">
            <h1 class="h3 mb-3">Situation des comptes clients</h1>
        </div>
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0" data-table-tools>
                <thead>
                    <tr>
                        <th>Numero de telephone</th>
                        <th class="text-end">Solde</th>
                        <th>Date de creation</th>
                        <th class="text-end">Nombre d'operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comptes as $c): ?>
                        <tr>
                            <td><?= esc($c['numero_telephone']) ?></td>
                            <td class="text-end"><?= number_format((float) $c['solde'], 0, ',', ' ') ?> Ar</td>
                            <td><?= esc($c['date_creation']) ?></td>
                            <td class="text-end"><?= esc($c['nombre_operations']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($comptes)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucun client enregistre.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/table-tools.js') ?>"></script>
</body>
</html>
