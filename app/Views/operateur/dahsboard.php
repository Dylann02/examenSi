<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Opérateur - Tableau de bord</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .dashboard-container { display: flex; flex-wrap: wrap; gap: 30px; margin-top: 20px; }
        .box { border: 1px solid #ccc; padding: 20px; border-radius: 8px; width: 250px; display: flex; flex-direction: column; justify-content: space-between; }
        .box h3 { margin-top: 0; color: #333; }
        .box p { font-size: 14px; color: #666; min-height: 40px; }
        .btn { display: block; text-align: center; text-decoration: none; padding: 10px; background-color: #007bff; color: white; border-radius: 4px; font-weight: bold; font-size: 14px; }
        .btn:hover { background-color: #0056b3; }
        .nav-bar { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-bottom: 30px; }
        .logout-link { color: #dc3545; font-weight: bold; text-decoration: none; }
        .logout-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <!-- Barre de navigation simplifiée -->
    <div class="nav-bar">
        <h2>Système de Gestion - Panel Opérateur</h2>
        <a href="<?= base_url('client/logout') ?>" class="logout-link">Déconnexion</a>
    </div>

    <p>Bienvenue dans votre espace opérateur. Choisissez une action ci-dessous :</p>

    <div class="dashboard-container">
        
        <!-- Section Gains -->
        <div class="box">
            <h3>Suivi des Gains</h3>
            <p>Consultez les statistiques financières et les gains générés.</p>
            <a href="<?= base_url('operateur/gains') ?>" class="btn">Voir les Gains</a>
        </div>

        <!-- Section Clients -->
        <div class="box">
            <h3>Gestion Clients</h3>
            <p>Suivi des comptes clients et historique des activités.</p>
            <a href="<?= base_url('operateur/clients') ?>" class="btn">Suivi Clients</a>
        </div>

        <!-- Section Préfixes -->
        <div class="box">
            <h3>CRUD Préfixes</h3>
            <p>Ajouter, modifier ou supprimer les préfixes téléphoniques.</p>
            <a href="<?= base_url('operateur/prefixes') ?>" class="btn">Gérer les Préfixes</a>
        </div>

        <!-- Section Barèmes -->
        <div class="box">
            <h3>CRUD Barèmes</h3>
            <p>Configuration et mise à jour des grilles de tarification.</p>
            <a href="<?= base_url('operateur/baremes') ?>" class="btn">Gérer les Barèmes</a>
        </div>

    </div>

</body>
</html>