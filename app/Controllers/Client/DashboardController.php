<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\TransactionModel;

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
            'soldeEpargne' => $client['solde_epargne'] ?? 0
            'epargnePourcentage' => $client['epargne_pourcentage'] ?? 0
        ]);
    }
    public function processEpargne(){
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }
        $pourcentage=(float)$this->request->getPost(epargne_pourcentage);
        //$clientModel=new ClientModel();
        //if(!$clientModel->mettre)
        return redirect()->to('/client/epargne')->with('success','epargne mise à jour');
    }

    public function depotForm()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        return view('client/depot');
    }

    public function processDepot()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir un montant positif.');
        }

        $transactionModel = new TransactionModel();

        if (!$transactionModel->deposer((int) session()->get('client_id'), $montant)) {
            return redirect()->back()->withInput()->with('error', 'Impossible d effectuer le depot.');
        }

        return redirect()->to('/client/dashboard')->with('success', 'Depot effectue avec succes.');
    }
}
