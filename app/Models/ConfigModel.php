<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigModel extends Model
{
    protected $table            = 'config';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['prefixe', 'id_operateur'];

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
        'prefixe'      => 'required|min_length[2]|max_length[10]|is_unique[config.prefixe,id,{id}]',
        'id_operateur' => 'required|is_natural_no_zero|is_not_unique[operateur.id]',
    ];
    protected $validationMessages   = [
        'prefixe' => [
            'required'   => 'Le préfixe est obligatoire.',
            'min_length' => 'Le préfixe doit contenir au moins 2 caractères.',
            'max_length' => 'Le préfixe ne doit pas dépasser 10 caractères.',
            'is_unique'  => 'Ce préfixe existe déjà.',
        ],
        'id_operateur' => [
            'required'           => "L'opérateur est obligatoire.",
            'is_natural_no_zero' => "L'opérateur est invalide.",
            'is_not_unique'      => "L'opérateur indiqué n'existe pas.",
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

    public function ajouterPrefixe(string $prefixe, int $idOperateur)
    {
        return $this->insert([
            'prefixe'      => $prefixe,
            'id_operateur' => $idOperateur,
        ]);
    }

    public function getOperateurByPrefixe(string $numero): ?array
    {
        $numeroEchappe = $this->db->escape($numero);

        return $this->select('operateur.id, operateur.nom, operateur.autre_operateur, operateur.pct_commission')
            ->join('operateur', 'operateur.id = config.id_operateur')
            ->where("SUBSTR({$numeroEchappe}, 1, LENGTH(config.prefixe)) = config.prefixe", null, false)
            ->orderBy('LENGTH(config.prefixe)', 'DESC')
            ->get(1)
            ->getRowArray();
    }
}
