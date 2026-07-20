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
    protected $allowedFields    = ['nom'];

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
        'nom' => 'required|min_length[2]|max_length[255]|is_unique[operateur.nom,id,{id}]',
    ];
    protected $validationMessages   = [
        'nom' => [
            'required'   => "Le nom de l'opérateur est obligatoire.",
            'min_length' => "Le nom de l'opérateur doit contenir au moins 2 caractères.",
            'max_length' => "Le nom de l'opérateur ne doit pas dépasser 255 caractères.",
            'is_unique'  => "Un opérateur avec ce nom existe déjà.",
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

    public function getValidPrefix(int $idOperateur): array
    {
        return $this->select('config.prefixe')
            ->join('config', 'config.id_operateur = operateur.id')
            ->where('operateur.id', $idOperateur)
            ->findAll();
    }

    public function getSituationGain(int $idOperateur): float
    {
        $row = $this->db->table('transactions')
            ->selectSum('transactions.frais_applique', 'gain')
            ->join('client', 'client.id = transactions.id_client')
            ->where('client.id_operateur', $idOperateur)
            ->get()
            ->getRowArray();

        return (float) ($row['gain'] ?? 0);
    }

    public function getSituationsClients(int $idOperateur): array
    {
        return $this->db->table('client')
            ->select('id, nom, numero, solde')
            ->where('id_operateur', $idOperateur)
            ->get()
            ->getResultArray();
    }
}
