<h1><?= isset($bareme) ? 'Modifier le barème' : 'Ajouter un barème' ?></h1>

<?php if (session()->getFlashdata('errors')): ?>
    <ul class="message-error">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="<?= isset($bareme)
                    ? '/operateur/baremes/update/' . $bareme['id']
                    : '/operateur/baremes/create' ?>"
    method="post" class="form-bareme">
    <?= csrf_field() ?>

    <label for="type_operation_id">Type d'opération</label>
    <select id="type_operation_id" name="type_operation_id" required>
        <?php foreach ($types as $t): ?>
            <option value="<?= $t['id'] ?>"
                <?= (isset($bareme) && $bareme['type_operation_id'] == $t['id']) ? 'selected' : '' ?>>
                <?= esc($t['libelle']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="montant_min">Montant min</label>
    <input type="number" step="0.01" id="montant_min" name="montant_min"
        value="<?= esc($bareme['montant_min'] ?? '') ?>" required>

    <label for="montant_max">Montant max</label>
    <input type="number" step="0.01" id="montant_max" name="montant_max"
        value="<?= esc($bareme['montant_max'] ?? '') ?>" required>

    <label for="frais">Frais</label>
    <input type="number" step="0.01" id="frais" name="frais"
        value="<?= esc($bareme['frais'] ?? '') ?>" required>

    <button type="submit"><?= isset($bareme) ? 'Enregistrer' : 'Ajouter' ?></button>
    <a href="/operateur/baremes">Annuler</a>
</form>