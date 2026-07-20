<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixes_operateur';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['prefixe', 'actif', 'operateur_id'];
    protected $useTimestamps    = false;
    protected $returnType       = 'array';

    protected $validationRules = [
        'prefixe' => 'required|exact_length[3]|numeric|is_unique[prefixes_operateur.prefixe,id,{id}]',
    ];

    protected $validationMessages = [
        'prefixe' => [
            'required'     => 'Le préfixe est obligatoire.',
            'exact_length' => 'Le préfixe doit contenir exactement 3 chiffres.',
            'numeric'      => 'Le préfixe doit être numérique.',
            'is_unique'    => 'Ce préfixe existe déjà.',
        ],
    ];

    // ---------------------------------------------------------
    // CRUD
    // ---------------------------------------------------------

    // Liste tous les préfixes
    public function listAll(): array
    {
        return $this->select('prefixes_operateur.*, operateurs.nom AS operateur_nom')
            ->join('operateurs', 'operateurs.id = prefixes_operateur.operateur_id', 'left')
            ->orderBy('prefixe', 'ASC')
            ->findAll();
    }

    // Récupère uniquement les préfixes actifs
    public function getActifs(): array
    {
        return $this->where('actif', 1)->findAll();
    }

    // Crée un nouveau préfixe (actif par défaut)
    public function createPrefixe(string $prefixe): bool
    {
        return (bool) $this->save([
            'prefixe'      => $prefixe,
            'actif'        => 1,
            'operateur_id' => null,
        ]);
    }

    // Modifie un préfixe existant
    public function updatePrefixe(int $id, string $prefixe): bool
    {
        return $this->update($id, ['prefixe' => $prefixe]);
    }

    // Inverse le statut actif/inactif d'un préfixe
    public function toggleActif(int $id): bool
    {
        $prefixe = $this->find($id);

        if (!$prefixe) {
            return false;
        }

        return $this->update($id, ['actif' => $prefixe['actif'] ? 0 : 1]);
    }

    // Supprime définitivement un préfixe
    public function deletePrefixe(int $id): bool
    {
        return $this->delete($id);
    }

    // ---------------------------------------------------------
    // Logique métier réutilisable ailleurs (ex: login client)
    // ---------------------------------------------------------

    // Vérifie si un numéro de téléphone commence par un préfixe valide et actif
    public function isPrefixeValide(string $numeroTelephone): bool
    {
        $prefixe = substr($numeroTelephone, 0, 3);
        return $this->where('prefixe', $prefixe)
            ->where('actif', 1)
            ->where('operateur_id', null)
            ->first() !== null;
    }

    public function estReseauPropre(string $numeroTelephone): bool
    {
        return $this->isPrefixeValide($numeroTelephone);
    }
}
