<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Monnaie Mobile - Authentification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .login-container { display: flex; gap: 50px; }
        .box { border: 1px solid #ccc; padding: 20px; borderRadius: 8px; width: 300px; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Système de Gestion - Monnaie Mobile</h2>

    <!-- Affichage des messages d'erreur globaux -->
    <?php if (session()->getFlashdata('error')): ?>
        <p class="error"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <div class="login-container">
        
        <!-- FORMULAIRE CLIENT : LOGIN AUTOMATIQUE -->
        <div class="box">
            <h3>Espace Client</h3>
            <form action="<?= base_url('client/login') ?>" method="post">
                <?= csrf_field() ?>
                <p>
                    <label for="numero">Numéro de téléphone :</label><br>
                    <input type="text" id="numero" name="numero" placeholder="Inserer votre numero" required maxlength="10">
                </p>
                <button type="submit">Se connecter</button>
            </form>
        </div>
        <!-- FORMULAIRE ADMIN / GESTION DES OPERATEURS -->
        <div class="box" style="background-color: #f9f9f9;">
            <h3>Espace Admin</h3>
            <form action="<?= base_url('operateur/handleLogin') ?>" method="post">
                <?= csrf_field() ?>
                <p>
                    <label for="nom_operateur">Identifiant :</label><br>
                    <input type="text" id="nom_operateur" name="nom_operateur" placeholder="ex: admin" required>
                </p>
                <p>
                    <label for="mot_de_passe">Mot de passe :</label><br>
                    <input type="password" id="mot_de_passe" name="mdp_operateur" placeholder="••••••••" required>
                </p>
                <button type="submit" style="background-color: #007BFF; color: white; border: none; padding: 10px; cursor: pointer; width: 100%;">
                    Se connecter
                </button>
            </form>
        </div>



    </div>

</body>
</html>