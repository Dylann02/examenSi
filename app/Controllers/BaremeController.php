<?php

namespace App\Controllers;

use App\Models\BaremeModel;
use App\Models\TypeOperationModel;
use App\Models\OperateurModel;

class BaremeController extends BaseController
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

    public function index()
    {
        $data = [
            'baremes' => $this->baremeModel->getBaremesWithDetails(),
            'operateurs' => $this->operateurModel->findAll(),
            'operations' => $this->typeOpModel->findAll(),
        ];

        return view('operateur/baremes', $data);
    }

    public function edit($id)
    {
        $bareme = $this->baremeModel->find($id);

        if (!$bareme) {
            return redirect()->to('/operateur/baremes')->with('error', 'Barème introuvable.');
        }

        $data = [
            'bareme_a_modifier' => $bareme,
            'baremes' => $this->baremeModel->getBaremesWithDetails(),
            'operations' => $this->typeOpModel->findAll(),
            'operateurs' => $this->operateurModel->findAll(),
        ];

        return view('operateur/baremes', $data);
    }

    public function store()
    {
        $this->baremeModel->save([
            'id_operation' => $this->request->getPost('id_operation'),
            'id_operateur' => $this->request->getPost('id_operateur'),
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais' => $this->request->getPost('frais'),
        ]);

        return redirect()->to('/operateur/baremes')->with('success', 'Tranche de barème ajoutée avec succès.');
    }

    public function update($id)
    {
        $this->baremeModel->update($id, [
            'id_operation' => $this->request->getPost('id_operation'),
            'id_operateur' => $this->request->getPost('id_operateur'),
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais' => $this->request->getPost('frais'),
        ]);

        return redirect()->to('/operateur/baremes')->with('success', 'Barème mis à jour avec succès.');
    }

    public function delete($id)
    {
        $this->baremeModel->delete($id);
        return redirect()->to('/operateur/baremes')->with('success', 'Tranche supprimée.');
    }
}