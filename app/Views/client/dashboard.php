<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Client - Tableau de bord</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f6f9;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 1.5rem;
            color: #2c3e50;
        }

        .phone-number {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .balance-card {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            text-align: center;
        }

        .balance-card span {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        .balance-card .amount {
            font-size: 2.2rem;
            font-weight: bold;
            margin-top: 10px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .action-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .action-card.full-width {
            grid-column: span 2;
        }

        .action-icon {
            font-size: 1.8rem;
            display: block;
            margin-bottom: 8px;
        }

        .logout-btn {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #e74c3c;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .logout-btn {
    display: inline-block;
    padding: 10px 20px;
    color: #e74c3c;
    background-color: #fdf2f2;
    border: 1px solid #f8d7da;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.2s, color 0.2s;
}

.logout-btn:hover {
    background-color: #e74c3c;
    color: #ffffff;
}
    </style>
</head>
<body>

<div class="container">
    <?php if (session()->getFlashdata('success')): ?>
        <div style="background:#d1fae5;color:#065f46;padding:12px;border-radius:8px;margin-bottom:16px;">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <!-- En-tête -->
    <div class="header">
        <div>
            <h1>Mon Espace Client</h1>
            <p class="phone-number">📱 <?= esc(session()->get('client_phone') ?? 'Numéro client') ?></p>
        </div>
    </div>

    <!-- Carte Solde -->
    <div class="balance-card">
        <span>Solde disponible</span>
        <div class="amount"><?= number_format($solde ?? 0, 0, ',', ' ') ?> Ar</div>
    </div>

    <!-- Actions rapides -->
    <div class="actions-grid">
        <a href="<?= base_url('client/depot') ?>" class="action-card">
            <span class="action-icon">➕</span>
            Faire un Dépôt
        </a>

        <a href="<?= base_url('client/retrait') ?>" class="action-card">
            <span class="action-icon">➖</span>
            Faire un Retrait
        </a>

        <a href="<?= base_url('client/transfert') ?>" class="action-card">
            <span class="action-icon">📲</span>
            Faire un Transfert
        </a>

        <a href="<?= base_url('client/historique') ?>" class="action-card">
            <span class="action-icon">📜</span>
            Historique
        </a>
    </div>

    <!-- Déconnexion -->
    <a href="<?= base_url('client/logout') ?>" class="logout-btn">Se déconnecter</a>
</div>

</body>
</html>
