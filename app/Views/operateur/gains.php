<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gains de l'Opérateur</title>
</head>
<body>
    <h1>Gain via les différents frais (Retrait & Transfert)</h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Total des Transactions Réussies</th>
                <th>Total des Gains (Ar)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $gains['total_transactions'] ?? 0 ?></td>
                <td><strong><?= number_format($gains['total_gains'] ?? 0, 2, ',', ' ') ?> Ar</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>