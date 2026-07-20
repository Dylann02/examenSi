<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transaction_mm';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['montant', 'frais', 'statut', 'id_operation', 'id_numero_source', 'id_numero_destination', 'date_transaction'];
    protected $returnType       = 'array';

    // ==========================================
    // 1. HISTORIQUES CLIENT
    // ==========================================

    public function getHistoriqueClient(int $idNumero)
    {
        return $this->select('transaction_mm.*, type_operation.nom as type_nom')
            ->join('type_operation', 'type_operation.id = transaction_mm.id_operation')
            ->where('id_numero_source', $idNumero)
            ->orWhere('id_numero_destination', $idNumero)
            ->orderBy('date_transaction', 'DESC')
            ->findAll();
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

    // ==========================================
    // 2. GESTION DES FRAIS
    // ==========================================

    public function getFrais(string $typeOperation, int $idOpSource, int $idOpDest = 0, float $montant = 0.0)
    {
        $db = \Config\Database::connect();

        // 1. Frais de base (Barème)
        $row = $db->table('bareme')
            ->join('type_operation', 'type_operation.id = bareme.id_operation')
            ->where('UPPER(type_operation.nom)', strtoupper($typeOperation))
            ->where('bareme.id_operateur', $idOpSource)
            ->where('bareme.montant_min <=', $montant)
            ->where('bareme.montant_max >=', $montant)
            ->get()
            ->getRowArray();

        $fraisBase = $row ? (float)$row['frais'] : 0.00;

        // 2. Ajout de la commission si inter-opérateur
        if (strtoupper($typeOperation) === 'TRANSFERT' && $idOpDest > 0 && $idOpSource !== $idOpDest) {
            $configCommission = $db->table('commission_interoperateur')
                ->where('id_operateur_source', $idOpSource)
                ->where('id_operateur_dest', $idOpDest)
                ->get()
                ->getRowArray();

            if ($configCommission && isset($configCommission['pourcentage'])) {
                $tauxCommission = (float)$configCommission['pourcentage'];
                $fraisBase += ($montant * ($tauxCommission / 100));
            }
        }

        return $fraisBase;
    }

    // ==========================================
    // 3. EXÉCUTION DES TRANSACTIONS
    // ==========================================

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
            'id_numero_source'      => null,
            'id_numero_destination' => $idNumero,
            'date_transaction'      => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();
        return $db->transStatus();
    }

    public function executerRetrait(int $idNumero, float $montant)
    {
        $numeroModel = model('App\Models\NumeroModel');
        $compte = $numeroModel->find($idNumero);

        if (!$compte) {
            return false;
        }

        $frais = $this->getFrais('RETRAIT', (int)$compte['id_operateur'], 0, $montant);
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
            'montant'               => $montant,
            'frais'                 => $frais,
            'statut'                => 'SUCCES',
            'id_operation'          => $op['id'],
            'id_numero_source'      => $idNumero,
            'id_numero_destination' => null,
            'date_transaction'      => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();
        return $db->transStatus();
    }

   public function executerTransfert($idSource, $numDest, $montant, $fraisChek)
{
    $numeroModel = new NumeroModel();
    $dest = $numeroModel->where('numero', $numDest)->first();

    $db = \Config\Database::connect();

    // 1. Initialisation par défaut
    $fraisRetrait = 0;

    // 2. Vérifications de base du destinataire
    if (!$dest) {
        return "numero_inexistant";
    }

    if ($dest['etat'] === 'BLOQUE') {
        return 'dest_introuvable';
    }

    if ($idSource === (int)$dest['id']) {
        return 'impossible_soi_meme';
    }

    $source = $numeroModel->find($idSource);
    
    // 3. Autorisation inter-opérateur (si opérateurs différents)
    if ((int)$source['id_operateur'] !== (int)$dest['id_operateur']) {
        $configCommission = $db->table('commission_interoperateur')
            ->where('id_operateur_source', (int)$source['id_operateur'])
            ->where('id_operateur_dest', (int)$dest['id_operateur'])
            ->get()
            ->getRowArray();

        if (!$configCommission) {
            return 'transfert_non_autorise';
        }
    }

    // 4. Calcul du frais d'envoi classique (Barème + Commission si inter-opérateur)
    $frais = $this->getFrais('TRANSFERT', (int)$source['id_operateur'], (int)$dest['id_operateur'], $montant);

    // 5. Calcul du frais de retrait (UNIQUEMENT si MÊME opérateur ET checkbox cochée)
    if ((int)$source['id_operateur'] === (int)$dest['id_operateur'] && !empty($fraisChek)) {
        $fraisRetrait = $this->getFrais('RETRAIT', (int)$source['id_operateur'], (int)$dest['id_operateur'], $montant);
    }

    // 6. Vérification si le montant rentre dans le barème (si frais d'envoi = 0)
    if ($frais === 0.00 && $montant > 0) {
        $checkBareme = $db->table('bareme')
            ->join('type_operation', 'type_operation.id = bareme.id_operation')
            ->where('UPPER(type_operation.nom)', 'TRANSFERT')
            ->where('bareme.id_operateur', (int)$source['id_operateur'])
            ->where('bareme.montant_min <=', $montant)
            ->where('bareme.montant_max >=', $montant)
            ->get()
            ->getRowArray();

        if (!$checkBareme) {
            return 'montant_hors_bareme';
        }
    }

    // 7. Calcul du Total complet à débiter chez l'émetteur
    $totalADeduire = $montant + $frais + $fraisRetrait;

    if ($source['solde'] < $totalADeduire) {
        return 'solde_insuffisant';
    }

    // 8. Transaction SQL
    $db->transStart();

    $op = $db->table('type_operation')->where('UPPER(nom)', 'TRANSFERT')->get()->getRowArray();
    if (!$op) {
        $db->table('type_operation')->insert(['nom' => 'TRANSFERT']);
        $op = ['id' => $db->insertID()];
    }

    // Le compte émetteur paie (Montant + Frais Envoi + Frais Retrait éventuels)
    $db->table('numero')->where('id', $idSource)->decrement('solde', $totalADeduire);
    
    // Le destinataire reçoit la somme exacte envoyée
    $db->table('numero')->where('id', $dest['id'])->increment('solde', $montant);

    // Sauvegarde avec le cumul des frais prélevés
    $this->save([
        'montant'               => $montant,
        'frais'                 => $frais + $fraisRetrait,
        'statut'                => 'SUCCES',
        'id_operation'          => $op['id'],
        'id_numero_source'      => $idSource,
        'id_numero_destination' => $dest['id'],
        'date_transaction'      => date('Y-m-d H:i:s')
    ]);

    $db->transComplete();
    return $db->transStatus() ? 'success' : 'error';
}

    // ==========================================
    // 4. STATISTIQUES ET TABLEAUX DU DASHBOARD
    // ==========================================

    /**
     * Tableau 1 (Ligne 1) : Gains MÊME OPÉRATEUR (Telma -> Telma / Retraits / Dépôts)
     */
   /**
 * 1. Gains sur transactions INTERNES (Telma vers Telma)
 */
