<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des gains</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>

    <h1>Situation gain via les différents frais (Telma)</h1>
    <p><a href="<?= site_url('operateur/') ?>">Retour au dashboard</a></p>

    <!-- SECTION 1 : GAINS PAR FRAIS -->
    <h2>1. Répartition des gains via les frais</h2>
    <table>
        <thead>
            <tr>
                <th>Réseau / Type de Transaction</th>
                <th>Nombre de Transactions</th>
                <th>Total des Gains / Frais (Ar)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Même Opérateur (Telma ➔ Telma)</td>
                <td><?= $gains_internes['total_transactions'] ?? 0 ?></td>
                <td><?= number_format($gains_internes['total_gains'] ?? 0, 2, ',', ' ') ?> Ar</td>
            </tr>
            <tr>
                <td>Autres Opérateurs (Telma ➔ Airtel, Orange, etc.)</td>
                <td><?= $gains_externes['total_transactions'] ?? 0 ?></td>
                <td><?= number_format($gains_externes['total_gains'] ?? 0, 2, ',', ' ') ?> Ar</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL GLOBAL DES GAINS</td>
                <td><?= ($gains_internes['total_transactions'] ?? 0) + ($gains_externes['total_transactions'] ?? 0) ?></td>
                <td><?= number_format(($gains_internes['total_gains'] ?? 0) + ($gains_externes['total_gains'] ?? 0), 2, ',', ' ') ?> Ar</td>
            </tr>
        </tbody>
    </table>

    <hr>

    <!-- SECTION 2 : COMMISSIONS À REVERSER (Ex: 2%) -->
    <h2>2. Situation des montants à envoyer à chaque autre opérateur</h2>
    <table>
        <thead>
            <tr>
                <th>Opérateur Destinataire</th>
                <th>Nombre de Transferts Envoyés</th>
                <th>Montant Total à Reverser (Ar)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($montants_a_envoyer)): ?>
                <tr>
                    <td colspan="3" class="text-center">Aucun transfert vers d'autres opérateurs enregistré.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($montants_a_envoyer as $row): ?>
                    <tr>
                        <td><?= esc($row['nom_operateur']) ?></td>
                        <td><?= $row['total_transferts'] ?></td>
                        <td><strong><?= number_format($row['total_a_reverser'], 2, ',', ' ') ?> Ar</strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <hr>

    <!-- SECTION 3 : HISTORIQUE DÉTAILLÉ -->
    <h2>3. Historique détaillé des transactions</h2>
    <table>
        <thead>
            <tr>
                <th>Date / Heure</th>
                <th>Client (Expéditeur)</th>
                <th>Type d'Opération</th>
                <th>Montant (Ar)</th>
                <th>Frais / Gain (Ar)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transactions)): ?>
                <tr>
                    <td colspan="5" class="text-center">Aucune transaction enregistrée.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= esc($t['date_transaction']) ?></td>
                        <td>
                            <strong><?= esc($t['nom_client'] ?? 'Client Inconnu') ?></strong><br>
                            <small>(<?= esc($t['numero_source'] ?? 'N/A') ?>)</small>
                        </td>
                        <td><?= esc($t['nom_operation']) ?></td>
                        <td><?= number_format($t['montant'], 2, ',', ' ') ?> Ar</td>
                        <td><strong><?= number_format($t['frais'], 2, ',', ' ') ?> Ar</strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>