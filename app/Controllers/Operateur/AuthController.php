<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\AdministrateurModel;

class AuthController extends BaseController
{
    // GET /operateur/login -> Affiche la page de connexion
    public function login()
    {
        // Si déjà connecté, rediriger directement vers le tableau de bord opérateur
        if (session()->has('operateur_id')) {
            return redirect()->to('/operateur/prefixes');
        }

        return view('operateur/login', [
            'identifiantDemo'  => 'operateur_demo',
            'motDePasseDemo'   => 'password123',
        ]);
    }

    // POST /operateur/login -> Traite la tentative de connexion
    public function processLogin()
    {
        $identifiant = trim((string) $this->request->getPost('identifiant'));
        $motDePasse  = (string) $this->request->getPost('mot_de_passe');

        if (empty($identifiant) || empty($motDePasse)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez remplir tous les champs.');
        }

        $adminModel = new AdministrateurModel();
        $utilisateur = $adminModel->verifierIdentifiants($identifiant, $motDePasse);

        if (!$utilisateur) {
            return redirect()->back()->withInput()->with('error', 'Identifiant ou mot de passe incorrect.');
        }

        // On s'assure que le compte a bien le rôle "operateur"
        // -> Adapter la valeur ci-dessous si le rôle stocké en base porte un autre nom
        if (($utilisateur['role'] ?? null) !== 'operateur') {
            return redirect()->back()->withInput()->with('error', 'Ce compte n\'est pas autorisé à se connecter ici.');
        }

        session()->set([
            'operateur_id'          => $utilisateur['id'],
            'operateur_nom'         => $utilisateur['nom_utilisateur'],
            'isOperateurLoggedIn'   => true,
        ]);

        return redirect()->to('/operateur/prefixes');
    }

    // GET /operateur/logout -> Déconnexion
    public function logout()
    {
        session()->remove(['operateur_id', 'operateur_nom', 'isOperateurLoggedIn']);
        return redirect()->to('/operateur/login');
    }
}