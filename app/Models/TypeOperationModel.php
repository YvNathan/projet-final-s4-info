<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table            = 'type_operation';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['libelle'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules      = [
        'libelle' => 'required|min_length[2]|max_length[255]|is_unique[type_operation.libelle,id,{id}]',
    ];
    protected $validationMessages   = [
        'libelle' => [
            'required'   => "Le libellé du type d'opération est obligatoire.",
            'min_length' => "Le libellé du type d'opération doit contenir au moins 2 caractères.",
            'max_length' => "Le libellé du type d'opération ne doit pas dépasser 255 caractères.",
            'is_unique'  => "Un type d'opération avec ce libellé existe déjà.",
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getIdByLibelle(string $libelle): int
    {
        $typeOperation = $this->select('id')
            ->where('libelle', $libelle)
            ->first();

        if ($typeOperation === null) {
            throw new \RuntimeException(sprintf("Le type d'opération '%s' est introuvable.", $libelle));
        }

        return (int) $typeOperation['id'];
    }

    public function getLibelleById(int $idTypeOperation): string
    {
        $typeOperation = $this->select('libelle')
            ->find($idTypeOperation);

        if ($typeOperation === null) {
            throw new \RuntimeException("Le type d'opération demandé est introuvable.");
        }

        return (string) $typeOperation['libelle'];
    }
}