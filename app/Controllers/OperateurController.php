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
}