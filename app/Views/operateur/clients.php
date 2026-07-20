<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients Telma</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <h1>Situation des comptes clients - Telma</h1>
    <p><a href="<?= site_url('operateur/') ?>">Retour au dashboard</a></p>

    <table>
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
            <?php if (!empty($clients)) : ?>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= esc($client['numero']) ?></td>
                        <td><?= esc($client['nom'] . ' ' . $client['prenom']) ?></td>
                        <td><?= esc($client['cin'] ?? 'N/A') ?></td>
                        <td><strong><?= esc($client['operateur']) ?></strong></td>
                        <td><strong><?= number_format($client['solde'], 2, ',', ' ') ?> Ar</strong></td>
                        <td><?= esc($client['etat']) ?></td>
                        <td>
                            <a href="<?= base_url('operateur/clients/historique/' . $client['id_numero']) ?>">
                                Voir l'historique
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center">Aucun client Telma trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>