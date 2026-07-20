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

    public function transferer(
        int $clientId,
        string $telephoneDestinataire,
        float $montant,
        string $modeTransfert = 'interne',
        ?int $operateurDestinataireId = null,
        bool $inclureFraisRetrait = false
    ): array
    {
        if ($montant <= 0 || strlen($telephoneDestinataire) !== 10 || !ctype_digit($telephoneDestinataire)) {
            return ['success' => false, 'message' => 'Montant invalide ou hors barème.'];
        }

        $clientModel = new ClientModel();
        $expediteur = $clientModel->find($clientId);

        if (!$expediteur) {
            return ['success' => false, 'message' => 'Client introuvable.'];
        }

        if ($modeTransfert !== 'autre_operateur') {
            return $this->transfererInterne($clientId, $telephoneDestinataire, $montant, $inclureFraisRetrait);
        }

        return $this->transfererExterne($expediteur, $telephoneDestinataire, $montant, $operateurDestinataireId);
    }

    private function transfererInterne(int $clientId, string $telephoneDestinataire, float $montant, bool $inclureFraisRetrait = false): array
    {
        $frais = $this->calculerFrais('TRANSFERT', $montant);
        $fraisRetrait = $inclureFraisRetrait ? $this->calculerFrais('RETRAIT', $montant) : 0;
        $clientModel = new ClientModel();
        $expediteur = $clientModel->find($clientId);
        $destinataire = $clientModel->connnecterOuInscrire($telephoneDestinataire);

        if ($frais === null || $fraisRetrait === null || !$expediteur || !$destinataire) {
            return ['success' => false, 'message' => 'Destinataire invalide.'];
        }

        if ((int) $destinataire['id'] === $clientId) {
            return ['success' => false, 'message' => 'Impossible de transferer vers le meme numero.'];
        }

        $totalFrais = (float) $frais + (float) $fraisRetrait;
        $totalDebit = $montant + $totalFrais;
        if ((float) $expediteur['solde'] < $totalDebit) {
            return ['success' => false, 'message' => 'Solde insuffisant pour couvrir le montant et les frais.'];
        }

        $this->db->transStart();

        $soldeExpediteur = (float) $expediteur['solde'] - $totalDebit;
        $soldeDestinataire = (float) $destinataire['solde'] + $montant + (float) $fraisRetrait;

        $clientModel->update($clientId, ['solde' => $soldeExpediteur]);
        $clientModel->update((int) $destinataire['id'], ['solde' => $soldeDestinataire]);

        $this->insert([
            'client_id'              => $clientId,
            'client_destinataire_id' => $destinataire['id'],
            'type_operation_id'      => $this->typeOperationId('TRANSFERT'),
            'montant'                => $montant,
            'frais'                  => $totalFrais,
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

    public function transfererMultiple(int $clientId, array $telephones, float $montantTotal, bool $inclureFraisRetrait = false): array
    {
        $telephones = array_values(array_unique(array_filter(array_map(static fn ($numero) => trim((string) $numero), $telephones))));

        if ($montantTotal <= 0 || count($telephones) < 2) {
            return ['success' => false, 'message' => 'Veuillez saisir au moins deux numeros et un montant valide.'];
        }

        $clientModel = new ClientModel();
        $expediteur = $clientModel->find($clientId);

        if (!$expediteur) {
            return ['success' => false, 'message' => 'Client introuvable.'];
        }

        $montantParNumero = round($montantTotal / count($telephones), 2);
        $fraisParNumero = $this->calculerFrais('TRANSFERT', $montantParNumero);
        $fraisRetraitParNumero = $inclureFraisRetrait ? $this->calculerFrais('RETRAIT', $montantParNumero) : 0;

        if ($fraisParNumero === null || $fraisRetraitParNumero === null) {
            return ['success' => false, 'message' => 'Montant par numero hors bareme.'];
        }

        foreach ($telephones as $telephone) {
            if (strlen($telephone) !== 10 || !ctype_digit($telephone) || !$clientModel->estPrefixeValide($telephone)) {
                return ['success' => false, 'message' => 'Envoi multiple autorise uniquement vers des numeros du meme operateur.'];
            }

            if ($telephone === $expediteur['numero_telephone']) {
                return ['success' => false, 'message' => 'Impossible de transferer vers votre propre numero.'];
            }
        }

        $totalFraisParNumero = (float) $fraisParNumero + (float) $fraisRetraitParNumero;
        $totalDebit = count($telephones) * ($montantParNumero + $totalFraisParNumero);

        if ((float) $expediteur['solde'] < $totalDebit) {
            return ['success' => false, 'message' => 'Solde insuffisant pour couvrir les montants et les frais.'];
        }

        $this->db->transStart();

        $soldeExpediteur = (float) $expediteur['solde'] - $totalDebit;
        $clientModel->update($clientId, ['solde' => $soldeExpediteur]);

        foreach ($telephones as $telephone) {
            $destinataire = $clientModel->connnecterOuInscrire($telephone);
            $soldeDestinataire = (float) $destinataire['solde'] + $montantParNumero + (float) $fraisRetraitParNumero;
            $clientModel->update((int) $destinataire['id'], ['solde' => $soldeDestinataire]);

            $this->insert([
                'client_id'              => $clientId,
                'client_destinataire_id' => $destinataire['id'],
                'type_operation_id'      => $this->typeOperationId('TRANSFERT'),
                'montant'                => $montantParNumero,
                'frais'                  => $totalFraisParNumero,
                'solde_apres'            => $soldeExpediteur,
                'mode_transfert'         => 'interne_multiple',
                'commission'             => 0,
            ]);
        }

        $this->db->transComplete();

        return [
            'success' => $this->db->transStatus(),
            'message' => $this->db->transStatus() ? 'Transferts multiples effectues avec succes.' : 'Echec de l envoi multiple.',
        ];
    }

    private function transfererExterne(array $expediteur, string $telephoneDestinataire, float $montant, ?int $operateurDestinataireId): array
    {
        $frais = $this->calculerFrais('TRANSFERT', $montant);
        $operateurModel = new OperateurModel();
        $operateur = $operateurDestinataireId ? $operateurModel->findActif($operateurDestinataireId) : null;

        if (!$operateur || $frais === null) {
            return ['success' => false, 'message' => 'Veuillez choisir un operateur destinataire valide.'];
        }

        $commission = $operateurModel->calculerCommission($operateur, $montant);
        $totalDebit = $montant + (float) $frais + $commission;

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
            'mode_transfert'                => 'autre_operateur',
            'operateur_destinataire_id'     => $operateur['id'],
            'numero_destinataire_externe'   => $telephoneDestinataire,
            'commission'                    => $commission,
        ]);

        $this->db->transComplete();

        return [
            'success' => $this->db->transStatus(),
            'message' => $this->db->transStatus() ? 'Transfert vers autre operateur enregistre avec succes.' : 'Echec du transfert vers autre operateur.',
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
        if ($modeTransfert === 'interne') {
            return $montant + $frais;
        }

        return $montant + $commission;
    }

    public function situationMontantsAEnvoyer(): array
    {
        $rows = $this->db->table('operations o')
            ->select(
                'op.nom AS operateur, o.mode_transfert, COUNT(o.id) AS nombre_operations, ' .
                'SUM(o.montant) AS total_montant, SUM(o.frais) AS total_frais, SUM(o.commission) AS total_commission'
            )
            ->join('operateurs op', 'op.id = o.operateur_destinataire_id')
            ->whereIn('o.mode_transfert', ['autre_operateur', 'externe_intermediaire', 'externe_direct'])
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

    public function baremesPour(string $codeOperation): array
    {
        return (new BaremeFraisModel())->listByType($this->typeOperationId($codeOperation));
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
