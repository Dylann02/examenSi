<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Situation des Comptes Clients</title>
</head>
<body>
    <h1>Situation des comptes clients</h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Client</th>
                <th>CIN</th>
                <th>Opérateur</th>
                <th>Solde (Ar)</th>
                <th>État</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= esc($client['numero']) ?></td>
                    <td><?= esc($client['nom'] . ' ' . $client['prenom']) ?></td>
                    <td><?= esc($client['cin'] ?? 'N/A') ?></td>
                    <td><?= esc($client['operateur']) ?></td>
                    <td><?= number_format($client['solde'], 2, ',', ' ') ?> Ar</td>
                    <td><?= esc($client['etat']) ?></td>
                    <td>
                        <a href="<?= base_url('operateur/clients/historique/' . $client['id_numero']) ?>">
                            Voir l'historique
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>