<?php

namespace App\Models;

use CodeIgniter\Model;

class AdministrateurModel extends Model
{
    protected $table            = 'administrateurs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nom_utilisateur', 'mot_de_passe', 'role', 'actif'];

    /**
     * Vérifie les identifiants et retourne l'admin si valides, sinon false
     */
    public function verifierIdentifiants(string $nomUtilisateur, string $motDePasse)
    {
        $admin = $this->where('nom_utilisateur', $nomUtilisateur)
            ->where('actif', 1)
            ->first();

        if (!$admin) {
            return false;
        }

        if (!password_verify($motDePasse, $admin['mot_de_passe'])) {
            return false;
        }

        return $admin;
    }
}
