<?php

namespace App\Controllers;

use App\Models\RapportModel;

class Rapport extends BaseController
{
    protected RapportModel $rapportModel;

    public function __construct()
    {
        $this->rapportModel = new RapportModel();
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
}