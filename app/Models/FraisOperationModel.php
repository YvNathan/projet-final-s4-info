<?php

namespace App\Models;

use CodeIgniter\Model;

class FraisOperationModel extends Model
{
    protected $table            = 'frais_operation';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_type_operation', 'borne_min', 'borne_max', 'frais'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id_type_operation' => 'required|is_natural_no_zero|is_not_unique[type_operation.id]',
        'borne_min'         => 'required|numeric|greater_than_equal_to[0]',
        'borne_max'         => 'required|numeric|greater_than_field[borne_min]',
        'frais'             => 'required|numeric|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [
        'id_type_operation' => [
            'required'             => "Le type d'opération est obligatoire.",
            'is_natural_no_zero'   => "Le type d'opération est invalide.",
            'is_not_unique'        => "Le type d'opération indiqué n'existe pas.",
        ],
        'borne_min' => [
            'required'             => 'La borne minimale est obligatoire.',
            'numeric'              => 'La borne minimale doit être un nombre.',
            'greater_than_equal_to' => 'La borne minimale doit être positive ou nulle.',
        ],
        'borne_max' => [
            'required'           => 'La borne maximale est obligatoire.',
            'numeric'            => 'La borne maximale doit être un nombre.',
            'greater_than_field' => 'La borne maximale doit être supérieure à la borne minimale.',
        ],
        'frais' => [
            'required'              => 'Le frais est obligatoire.',
            'numeric'               => 'Le frais doit être un nombre.',
            'greater_than_equal_to' => 'Le frais doit être positif ou nul.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Récupère le frais applicable pour un type d'opération et un montant donnés,
     * en filtrant sur la tranche (borne_min exclue, borne_max incluse).
     */
    public function getFrais(int $idTypeOperation, float $montant): ?float
    {
        $row = $this->where('id_type_operation', $idTypeOperation)
            ->where('borne_min <', $montant)
            ->where('borne_max >=', $montant)
            ->first();

        return $row !== null ? (float) $row['frais'] : null;
    }
}
