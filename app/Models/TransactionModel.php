<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table         = 'operations';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'client_id',
        'client_destinataire_id',
        'type_operation_id',
        'montant',
        'frais',
        'solde_apres',
        'date_operation',
    ];

    public function deposer(int $clientId, float $montant): bool
    {
        if ($montant <= 0) {
            return false;
        }

        $this->db->transStart();

        $clientModel = new ClientModel();
        $client = $clientModel->find($clientId);

        if (!$client) {
            $this->db->transRollback();
            return false;
        }

        $nouveauSolde = (float) $client['solde'] + $montant;
        $clientModel->update($clientId, ['solde' => $nouveauSolde]);

        $this->insert([
            'client_id'         => $clientId,
            'type_operation_id' => $this->typeOperationId('DEPOT'),
            'montant'           => $montant,
            'frais'             => 0,
            'solde_apres'       => $nouveauSolde,
        ]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function retirer(int $clientId, float $montant): array
    {
        $frais = $this->calculerFrais('RETRAIT', $montant);

        if ($montant <= 0 || $frais === null) {
            return ['success' => false, 'message' => 'Montant invalide ou hors barème.'];
        }

        $clientModel = new ClientModel();
        $client = $clientModel->find($clientId);

        if (!$client) {
            return ['success' => false, 'message' => 'Client introuvable.'];
        }

        $totalDebit = $montant + $frais;
        if ((float) $client['solde'] < $totalDebit) {
            return ['success' => false, 'message' => 'Solde insuffisant pour couvrir le montant et les frais.'];
        }

        $this->db->transStart();

        $nouveauSolde = (float) $client['solde'] - $totalDebit;
        $clientModel->update($clientId, ['solde' => $nouveauSolde]);

        $this->insert([
            'client_id'         => $clientId,
            'type_operation_id' => $this->typeOperationId('RETRAIT'),
            'montant'           => $montant,
            'frais'             => $frais,
            'solde_apres'       => $nouveauSolde,
        ]);

        $this->db->transComplete();

        return [
            'success' => $this->db->transStatus(),
            'message' => $this->db->transStatus() ? 'Retrait effectue avec succes.' : 'Echec du retrait.',
        ];
    }

    public function transferer(int $clientId, string $telephoneDestinataire, float $montant): array
    {
        $frais = $this->calculerFrais('TRANSFERT', $montant);

        if ($montant <= 0 || $frais === null) {
            return ['success' => false, 'message' => 'Montant invalide ou hors barème.'];
        }

        $clientModel = new ClientModel();
        $expediteur = $clientModel->find($clientId);
        $destinataire = $clientModel->connnecterOuInscrire($telephoneDestinataire);

        if (!$expediteur || !$destinataire) {
            return ['success' => false, 'message' => 'Destinataire invalide.'];
        }

        if ((int) $destinataire['id'] === $clientId) {
            return ['success' => false, 'message' => 'Impossible de transferer vers le meme numero.'];
        }

        $totalDebit = $montant + $frais;
        if ((float) $expediteur['solde'] < $totalDebit) {
            return ['success' => false, 'message' => 'Solde insuffisant pour couvrir le montant et les frais.'];
        }

        $this->db->transStart();

        $soldeExpediteur = (float) $expediteur['solde'] - $totalDebit;
        $soldeDestinataire = (float) $destinataire['solde'] + $montant;

        $clientModel->update($clientId, ['solde' => $soldeExpediteur]);
        $clientModel->update((int) $destinataire['id'], ['solde' => $soldeDestinataire]);

        $this->insert([
            'client_id'              => $clientId,
            'client_destinataire_id' => $destinataire['id'],
            'type_operation_id'      => $this->typeOperationId('TRANSFERT'),
            'montant'                => $montant,
            'frais'                  => $frais,
            'solde_apres'            => $soldeExpediteur,
        ]);

        $this->db->transComplete();

        return [
            'success' => $this->db->transStatus(),
            'message' => $this->db->transStatus() ? 'Transfert effectue avec succes.' : 'Echec du transfert.',
        ];
    }

    public function historiqueClient(int $clientId): array
    {
        return $this->db->table('operations o')
            ->select(
                'o.*, t.code AS type_operation, c.numero_telephone AS numero_client, ' .
                'd.numero_telephone AS numero_destinataire'
            )
            ->join('types_operation t', 't.id = o.type_operation_id')
            ->join('clients c', 'c.id = o.client_id')
            ->join('clients d', 'd.id = o.client_destinataire_id', 'left')
            ->groupStart()
            ->where('o.client_id', $clientId)
            ->orWhere('o.client_destinataire_id', $clientId)
            ->groupEnd()
            ->orderBy('o.date_operation', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function calculerFrais(string $codeOperation, float $montant): ?float
    {
        if ($montant <= 0) {
            return null;
        }

        $typeId = $this->typeOperationId($codeOperation);
        $baremeModel = new BaremeFraisModel();

        return $baremeModel->calculerFrais($typeId, $montant);
    }

    private function typeOperationId(string $code): int
    {
        $type = (new TypeOperationModel())->findByCode($code);

        if (!$type) {
            throw new \RuntimeException('Type operation introuvable: ' . $code);
        }

        return (int) $type['id'];
    }
}
