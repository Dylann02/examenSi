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

    // Situation des gains de l'opérateur via les frais
    public function gains()
    {
        $data['gains'] = $this->transactionModel->getGainsOperateur();
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
    public function handleLogin()
    {
        $nom = $this->request->getPost('nom_operateur');
        $mdp = $this->request->getPost('mdp_operateur');

        // 1. Validation des champs vides
        if (empty($nom) || empty($mdp)) {
            return redirect()->back()->with('error', 'Veuillez remplir tous les champs.');
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