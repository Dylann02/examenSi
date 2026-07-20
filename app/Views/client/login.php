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

        <!-- FORMULAIRE ADMIN / OPERATEUR -->
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