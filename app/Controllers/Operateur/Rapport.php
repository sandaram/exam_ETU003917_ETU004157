<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\RapportModel;
use App\Models\TransactionModel;

class Rapport extends BaseController
{
    protected RapportModel $rapportModel;
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->rapportModel = new RapportModel();
        $this->transactionModel = new TransactionModel();
    }

    public function gains()
    {
        $data['situations'] = $this->rapportModel->situationGains();
        $data['total']      = $this->rapportModel->totalGains();

        return view('operateur/rapport_gains', $data);
    }

    public function comptesClients()
    {
        $data['comptes']        = $this->rapportModel->situationComptesClients();
        $data['totalSoldes']    = $this->rapportModel->totalSoldesClients();
        $data['nombreClients']  = $this->rapportModel->nombreClients();

        return view('operateur/rapport_comptes', $data);
    }

    public function montantsAEnvoyer()
    {
        return view('operateur/montants_a_envoyer', [
            'lignes' => $this->transactionModel->situationMontantsAEnvoyer(),
        ]);
    }
}
