<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Accueil Operateur<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .card-module {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 24px;
        text-decoration: none;
        color: #1e293b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-module:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-color: #38bdf8;
    }

    .card-icon {
        font-size: 2rem;
        margin-bottom: 12px;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: #0f172a;
    }

    .card-description {
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.4;
    }

    .card-arrow {
        margin-top: 16px;
        font-weight: bold;
        color: #0284c7;
        font-size: 0.9rem;
    }
</style>

<h1>Espace d'Administration Opérateur</h1>
<p style="color: #64748b;">Sélectionnez un module pour accéder aux fonctionnalités de gestion.</p>

<div class="dashboard-grid">
    <!-- Module Préfixes -->
    <a href="<?= base_url('operateur/prefixes') ?>" class="card-module">
        <div>
            <div class="card-icon">📱</div>
            <div class="card-title">Gestion des Préfixes</div>
            <div class="card-description">Configurer et attribuer les préfixes téléphoniques par opérateur.</div>
        </div>
        <div class="card-arrow">Accéder →</div>
    </a>

    <!-- Module Barèmes -->
    <a href="<?= base_url('operateur/baremes') ?>" class="card-module">
        <div>
            <div class="card-title">Barèmes de Frais</div>
            <div class="card-description">Définir les tranches de montants et frais applicables par opération.</div>
        </div>
        <div class="card-arrow">Accéder →</div>
    </a>

    <!-- Module Gains -->
    <a href="<?= base_url('operateur/gains') ?>" class="card-module">
        <div>
            <div class="card-title">Gains & Revenus</div>
            <div class="card-description">Consulter le suivi et la répartition des gains générés.</div>
        </div>
        <div class="card-arrow">Accéder →</div>
    </a>

    <!-- Module Suivi Clients -->
    <a href="<?= base_url('operateur/clients') ?>" class="card-module">
        <div>
            <div class="card-title">Suivi des Clients</div>
            <div class="card-description">Consulter la liste des clients et leur historique de transactions.</div>
        </div>
        <div class="card-arrow">Accéder →</div>
    </a>
</div>

<?= $this->endSection() ?>