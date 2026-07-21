<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?= base_url('client/epargne') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="montant" class="form-label">pourcentage</label>
                <input type="number" min="1" step="1" class="form-control" id="montant" name="epargne" value="<?= old('montant') ?>" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100">Valider</button>
        </form>
</body>
</html>