<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Opérateur - Tableau de bord</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>

    <div class="nav-bar">
        <h2>Système de Gestion - Panel Opérateur</h2>
        <a href="<?= base_url('client/logout') ?>" class="logout-link">Déconnexion</a>
    </div>

    <p>Bienvenue dans votre espace opérateur. Choisissez une action ci-dessous :</p>

    <div class="dashboard-container">
        
        <div class="box">
            <h3>Suivi des Gains</h3>
            <p>Consultez les statistiques financières et les gains générés.</p>
            <a href="<?= base_url('operateur/gains') ?>" class="btn">Voir les Gains</a>
        </div>

        <div class="box">
            <h3>Gestion Clients</h3>
            <p>Suivi des comptes clients et historique des activités.</p>
            <a href="<?= base_url('operateur/clients') ?>" class="btn">Suivi Clients</a>
        </div>

        <div class="box">
            <h3>CRUD Préfixes</h3>
            <p>Ajouter, modifier ou supprimer les préfixes téléphoniques.</p>
            <a href="<?= base_url('operateur/prefixes') ?>" class="btn">Gérer les Préfixes</a>
        </div>

        <div class="box">
            <h3>CRUD Barèmes</h3>
            <p>Configuration et mise à jour des grilles de tarification.</p>
            <a href="<?= base_url('operateur/baremes') ?>" class="btn">Gérer les Barèmes</a>
        </div>

    </div>

</body>
</html>