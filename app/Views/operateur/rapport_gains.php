<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gains - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/operateur_navbar') ?>

<main class="container py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h1 class="h3 mb-2">Situation des gains</h1>
            <p class="text-muted mb-1">Total general des frais percus</p>
            <div class="display-6 fw-semibold text-primary"><?= number_format((float) $total, 0, ',', ' ') ?> Ar</div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Type d'operation</th>
                        <th class="text-end">Nombre d'operations</th>
                        <th class="text-end">Total des frais percus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($situations as $s): ?>
                        <tr>
                            <td><?= esc($s['type_operation']) ?></td>
                            <td class="text-end"><?= esc($s['nombre_operations']) ?></td>
                            <td class="text-end"><?= number_format((float) $s['total_frais_percus'], 0, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($situations)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">Aucune operation enregistree.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
