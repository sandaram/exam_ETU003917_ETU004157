<h1>Barèmes de frais par tranche</h1>

<?php if (session()->getFlashdata('success')): ?>
    <p class="message-success"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <p class="message-error"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<a href="/operateur/baremes/create">Ajouter une tranche</a>

<table class="table-baremes">
    <thead>
        <tr>
            <th>Type d'opération</th>
            <th>Montant min</th>
            <th>Montant max</th>
            <th>Frais</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($baremes as $b): ?>
            <tr>
                <td><?= esc($b['libelle']) ?></td>
                <td><?= esc($b['montant_min']) ?></td>
                <td><?= esc($b['montant_max']) ?></td>
                <td><?= esc($b['frais']) ?></td>
                <td>
                    <a href="/operateur/baremes/edit/<?= $b['id'] ?>">Modifier</a>
                    <a href="/operateur/baremes/delete/<?= $b['id'] ?>"
                        onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($baremes)): ?>
            <tr>
                <td colspan="5">Aucun barème enregistré.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>