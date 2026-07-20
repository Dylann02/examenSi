<?php

namespace App\Models;

use CodeIgniter\Model;

class NumeroModel extends Model
{

    protected $table            = 'numero';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['numero', 'solde', 'etat', 'id_client', 'id_operateur'];
    protected $returnType       = 'array';

    
    //Tâche LOGIN 
   
    public function loginAutomatique(string $num)
    {
        $compte = $this->where('numero', $num)->first();
        if ($compte) {
            if ($compte['etat'] === 'BLOQUE') {
                return ['status' => 'bloque', 'message' => 'Ce numéro est bloqué.'];
            }
            return ['status' => 'success', 'data' => $compte];
        }

        $prefixeSaisi = substr($num, 0, 3);

        $db = \Config\Database::connect();
        $prefixeData = $db->table('prefixe')
            ->where('prefixe', $prefixeSaisi)
            ->get()
            ->getRowArray();

        if (!$prefixeData) {
            return ['status' => 'error', 'message' => 'Opérateur non supporté (préfixe invalide).'];
        }

        $clientModel = model('App\Models\ClientModel');
        $idClient = $clientModel->insert([
            'nom'    => 'Client_' . $num,
            'prenom' => 'Auto',
            'cin'    => 'TEMP_' . $num 
        ]);

        $idNumero = $this->insert([
            'numero'       => $num,
            'solde'        => 0.00,
            'etat'         => 'ACTIF',
            'id_client'    => $idClient,
            'id_operateur' => $prefixeData['id_operateur']
        ]);

        return ['status' => 'success', 'data' => $this->find($idNumero)];
    }

    // Tâche SUIVI CLIENT : Liste les comptes clients avec leurs détails et soldes
    public function getSituationComptes(?int $idOperateur = null)
    {
        $builder = $this->db->table($this->table . ' n')
            ->select('n.id as id_numero, n.numero, n.solde, n.etat, c.nom, c.prenom, c.cin, o.nom as operateur')
            ->join('client c', 'c.id = n.id_client')
            ->join('operateur o', 'o.id = n.id_operateur');

        if ($idOperateur) {
            $builder->where('n.id_operateur', $idOperateur);
        }

        return $builder->get()->getResultArray();
    }
}