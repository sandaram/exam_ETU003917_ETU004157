<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use App\Models\TypeOperationModel;

class BaremeFrais extends BaseController
{
    protected BaremeFraisModel $baremeModel;
    protected TypeOperationModel $typeOperationModel;

    public function __construct()
    {
        $this->baremeModel = new BaremeFraisModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index()
    {
        $data['baremes'] = $this->baremeModel->listAll();
        return view('operateur/baremes', $data);
    }

    public function createForm()
    {
        $data['types'] = $this->typeOperationModel->listAll();
        return view('operateur/baremes_edit', $data);
    }

    public function create()
    {
        $payload = [
            'type_operation_id' => $this->request->getPost('type_operation_id'),
            'montant_min'       => $this->request->getPost('montant_min'),
            'montant_max'       => $this->request->getPost('montant_max'),
            'frais'             => $this->request->getPost('frais'),
        ];

        if ($this->baremeModel->tranchesChevauchent(
            (int) $payload['type_operation_id'],
            (float) $payload['montant_min'],
            (float) $payload['montant_max']
        )) {
            session()->setFlashdata('error', 'Cette tranche chevauche une tranche existante.');
            return redirect()->back()->withInput();
        }

        if (!$this->baremeModel->createBareme($payload)) {
            session()->setFlashdata('errors', $this->baremeModel->errors());
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Barème ajouté avec succès.');
        return redirect()->to('/operateur/baremes');
    }

    public function editForm($id)
    {
        $data['bareme'] = $this->baremeModel->find($id);
        $data['types']  = $this->typeOperationModel->listAll();

        if (!$data['bareme']) {
            session()->setFlashdata('error', 'Barème introuvable.');
            return redirect()->to('/operateur/baremes');
        }

        return view('operateur/baremes_edit', $data);
    }

    public function update($id)
    {
        $payload = [
            'type_operation_id' => $this->request->getPost('type_operation_id'),
            'montant_min'       => $this->request->getPost('montant_min'),
            'montant_max'       => $this->request->getPost('montant_max'),
            'frais'             => $this->request->getPost('frais'),
        ];

        if ($this->baremeModel->tranchesChevauchent(
            (int) $payload['type_operation_id'],
            (float) $payload['montant_min'],
            (float) $payload['montant_max'],
            (int) $id
        )) {
            session()->setFlashdata('error', 'Cette tranche chevauche une tranche existante.');
            return redirect()->back()->withInput();
        }

        if (!$this->baremeModel->updateBareme($id, $payload)) {
            session()->setFlashdata('errors', $this->baremeModel->errors());
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Barème modifié avec succès.');
        return redirect()->to('/operateur/baremes');
    }

    public function delete($id)
    {
        $this->baremeModel->deleteBareme($id);
        session()->setFlashdata('success', 'Barème supprimé.');
        return redirect()->to('/operateur/baremes');
    }
}