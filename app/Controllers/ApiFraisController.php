<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FraisOperationModel;
use App\Models\TypeOperationModel;

class ApiFraisController extends BaseController
{
    public function getFrais()
    {
        $montant = (float) $this->request->getGet('montant');
        $typeOperation = $this->request->getGet('type_operation');
        $numeroDest = $this->request->getGet('numero_destinataire') ?? $this->request->getGet('numero_dest');

        if ($montant <= 0) {
            return $this->response->setJSON([
                'frais' => 0,
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

        $frais = $fraisModel->getFrais($idTypeOperation, $montant, $numeroDest);
        $commission = $fraisModel->getCommission($montant, $numeroDest);

        return $this->response->setJSON([
            'frais' => $frais,
            'commission' => $commission,
            'montant_total' => $montant + $frais + $commission,
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
