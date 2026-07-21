<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\OperateurModel;

class Operateur extends BaseController
{
    protected OperateurModel $operateurModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
    }

    public function index()
    {
        return view('operateur/operateurs', [
            'operateurs' => $this->operateurModel->listAllWithStats(),
            'totalGlobal' => $this->operateurModel->getTotalGlobal(),
        ]);
    }

    public function update($id)
    {
        if (!$this->operateurModel->find((int) $id)) {
            session()->setFlashdata('error', 'Operateur introuvable.');
            return redirect()->to('/operateur/operateurs');
        }

        if (!$this->operateurModel->updateOperateur((int) $id, $this->request->getPost())) {
            session()->setFlashdata('error', 'Impossible de modifier cet operateur.');
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Operateur modifie avec succes.');
        return redirect()->to('/operateur/operateurs');
    }
}
