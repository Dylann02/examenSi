<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transaction_mm';
    protected $primaryKey       = 'id';
    protected $allowedFields = ['montant', 'frais', 'statut', 'id_operation', 'id_numero_source', 'id_numero_destination', 'date_transaction'];
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

        // Récupérer l'ID de l'opération ou le créer s'il manque
        $op = $db->table('type_operation')->where('nom', 'DEPOT')->get()->getRowArray();
        if (!$op) {
            $db->table('type_operation')->insert(['nom' => 'DEPOT']);
            $op = ['id' => $db->insertID()];
        }

        // Mettre à jour le solde du numéro
        $db->table('numero')->where('id', $idNumero)->increment('solde', $montant);

        // Enregistrer la transaction en ajoutant explicitement la date
        $this->save([
            'montant'               => $montant,
            'frais'                 => 0,
            'statut'                => 'SUCCES',
            'id_operation'          => $op['id'],
            'id_numero_destination' => $idNumero,
            'date_transaction'      => date('Y-m-d H:i:s') // Force la date si la BDD ne le fait pas toute seule
        ]);

        $db->transComplete();
        return $db->transStatus();
    }


    /**
     * Tâche ACTION : RETRAIT (Automatique avec calcul des frais)
     */
    public function executerRetrait(int $idNumero, float $montant)
    {
        $frais = $this->getFrais('RETRAIT', $montant);
        $totalAObtenir = $montant + $frais;

        $numeroModel = model('App\Models\NumeroModel');
        $compte = $numeroModel->find($idNumero);

        if (!$compte || $compte['solde'] < $totalAObtenir) {
            return false; // Solde insuffisant pour le retrait + les frais
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Récupérer l'ID de l'opération ou le créer s'il manque
        $op = $db->table('type_operation')->where('nom', 'RETRAIT')->get()->getRowArray();
        if (!$op) {
            $db->table('type_operation')->insert(['nom' => 'RETRAIT']);
            $op = ['id' => $db->insertID()];
        }

        // Déduire le montant global (Montant + Frais)
        $db->table('numero')->where('id', $idNumero)->decrement('solde', $totalAObtenir);

        // Enregistrer la transaction avec la date forcée
        $this->save([
            'montant'          => $montant,
            'frais'            => $frais,
            'statut'           => 'SUCCES',
            'id_operation'     => $op['id'],
            'id_numero_source' => $idNumero,
            'date_transaction' => date('Y-m-d H:i:s') // Force la date actuelle
        ]);

        $db->transComplete();
        return $db->transStatus();
    }

    /**
     * Tâche ACTION : TRANSFERT (Avec inscription automatique du destinataire si inconnu)
     */
    public function executerTransfert(int $idSource, string $numDest, float $montant)
    {
        $numeroModel = model('App\Models\NumeroModel');

        // 1. Chercher si le destinataire existe déjà
        $dest = $numeroModel->where('numero', $numDest)->first();

        // 2. S'il n'existe pas, on le répertorie automatiquement à la volée !
        if (!$dest) {
            $prefixeSaisi = substr($numDest, 0, 3);
            $db = \Config\Database::connect();
            $prefixeData = $db->table('prefixe')->where('prefixe', $prefixeSaisi)->get()->getRowArray();

            if (!$prefixeData) {
                return 'dest_introuvable'; // Opérateur non géré par le système
            }

            // Création automatique du Client destinataire
            $clientModel = model('App\Models\ClientModel');
            $idClientDest = $clientModel->insert([
                'nom'    => 'Client_' . $numDest,
                'prenom' => 'Auto_Transfert',
                'cin'    => 'TEMP_' . $numDest
            ]);

            // Création du Numéro avec un solde initial à 0.00 (il sera incrémenté juste après)
            $idNumeroDest = $numeroModel->insert([
                'numero'       => $numDest,
                'solde'        => 0.00,
                'etat'         => 'ACTIF',
                'id_client'    => $idClientDest,
                'id_operateur' => $prefixeData['id_operateur']
            ]);

            // On recharge le destinataire tout juste créé pour la suite du script
            $dest = $numeroModel->find($idNumeroDest);
        }

        // 3. Vérifications de sécurité standard
        if ($dest['etat'] === 'BLOQUE') {
            return 'dest_introuvable';
        }

        if ($idSource === (int)$dest['id']) {
            return 'impossible_soi_meme';
        }

        $frais = $this->getFrais('TRANSFERT', $montant);
        $totalADeduire = $montant + $frais;

        $source = $numeroModel->find($idSource);
        if ($source['solde'] < $totalADeduire) {
            return 'solde_insuffisant';
        }

        // 4. Exécution de la transaction monétaire
        $db = \Config\Database::connect();
        $db->transStart();

        $op = $db->table('type_operation')->where('nom', 'TRANSFERT')->get()->getRowArray();
        if (!$op) {
            $db->table('type_operation')->insert(['nom' => 'TRANSFERT']);
            $op = ['id' => $db->insertID()];
        }

        // Mouvements de soldes
        $db->table('numero')->where('id', $idSource)->decrement('solde', $totalADeduire);
        $db->table('numero')->where('id', $dest['id'])->increment('solde', $montant);

        // Enregistrer l'historique de transaction
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

    // Tâche GAIN : Calcule le total des frais perçus (Retrait et Transfert)
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

    // Tâche HISTORIQUE CLIENT : Récupère les transactions d'un client précis pour l'opérateur
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
