<?php
namespace App\Models;
use CodeIgniter\Model;
class ProduitModel extends Model
{
    protected $table = "produit";
    protected $primaryKey ='id';
    protected $allowedFields =['nom' , 'prix'];
}
?>