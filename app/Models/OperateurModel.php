<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table            = 'operateurs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nom', 'commission_pct', 'actif'];

    public function ajouterPromotion(float $frais, int $idEnvoyeur) {
        $pourcentage = $this->select('operations')->where('id', $idEnvoyeur);
        $fraisResultante = $frais - $frais * $pourcentage;
        return $fraisResultante;
    }

    public function listActifs(): array
    {
        return $this->where('actif', 1)
            ->orderBy('nom', 'ASC')
            ->findAll();
    }

    public function findActif(int $id): ?array
    {
        return $this->where('id', $id)
            ->where('actif', 1)
            ->first() ?: null;
    }

    public function listAll(): array
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    public function updateOperateur(int $id, array $payload): bool
    {
        return $this->update($id, [
            'nom'            => trim((string) ($payload['nom'] ?? '')),
            'commission_pct' => (float) ($payload['commission_pct'] ?? 0),
            'actif'          => isset($payload['actif']) ? 1 : 0,
        ]);
    }

    public function trouverOperateurParNumero(string $telephone): ?array
    {
        if (strlen($telephone) < 3 || !ctype_digit($telephone)) {
            return null;
        }

        return $this->db->table('prefixes_operateur p')
            ->select('o.*, p.prefixe')
            ->join('operateurs o', 'o.id = p.operateur_id')
            ->where('p.prefixe', substr($telephone, 0, 3))
            ->where('p.actif', 1)
            ->where('o.actif', 1)
            ->get()
            ->getRowArray() ?: null;
    }

    public function calculerCommission(array $operateur, float $montant): float
    {
        return round($montant * ((float) ($operateur['commission_pct'] ?? 0) / 100), 2);
    }

    /**
     * Récupère tous les opérateurs avec leurs statistiques de commissions et d'opérations
     */
    public function listAllWithStats(): array
    {
        $operateurs = $this->listAll();
        
        foreach ($operateurs as &$operateur) {
            $stats = $this->db->table('operations o')
                ->select('COUNT(o.id) AS nb_operations, SUM(o.commission) AS total_commission')
                ->where('o.operateur_destinataire_id', $operateur['id'])
                ->where('o.commission >', 0)
                ->get()
                ->getRowArray();
            
            $operateur['nb_operations'] = (int) ($stats['nb_operations'] ?? 0);
            $operateur['total_commission'] = (float) ($stats['total_commission'] ?? 0);
        }
        
        return $operateurs;
    }

    /**
     * Récupère le total global pour tous les clients (toutes les commissions)
     */
    public function getTotalGlobal(): array
    {
        return $this->db->table('operations')
            ->select('COUNT(id) AS nb_operations_total, SUM(commission) AS total_commission_global')
            ->where('commission >', 0)
            ->get()
            ->getRowArray() ?: [
                'nb_operations_total' => 0,
                'total_commission_global' => 0,
            ];
    }
}
