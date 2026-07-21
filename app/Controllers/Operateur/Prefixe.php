<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\PrefixeModel;
use App\Models\OperateurModel;

class Prefixe extends BaseController
{
    protected PrefixeModel $prefixeModel;

    public function __construct()
    {
        $this->prefixeModel = new PrefixeModel();
    }

    public function index()
    {
        $data['prefixes'] = $this->prefixeModel->listAll();
        $data['operateurs'] = (new OperateurModel())->listActifs();
        return view('operateur/prefixes', $data);
    }

    public function create()
    {
        $prefixe = $this->request->getPost('prefixe');
        $operateurId = $this->request->getPost('operateur_id');

        if (!$this->prefixeModel->createPrefixe($prefixe, $operateurId ? (int) $operateurId : null)) {
            session()->setFlashdata('errors', $this->prefixeModel->errors());
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Préfixe ajouté avec succès.');
        return redirect()->to('/operateur/prefixes');
    }

    public function editForm($id)
    {
        $data['prefixe'] = $this->prefixeModel->find($id);
        $data['operateurs'] = (new OperateurModel())->listActifs();

        if (!$data['prefixe']) {
            session()->setFlashdata('error', 'Préfixe introuvable.');
            return redirect()->to('/operateur/prefixes');
        }

        return view('operateur/prefixes_edit', $data);
    }

    public function update($id)
    {
        $prefixe = $this->request->getPost('prefixe');
        $operateurId = $this->request->getPost('operateur_id');

        if (!$this->prefixeModel->updatePrefixe($id, $prefixe, $operateurId ? (int) $operateurId : null)) {
            session()->setFlashdata('errors', $this->prefixeModel->errors());
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Préfixe modifié avec succès.');
        return redirect()->to('/operateur/prefixes');
    }

    public function toggle($id)
    {
        if (!$this->prefixeModel->toggleActif($id)) {
            session()->setFlashdata('error', 'Préfixe introuvable.');
            return redirect()->to('/operateur/prefixes');
        }

        session()->setFlashdata('success', 'Statut mis à jour.');
        return redirect()->to('/operateur/prefixes');
    }

    public function delete($id)
    {
        $this->prefixeModel->deletePrefixe($id);
        session()->setFlashdata('success', 'Préfixe supprimé.');
        return redirect()->to('/operateur/prefixes');
    }
}
