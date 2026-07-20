<!-- Styles de la Sidebar -->
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        display: flex;
    }
    
    .sidebar {
        width: 240px;
        height: 100vh;
        background-color: #2c3e50;
        color: #ecf0f1;
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 20px;
        box-sizing: border-box;
    }

    .sidebar h2 {
        text-align: center;
        font-size: 1.2rem;
        margin-bottom: 30px;
        color: #3498db;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar li a {
        display: block;
        padding: 15px 20px;
        color: #ecf0f1;
        text-decoration: none;
        font-size: 0.95rem;
        transition: background 0.3s;
    }

    .sidebar li a:hover,
    .sidebar li a.active {
        background-color: #34495e;
        border-left: 4px solid #3498db;
    }

    .content-container {
        margin-left: 240px; /* Décale le contenu principal pour ne pas être masqué par la sidebar */
        padding: 20px;
        width: calc(100% - 240px);
    }
</style>

<!-- Structure de la Sidebar -->
<div class="sidebar">
    <h2>Gestion Opérateurs</h2>
    <ul>
        <li>
            <a href="<?= base_url('operateur/prefixes') ?>" class="<?= url_is('operateur/prefixes*') ? 'active' : '' ?>">
                📱 Préfixes
            </a>
        </li>
        <li>
            <a href="<?= base_url('operateur/baremes') ?>" class="<?= url_is('operateur/baremes*') ? 'active' : '' ?>">
                📊 Barèmes de Frais
            </a>
        </li>
    </ul>
</div>