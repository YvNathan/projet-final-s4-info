<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table            = 'operateur';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom', 'autre_operateur', 'pct_commission'];

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
        'id'              => 'permit_empty|is_natural_no_zero',
        'nom'             => 'required|min_length[2]|max_length[255]|is_unique[operateur.nom,id,{id}]',
        'autre_operateur' => 'permit_empty|in_list[0,1]',
        'pct_commission'  => 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
    ];
    protected $validationMessages   = [
        'nom' => [
            'required'   => "Le nom de l'opérateur est obligatoire.",
            'min_length' => "Le nom de l'opérateur doit contenir au moins 2 caractères.",
            'max_length' => "Le nom de l'opérateur ne doit pas dépasser 255 caractères.",
            'is_unique'  => "Un opérateur avec ce nom existe déjà.",
        ],
        'autre_operateur' => [
            'in_list' => "La valeur 'autre opérateur' est invalide.",
        ],
        'pct_commission' => [
            'numeric'               => 'Le pourcentage de commission doit être un nombre.',
            'greater_than_equal_to' => 'Le pourcentage de commission doit être positif ou nul.',
            'less_than_equal_to'    => 'Le pourcentage de commission ne doit pas dépasser 100.',
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

    public function getAll(): array
    {
        return $this->orderBy('nom', 'ASC')->findAll();
    }

    public function getAutresOperateurs(): array
    {
        return $this->where('autre_operateur', 1)
            ->orderBy('nom', 'ASC')
            ->findAll();
    }

    public function getSoiMeme(): array
    {
        $operateur = $this->where('autre_operateur', 0)->first();

        if ($operateur === null) {
            throw new \RuntimeException("L'opérateur principal n'est pas configuré.");
        }

        return $operateur;
    }

    public function ajouterOperateur(string $nom, float $pctCommission)
    {
        return $this->insert([
            'nom'             => $nom,
            'autre_operateur' => 1,
            'pct_commission'  => $pctCommission,
        ]);
    }

    public function modify(int $id, string $nom, float $pctCommission): bool
    {
        $operateur = $this->find($id);

        if ($operateur === null) {
            throw new \RuntimeException("Cet opérateur n'existe pas.");
        }

        if ((int) $operateur['autre_operateur'] === 0) {
            throw new \RuntimeException("Le pourcentage de commission de l'opérateur principal ne peut pas être modifié.");
        }

        return (bool) $this->update($id, [
            'id'             => $id,
            'nom'            => $nom,
            'pct_commission' => $pctCommission,
        ]);
    }

    public function delete($id = null, bool $purge = false)
    {
        $operateur = $this->find($id);

        if ($operateur !== null && (int) $operateur['autre_operateur'] === 0) {
            throw new \RuntimeException("L'opérateur principal ne peut pas être supprimé.");
        }

        return parent::delete($id, $purge);
    }
}
