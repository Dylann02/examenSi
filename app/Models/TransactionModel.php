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
                ->where('UPPER(type_operation.nom)', strtoupper($typeOperation))
                ->where('bareme.id_operateur', $idOperateur) 
                ->where('bareme.montant_min <=', $montant)
                ->where('bareme.montant_max >=', $montant)
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

        $op = $db->table('type_operation')->where('UPPER(nom)', 'DEPOT')->get()->getRowArray();
        if (!$op) {
            $db->table('type_operation')->insert(['nom' => 'DEPOT']);
            $op = ['id' => $db->insertID()];
        }

        $db->table('numero')->where('id', $idNumero)->increment('solde', $montant);

        $this->save([
            'montant'               => $montant,
            'frais'                 => 0.00,
            'statut'                => 'SUCCES',
            'id_operation'          => $op['id'],
            'id_numero_destination' => $idNumero,
            'date_transaction'      => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();
        return $db->transStatus();
    }

    /**
     * Tâche ACTION : RETRAIT
     */
    public function executerRetrait(int $idNumero, float $montant)
    {
        $numeroModel = model('App\Models\NumeroModel');
        $compte = $numeroModel->find($idNumero);

        if (!$compte) {
            return false;
        }

        $frais = $this->getFrais('RETRAIT', (int)$compte['id_operateur'], $montant);
        $totalAObtenir = $montant + $frais;

        if ($compte['solde'] < $totalAObtenir) {
            return false; 
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $op = $db->table('type_operation')->where('UPPER(nom)', 'RETRAIT')->get()->getRowArray();
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
     * Tâche ACTION : TRANSFERT avec restrictions inter-opérateurs et % spécifiques
     */
/**
     * Tâche ACTION : TRANSFERT avec restrictions inter-opérateurs et sécurité barème
     */
    public function executerTransfert(int $idSource, string $numDest, float $montant)
    {
        $numeroModel = model('App\Models\NumeroModel');
        $dest = $numeroModel->where('numero', $numDest)->first();

        $db = \Config\Database::connect();

        if (!$dest) {
            $prefixeSaisi = substr($numDest, 0, 3);
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
        
        // --- VÉRIFICATION DU BARÈME (Nouveau) ---
        // On vérifie d'abord si le montant existe bien dans le barème de l'opérateur source
        $checkBareme = $db->table('bareme')
            ->join('type_operation', 'type_operation.id = bareme.id_operation')
            ->where('UPPER(type_operation.nom)', 'TRANSFERT')
            ->where('bareme.id_operateur', (int)$source['id_operateur'])
            ->where('bareme.montant_min <=', $montant)
            ->where('bareme.montant_max >=', $montant)
            ->get()
            ->getRowArray();

        if (!$checkBareme) {
            return 'montant_hors_bareme'; // Le montant est trop grand ou trop petit pour le barème
        }

        $frais = (float)$checkBareme['frais'];
        $totalADeduire = $montant + $frais;
        // ----------------------------------------

        $montantFinalDestinataire = $montant;

        // GESTION DES RESTRICTIONS ET POURCENTAGES INTER-OPÉRATEURS
        if ((int)$source['id_operateur'] !== (int)$dest['id_operateur']) {
            
            $configCommission = $db->table('commission_interoperateur')
                ->where('id_operateur_source', (int)$source['id_operateur'])
                ->where('id_operateur_dest', (int)$dest['id_operateur'])
                ->get()
                ->getRowArray();

            if (!$configCommission) {
                return 'transfert_non_autorise';
            }

            $tauxCommission = (float)$configCommission['pourcentage'];
            $commissionInter = $montant * ($tauxCommission / 100);
            
            $montantFinalDestinataire = $montant + $commissionInter;
        }

        if ($source['solde'] < $totalADeduire) {
            return 'solde_insuffisant';
        }

        $db->transStart();

        $op = $db->table('type_operation')->where('UPPER(nom)', 'TRANSFERT')->get()->getRowArray();
        if (!$op) {
            $db->table('type_operation')->insert(['nom' => 'TRANSFERT']);
            $op = ['id' => $db->insertID()];
        }

        $db->table('numero')->where('id', $idSource)->decrement('solde', $totalADeduire);
        $db->table('numero')->where('id', $dest['id'])->increment('solde', $montantFinalDestinataire);

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
            ->select('COALESCE(SUM(t.frais), 0) as total_gains, COUNT(t.id) as total_transactions')
            ->where('t.statut', 'SUCCES');

        if ($idOperateur) {
            $builder->join('numero n', 'n.id = t.id_numero_source OR n.id = t.id_numero_destination', 'left')
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

    public function getDetailGainsOperateur(int $idOperateur)
    {
        return $this->db->table('transaction_mm t')
            ->select('t.*, 
                      n.numero as numero_source, 
                      c.nom as nom_client, 
                      op.nom as nom_operation') 
            ->join('numero n', 'n.id = t.id_numero_source')
            ->join('client c', 'c.id = n.id_client', 'left')
            ->join('type_operation op', 'op.id = t.id_operation', 'left')
            ->where('n.id_operateur', $idOperateur)
            ->where('t.statut', 'SUCCES')
            ->orderBy('t.id', 'DESC')
            ->get()
            ->getResultArray();
    }
}