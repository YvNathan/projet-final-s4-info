<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FraisOperationModel;
use App\Models\PromotionModel;
use App\Models\TypeOperationModel;

class ApiFraisController extends BaseController
{
    public function getFrais()
    {
        $montant = (float) $this->request->getGet('montant');
        $typeOperation = $this->request->getGet('type_operation');
        $numeroDest = $this->request->getGet('numero_destinataire') ?? $this->request->getGet('numero_dest');
        $inclureFraisRetrait = $this->request->getGet('inclure_frais_retrait') === '1';

        if ($montant <= 0) {
            return $this->response->setJSON([
                'frais_transfert' => 0,
                'frais_retrait' => 0,
                'commission' => 0,
                'montant_total' => 0,
            ]);
        }

        $fraisModel = new FraisOperationModel();
        $typeOperationModel = new TypeOperationModel();

        $idTypeOperation = null;

        if (is_numeric($typeOperation)) {
            $idTypeOperation = (int) $typeOperation;
        } elseif ($typeOperation !== null) {
            try {
                $idTypeOperation = $typeOperationModel->getIdByLibelle((string) $typeOperation);
            } catch (\Throwable $e) {
                return $this->response->setStatusCode(400)->setJSON([
                    'error' => 'Type d\'opération invalide',
                ]);
            }
        }

        if ($idTypeOperation === null) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Type d\'opération requis',
            ]);
        }

        $libelleOperation = $typeOperationModel->find($idTypeOperation);
        $libelle = is_array($libelleOperation) && isset($libelleOperation['libelle'])
            ? (string) $libelleOperation['libelle']
            : '';

        if ($libelle === 'transfert') {
            $details = $fraisModel->getDetailsTransfert($montant, $numeroDest, $inclureFraisRetrait);

            return $this->response->setJSON($details);
        }

        $frais = $fraisModel->getFrais($idTypeOperation, $montant, $numeroDest);

        return $this->response->setJSON([
            'frais' => $frais,
            'montant_total' => $montant + $frais,
        ]);
    }

    public function getFraisMultiple()
    {
        $montant = (float) $this->request->getGet('montant');
        $numerosDest = $this->request->getGet('numeros_destinataire') ?? $this->request->getGet('numeros_dest') ?? [];
        $inclureFraisRetrait = $this->request->getGet('inclure_frais_retrait') === '1';

        if (is_string($numerosDest)) {
            $numerosDest = array_filter(array_map('trim', explode(',', $numerosDest)));
        }

        $numerosDest = array_values(array_unique(array_filter($numerosDest, static fn ($n) => trim((string) $n) !== '')));

        if ($montant <= 0 || count($numerosDest) < 2) {
            return $this->response->setJSON([
                'destinataires' => [],
                'montant_par_destinataire' => 0,
                'montant_total' => 0,
            ]);
        }

        $fraisModel = new FraisOperationModel();
        $montantParDestinataire = $montant / count($numerosDest);

        $destinataires = [];
        $montantTotal = 0.0;

        foreach ($numerosDest as $numeroDest) {
            $details = $fraisModel->getDetailsTransfert($montantParDestinataire, (string) $numeroDest, $inclureFraisRetrait);

            $destinataires[] = array_merge(['numero' => $numeroDest], $details);
            $montantTotal += $details['montant_total'];
        }

        return $this->response->setJSON([
            'destinataires' => $destinataires,
            'montant_par_destinataire' => $montantParDestinataire,
            'montant_total' => $montantTotal,
        ]);
    }

    public function getCommission()
    {
        $montant = (float) $this->request->getGet('montant');
        $numeroDest = $this->request->getGet('numero_destinataire') ?? $this->request->getGet('numero_dest');

        $fraisModel = new FraisOperationModel();

        return $this->response->setJSON([
            'commission' => $fraisModel->getCommission($montant, $numeroDest),
            'montant_total' => $montant + $fraisModel->getCommission($montant, $numeroDest),
        ]);
    }
}
