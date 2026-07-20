<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - Mobile Money</title>
    <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
<?= view('partials/client_navbar') ?>
<div class="container py-5">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4">Historique des operations</h3>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Operation</th>
                        <th>Sens</th>
                        <th>Numero</th>
                        <th class="text-end">Montant</th>
                        <th class="text-end">Frais</th>
                        <th class="text-end">Solde apres</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($operations)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Aucune operation.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($operations as $operation): ?>
                        <?php
                            $estTransfert = $operation['type_operation'] === 'TRANSFERT';
                            $entrant = $estTransfert && (int) ($operation['client_destinataire_id'] ?? 0) === $clientId;
                            $sens = '-';
                            $numero = '-';

                            if ($estTransfert) {
                                $sens = $entrant ? 'Recu de' : 'Envoye a';
                                $numero = $entrant ? $operation['numero_client'] : ($operation['numero_destinataire'] ?? '-');
                            }
                        ?>
                        <tr>
                            <td><?= esc($operation['date_operation']) ?></td>
                            <td><?= esc($operation['type_operation']) ?></td>
                            <td>
                                <?php if ($estTransfert): ?>
                                    <span class="badge <?= $entrant ? 'text-bg-success' : 'text-bg-primary' ?>"><?= esc($sens) ?></span>
                                <?php else: ?>
                                    <?= esc($sens) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($numero) ?></td>
                            <td class="text-end"><?= number_format((float) $operation['montant'], 0, ',', ' ') ?> Ar</td>
                            <td class="text-end"><?= $entrant ? '-' : number_format((float) $operation['frais'], 0, ',', ' ') . ' Ar' ?></td>
                            <td class="text-end"><?= $entrant ? '-' : number_format((float) $operation['solde_apres'], 0, ',', ' ') . ' Ar' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
