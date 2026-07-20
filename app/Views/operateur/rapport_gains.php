<h1>Situation des gains (frais perçus)</h1>

<p>Total général des frais perçus : <strong><?= number_format($total, 2) ?></strong></p>

<table class="table-rapport">
    <thead>
        <tr>
            <th>Type d'opération</th>
            <th>Nombre d'opérations</th>
            <th>Total des frais perçus</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($situations as $s): ?>
            <tr>
                <td><?= esc($s['type_operation']) ?></td>
                <td><?= esc($s['nombre_operations']) ?></td>
                <td><?= number_format($s['total_frais_percus'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($situations)): ?>
            <tr>
                <td colspan="3">Aucune opération enregistrée pour l'instant.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>