/**
 * 1. Gains INTERNES (Telma vers Telma OU Retrait/Dépôt)
 */
public function getGainsInternesTelma(int $idTelma = 1)
{
    $builder = $this->db->table('transaction_mm t')
        ->select('COUNT(t.id) as total_transactions, COALESCE(SUM(t.frais), 0) as total_gains')
        ->join('numero ns', 'ns.id = t.id_numero_source', 'left')
        ->join('numero nd', 'nd.id = t.id_numero_destination', 'left')
        ->groupStart()
            ->where('nd.id_operateur', $idTelma)
            ->orWhere('t.id_numero_destination IS NULL')
        ->groupEnd()
        ->where('ns.id_operateur', $idTelma);

    return $builder->get()->getRowArray() ?: ['total_transactions' => 0, 'total_gains' => 0];
}

/**
 * 2. Gains EXTERNES (Telma vers autre opérateur)
 */
public function getGainsExternesTelma(int $idTelma = 1)
{
    $builder = $this->db->table('transaction_mm t')
        ->select('COUNT(t.id) as total_transactions, COALESCE(SUM(t.frais), 0) as total_gains')
        ->join('numero ns', 'ns.id = t.id_numero_source', 'left')
        ->join('numero nd', 'nd.id = t.id_numero_destination', 'left')
        ->where('ns.id_operateur', $idTelma)
        ->where('nd.id_operateur IS NOT NULL')
        ->where('nd.id_operateur !=', $idTelma);

    return $builder->get()->getRowArray() ?: ['total_transactions' => 0, 'total_gains' => 0];
}

/**
 * Tableau 2 : Montant des commissions (ex: 2%) à reverser à chaque opérateur destinataire
 */
/**
 * Tableau 2 : Calcul des commissions à reverser via la table commission_interoperateur
 */
public function getMontantsAEnvoyerParOperateur(int $idTelma = 1)
{
    return $this->db->table('transaction_mm t')
        ->select('
            op.nom as nom_operateur, 
            COUNT(t.id) as total_transferts, 
            COALESCE(SUM(t.montant * (COALESCE(c.pourcentage, 0) / 100.0)), 0) as total_a_reverser
        ')
        ->join('numero ns', 'ns.id = t.id_numero_source', 'left')
        ->join('numero nd', 'nd.id = t.id_numero_destination', 'left')
        ->join('operateur op', 'op.id = nd.id_operateur', 'left')
        ->join(
            'commission_interoperateur c', 
            'c.id_operateur_source = ns.id_operateur AND c.id_operateur_dest = nd.id_operateur', 
            'left'
        )
        ->where('ns.id_operateur', $idTelma)
        ->where('nd.id_operateur IS NOT NULL')
        ->where('nd.id_operateur !=', $idTelma)
        ->groupBy('op.id, op.nom, c.pourcentage')
        ->get()
        ->getResultArray();
}
   
    public function getDetailGainsOperateur(int $idOperateur = 1)
    {
        return $this->db->table('transaction_mm t')
            ->select('t.*, 
                      ns.numero as numero_source, 
                      c.nom as nom_client, 
                      op.nom as nom_operation') 
            ->join('numero ns', 'ns.id = t.id_numero_source', 'left')
            ->join('client c', 'c.id = ns.id_client', 'left')
            ->join('type_operation op', 'op.id = t.id_operation', 'left')
            ->groupStart()
                ->where('ns.id_operateur', $idOperateur)
                ->orWhere('t.id_numero_source IS NULL') // Inclut aussi les dépôts
            ->groupEnd()
            ->whereIn('UPPER(t.statut)', ['SUCCES', 'SUCCESS'])
            ->orderBy('t.id', 'DESC')
            ->get()
            ->getResultArray();
    }
}