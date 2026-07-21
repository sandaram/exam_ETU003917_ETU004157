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

    <!-- Récapitulatif global -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 bg-primary bg-opacity-10">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total global - Tous les clients</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1"><strong><?= (int) $totalGlobal['nb_operations_total'] ?></strong> opérations</p>
                            <p class="mb-0 text-muted small">Transferts traités</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-1 h5"><strong><?= number_format((float) $totalGlobal['total_commission_global'], 2, ',', ' ') ?></strong> F</p>
                            <p class="mb-0 text-muted small">Commissions gagnées</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0" data-table-tools>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th class="text-end">Commission</th>
                        <th class="text-end">Opérations</th>
                        <th class="text-end">Total commissions</th>
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
                            <td class="text-end" data-sort-value="<?= $operateur['nb_operations'] ?>">
                                <span class="badge bg-info"><?= $operateur['nb_operations'] ?></span>
                            </td>
                            <td class="text-end" data-sort-value="<?= $operateur['total_commission'] ?>">
                                <strong><?= number_format((float) $operateur['total_commission'], 2, ',', ' ') ?></strong> F
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
                            <td colspan="6" class="text-center text-muted">Aucun operateur configure.</td>
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
