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

    public function getFrais(int $idTypeOperation, float $montant, ?string $numeroDest = null): float
    {
        $libelleOperation = $this->getLibelleOperation($idTypeOperation);

        if ($libelleOperation !== 'transfert') {
            return $this->calculerFraisParTypeOperation($idTypeOperation, $montant);
        }

        return $this->calculerFraisParTypeOperation($idTypeOperation, $montant);
    }

    public function getFraisRetrait(float $montant): float
    {
        $typeOperationModel = new TypeOperationModel();
        $idTypeOperation = $typeOperationModel->getIdByLibelle('retrait');

        return $this->calculerFraisParTypeOperation($idTypeOperation, $montant);
    }

    public function getDetailsTransfert(float $montant, ?string $numeroDest = null, bool $inclureFraisRetrait = false): array
    {
        $typeOperationModel = new TypeOperationModel();
        $idTypeOperationTransfert = $typeOperationModel->getIdByLibelle('transfert');

        $fraisTransfert = $this->getFrais($idTypeOperationTransfert, $montant, $numeroDest);
        $fraisRetrait = 0.0;
        $commission = 0.0;

        if ($this->estAutreOperateur($numeroDest ?? '')) {
            $commission = $this->getCommission($montant, $numeroDest);
        } elseif ($inclureFraisRetrait) {
            $fraisRetrait = $this->getFraisRetrait($montant);
        }

        return [
            'frais_transfert' => $fraisTransfert,
            'frais_retrait' => $fraisRetrait,
            'commission' => $commission,
            'montant_total' => $montant + $fraisTransfert + $fraisRetrait + $commission,
        ];
    }

    public function getCommission(float $montant, ?string $numeroDest): float
    {
        if ($montant <= 0 || $numeroDest === null || trim($numeroDest) === '') {
            return 0.0;
        }

        if (!$this->estAutreOperateur($numeroDest)) {
            return 0.0;
        }

        $configModel = new ConfigModel();
        $operateur = $configModel->getOperateurByPrefixe($this->normaliserNumero($numeroDest));

        if ($operateur === null) {
            return 0.0;
        }

        $pctCommission = (float) ($operateur['pct_commission'] ?? 0);

        return $montant * ($pctCommission / 100);
    }

    private function calculerFraisParTypeOperation(int $idTypeOperation, float $montant): float
    {
        $row = $this->where('id_type_operation', $idTypeOperation)
            ->where('borne_min <', $montant)
            ->where('borne_max >=', $montant)
            ->first();

        return $row !== null ? (float) $row['frais'] : 0.0;
    }

    private function getLibelleOperation(int $idTypeOperation): string
    {
        $typeOperationModel = new TypeOperationModel();
        $operation = $typeOperationModel->find($idTypeOperation);

        return is_array($operation) && isset($operation['libelle'])
            ? (string) $operation['libelle']
            : '';
    }

    private function estAutreOperateur(string $numero): bool
    {
        $numero = $this->normaliserNumero($numero);

        if ($numero === '' || !preg_match('/^0[0-9]{9}$/', $numero)) {
            return false;
        }

        $configModel = new ConfigModel();
        $operateur = $configModel->getOperateurByPrefixe($numero);

        if ($operateur === null) {
            return false;
        }

        return (int) ($operateur['autre_operateur'] ?? 0) === 1;
    }

    private function normaliserNumero(string $numero): string
    {
        $numero = preg_replace('/\s+/', '', $numero);

        if (str_starts_with($numero, '+261')) {
            return '0' . substr($numero, 4);
        }

        return $numero;
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
