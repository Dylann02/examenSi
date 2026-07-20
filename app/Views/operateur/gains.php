<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail des Gains - Telma</title>
</head>
<body>
    <h1>Historique des Gains et Frais - Telma</h1>
    <a href="<?= site_url('operateur/') ?>">retour au dashboard</a>
    <!-- Tableau détaillé des transactions -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Date / Heure</th>
                <th>Client (Expéditeur)</th>
                <th>Type d'Opération</th>
                <th>Montant de la Transaction</th>
                <th>Frais / Gain (Ar)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $totalGains = 0;
                $totalTransactions = 0;
            ?>

            <?php if (!empty($transactions)) : ?>
                <?php foreach ($transactions as $t) : 
                    $totalGains += $t['frais'];
                    $totalTransactions++;
                ?>
                    <tr>
                        <td><?= esc($t['date_transaction'] ?? $t['created_at'] ?? 'N/A') ?></td>
                        <td>
                            <strong><?= esc($t['nom_client'] ?? 'Client') ?></strong><br>
                            <small>(<?= esc($t['numero_source']) ?>)</small>
                        </td>
                        <td><?= esc($t['nom_operation'] ?? 'N/A') ?></td>
                        <td><?= number_format($t['montant'], 2, ',', ' ') ?> Ar</td>
                        <td><strong><?= number_format($t['frais'], 2, ',', ' ') ?> Ar</strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Aucune transaction enregistrée pour Telma.</td>
                </tr>
            <?php endif; ?>
        </tbody>

        <!-- Total résumé en bas -->
        <?php if (!empty($transactions)) : ?>
            <tfoot>
                <tr style="background-color: #f0f0f0;">
                    <td colspan="3"><strong>TOTAL (<?= $totalTransactions ?> transactions)</strong></td>
                    <td colspan="2"><strong><?= number_format($totalGains, 2, ',', ' ') ?> Ar</strong></td>
                </tr>
            </tfoot>
        <?php endif; ?>
    </table>
</body>
</html>