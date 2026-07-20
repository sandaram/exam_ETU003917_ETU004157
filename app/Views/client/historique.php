<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <a href="<?= base_url('client/dashboard') ?>" class="btn btn-link px-0 mb-3">Retour</a>
    <div class="card shadow-sm p-4">
        <h3 class="mb-4">Historique des operations</h3>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Operation</th>
                        <th>Contact</th>
                        <th class="text-end">Montant</th>
                        <th class="text-end">Frais</th>
                        <th class="text-end">Solde apres</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($operations)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucune operation.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($operations as $operation): ?>
                        <?php
                            $entrant = (int) ($operation['client_destinataire_id'] ?? 0) === $clientId;
                            $libelle = $entrant ? 'TRANSFERT RECU' : $operation['type_operation'];
                            $contact = $entrant ? $operation['numero_client'] : ($operation['numero_destinataire'] ?? '-');
                        ?>
                        <tr>
                            <td><?= esc($operation['date_operation']) ?></td>
                            <td><?= esc($libelle) ?></td>
                            <td><?= esc($contact) ?></td>
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
</body>
</html>
