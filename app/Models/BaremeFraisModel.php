<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table            = 'baremes_frais';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['type_operation_id', 'montant_min', 'montant_max', 'frais'];
    protected $useTimestamps    = false;
    protected $returnType       = 'array';

    protected $validationRules = [
        'type_operation_id' => 'required|integer',
        'montant_min'       => 'required|numeric|greater_than_equal_to[0]',
        'montant_max'       => 'required|numeric|greater_than[montant_min]',
        'frais'             => 'required|numeric|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'montant_max' => [
            'greater_than' => 'Le montant max doit être supérieur au montant min.',
        ],
    ];

    // ---------------------------------------------------------
    // CRUD
    // ---------------------------------------------------------

    // Liste tous les barèmes, avec le libellé du type d'opération
    public function listAll(): array
    {
        return $this->select('baremes_frais.*, types_operation.libelle, types_operation.code')
            ->join('types_operation', 'types_operation.id = baremes_frais.type_operation_id')
            ->orderBy('type_operation_id', 'ASC')
            ->orderBy('montant_min', 'ASC')
            ->findAll();
    }

    // Liste les barèmes pour un type d'opération donné (ex: pour affichage/formulaire)
    public function listByType(int $typeOperationId): array
    {
        return $this->where('type_operation_id', $typeOperationId)
            ->orderBy('montant_min', 'ASC')
            ->findAll();
    }

    // Crée une nouvelle tranche
    public function createBareme(array $data): bool
    {
        return (bool) $this->save($data);
    }

    // Modifie une tranche existante
    public function updateBareme(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    // Supprime une tranche
    public function deleteBareme(int $id): bool
    {
        return $this->delete($id);
    }

    // ---------------------------------------------------------
    // Logique métier réutilisable ailleurs (ex: lors d'un retrait/transfert)
    // ---------------------------------------------------------

    // Calcule les frais applicables pour un montant et un type d'opération donné
    // $typeOperationId : id dans la table types_operation
    public function calculerFrais(int $typeOperationId, float $montant): ?float
    {
        $bareme = $this->where('type_operation_id', $typeOperationId)
            ->where('montant_min <=', $montant)
            ->where('montant_max >=', $montant)
            ->first();

        return $bareme ? (float) $bareme['frais'] : null;
    }

    // Vérifie qu'il n'existe pas de chevauchement entre tranches pour un même type d'opération
    // (utile en validation avant create/update pour garder un barème cohérent)
    public function tranchesChevauchent(int $typeOperationId, float $min, float $max, ?int $excludeId = null): bool
    {
        $builder = $this->where('type_operation_id', $typeOperationId)
            ->groupStart()
            ->where('montant_min <=', $max)
            ->where('montant_max >=', $min)
            ->groupEnd();

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }
}
