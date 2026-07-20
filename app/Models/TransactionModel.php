<?php
namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transaction_mm';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['montant', 'frais', 'statut', 'id_operation', 'id_numero_source', 'id_numero_destination', 'date_transaction'];
    protected $returnType       = 'array';

    // Tâche HISTORIQUE : Récupère toutes les transactions impliquant le numéro connecté
    public function getHistoriqueClient(int $idNumero)
    {
        return $this->select('transaction_mm.*, type_operation.nom as type_nom')
            ->join('type_operation', 'type_operation.id = transaction_mm.id_operation')
            ->where('id_numero_source', $idNumero)
            ->orWhere('id_numero_destination', $idNumero)
            ->orderBy('date_transaction', 'DESC')
            ->findAll();
    }

    // Récupère le frais exact depuis la table bareme pour UN opérateur donné
    public function getFrais(string $typeOperation, int $idOperateur, float $montant)
    {
        $db = \Config\Database::connect();
        $row = $db->table('bareme')
                ->join('type_operation', 'type_operation.id = bareme.id_operation')
                ->where('type_operation.nom', $typeOperation)
                ->where('bareme.id_operateur', $idOperateur) // <-- Filtrer par opérateur
                ->where('montant_min <=', $montant)
                ->where('montant_max >=', $montant)
                ->get()
                ->getRowArray();
                
        return $row ? (float)$row['frais'] : 0.00;
    }

    /**
     * Tâche ACTION : DEPOT (Automatique)
     */
    public function executerDepot(int $idNumero, float $montant)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $op = $db->table('type_operation')->where('nom', 'DEPOT')->get()->getRowArray();
        if (!$op) {
            $db->table('type_operation')->insert(['nom' => 'DEPOT']);
            $op = ['id' => $db->insertID()];
        }

        $db->table('numero')->where('id', $idNumero)->increment('solde', $montant);

        $this->save([
            'montant'               => $montant,
            'frais'                 => 0,
            'statut'                => 'SUCCES',
            'id_operation'          => $op['id'],
            'id_numero_destination' => $idNumero,
            'date_transaction'      => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();
        return $db->transStatus();
    }

    /**
     * Tâche ACTION : RETRAIT (Corrigé avec passage de l'id_operateur)
     */
    public function executerRetrait(int $idNumero, float $montant)
    {
        // 1. Récupérer d'abord le compte pour connaître son id_operateur
        $numeroModel = model('App\Models\NumeroModel');
        $compte = $numeroModel->find($idNumero);

        if (!$compte) {
            return false;
        }

        // 2. Calculer les frais en transmettant bien les 3 arguments requis
        $frais = $this->getFrais('RETRAIT', (int)$compte['id_operateur'], $montant);
        $totalAObtenir = $montant + $frais;

        if ($compte['solde'] < $totalAObtenir) {
            return false; // Solde insuffisant pour le retrait + les frais
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $op = $db->table('type_operation')->where('nom', 'RETRAIT')->get()->getRowArray();
        if (!$op) {
            $db->table('type_operation')->insert(['nom' => 'RETRAIT']);
            $op = ['id' => $db->insertID()];
        }

        $db->table('numero')->where('id', $idNumero)->decrement('solde', $totalAObtenir);

        $this->save([
            'montant'          => $montant,
            'frais'            => $frais,
            'statut'           => 'SUCCES',
            'id_operation'     => $op['id'],
            'id_numero_source' => $idNumero,
            'date_transaction' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();
        return $db->transStatus();
    }

    /**
     * Tâche ACTION : TRANSFERT (Corrigé avec passage de l'id_operateur)
     */
    public function executerTransfert(int $idSource, string $numDest, float $montant)
    {
        $numeroModel = model('App\Models\NumeroModel');

        $dest = $numeroModel->where('numero', $numDest)->first();

        if (!$dest) {
            $prefixeSaisi = substr($numDest, 0, 3);
            $db = \Config\Database::connect();
            $prefixeData = $db->table('prefixe')->where('prefixe', $prefixeSaisi)->get()->getRowArray();

            if (!$prefixeData) {
                return 'dest_introuvable';
            }

            $clientModel = model('App\Models\ClientModel');
            $idClientDest = $clientModel->insert([
                'nom'    => 'Client_' . $numDest,
                'prenom' => 'Auto_Transfert',
                'cin'    => 'TEMP_' . $numDest
            ]);

            $idNumeroDest = $numeroModel->insert([
                'numero'       => $numDest,
                'solde'        => 0.00,
                'etat'         => 'ACTIF',
                'id_client'    => $idClientDest,
                'id_operateur' => $prefixeData['id_operateur']
            ]);

            $dest = $numeroModel->find($idNumeroDest);
        }

        if ($dest['etat'] === 'BLOQUE') {
            return 'dest_introuvable';
        }

        if ($idSource === (int)$dest['id']) {
            return 'impossible_soi_meme';
        }

        $source = $numeroModel->find($idSource);
        
        // Correction ici aussi : Passage de l'id_operateur de la source pour les frais de transfert
        $frais = $this->getFrais('TRANSFERT', (int)$source['id_operateur'], $montant);
        $totalADeduire = $montant + $frais;

        if ($source['solde'] < $totalADeduire) {
            return 'solde_insuffisant';
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $op = $db->table('type_operation')->where('nom', 'TRANSFERT')->get()->getRowArray();
        if (!$op) {
            $db->table('type_operation')->insert(['nom' => 'TRANSFERT']);
            $op = ['id' => $db->insertID()];
        }

        $db->table('numero')->where('id', $idSource)->decrement('solde', $totalADeduire);
        $db->table('numero')->where('id', $dest['id'])->increment('solde', $montant);

        $this->save([
            'montant'               => $montant,
            'frais'                 => $frais,
            'statut'                => 'SUCCES',
            'id_operation'          => $op['id'],
            'id_numero_source'      => $idSource,
            'id_numero_destination' => $dest['id'],
            'date_transaction'      => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();
        return $db->transStatus() ? 'success' : 'error';
    }

    public function getGainsOperateur(?int $idOperateur = null)
    {
        $builder = $this->db->table($this->table . ' t')
            ->select('SUM(t.frais) as total_gains, COUNT(t.id) as total_transactions')
            ->where('t.statut', 'SUCCES');

        if ($idOperateur) {
            $builder->join('numero n', 'n.id = t.id_numero_source')
                    ->where('n.id_operateur', $idOperateur);
        }

        return $builder->get()->getRowArray();
    }

    public function getHistoriqueCompletClient(int $idNumero)
    {
        return $this->db->table($this->table . ' t')
            ->select('t.*, top.nom as type_operation, ns.numero as source, nd.numero as destination')
            ->join('type_operation top', 'top.id = t.id_operation')
            ->join('numero ns', 'ns.id = t.id_numero_source', 'left')
            ->join('numero nd', 'nd.id = t.id_numero_destination', 'left')
            ->groupStart()
                ->where('t.id_numero_source', $idNumero)
                ->orWhere('t.id_numero_destination', $idNumero)
            ->groupEnd()
            ->orderBy('t.date_transaction', 'DESC')
            ->get()
            ->getResultArray();
    }
}