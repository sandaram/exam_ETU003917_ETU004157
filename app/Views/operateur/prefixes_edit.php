<h1>Modifier le préfixe</h1>

<?php if (session()->getFlashdata('errors')): ?>
    <ul class="message-error">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="/operateur/prefixes/update/<?= $prefixe['id'] ?>" method="post" class="form-prefixe">
    <?= csrf_field() ?>
    <label for="prefixe">Préfixe</label>
    <input type="text" id="prefixe" name="prefixe" maxlength="3"
        value="<?= esc($prefixe['prefixe']) ?>" required>
    <button type="submit">Enregistrer</button>
    <a href="/operateur/prefixes">Annuler</a>
</form>