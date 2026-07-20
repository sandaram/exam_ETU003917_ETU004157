<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\ClientModel;

class AuthController extends BaseController
{
    // GET /client/login -> Affiche la page de connexion
    public function login()
    {
        // Si déjà connecté, rediriger directement vers le dashboard
        if (session()->has('client_id')) {
            return redirect()->to('/client/dashboard');
        }

        return view('client/login');
    }

    // POST /client/login -> Traite la tentative de connexion
    // POST /client/login -> Traite la tentative de connexion
public function processLogin()
{
    $telephone = trim($this->request->getPost('telephone') ?? '');

    if (empty($telephone)) {
        return redirect()->back()->with('error', 'Veuillez saisir un numéro de téléphone.');
    }

    $clientModel = new ClientModel();
    $client = $clientModel->connnecterOuInscrire($telephone);

    if (!$client) {
        return redirect()->back()->with('error', 'Numéro invalide. Le préfixe n\'est pas autorisé par l\'opérateur.');
    }

    // Sauvegarde des informations en session (en utilisant numero_telephone)
    session()->set([
        'client_id'        => $client['id'],
        'client_telephone' => $client['numero_telephone'],
        'client_phone'     => $client['numero_telephone'], // Pour le dashboard.php
        'isLoggedIn'       => true
    ]);

    return redirect()->to('/client/dashboard');
}

    // GET /client/logout -> Déconnexion
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/client/login');
    }
}