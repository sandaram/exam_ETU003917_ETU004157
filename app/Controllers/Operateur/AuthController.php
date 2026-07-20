<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\AdministrateurModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->has('operateur_connecte')) {
            return redirect()->to('/operateur/prefixes');
        }

        return view('operateur/login', [
            'identifiantDemo' => 'admin',
            'motDePasseDemo'  => 'admin123',
        ]);
    }

    public function processLogin()
    {
        $identifiant = trim($this->request->getPost('identifiant') ?? '');
        $motDePasse = trim($this->request->getPost('mot_de_passe') ?? '');

        if ($identifiant === '' || $motDePasse === '') {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir vos identifiants.');
        }

        $administrateurModel = new AdministrateurModel();
        $administrateur = $administrateurModel->verifierIdentifiants($identifiant, $motDePasse);

        if (!$administrateur) {
            return redirect()->back()->withInput()->with('error', 'Identifiants operateur incorrects.');
        }

        session()->set([
            'operateur_connecte' => true,
            'operateur_id'       => $administrateur['id'],
            'operateur_nom'      => $administrateur['nom_utilisateur'],
            'operateur_role'     => $administrateur['role'],
        ]);

        return redirect()->to('/operateur/prefixes')->with('success', 'Connexion operateur reussie.');
    }

    public function logout()
    {
        session()->remove(['operateur_connecte', 'operateur_id', 'operateur_nom', 'operateur_role']);

        return redirect()->to('/operateur/login');
    }
}
