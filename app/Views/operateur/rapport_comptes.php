<h1>Situation des comptes clients</h1>

<p>Nombre de clients : <strong><?= $nombreClients ?></strong></p>
<p>Total des soldes clients : <strong><?= number_format($totalSoldes, 2) ?></strong></p>

<table class="table-rapport">
    <thead>
        <tr>
            <th>Numéro de téléphone</th>
            <th>Solde</th>
            <th>Date de création</th>
            <th>Nombre d'opérations</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($comptes as $c): ?>
            <tr>
                <td><?= esc($c['numero_telephone']) ?></td>
                <td><?= number_format($c['solde'], 2) ?></td>
                <td><?= esc($c['date_creation']) ?></td>
                <td><?= esc($c['nombre_operations']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($comptes)): ?>
            <tr>
                <td colspan="4">Aucun client enregistré pour l'instant.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>