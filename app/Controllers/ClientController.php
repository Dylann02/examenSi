<?php

namespace App\Controllers;
use App\Models\TransactionModel;
use App\Models\NumeroModel;
use App\Models\ClientModel;

class ClientController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function login()
    {
        return view('client/login');
    }

    public function handleLogin()
    {
        $num = $this->request->getPost('numero');
        if (empty($num)) {
            return redirect()->back()->with('error', 'Veuillez saisir un numéro.');
        }

        $prefixeSaisi = substr($num, 0, 3);
        $db = \Config\Database::connect();
        $prefixeData = $db->table('prefixe')->where('prefixe', $prefixeSaisi)->get()->getRowArray();

        if (!$prefixeData || (int)$prefixeData['id_operateur'] !== 1) {
            return redirect()->to(base_url('client/login'))->with('error', 'Seuls les clients Telma peuvent utiliser cette application.');
        }

        $numeroModel = model('App\Models\NumeroModel');
        $compte = $numeroModel->where('numero', $num)->first();

        if ($compte) {
            if ($compte['etat'] === 'BLOQUE') {
                return redirect()->back()->with('error', 'Ce numéro est bloqué.');
            }
            $this->session->set('client_numero', $compte);
            return redirect()->to(base_url('client/dashboard'));
        }

        $nom = $this->request->getPost('nom');
        if (empty($nom)) {
            return redirect()->back()->withInput()->with('inscription_numero', $num);
        }

        $prenom = $this->request->getPost('prenom');
        $cin = $this->request->getPost('cin');

        $clientModel = model('App\Models\ClientModel');
        if ($clientModel->where('cin', $cin)->first()) {
            return redirect()->back()->withInput()->with('inscription_numero', $num)->with('error', 'Ce CIN est déjà enregistré.');
        }

        $db->transStart();
        $idClient = $clientModel->insert(['nom' => $nom, 'prenom' => $prenom, 'cin' => $cin]);
        $idNumero = $numeroModel->insert([
            'numero'       => $num,
            'solde'        => 0.00,
            'etat'         => 'ACTIF',
            'id_client'    => $idClient,
            'id_operateur' => 1
        ]);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('client/login'))->with('error', 'Erreur d\'inscription.');
        }

        $this->session->set('client_numero', $numeroModel->find($idNumero));
        return redirect()->to(base_url('client/dashboard'));
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('client/login'));
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
    
        $historique = $transactionModel->getHistoriqueCompletClient($compte['id']);

        return view('client/dashboard', [
            'compte'     => $compte,
            'historique' => $historique
        ]);
    }

    // AJOUT ICI : Gestion des actions Soumises depuis le Dashboard (Dépôt / Retrait / Transfert)
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

        $transactionModel = new TransactionModel();

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
                    return redirect()->back()->with('error', 'Numéro destinataire introuvable ou opérateur non supporté.');
                } elseif ($res === 'impossible_soi_meme') {
                    return redirect()->back()->with('error', 'Impossible de s\'envoyer de l\'argent à soi-même.');
                } elseif ($res === 'solde_insuffisant') {
                    return redirect()->back()->with('error', 'Solde insuffisant pour couvrir le transfert et ses frais.');
                } elseif ($res === 'transfert_non_autorise') {
                    // Capter l'interdiction entre les opérateurs non autorisés
                    return redirect()->back()->with('error', 'Les transferts directs entre ces deux opérateurs ne sont pas autorisés.');
                }
                break;
        }

        return redirect()->back()->with('error', 'Une erreur inattendue est survenue.');
    }
}