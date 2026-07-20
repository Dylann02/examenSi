<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Monnaie Mobile - Authentification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .login-container { display: flex; gap: 50px; }
        .box { border: 1px solid #ccc; padding: 20px; border-radius: 8px; width: 320px; }
        .error { color: red; font-weight: bold; }
        .info-inscription { background: #fff3cd; color: #856404; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>

    <h2>Système de Gestion - Monnaie Mobile</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <p class="error"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <div class="login-container">
        
        <!-- ESPACE CLIENT -->
<!-- FORMULAIRE CLIENT : LOGIN AUTOMATIQUE -->
<div class="box">
    <h3>Espace Client</h3>
    <!-- Changement ici : on cible 'client/login' qui est bien géré par ton groupe de routes -->
    <form action="<?= base_url('client/login') ?>" method="post">
        <?= csrf_field() ?>
        
        <?php if (session()->getFlashdata('inscription_numero')): ?>
            <div class="info-inscription">
                Numéro inconnu. Veuillez remplir vos informations pour créer votre compte.
            </div>
            <input type="hidden" name="numero" value="<?= esc(session()->getFlashdata('inscription_numero')) ?>">
            <p>
                <label>Numéro sélectionné :</label><strong> <?= esc(session()->getFlashdata('inscription_numero')) ?></strong>
            </p>
            <p>
                <label for="nom">Nom :</label><br>
                <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
            </p>
            <p>
                <label for="prenom">Prénom :</label><br>
                <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
            </p>
            <p>
                <label for="cin">Numéro CIN :</label><br>
                <input type="text" id="cin" name="cin" placeholder="Votre CIN" required maxlength="20">
            </p>
            <button type="submit">Créer mon compte & Connexion</button>
            <br><br>
            <a href="<?= base_url('client/login') ?>" style="font-size: 13px;">Retour</a>

        <?php else: ?>
            <p>
                <label for="numero">Numéro de téléphone :</label><br>
                <input type="text" id="numero" name="numero" placeholder="Insérer votre numéro" required maxlength="10">
            </p>
            <button type="submit">Se connecter</button>
        <?php endif; ?>
    </form>
</div>

        <!-- ESPACE ADMIN / OPERATEUR -->
        <div class="box">
            <h3>Espace Admin</h3>
            <form action="<?= base_url('operateur/handleLogin') ?>" method="POST">
                <?= csrf_field() ?>
                <p>
                    <label>Identifiant :</label><br>
                    <input type="text" name="username" placeholder="ex: admin" required>
                </p>
                <p>
                    <label>Mot de passe :</label><br>
                    <input type="password" name="password" required>
                </p>
                <button type="submit">Se connecter</button>
            </form>
        </div>

    </div>

</body>
</html>