<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique du Client</title>
</head>
<body>
    <h1>Historique des transactions du compte #<?= esc($id_numero) ?></h1>
    <a href="<?= site_url('operateur/') ?>">retour au dashboard</a>
    <a href="<?= base_url('operateur/clients') ?>">← Retour au suivi des clients</a>
    <br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Type Opération</th>
                <th>Source</th>
                <th>Destination</th>
                <th>Montant (Ar)</th>
                <th>Frais (Ar)</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($historique)): ?>
                <tr>
                    <td colspan="8">Aucune transaction trouvée pour ce client.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($historique as $tx): ?>
                    <tr>
                        <td><?= $tx['id'] ?></td>
                        <td><?= $tx['date_transaction'] ?></td>
                        <td><?= esc($tx['type_operation']) ?></td>
                        <td><?= esc($tx['source'] ?? '-') ?></td>
                        <td><?= esc($tx['destination'] ?? '-') ?></td>
                        <td><?= number_format($tx['montant'], 2, ',', ' ') ?> Ar</td>
                        <td><?= number_format($tx['frais'], 2, ',', ' ') ?> Ar</td>
                        <td><?= esc($tx['statut']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>