<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baremes - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/operateur_navbar') ?>

<main class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Baremes de frais</h1>
            <p class="text-muted mb-0">Gestion des frais par type d'operation et tranche de montant.</p>
        </div>
        <a href="<?= base_url('operateur/baremes/create') ?>" class="btn btn-primary align-self-md-start">Ajouter une tranche</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0" data-table-tools>
                <thead>
                    <tr>
                        <th>Type d'operation</th>
                        <th class="text-end">Montant min</th>
                        <th class="text-end">Montant max</th>
                        <th class="text-end">Frais</th>
                        <th class="text-end no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($baremes as $b): ?>
                        <tr>
                            <td><?= esc($b['libelle']) ?></td>
                            <td class="text-end"><?= number_format((float) $b['montant_min'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-end"><?= number_format((float) $b['montant_max'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-end"><?= number_format((float) $b['frais'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('operateur/baremes/edit/' . $b['id']) ?>">Modifier</a>
                                <a class="btn btn-sm btn-outline-danger" href="<?= base_url('operateur/baremes/delete/' . $b['id']) ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($baremes)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucun bareme enregistre.</td>
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
