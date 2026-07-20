<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Montants a envoyer - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/operateur_navbar') ?>

<main class="container py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <h1 class="h3 mb-2">Montants a envoyer aux autres operateurs</h1>
            <p class="text-muted mb-0">Synthese des transferts externes, sans compte ni login pour les autres operateurs.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Operateur</th>
                        <th>Cas de figure</th>
                        <th class="text-end">Operations</th>
                        <th class="text-end">Argent</th>
                        <th class="text-end">Frais</th>
                        <th class="text-end">Commission</th>
                        <th class="text-end">Total a envoyer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes as $ligne): ?>
                        <?php $scenario = $ligne['mode_transfert'] === 'externe_direct' ? 'Direct' : 'Via notre operateur'; ?>
                        <tr>
                            <td><?= esc($ligne['operateur']) ?></td>
                            <td><?= esc($scenario) ?></td>
                            <td class="text-end"><?= esc($ligne['nombre_operations']) ?></td>
                            <td class="text-end"><?= number_format((float) $ligne['total_montant'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-end"><?= number_format((float) $ligne['total_frais'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-end"><?= number_format((float) $ligne['total_commission'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-end text-primary fw-semibold"><?= number_format((float) $ligne['total_a_envoyer'], 0, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($lignes)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Aucun transfert externe enregistre.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
