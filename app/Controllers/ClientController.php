<?php

namespace App\Controllers;

use App\Models\NumeroModel;
use App\Models\TransactionModel;

class ClientController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function login()
    {
        // On supprime la redirection automatique pour forcer l'affichage du login.php
        return view('client/login');
    }

    public function handleLogin()
    {
        $num = $this->request->getPost('numero');
        if (empty($num)) {
            return redirect()->back()->with('error', 'Veuillez saisir un numéro.');
        }

        $numeroModel = model('App\Models\NumeroModel');
        $result = $numeroModel->loginAutomatique($num);

        if ($result['status'] === 'success') {
            $this->session->set('client_numero', $result['data']);
            return redirect()->to('/client/dashboard');
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/client/login');
    }

    public function dashboard()
    {
        if (!$this->session->has('client_numero')) {
            return redirect()->to('/client/login');
        }

        $sessionData = $this->session->get('client_numero');
        $numeroModel = model('App\Models\NumeroModel');
        $compte = $numeroModel->find($sessionData['id']);

        $transactionModel = model('App\Models\TransactionModel');
        $historique = $transactionModel->getHistoriqueClient($compte['id']);

        return view('client/dashboard', [
            'compte'     => $compte,
            'historique' => $historique
        ]);
    }

    public function executerAction()
    {
        if (!$this->session->has('client_numero')) {
            return redirect()->to('/client/login');
        }

        $idSource = $this->session->get('client_numero')['id'];
        $action = $this->request->getPost('action');
        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Le montant doit être supérieur à 0 Ar.');
        }

        $transactionModel = model('App\Models\TransactionModel');

        switch ($action) {
            case 'depot':
                if ($transactionModel->executerDepot($idSource, $montant)) {
                    return redirect()->back()->with('success', 'Dépôt effectué avec succès.');
                }
                break;

            case 'retrait':
                if ($transactionModel->executerRetrait($idSource, $montant)) {
                    return redirect()->back()->with('success', 'Retrait effectué avec succès.');
                }
                return redirect()->back()->with('error', 'Solde insuffisant (Frais inclus).');

            case 'transfert':
                $numDest = $this->request->getPost('numero_dest');
                if (empty($numDest)) {
                    return redirect()->back()->with('error', 'Le numéro du destinataire est requis.');
                }

                $res = $transactionModel->executerTransfert($idSource, $numDest, $montant);
                if ($res === 'success') {
                    return redirect()->back()->with('success', 'Transfert effectué avec succès.');
                } elseif ($res === 'dest_introuvable') {
                    return redirect()->back()->with('error', 'Numéro destinataire introuvable ou bloqué.');
                } elseif ($res === 'impossible_soi_meme') {
                    return redirect()->back()->with('error', 'Impossible de s\'envoyer de l\'argent à soi-même.');
                } elseif ($res === 'solde_insuffisant') {
                    return redirect()->back()->with('error', 'Solde insuffisant pour couvrir le transfert et ses frais.');
                }
                break;
        }

        return redirect()->back()->with('error', 'Une erreur inattendue est survenue.');
    }
}