<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/client_navbar') ?>
<div class="container py-5" style="max-width: 520px;">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4">Faire un transfert</h3>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('client/transfert') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="telephone_destinataire" class="form-label">Numero destinataire</label>
                <input type="text" class="form-control" id="telephone_destinataire" name="telephone_destinataire" value="<?= old('telephone_destinataire') ?>" placeholder="Ex: 0372345678" required autofocus>
            </div>
            <div class="mb-3">
                <label for="montant" class="form-label">Montant</label>
                <input type="number" min="1" step="1" class="form-control" id="montant" name="montant" value="<?= old('montant') ?>" required>
                <div class="form-text">Si le prefixe est interne, le destinataire est cree automatiquement. Si le prefixe est externe, le transfert est comptabilise pour l'autre operateur.</div>
            </div>
            <div class="mb-4">
                <label for="mode_transfert" class="form-label">Cas de transfert externe</label>
                <select class="form-select" id="mode_transfert" name="mode_transfert">
                    <option value="externe_intermediaire" <?= old('mode_transfert') === 'externe_intermediaire' ? 'selected' : '' ?>>Via notre operateur : montant + frais + commission</option>
                    <option value="externe_direct" <?= old('mode_transfert') === 'externe_direct' ? 'selected' : '' ?>>Direct vers l'autre operateur : montant + commission</option>
                </select>
                <div class="form-text">Ce choix est ignore pour un transfert interne.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Envoyer</button>
        </form>
    </div>
</div>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
