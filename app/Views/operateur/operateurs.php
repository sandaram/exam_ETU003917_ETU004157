<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operateurs - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/operateur_navbar') ?>

<main class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Operateurs externes</h1>
            <p class="text-muted mb-0">Configuration des commissions appliquees aux transferts vers chaque operateur.</p>
        </div>
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
                        <th>Nom</th>
                        <th class="text-end">Commission</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($operateurs as $operateur): ?>
                        <tr>
                            <td>
                                <form id="operateur-<?= esc($operateur['id']) ?>" action="<?= base_url('operateur/operateurs/update/' . $operateur['id']) ?>" method="post" class="m-0">
                                    <?= csrf_field() ?>
                                    <input type="text" class="form-control" name="nom" value="<?= esc($operateur['nom']) ?>" required>
                                </form>
                            </td>
                            <td class="text-end" data-sort-value="<?= esc($operateur['commission_pct']) ?>">
                                <div class="input-group justify-content-end">
                                    <input form="operateur-<?= esc($operateur['id']) ?>" type="number" min="0" max="100" step="0.01" class="form-control text-end table-number-input" name="commission_pct" value="<?= esc($operateur['commission_pct']) ?>" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-switch m-0">
                                    <input form="operateur-<?= esc($operateur['id']) ?>" class="form-check-input" type="checkbox" role="switch" name="actif" value="1" <?= $operateur['actif'] ? 'checked' : '' ?>>
                                    <label class="form-check-label"><?= $operateur['actif'] ? 'Actif' : 'Inactif' ?></label>
                                </div>
                            </td>
                            <td class="text-end">
                                <button form="operateur-<?= esc($operateur['id']) ?>" type="submit" class="btn btn-sm btn-outline-primary">Enregistrer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($operateurs)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucun operateur configure.</td>
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
