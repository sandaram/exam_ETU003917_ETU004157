<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <div class="form-text">Le destinataire est cree automatiquement si son prefixe est valide.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Envoyer</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
