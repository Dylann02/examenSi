<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Préfixes</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <h1>Configuration des préfixes valables de l'opérateur</h1>
    <p><a href="<?= site_url('operateur/') ?>">Retour au dashboard</a></p>

    <?php if (session()->getFlashdata('success')): ?>
        <p class="alert-success"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <p class="alert-error"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <!-- FORMULAIRE DE MODIFICATION -->
    <?php if (isset($prefixe_a_modifier)): ?>
        <h3>Modifier le préfixe #<?= $prefixe_a_modifier['id'] ?></h3>
        <form action="<?= base_url('operateur/prefixes/update/' . $prefixe_a_modifier['id']) ?>" method="post">
            <?= csrf_field() ?>

            <label>Préfixe (3 chiffres) :</label>
            <input type="text" name="prefixe" value="<?= esc($prefixe_a_modifier['prefixe']) ?>" maxlength="3" required>

            <label>Opérateur :</label>
            <select name="id_operateur" required>
                <?php foreach ($operateurs as $op): ?>
                    <option value="<?= $op['id'] ?>" <?= ($op['id'] == $prefixe_a_modifier['id_operateur']) ? 'selected' : '' ?>>
                        <?= esc($op['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Mettre à jour</button>
            <a href="<?= base_url('operateur/prefixes') ?>" class="btn-cancel">Annuler</a>
        </form>
        <hr>
    <?php else: ?>
        <!-- FORMULAIRE D'AJOUT -->
        <h3>Ajouter un préfixe</h3>
        <form action="<?= base_url('operateur/prefixes/store') ?>" method="post">
            <?= csrf_field() ?>

            <label>Préfixe (3 chiffres) :</label>
            <input type="text" name="prefixe" maxlength="3" required>

            <label>Opérateur :</label>
            <select name="id_operateur" required>
                <?php foreach ($operateurs as $op): ?>
                    <option value="<?= $op['id'] ?>"><?= esc($op['nom']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Ajouter</button>
        </form>
        <hr>
    <?php endif; ?>

    <!-- LISTE DES PRÉFIXES -->
    <h3>Liste des préfixes enregistrés</h3>
    <table>
        <thead>
            <tr>
                <th>Préfixe</th>
                <th>Opérateur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prefixes as $p): ?>
                <tr>
                    <td><strong><?= esc($p['prefixe']) ?></strong></td>
                    <td><?= esc($p['operateur_nom']) ?></td>
                    <td>
                        <a href="<?= base_url('operateur/prefixes/edit/' . $p['id']) ?>">Modifier</a> |
                        <a href="<?= base_url('operateur/prefixes/delete/' . $p['id']) ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>