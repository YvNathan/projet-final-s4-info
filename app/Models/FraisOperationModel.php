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

    public function getAll(int $nbPage = 10, ?int $idTypeOperation = null): array
    {
        $builder = $this->select('id, id_type_operation, borne_min, borne_max, frais');

        if ($idTypeOperation !== null) {
            $builder = $builder->where('id_type_operation', $idTypeOperation);
        }

        return $builder->paginate($nbPage);
    }

    public function getFrais(int $idTypeOperation, float $montant): ?float
    {
        $row = $this->where('id_type_operation', $idTypeOperation)
            ->where('borne_min <', $montant)
            ->where('borne_max >=', $montant)
            ->first();

        return $row !== null ? (float) $row['frais'] : null;
    }

    public function ajouterBareme(int $idTypeOperation, float $borneMin, float $borneMax, float $frais)
    {
        if ($this->chevaucheTrancheExistante($idTypeOperation, $borneMin, $borneMax)) {
            throw new \RuntimeException("Cette tranche chevauche une tranche existante pour ce type d'opération.");
        }

        return $this->insert([
            'id_type_operation' => $idTypeOperation,
            'borne_min'         => $borneMin,
            'borne_max'         => $borneMax,
            'frais'             => $frais,
        ]);
    }

    public function modifierBareme(int $id, float $borneMin, float $borneMax, float $frais): bool
    {
        $tranche = $this->find($id);

        if ($tranche === null) {
            throw new \RuntimeException("Cette tranche de frais n'existe pas.");
        }

        if ($this->chevaucheTrancheExistante((int) $tranche['id_type_operation'], $borneMin, $borneMax, $id)) {
            throw new \RuntimeException("Cette tranche chevauche une tranche existante pour ce type d'opération.");
        }

        return (bool) $this->update($id, [
            'borne_min' => $borneMin,
            'borne_max' => $borneMax,
            'frais'     => $frais,
        ]);
    }

    public function supprimerBareme(int $id): bool
    {
        return $this->delete($id);
    }

    private function chevaucheTrancheExistante(int $idTypeOperation, float $borneMin, float $borneMax, ?int $excludeId = null): bool
    {
        $builder = $this->where('id_type_operation', $idTypeOperation)
            ->where('borne_min <', $borneMax)
            ->where('borne_max >', $borneMin);

        if ($excludeId !== null) {
            $builder = $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }
}
