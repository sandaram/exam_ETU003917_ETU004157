<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['numero_telephone', 'solde', 'date_creation'];

    /**
     * Vérifie si le préfixe du numéro est autorisé par l'opérateur
     */
    public function estPrefixeValide(string $telephone): bool
    {
        // On récupère les 3 premiers chiffres
        $prefixeSaisi = substr($telephone, 0, 3);

        $db = \Config\Database::connect();
        $builder = $db->table('prefixes_operateur');
        
        $result = $builder->where('prefixe', $prefixeSaisi)
                          ->where('operateur_id', null)
                          ->where('actif', 1)
                          ->get()
                          ->getRow();

        return $result !== null;
    }

    /**
     * Connecte le client ou l'inscrit automatiquement s'il n'existe pas
     */
    public function connnecterOuInscrire(string $telephone)
{
    // 1. Vérification de la longueur minimale / exacte (ex: 10 chiffres pour Madagascar)
    if (strlen($telephone) !== 10 || !ctype_digit($telephone)) {
        return false;
    }

    // 2. Vérification du préfixe autorisé
    if (!$this->estPrefixeValide($telephone)) {
        return false;
    }

    // 3. Recherche du client par son numéro de téléphone
    $client = $this->where('numero_telephone', $telephone)->first();

    // 4. Inscription automatique si préfixe valide et format correct
    if (!$client) {
        $id = $this->insert([
            'numero_telephone' => $telephone,
            'solde'            => 0
        ]);

        $client = $this->find($id);
    }

    return $client;
}
}
