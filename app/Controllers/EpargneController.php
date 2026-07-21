<?php

namespace App\Controllers;

use App\Models\BaremeModel;
use App\Models\TypeOperationModel;
use App\Models\OperateurModel;
use App\Models\EpargneModel;
class EpargneController extends BaseController
{
    protected $baremeModel;
    protected $typeOpModel;
    protected $operateurModel;

    public function __construct()
    {
        $this->baremeModel = new BaremeModel();
        $this->operateurModel = new OperateurModel();
        $this->typeOpModel = new TypeOperationModel();
    }
     public function indexEpargne($id_client){
        return view('client/epargne' , ['id_client' => $id_client]);
    }

    public function traitementEpargne(){
        $pourcentage = $this->request->getPost('pourcentage_epargne');
        $id_client = $this->request->getPost('id_client');

        $epargneModel = new EpargneModel();
        $verifClient=$epargneModel->findByIdLicent($id_client);

        $data = [
            'pourcentage_epargne' =>$pourcentage,
            'id_lient' => $id_client
        ];
        
        if(!empty($verifClient)){
            $epargneModel->update($data);
        }else {
            $epargneModel->save($data);
        }
       return redirect()->to('client/dashboard')->withInput()->with('modif', 'modif epargne');
    }


}