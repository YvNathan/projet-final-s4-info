<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_client',
        'id_type_operation',
        'date_heure',
        'montant',
        'frais_applique',
        'numero_destinataire',
    ];

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
    protected $validationRules      = [];
    protected $validationMessages   = [];
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

    public function createTransaction(int $idTypeOperation, string $numero, string $dateHeure, float $montant, ?string $numeroDest = null): int
    {
        $numero = $this->normaliserNumero($numero);
        $numeroDest = $numeroDest !== null ? $this->normaliserNumero($numeroDest) : null;

        if ($montant <= 0) {
            throw new \RuntimeException('Le montant doit être strictement positif.');
        }

        $clientModel = new ClientModel();
        $fraisModel = new FraisOperationModel();
        $typeOperationModel = new TypeOperationModel();

        $client = $clientModel->where('numero', $numero)->first();

        if ($client === null) {
            throw new \RuntimeException("Le numéro du client n'a pas encore de compte.");
        }

        $frais = $fraisModel->getFrais($idTypeOperation, $montant);

        if ($frais === null) {
            throw new \RuntimeException("Aucun barème de frais ne correspond à ce montant.");
        }

        $libelleOperation = $typeOperationModel->getLibelleById($idTypeOperation);

        $destinataire = null;

        if ($libelleOperation === 'transfert' && $numeroDest !== null) {
            $destinataire = $clientModel->where('numero', $numeroDest)->first();

            if ($destinataire === null) {
                throw new \RuntimeException("Le numéro du destinataire n'a pas encore de compte");
            }
        }

        $soldeClientApresOperation = match ($libelleOperation) {
            'depot' => (float) $client['solde'] + $montant,
            'retrait', 'transfert' => (float) $client['solde'] - $montant - $frais,
            default => throw new \RuntimeException("Le type d'opération '{$libelleOperation}' n'est pas pris en charge."),
        };

        if ($soldeClientApresOperation < 0) {
            throw new \RuntimeException('Le solde du client est insuffisant pour effectuer cette opération.');
        }

        $soldeDestinataireApresOperation = null;

        if ($destinataire !== null) {
            $soldeDestinataireApresOperation = (float) $destinataire['solde'] + $montant;
        }

        $this->db->transBegin();

        try {
            $transactionId = $this->insert([
                'id_client'           => (int) $client['id'],
                'id_type_operation'   => $idTypeOperation,
                'date_heure'          => $dateHeure,
                'montant'             => $montant,
                'frais_applique'      => $frais,
                'numero_destinataire' => $numeroDest,
            ]);

            if ($transactionId === false) {
                throw new \RuntimeException('Impossible de créer la transaction.');
            }

            if ($this->db->table('client')
                ->where('id', (int) $client['id'])
                ->update(['solde' => $soldeClientApresOperation]) === false) {
                throw new \RuntimeException('Impossible de mettre à jour le solde du client.');
            }

            if ($destinataire !== null && $soldeDestinataireApresOperation !== null) {
                if ($this->db->table('client')
                    ->where('id', (int) $destinataire['id'])
                    ->update(['solde' => $soldeDestinataireApresOperation]) === false) {
                    throw new \RuntimeException('Impossible de mettre à jour le solde du destinataire.');
                }
            }

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('La transaction a échoué.');
            }

            $this->db->transCommit();

            return (int) $transactionId;
        } catch (\Throwable $throwable) {
            $this->db->transRollback();

            if ($throwable instanceof \RuntimeException) {
                throw $throwable;
            }

            throw new \RuntimeException('Une erreur est survenue pendant la création de la transaction.', 0, $throwable);
        }
    }

    public function createDepot(string $numero, string $dateHeure, float $montant): int
    {
        $typeOperationModel = new TypeOperationModel();

        return $this->createTransaction(
            $typeOperationModel->getIdByLibelle('depot'),
            $numero,
            $dateHeure,
            $montant,
            null,
        );
    }

    public function createRetrait(string $numero, string $dateHeure, float $montant): int
    {
        $typeOperationModel = new TypeOperationModel();

        return $this->createTransaction(
            $typeOperationModel->getIdByLibelle('retrait'),
            $numero,
            $dateHeure,
            $montant,
            null,
        );
    }

    public function createTransfert(string $numero, string $dateHeure, float $montant, string $numeroDest): int
    {
        $typeOperationModel = new TypeOperationModel();

        return $this->createTransaction(
            $typeOperationModel->getIdByLibelle('transfert'),
            $numero,
            $dateHeure,
            $montant,
            $numeroDest,
        );
    }

    private function normaliserNumero(string $numero): string
    {
        $numero = preg_replace('/\s+/', '', $numero);

        if (str_starts_with($numero, '+261')) {
            return '0' . substr($numero, 4);
        }

        return $numero;
    }
}