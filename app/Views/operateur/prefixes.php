<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prefixes Operateur - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/operateur_navbar') ?>

<main class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Gestion des prefixes</h1>
            <p class="text-muted mb-0">Ajoutez, activez ou supprimez les prefixes autorises.</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="<?= base_url('operateur/prefixes/create') ?>" method="post" class="row g-3 align-items-end">
                <?= csrf_field() ?>
                <div class="col-md-5">
                    <label for="prefixe" class="form-label">Nouveau prefixe</label>
                    <input type="text" class="form-control" id="prefixe" name="prefixe" maxlength="3" placeholder="Ex: 033" required>
                </div>
                <div class="col-md-4">
                    <label for="operateur_id" class="form-label">Reseau</label>
                    <select class="form-select" id="operateur_id" name="operateur_id">
                        <option value="">Interne</option>
                        <?php foreach ($operateurs as $operateur): ?>
                            <option value="<?= esc($operateur['id']) ?>"><?= esc($operateur['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0" data-table-tools>
                <thead>
                    <tr>
                        <th>Prefixe</th>
                        <th>Reseau</th>
                        <th>Statut</th>
                        <th class="text-end no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prefixes as $p): ?>
                        <tr>
                            <td><?= esc($p['prefixe']) ?></td>
                            <td><?= esc($p['operateur_nom'] ?? 'Interne') ?></td>
                            <td>
                                <span class="badge <?= $p['actif'] ? 'text-bg-success' : 'text-bg-secondary' ?>">
                                    <?= $p['actif'] ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('operateur/prefixes/edit/' . $p['id']) ?>">
                                    Modifier
                                </a>
                                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('operateur/prefixes/toggle/' . $p['id']) ?>">
                                    <?= $p['actif'] ? 'Desactiver' : 'Activer' ?>
                                </a>
                                <a class="btn btn-sm btn-outline-danger" href="<?= base_url('operateur/prefixes/delete/' . $p['id']) ?>" onclick="return confirm('Confirmer la suppression ?')">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($prefixes)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucun prefixe enregistre.</td>
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
