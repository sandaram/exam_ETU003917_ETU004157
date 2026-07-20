<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\ClientModel;

class DashboardController extends BaseController
{
    public function index()
    {
        // Sécurité : vérifier si l'utilisateur est connecté
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $clientModel = new ClientModel();
        $client = $clientModel->find(session()->get('client_id'));

        // Transmettre le solde à la vue dashboard.php
        return view('client/dashboard', [
            'solde' => $client['solde'] ?? 0
        ]);
    }
}