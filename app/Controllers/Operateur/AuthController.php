<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    private const IDENTIFIANT = 'operateur';
    private const MOT_DE_PASSE = 'operateur123';

    public function login()
    {
        if (session()->has('operateur_connecte')) {
            return redirect()->to('/operateur/prefixes');
        }

        return view('operateur/login', [
            'identifiantDemo' => self::IDENTIFIANT,
            'motDePasseDemo'  => self::MOT_DE_PASSE,
        ]);
    }

    public function processLogin()
    {
        $identifiant = trim($this->request->getPost('identifiant') ?? '');
        $motDePasse = trim($this->request->getPost('mot_de_passe') ?? '');

        if ($identifiant !== self::IDENTIFIANT || $motDePasse !== self::MOT_DE_PASSE) {
            return redirect()->back()->withInput()->with('error', 'Identifiants operateur incorrects.');
        }

        session()->set([
            'operateur_connecte' => true,
            'operateur_nom'      => self::IDENTIFIANT,
        ]);

        return redirect()->to('/operateur/prefixes')->with('success', 'Connexion operateur reussie.');
    }

    public function logout()
    {
        session()->remove(['operateur_connecte', 'operateur_nom']);

        return redirect()->to('/operateur/login');
    }
}
