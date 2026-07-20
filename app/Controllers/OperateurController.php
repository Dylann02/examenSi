<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\NumeroModel;

class OperateurController extends BaseController
{
    protected $transactionModel;
    protected $numeroModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->numeroModel      = new NumeroModel();
    }

    /**
     * Page d'accueil du panel opérateur (Tableau de bord)
     */
    public function index()
    {
        // Affiche la vue avec les 4 gros boutons d'action
        return view('operateur/dahsboard');
    }

    // Situation des gains de l'opérateur via les frais
 public function gains()
{
    $selectedOperateur = $this->request->getGet('id_operateur');

    $db = \Config\Database::connect();
    
    // 1. Liste complète des opérateurs pour le <select>
    $data['operateurs_list'] = $db->table('operateur')->get()->getResultArray();

    // 2. Gains calculés par opérateur
    $data['gains_par_operateur'] = $this->transactionModel->getGainsParOperateur(
        !empty($selectedOperateur) ? (int)$selectedOperateur : null
    );

    // 3. Valeur sélectionnée pour conserver l'option dans le filtre
    $data['selected_operateur'] = $selectedOperateur;

    return view('operateur/gains', $data);
}

    // Situation globale des comptes clients
    public function suiviClients()
    {
        $data['clients'] = $this->numeroModel->getSituationComptes();
        return view('operateur/clients', $data);
    }

    // Historique complet des transactions d'un client spécifique
    public function historiqueClient($idNumero)
    {
        $data['historique'] = $this->transactionModel->getHistoriqueCompletClient($idNumero);
        $data['id_numero']  = $idNumero;
        return view('operateur/historique_client', $data);
    }

    /**
     * Gestion de la connexion Admin / Opérateur
     */
    public function handleLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Vérification brute avec tes identifiants (admin / admin)
        if ($username === 'admin' && $password === 'admin') {

            session()->set([
                'isLoggedIn' => true,
                'role'       => 'operateur'
            ]);

            // Redirection vers l'accueil du groupe 'operateur' (qui appelle la méthode index())
            return redirect()->to(base_url('operateur'));
        } else {
            return redirect()->back()->with('error', 'Identifiants incorrects');
        }

        // 2. Vérification stricte des identifiants admin
        if ($nom !== 'admin' || $mdp !== 'admin') {
            return redirect()->back()->with('error', 'Identifiants administrateur incorrects.');
        }

        // 3. Stockage en session
        $session = \Config\Services::session();
        $session->set('operateur_nom', 'Administrateur');

        // Message de succès personnalisé pour la gestion globale
        return view('operateur/dashboard');
    }
    public function dashboard()
    {
        return view('operateur/dashboard');
    }
}
