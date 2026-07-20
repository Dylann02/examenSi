<?php

namespace App\Controllers;

use App\Models\PrefixeModel;
use App\Models\OperateurModel;

class PrefixeController extends BaseController
{
    protected $prefixeModel;
    protected $operateurModel;

    public function __construct()
    {
        $this->prefixeModel   = new PrefixeModel();
        $this->operateurModel = new OperateurModel();
    }

    // Liste des préfixes et formulaire d'ajout
    public function index()
    {
        $data = [
            'prefixes'   => $this->prefixeModel->getPrefixesWithOperateurs(),
            'operateurs' => $this->operateurModel->findAll(),
        ];

        return view('operateur/prefixes', $data);
    }

    // Charger le formulaire de modification avec les données
    public function edit($id)
    {
        $prefixe = $this->prefixeModel->find($id);

        if (!$prefixe) {
            return redirect()->to('/operateur/prefixes')->with('error', 'Préfixe introuvable.');
        }

        $data = [
            'prefixe_a_modifier' => $prefixe,
            'prefixes'           => $this->prefixeModel->getPrefixesWithOperateurs(),
            'operateurs'         => $this->operateurModel->findAll(),
        ];

        return view('operateur/prefixes', $data);
    }

    // Enregistrer un nouveau préfixe
    public function store()
    {
        $rules = [
            'prefixe'      => 'required|exact_length[3]|is_unique[prefixe.prefixe]',
            'id_operateur' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->prefixeModel->save([
            'prefixe'      => $this->request->getPost('prefixe'),
            'id_operateur' => $this->request->getPost('id_operateur'),
        ]);

        return redirect()->to('/operateur/prefixes')->with('success', 'Préfixe ajouté avec succès.');
    }

    //  Mettre à jour un préfixe existant
    public function update($id)
    {
        $rules = [
            'prefixe'      => "required|exact_length[3]|is_unique[prefixe.prefixe,id,{$id}]",
            'id_operateur' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->prefixeModel->update($id, [
            'prefixe'      => $this->request->getPost('prefixe'),
            'id_operateur' => $this->request->getPost('id_operateur'),
        ]);

        return redirect()->to('/operateur/prefixes')->with('success', 'Préfixe mis à jour avec succès.');
    }

    // Supprimer un préfixe
    public function delete($id)
    {
        if ($this->prefixeModel->find($id)) {
            $this->prefixeModel->delete($id);
            return redirect()->to('/operateur/prefixes')->with('success', 'Préfixe supprimé.');
        }

        return redirect()->to('/operateur/prefixes')->with('error', 'Préfixe introuvable.');
    }
}