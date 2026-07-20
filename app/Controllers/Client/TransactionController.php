<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\OperateurModel;
use App\Models\TransactionModel;

class TransactionController extends BaseController
{
    private TransactionModel $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function retraitForm()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        return view('client/retrait');
    }

    public function processRetrait()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $montant = (float) $this->request->getPost('montant');
        $result = $this->transactionModel->retirer((int) session()->get('client_id'), $montant);

        if (!$result['success']) {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        return redirect()->to('/client/dashboard')->with('success', $result['message']);
    }

    public function transfertForm()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        return view('client/transfert', [
            'operateurs'      => (new OperateurModel())->listActifs(),
            'baremesTransfert' => $this->transactionModel->baremesPour('TRANSFERT'),
            'baremesRetrait'   => $this->transactionModel->baremesPour('RETRAIT'),
        ]);
    }

    public function processTransfert()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        $telephone = trim($this->request->getPost('telephone_destinataire') ?? '');
        $montant = (float) $this->request->getPost('montant');
        $modeTransfert = $this->request->getPost('mode_transfert') ?? 'interne';
        $typeEnvoi = $this->request->getPost('type_envoi') ?? 'simple';
        $operateurDestinataireId = $this->request->getPost('operateur_destinataire_id');
        $inclureFraisRetrait = (bool) $this->request->getPost('inclure_frais_retrait');

        if ($typeEnvoi === 'multiple') {
            $telephones = preg_split('/[\s,;]+/', $this->request->getPost('telephones_destinataires') ?? '', -1, PREG_SPLIT_NO_EMPTY);
            $result = $this->transactionModel->transfererMultiple(
                (int) session()->get('client_id'),
                $telephones ?: [],
                $montant,
                $inclureFraisRetrait
            );

            if (!$result['success']) {
                return redirect()->back()->withInput()->with('error', $result['message']);
            }

            return redirect()->to('/client/dashboard')->with('success', $result['message']);
        }

        if ($telephone === '') {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir le numero destinataire.');
        }

        $result = $this->transactionModel->transferer(
            (int) session()->get('client_id'),
            $telephone,
            $montant,
            $modeTransfert,
            $operateurDestinataireId ? (int) $operateurDestinataireId : null,
            $inclureFraisRetrait
        );

        if (!$result['success']) {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        return redirect()->to('/client/dashboard')->with('success', $result['message']);
    }

    public function historique()
    {
        if (!session()->has('client_id')) {
            return redirect()->to('/client/login');
        }

        return view('client/historique', [
            'operations' => $this->transactionModel->historiqueClient((int) session()->get('client_id')),
            'clientId'   => (int) session()->get('client_id'),
        ]);
    }
}
