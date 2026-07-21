<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Espace Client - Monnaie Mobile</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .solde-box {
            background: #e2f0d9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #385723;
        }

        .actions-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            flex: 1;
            background: #fafafa;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Compte Client N° : <?= esc($compte['numero']) ?></h2>
        <a href="<?= base_url('client/logout') ?>"><button>Déconnexion</button></a>
    </div>

    <!-- 1. Tâche : VOIR SOLDE -->
    <div class="solde-box">
        <h3>Mon Solde : <span
                style="font-size: 24px; color: #385723;"><?= number_format($compte['solde'], 2, ',', ' ') ?> Ar</span>
        </h3>
    </div>

    <!-- Alertes retours formulaires -->
    <?php if (session()->getFlashdata('success')): ?>
        <p class="success"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <p class="error"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <!-- 2. Tâches : ACTIONS (DEPOT / RETRAIT / TRANSFERT) -->
    <div class="actions-container">

        <!-- FORMULAIRE DEPOT -->
        <div class="card">
            <h4>Faire un Dépôt</h4>
            <form action="<?= base_url('client/action') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="depot">
                <input type="number" name="montant" placeholder="Montant en Ar" required min="1"><br><br>
                <button type="submit">Valider le dépôt</button>
            </form>
        </div>

        <!-- FORMULAIRE RETRAIT -->
        <div class="card">
            <h4>Faire un Retrait</h4>
            <form action="<?= base_url('client/action') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="retrait">
                <input type="number" name="montant" placeholder="Montant en Ar" required min="1"><br><br>
                <button type="submit">Valider le retrait</button>
            </form>
        </div>

        <!-- FORMULAIRE TRANSFERT -->
        <div class="card">
            <h4>Faire un Transfert</h4>
            <form action="<?= base_url('client/action') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="transfert">
                <input type="text" name="numero_dest" placeholder="N° du destinataire" required><br><br>
                <input type="number" name="montant" placeholder="Montant en Ar" required min="1"><br><br>
                <label>
                    <input type="checkbox" name="frais_retrait" >
                    envoyer avec frais de retrait
                </label>
                <button type="submit"
                    style="background-color: #203764; color: white; border: none; padding: 5px 10px; cursor: pointer;">Transférer</button>
            </form>
        </div>

    </div>

    <!-- 3. Tâche : VOIR HISTORIQUE -->
    <h3>Historique de mes transactions</h3>
    <table>
        <thead>
            <tr>
                <th>Date & Heure</th>
                <th>Type d'opération</th>
                <th>Montant</th>
                <th>Frais appliqués</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($historique)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Aucune opération effectuée pour le moment.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($historique as $tx): ?>
                    <tr>
                        <td><?= $tx['date_transaction'] ?></td>
                        <!-- Remplacement de $tx['type_nom'] par $tx['type_operation'] -->
                        <td><strong><?= esc($tx['type_operation']) ?></strong></td>
                        <td><?= number_format($tx['montant'], 2, ',', ' ') ?> Ar</td>
                        <td style="color: #c00000;"><?= number_format($tx['frais'], 2, ',', ' ') ?> Ar</td>
                        <td style="font-weight: bold; color: <?= $tx['statut'] === 'SUCCES' ? 'green' : 'orange' ?>;">
                            <?= $tx['statut'] ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>