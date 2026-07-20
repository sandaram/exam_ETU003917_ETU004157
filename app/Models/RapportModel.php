<?php

namespace App\Models;

use CodeIgniter\Model;

class RapportModel extends Model
{
    // Ce modèle n'écrit jamais, il lit uniquement les vues SQL
    protected $table         = 'vue_situation_gains'; // table par défaut, non utilisée directement
    protected $primaryKey    = 'id';
    protected $useTimestamps = false;
    protected $returnType    = 'array';

    // ---------------------------------------------------------
    // Situation des gains via les frais (retrait et transfert)
    // ---------------------------------------------------------

    public function situationGains(): array
    {
        return $this->db->table('vue_situation_gains')->get()->getResultArray();
    }

    // Total général des frais perçus, toutes opérations confondues
    public function totalGains(): float
    {
        $result = $this->db->table('vue_situation_gains')
                            ->selectSum('total_frais_percus')
                            ->get()
                            ->getRowArray();

        return (float) ($result['total_frais_percus'] ?? 0);
    }

    // Gains filtrés sur une période (utile si vous ajoutez un filtre de dates plus tard)
    public function situationGainsParPeriode(string $dateDebut, string $dateFin): array
    {
        return $this->db->table('operations o')
                         ->select('t.code AS type_operation, COUNT(o.id) AS nombre_operations, SUM(o.frais) AS total_frais_percus')
                         ->join('types_operation t', 't.id = o.type_operation_id')
                         ->whereIn('t.code', ['RETRAIT', 'TRANSFERT'])
                         ->where('o.date_operation >=', $dateDebut)
                         ->where('o.date_operation <=', $dateFin)
                         ->groupBy('t.code')
                         ->get()
                         ->getResultArray();
    }

    // ---------------------------------------------------------
    // Situation des comptes clients
    // ---------------------------------------------------------

    public function situationComptesClients(): array
    {
        return $this->db->table('vue_situation_comptes_clients')->get()->getResultArray();
    }

    // Solde total de tous les comptes clients (vue d'ensemble opérateur)
    public function totalSoldesClients(): float
    {
        $result = $this->db->table('vue_situation_comptes_clients')
                            ->selectSum('solde')
                            ->get()
                            ->getRowArray();

        return (float) ($result['solde'] ?? 0);
    }

    // Nombre total de clients enregistrés
    public function nombreClients(): int
    {
        return $this->db->table('vue_situation_comptes_clients')->countAllResults();
    }
}