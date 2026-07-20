<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gains des Opérateurs</title>
</head>
<body>
    <h1>Gain via les différents frais (Retrait & Transfert)</h1>

    <!-- Formulaire de Filtre -->
    <form method="get" action="<?= base_url('operateur/gains') ?>" style="margin-bottom: 20px;">
        <label for="id_operateur"><strong>Filtrer par Opérateur :</strong></label>
        <select name="id_operateur" id="id_operateur">
            <option value="">-- Tous les opérateurs --</option>
            <?php if (!empty($operateurs_list)) : ?>
                <?php foreach ($operateurs_list as $op) : ?>
                    <option value="<?= $op['id'] ?>" <?= (isset($selected_operateur) && $selected_operateur == $op['id']) ? 'selected' : '' ?>>
                        <?= esc($op['nom']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <button type="submit">Filtrer</button>

        <?php if (!empty($selected_operateur)) : ?>
            <a href="<?= base_url('operateur/gains') ?>">Réinitialiser</a>
        <?php endif; ?>
    </form>

    <!-- Tableau des Gains par Opérateur -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Opérateur</th>
                <th>Total des Transactions Réussies</th>
                <th>Total des Gains (Ar)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $grandTotalGains = 0;
                $grandTotalTransactions = 0;
            ?>

            <?php if (!empty($gains_par_operateur)) : ?>
                <?php foreach ($gains_par_operateur as $row) : 
                    $grandTotalGains += $row['total_gains'];
                    $grandTotalTransactions += $row['total_transactions'];
                ?>
                    <tr>
                        <td><strong><?= esc($row['nom_operateur']) ?></strong></td>
                        <td><?= number_format($row['total_transactions'], 0, ',', ' ') ?></td>
                        <td><strong><?= number_format($row['total_gains'], 2, ',', ' ') ?> Ar</strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3" style="text-align: center;">Aucune donnée disponible.</td>
                </tr>
            <?php endif; ?>
        </tbody>

        <!-- Total Global en bas de tableau -->
        <?php if (!empty($gains_par_operateur)) : ?>
            <tfoot>
                <tr style="background-color: #f0f0f0;">
                    <td><strong>TOTAL</strong></td>
                    <td><strong><?= number_format($grandTotalTransactions, 0, ',', ' ') ?></strong></td>
                    <td><strong><?= number_format($grandTotalGains, 2, ',', ' ') ?> Ar</strong></td>
                </tr>
            </tfoot>
        <?php endif; ?>
    </table>
</body>
</html>