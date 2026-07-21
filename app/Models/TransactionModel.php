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
        'commission',
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
        $promotionModel = new PromotionModel();

        $promotionValue = $promotionModel->getPromotion();

        $client = $clientModel->where('numero', $numero)->first();

        if ($client === null) {
            throw new \RuntimeException("Le numéro du client n'a pas encore de compte.");
        }

        $frais = $fraisModel->getFrais($idTypeOperation, $montant, $numeroDest);

        $commission = 0.0;
        $libelleOperation = $typeOperationModel->getLibelleById($idTypeOperation);

        if ($libelleOperation === 'transfert' && $numeroDest !== null) {
            $commission = $fraisModel->getCommission($montant, $numeroDest);
        }

        if ($frais === null) {
            throw new \RuntimeException("Aucun barème de frais ne correspond à ce montant.");
        }

        $destinataire = null;

        if ($libelleOperation === 'transfert' && $numeroDest !== null) {
            $estAutreOperateur = $fraisModel->estAutreOperateur($numeroDest);
            $destinataire = $clientModel->where('numero', $numeroDest)->first();

            if (!$estAutreOperateur){
                $frais = $frais * (1 - ($promotionValue / 100));
            }

            if (!$estAutreOperateur && $destinataire === null) {
                throw new \RuntimeException("Le numéro du destinataire n'a pas encore de compte.");
            }
        }

        if ($libelleOperation === 'depot') {
            $soldeClientApresOperation = (float) $client['solde'] + $montant;
        } elseif ($libelleOperation === 'retrait' || $libelleOperation === 'transfert') {
            $soldeClientApresOperation = (float) $client['solde'] - $montant - $frais - $commission;
        } else {
            throw new \RuntimeException("Le type d'opération '{$libelleOperation}' n'est pas pris en charge.");
        }

        if ($soldeClientApresOperation < 0) {
            throw new \RuntimeException('Le solde du client est insuffisant pour effectuer cette opération.');
        }

        $soldeDestinataireApresOperation = null;
        $soldeEpargneDestinataireApresOperation = null;


        if ($destinataire !== null) {
            $destE = $destinataire['pct_epargne'];

            $montantEpargne = $montant * ($destE /100);
            $montantSolde = $montant - $montantEpargne;
            $soldeDestinataireApresOperation = (float) $destinataire['solde'] + $montantSolde;
            $soldeEpargneDestinataireApresOperation = (float) $destinataire['solde_epargne'] + $montantEpargne;
        }

        $this->db->transBegin();

        try {
            $transactionId = $this->insert([
                'id_client'           => (int) $client['id'],
                'id_type_operation'   => $idTypeOperation,
                'date_heure'          => $dateHeure,
                'montant'             => $montant,
                'frais_applique'      => $frais,
                'commission'          => $commission,
                'numero_destinataire' => $numeroDest,
            ]);

            if ($transactionId === false) {
                throw new \RuntimeException('Impossible de créer la transaction.');
            }

            if ($this->db->table('client')
                ->where('id', (int) $client['id'])
                ->update(['solde' => $soldeClientApresOperation]) === false
            ) {
                throw new \RuntimeException('Impossible de mettre à jour le solde du client.');
            }

            if ($destinataire !== null && $soldeDestinataireApresOperation !== null) {
                if ($this->db->table('client')
                    ->where('id', (int) $destinataire['id'])
                    ->update(['solde' => $soldeDestinataireApresOperation , 'solde_epargne' => $soldeEpargneDestinataireApresOperation]) === false
                ) {
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

    public function createTransfertMultiple(string $numero, string $dateHeure, float $montant, array $numerosDest, bool $inclureFraisRetrait = false): array
    {
        $numerosDest = array_values(array_unique(array_map(
            fn (string $numeroDest) => $this->normaliserNumero($numeroDest),
            $numerosDest
        )));

        if (count($numerosDest) < 2) {
            throw new \RuntimeException('Un transfert multiple doit avoir au moins deux destinataires distincts.');
        }

        $configModel = new ConfigModel();
        $idOperateurCommun = null;
        $estAutreOperateur = false;

        foreach ($numerosDest as $numeroDest) {
            $operateur = $configModel->getOperateurByPrefixe($numeroDest);

            if ($operateur === null) {
                throw new \RuntimeException("Le numéro {$numeroDest} n'appartient à aucun opérateur connu.");
            }

            if ($idOperateurCommun === null) {
                $idOperateurCommun = (int) $operateur['id'];
                $estAutreOperateur = (int) $operateur['autre_operateur'] === 1;
            } elseif ($idOperateurCommun !== (int) $operateur['id']) {
                throw new \RuntimeException('Tous les destinataires doivent appartenir au même opérateur.');
            }
        }

        $typeOperationModel = new TypeOperationModel();
        $fraisModel = new FraisOperationModel();
        $idTypeTransfert = $typeOperationModel->getIdByLibelle('transfert');
        $montantParDestinataire = $montant / count($numerosDest);

        if ($inclureFraisRetrait && !$estAutreOperateur) {
            $montantParDestinataire += $fraisModel->getFraisRetrait($montantParDestinataire);
        }

        $this->db->transBegin();

        try {
            $idsTransactions = [];

            foreach ($numerosDest as $numeroDest) {
                $idsTransactions[] = $this->createTransaction(
                    $idTypeTransfert,
                    $numero,
                    $dateHeure,
                    $montantParDestinataire,
                    $numeroDest,
                );
            }

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('Le transfert multiple a échoué.');
            }

            $this->db->transCommit();

            return $idsTransactions;
        } catch (\Throwable $throwable) {
            $this->db->transRollback();

            if ($throwable instanceof \RuntimeException) {
                throw $throwable;
            }

            throw new \RuntimeException('Une erreur est survenue pendant le transfert multiple.', 0, $throwable);
        }
    }

    public function getHistoriqueTransactions(string $numero)
    {
        $numero = $this->normaliserNumero($numero);

        return $this->select("
            transactions.*, 
            type_operation.libelle AS type_operation,

            CASE
                WHEN transactions.numero_destinataire = '$numero'
                THEN 'recu'

                ELSE 'envoye'

            END AS sens
        ")
            ->join(
                'type_operation',
                'transactions.id_type_operation = type_operation.id'
            )
            ->join(
                'client',
                'transactions.id_client = client.id'
            )
            ->groupStart()
            ->where('client.numero', $numero)
            ->orWhere('transactions.numero_destinataire', $numero)
            ->groupEnd()
            ->orderBy('transactions.date_heure', 'DESC')
            ->findAll();
    }

    public function getSituationGains(?int $idTypeOperation = null): array
    {
        $builder = $this->select("
                COALESCE(operateur_dest.id, operateur_soi.id) AS id_operateur,
                COALESCE(operateur_dest.nom, operateur_soi.nom) AS nom_operateur,
                SUM(transactions.frais_applique) AS gain
            ")
            ->join(
                'config',
                "SUBSTR(transactions.numero_destinataire, 1, LENGTH(config.prefixe)) = config.prefixe",
                'left'
            )
            ->join('operateur operateur_dest', 'operateur_dest.id = config.id_operateur', 'left')
            ->join('operateur operateur_soi', 'operateur_soi.autre_operateur = 0', 'left')
            ->groupBy('COALESCE(operateur_dest.id, operateur_soi.id)');

        if ($idTypeOperation !== null) {
            $builder->where('transactions.id_type_operation', $idTypeOperation);
        }

        $lignes = $builder->get()->getResultArray();

        $operateurModel = new OperateurModel();
        $idSoiMeme = (int) $operateurModel->getSoiMeme()['id'];

        $soiMeme = 0.0;
        $autresOperateurs = [];
        $total = 0.0;

        foreach ($lignes as $ligne) {
            $gain = (float) $ligne['gain'];
            $total += $gain;

            if ((int) $ligne['id_operateur'] === $idSoiMeme) {
                $soiMeme += $gain;
            } else {
                $autresOperateurs[] = [
                    'id_operateur' => (int) $ligne['id_operateur'],
                    'nom'          => $ligne['nom_operateur'],
                    'gain'         => $gain,
                ];
            }
        }

        return [
            'total'             => $total,
            'soi_meme'          => $soiMeme,
            'autres_operateurs' => $autresOperateurs,
        ];
    }

    public function getMontantsAEnvoyerAutresOperateurs(): array
    {
        $typeOperationModel = new TypeOperationModel();
        $idTypeTransfert = $typeOperationModel->getIdByLibelle('transfert');

        $lignes = $this->select("
                operateur.id AS id_operateur,
                operateur.nom AS nom_operateur,
                SUM(transactions.montant) AS montant_transfere,
                SUM(transactions.commission) AS commission
            ")
            ->join('config', "SUBSTR(transactions.numero_destinataire, 1, LENGTH(config.prefixe)) = config.prefixe")
            ->join('operateur', 'operateur.id = config.id_operateur AND operateur.autre_operateur = 1')
            ->where('transactions.id_type_operation', $idTypeTransfert)
            ->groupBy('operateur.id')
            ->get()
            ->getResultArray();

        $montants = [];

        foreach ($lignes as $ligne) {
            $montantTransfere = (float) $ligne['montant_transfere'];
            $commission = (float) $ligne['commission'];

            $montants[] = [
                'id_operateur'      => (int) $ligne['id_operateur'],
                'nom'               => $ligne['nom_operateur'],
                'montant_transfere' => $montantTransfere,
                'commission'        => $commission,
                'total'             => $montantTransfere + $commission,
            ];
        }

        return $montants;
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
