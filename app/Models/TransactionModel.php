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
        'mode_transfert',
        'operateur_destinataire_id',
        'numero_destinataire_externe',
        'commission',
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

    public function transferer(int $clientId, string $telephoneDestinataire, float $montant, string $modeTransfert = 'interne'): array
    {
        if ($montant <= 0 || strlen($telephoneDestinataire) !== 10 || !ctype_digit($telephoneDestinataire)) {
            return ['success' => false, 'message' => 'Montant invalide ou hors barème.'];
        }

        $clientModel = new ClientModel();
        $expediteur = $clientModel->find($clientId);

        if (!$expediteur) {
            return ['success' => false, 'message' => 'Client introuvable.'];
        }

        if ($clientModel->estPrefixeValide($telephoneDestinataire)) {
            return $this->transfererInterne($clientId, $telephoneDestinataire, $montant);
        }

        return $this->transfererExterne($expediteur, $telephoneDestinataire, $montant, $modeTransfert);
    }

    private function transfererInterne(int $clientId, string $telephoneDestinataire, float $montant): array
    {
        $frais = $this->calculerFrais('TRANSFERT', $montant);
        $clientModel = new ClientModel();
        $expediteur = $clientModel->find($clientId);
        $destinataire = $clientModel->connnecterOuInscrire($telephoneDestinataire);

        if ($frais === null || !$expediteur || !$destinataire) {
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
            'mode_transfert'         => 'interne',
            'commission'             => 0,
        ]);

        $this->db->transComplete();

        return [
            'success' => $this->db->transStatus(),
            'message' => $this->db->transStatus() ? 'Transfert effectue avec succes.' : 'Echec du transfert.',
        ];
    }

    private function transfererExterne(array $expediteur, string $telephoneDestinataire, float $montant, string $modeTransfert): array
    {
        $modeTransfert = $modeTransfert === 'externe_direct' ? 'externe_direct' : 'externe_intermediaire';
        $frais = $modeTransfert === 'externe_intermediaire' ? $this->calculerFrais('TRANSFERT', $montant) : 0;
        $operateurModel = new OperateurModel();
        $operateur = $operateurModel->trouverOperateurParNumero($telephoneDestinataire);

        if (!$operateur || $frais === null) {
            return ['success' => false, 'message' => 'Operateur destinataire invalide.'];
        }

        $commission = $operateurModel->calculerCommission($operateur, $montant);
        $totalDebit = $this->calculerMontantAEnvoyer($modeTransfert, $montant, (float) $frais, $commission);

        if ((float) $expediteur['solde'] < $totalDebit) {
            return ['success' => false, 'message' => 'Solde insuffisant pour couvrir le montant, les frais et la commission.'];
        }

        $this->db->transStart();

        $soldeExpediteur = (float) $expediteur['solde'] - $totalDebit;
        (new ClientModel())->update((int) $expediteur['id'], ['solde' => $soldeExpediteur]);

        $this->insert([
            'client_id'                     => $expediteur['id'],
            'client_destinataire_id'        => null,
            'type_operation_id'             => $this->typeOperationId('TRANSFERT'),
            'montant'                       => $montant,
            'frais'                         => $frais,
            'solde_apres'                   => $soldeExpediteur,
            'mode_transfert'                => $modeTransfert,
            'operateur_destinataire_id'     => $operateur['id'],
            'numero_destinataire_externe'   => $telephoneDestinataire,
            'commission'                    => $commission,
        ]);

        $this->db->transComplete();

        return [
            'success' => $this->db->transStatus(),
            'message' => $this->db->transStatus() ? 'Transfert externe enregistre avec succes.' : 'Echec du transfert externe.',
        ];
    }

    public function historiqueClient(int $clientId): array
    {
        return $this->db->table('operations o')
            ->select(
                'o.*, t.code AS type_operation, c.numero_telephone AS numero_client, ' .
                'd.numero_telephone AS numero_destinataire, op.nom AS operateur_externe'
            )
            ->join('types_operation t', 't.id = o.type_operation_id')
            ->join('clients c', 'c.id = o.client_id')
            ->join('clients d', 'd.id = o.client_destinataire_id', 'left')
            ->join('operateurs op', 'op.id = o.operateur_destinataire_id', 'left')
            ->groupStart()
            ->where('o.client_id', $clientId)
            ->orWhere('o.client_destinataire_id', $clientId)
            ->groupEnd()
            ->orderBy('o.date_operation', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function calculerMontantAEnvoyer(string $modeTransfert, float $montant, float $frais, float $commission): float
    {
        if ($modeTransfert === 'externe_direct') {
            return $montant + $commission;
        }

        return $montant + $frais + $commission;
    }

    public function situationMontantsAEnvoyer(): array
    {
        $rows = $this->db->table('operations o')
            ->select(
                'op.nom AS operateur, o.mode_transfert, COUNT(o.id) AS nombre_operations, ' .
                'SUM(o.montant) AS total_montant, SUM(o.frais) AS total_frais, SUM(o.commission) AS total_commission'
            )
            ->join('operateurs op', 'op.id = o.operateur_destinataire_id')
            ->whereIn('o.mode_transfert', ['externe_intermediaire', 'externe_direct'])
            ->groupBy('op.nom, o.mode_transfert')
            ->orderBy('op.nom', 'ASC')
            ->orderBy('o.mode_transfert', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['total_a_envoyer'] = $this->calculerMontantAEnvoyer(
                $row['mode_transfert'],
                (float) $row['total_montant'],
                (float) $row['total_frais'],
                (float) $row['total_commission']
            );
        }

        return $rows;
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
