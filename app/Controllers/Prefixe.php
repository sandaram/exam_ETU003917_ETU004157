<?php

namespace App\Controllers;

use App\Models\PrefixeModel;

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
        return view('operateur/prefixes', $data);
    }

    public function create()
    {
        $prefixe = $this->request->getPost('prefixe');

        if (!$this->prefixeModel->createPrefixe($prefixe)) {
            session()->setFlashdata('errors', $this->prefixeModel->errors());
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Préfixe ajouté avec succès.');
        return redirect()->to('/operateur/prefixes');
    }

    public function editForm($id)
    {
        $data['prefixe'] = $this->prefixeModel->find($id);

        if (!$data['prefixe']) {
            session()->setFlashdata('error', 'Préfixe introuvable.');
            return redirect()->to('/operateur/prefixes');
        }

        return view('operateur/prefixes_edit', $data);
    }

    public function update($id)
    {
        $prefixe = $this->request->getPost('prefixe');

        if (!$this->prefixeModel->updatePrefixe($id, $prefixe)) {
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