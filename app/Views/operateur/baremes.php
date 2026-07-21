<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Barèmes de Frais</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <h1>Création et modification des barèmes de frais par opérateur</h1>
    <p><a href="<?= site_url('operateur/') ?>">Retour au dashboard</a></p>

    <?php if (session()->getFlashdata('success')): ?>
        <p class="alert-success"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <p class="alert-error"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <!-- FORMULAIRE DE MODIFICATION -->
    <?php if (isset($bareme_a_modifier)): ?>
        <h3>Modifier le barème #<?= $bareme_a_modifier['id'] ?></h3>
        <form action="<?= base_url('operateur/baremes/update/' . $bareme_a_modifier['id']) ?>" method="post">
            <?= csrf_field() ?>

            <label>Opérateur :</label>
            <select name="id_operateur" required>
                <?php foreach ($operateurs as $op): ?>
                    <option value="<?= $op['id'] ?>" <?= ($op['id'] == $bareme_a_modifier['id_operateur']) ? 'selected' : '' ?>>
                        <?= esc($op['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Type Opération :</label>
            <select name="id_operation" required>
                <?php foreach ($operations as $op): ?>
                    <option value="<?= $op['id'] ?>" <?= ($op['id'] == $bareme_a_modifier['id_operation']) ? 'selected' : '' ?>>
                        <?= esc($op['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Montant Min :</label>
            <input type="number" step="0.01" name="montant_min" value="<?= esc($bareme_a_modifier['montant_min']) ?>" required>

            <label>Montant Max :</label>
            <input type="number" step="0.01" name="montant_max" value="<?= esc($bareme_a_modifier['montant_max']) ?>" required>

            <label>Frais (Ar) :</label>
            <input type="number" step="0.01" name="frais" value="<?= esc($bareme_a_modifier['frais']) ?>" required>

            <button type="submit">Mettre à jour</button>
            <a href="<?= base_url('operateur/baremes') ?>" class="btn-cancel">Annuler</a>
        </form>
        <hr>

    <!-- FORMULAIRE D'AJOUT -->
    <?php else: ?>
        <h3>Ajouter une tranche de barème</h3>
        <form action="<?= base_url('operateur/baremes/store') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id_operateur" value="1">

            <label>Type Opération :</label>
            <select name="id_operation" required>
                <?php foreach ($operations as $op): ?>
                    <option value="<?= $op['id'] ?>"><?= esc($op['nom']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Montant Min :</label>
            <input type="number" step="0.01" name="montant_min" required>

            <label>Montant Max :</label>
            <input type="number" step="0.01" name="montant_max" required>

            <label>Frais (Ar) :</label>
            <input type="number" step="0.01" name="frais" required>

            <button type="submit">Enregistrer</button>
        </form>
        <hr>
    <?php endif; ?>

    <!-- TABLEAU DES BARÈMES -->
    <h3>Barèmes actuels</h3>
    <table>
        <thead>
            <tr>
                <th>Opérateur</th>
                <th>Opération</th>
                <th>Tranche de montant (Min - Max)</th>
                <th>Frais (Ar)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($baremes)): ?>
                <?php foreach ($baremes as $b): ?>
                    <tr>
                        <td><strong><?= esc($b['operateur_nom'] ?? 'N/A') ?></strong></td>
                        <td><?= esc($b['operation_nom']) ?></td>
                        <td>De <?= number_format($b['montant_min'], 2, ',', ' ') ?> à <?= number_format($b['montant_max'], 2, ',', ' ') ?> Ar</td>
                        <td><strong><?= number_format($b['frais'], 2, ',', ' ') ?> Ar</strong></td>
                        <td>
                            <a href="<?= base_url('operateur/baremes/edit/' . $b['id']) ?>">Modifier</a> |
                            <a href="<?= base_url('operateur/baremes/delete/' . $b['id']) ?>" onclick="return confirm('Supprimer ce barème ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Aucun barème trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>