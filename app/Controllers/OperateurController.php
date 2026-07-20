<?php

namespace App\Controllers;

class OperateurController extends BaseController
{
public function handleLogin()
    {
        $nom = $this->request->getPost('nom_operateur');
        $mdp = $this->request->getPost('mdp_operateur');

        // 1. Validation des champs vides
        if (empty($nom) || empty($mdp)) {
            return redirect()->back()->with('error', 'Veuillez remplir tous les champs.');
        }

        // 2. Vérification stricte des identifiants admin
        if ($nom !== 'admin' || $mdp !== 'admin') {
            return redirect()->back()->with('error', 'Identifiants administrateur incorrects.');
        }

        // 3. Stockage en session
        $session = \Config\Services::session();
        $session->set('operateur_nom', 'Administrateur');

        // Message de succès personnalisé pour la gestion globale
        return "<div style='font-family: sans-serif; text-align: center; margin-top: 100px;'>
                    <h1 style='color: #203764;'>Connexion Réussie — Espace Admin</h1>
                    <p>Bienvenue dans le panneau de gestion globale des opérateurs.</p>
                </div>";
    }

}