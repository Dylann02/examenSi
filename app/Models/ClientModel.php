<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'client';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nom', 'prenom', 'cin'];
    protected $returnType       = 'array';
    public function getClientsParOperateur(int $idOperateur)
{
    return $this->db->table('numero n')
        ->select('n.id as id_numero, n.numero, n.solde, n.etat, c.nom, c.prenom, c.cin, op.nom as operateur')
        ->join('client c', 'c.id = n.id_client', 'left')
        ->join('operateur op', 'op.id = n.id_operateur', 'left')
        ->where('n.id_operateur', $idOperateur)
        ->orderBy('c.nom', 'ASC')
        ->get()
        ->getResultArray();
}
}