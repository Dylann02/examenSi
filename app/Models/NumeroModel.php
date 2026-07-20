<?php

namespace App\Models;

use CodeIgniter\Model;


class NumeroModel extends Model
{
    protected $table            = 'numero';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['numero', 'solde', 'etat', 'id_client', 'id_operateur'];
    protected $returnType       = 'array';

    /**
     * Tâche LOGIN : Vérifie le numéro et l'inscrit automatiquement s'il est valide
     */
    public function loginAutomatique(string $num)
    {
        // 1. Vérifier si le numéro existe déjà
        $compte = $this->where('numero', $num)->first();
        if ($compte) {
            if ($compte['etat'] === 'BLOQUE') {
                return ['status' => 'bloque', 'message' => 'Ce numéro est bloqué.'];
            }
            return ['status' => 'success', 'data' => $compte];
        }

        // 2. Si le numéro n'existe pas, extraire le préfixe (les 3 premiers chiffres)
        $prefixeSaisi = substr($num, 0, 3);
        
        $db = \Config\Database::connect();
        $prefixeData = $db->table('prefixe')
                          ->where('prefixe', $prefixeSaisi)
                          ->get()
                          ->getRowArray();

        if (!$prefixeData) {
            return ['status' => 'error', 'message' => 'Opérateur non supporté (préfixe invalide).'];
        }

        // 3. Création automatique du Client
        $clientModel = new ClientModel();
        $idClient = $clientModel->insert([
            'nom'    => 'Client_' . $num,
            'prenom' => 'Auto',
            'cin'    => null // Optionnel au début
        ]);

        // 4. Création du Numéro associé
        $idNumero = $this->insert([
            'numero'       => $num,
            'solde'        => 0.00,
            'etat'         => 'ACTIF',
            'id_client'    => $idClient,
            'id_operateur' => $prefixeData['id_operateur']
        ]);

        return ['status' => 'success', 'data' => $this->find($idNumero)];
    }
}