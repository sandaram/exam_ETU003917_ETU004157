<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table         = 'types_operation';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['code', 'libelle'];

    public function listAll(): array
    {
        return $this->orderBy('code', 'ASC')->findAll();
    }

    public function findByCode(string $code): ?array
    {
        return $this->where('code', strtoupper($code))->first();
    }
}
