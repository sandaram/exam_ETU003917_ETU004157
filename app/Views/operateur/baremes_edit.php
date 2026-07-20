<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($bareme) ? 'Modifier' : 'Ajouter' ?> un bareme - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/operateur_navbar') ?>

<main class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h3 mb-4"><?= isset($bareme) ? 'Modifier le bareme' : 'Ajouter un bareme' ?></h1>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <div><?= esc($error) ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                    <?php endif; ?>

                    <form action="<?= isset($bareme) ? base_url('operateur/baremes/update/' . $bareme['id']) : base_url('operateur/baremes/create') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="type_operation_id" class="form-label">Type d'operation</label>
                            <select class="form-select" id="type_operation_id" name="type_operation_id" required>
                                <?php foreach ($types as $t): ?>
                                    <option value="<?= $t['id'] ?>" <?= (isset($bareme) && $bareme['type_operation_id'] == $t['id']) ? 'selected' : '' ?>>
                                        <?= esc($t['libelle']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="montant_min" class="form-label">Montant min</label>
                                <input type="number" step="0.01" class="form-control" id="montant_min" name="montant_min" value="<?= esc($bareme['montant_min'] ?? old('montant_min')) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="montant_max" class="form-label">Montant max</label>
                                <input type="number" step="0.01" class="form-control" id="montant_max" name="montant_max" value="<?= esc($bareme['montant_max'] ?? old('montant_max')) ?>" required>
                            </div>
                        </div>

                        <div class="mb-4 mt-3">
                            <label for="frais" class="form-label">Frais</label>
                            <input type="number" step="0.01" class="form-control" id="frais" name="frais" value="<?= esc($bareme['frais'] ?? old('frais')) ?>" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><?= isset($bareme) ? 'Enregistrer' : 'Ajouter' ?></button>
                            <a href="<?= base_url('operateur/baremes') ?>" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
