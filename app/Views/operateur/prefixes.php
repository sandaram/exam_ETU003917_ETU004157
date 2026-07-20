<h1>Gestion des préfixes opérateur</h1>

<?php if (session()->getFlashdata('success')): ?>
    <p class="message-success"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <p class="message-error"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <ul class="message-error">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="/operateur/prefixes/create" method="post" class="form-prefixe">
    <?= csrf_field() ?>
    <label for="prefixe">Nouveau préfixe</label>
    <input type="text" id="prefixe" name="prefixe" maxlength="3" placeholder="Ex: 033" required>
    <button type="submit">Ajouter</button>
</form>

<table class="table-prefixes">
    <thead>
        <tr>
            <th>Préfixe</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($prefixes as $p): ?>
            <tr>
                <td><?= esc($p['prefixe']) ?></td>
                <td><?= $p['actif'] ? 'Actif' : 'Inactif' ?></td>
                <td>
                    <a href="/operateur/prefixes/toggle/<?= $p['id'] ?>">
                        <?= $p['actif'] ? 'Désactiver' : 'Activer' ?>
                    </a>
                    <a href="/operateur/prefixes/delete/<?= $p['id'] ?>"
                        onclick="return confirm('Confirmer la suppression ?')">
                        Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($prefixes)): ?>
            <tr>
                <td colspan="3">Aucun préfixe enregistré.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>