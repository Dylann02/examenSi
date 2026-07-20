<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ProduitModel;
class ProduitController extends BaseController
{ 
    public function getProduit()
    {
        $model = new ProduitModel();
        $data['produit'] = $model->findAll();
        return view('produits', $data);
    }

    public function getProduitById($id)
    {
        $model = new ProduitModel();
        $dataId['produitId'] = $model->find($id);
        return view('produits', $dataId);
    }
}
?>