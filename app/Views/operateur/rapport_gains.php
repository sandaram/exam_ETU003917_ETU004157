<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gains - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/operateur_navbar') ?>

<main class="container py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h1 class="h3 mb-2">Situation des gains</h1>
            <p class="text-muted mb-1">Total general des frais et commissions</p>
            <div class="display-6 fw-semibold text-primary"><?= number_format((float) $total, 0, ',', ' ') ?> Ar</div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0" data-table-tools>
                <thead>
                    <tr>
                        <th>Type d'operation</th>
                        <th>Beneficiaire</th>
                        <th class="text-end">Nombre d'operations</th>
                        <th class="text-end">Total des gains</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($situations as $s): ?>
                        <tr>
                            <td><?= esc($s['type_operation']) ?></td>
                            <td><?= esc($s['beneficiaire']) ?></td>
                            <td class="text-end"><?= esc($s['nombre_operations']) ?></td>
                            <td class="text-end"><?= number_format((float) $s['total_gains'], 0, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($situations)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucune operation enregistree.</td>
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
